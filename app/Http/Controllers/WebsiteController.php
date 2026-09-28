<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FarmerProfile;
use App\Models\Market;
use App\Models\MarketFarmer;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PickupSlot;
use App\Models\Product;
use App\Models\Review;
use App\Services\Cart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WebsiteController extends Controller
{
    public function home(): View
    {
        $markets = Market::latest()->take(3)->get();
        $categories = Category::withCount(['products' => fn(Builder $query) => $query
            ->where('is_active', true)->where('stock_quantity', '>', 0)
            ->whereHas('farmer', fn(Builder $farmer) => $farmer->where('approval_status', 'approved'))])
            ->orderBy('name')->get();

        $products = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->whereHas('farmer', fn(Builder $query) => $query->where('approval_status', 'approved'))
            ->with(['farmer.user', 'category'])
            ->latest()
            ->take(4)
            ->get();

        $reviews = $this->communityReviews()->take(8)->get();

        return view('Website.Home.index', compact('categories', 'markets', 'reviews', 'products'));
    }

    public function markets(Request $request): View
    {
        $request->validate(['q' => 'nullable|string|max:120', 'city' => 'nullable|string|max:100', 'day' => 'nullable|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday']);
        $query = Market::withCount(['marketFarmers' => fn($members) => $members->where('is_active', true)
            ->whereIn('farmer_id', FarmerProfile::where('approval_status', 'approved')->select('user_id'))]);
        if ($request->filled('q')) {
            $search = $request->string('q')->trim()->toString();
            $query->where(fn($inner) => $inner->where('name', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")->orWhere('address', 'like', "%{$search}%"));
        }
        $query->when($request->filled('city'), fn($q) => $q->where('city', $request->input('city')))
            ->when($request->filled('day'), fn($q) => $q->where('operating_days', 'like', '%' . substr($request->input('day'), 0, 3) . '%'));
        match ($request->input('sort')) {
            'most_farmers' => $query->orderByDesc('market_farmers_count'),
            'newest' => $query->latest(),
            default => $query->orderBy('name'),
        };
        $markets = $query->paginate(9)->withQueryString();
        $cities = Market::distinct()->orderBy('city')->pluck('city');

        return view('Website.Markets.index', compact('markets', 'cities'));
    }

    protected function discoveryFarmers(): Builder
    {
        return FarmerProfile::where('approval_status', 'approved')->with(['user', 'products' => fn($q) => $q->where('is_active', true)->with('category')->limit(3)])
            ->withCount(['products' => fn($q) => $q->where('is_active', true), 'reviews' => fn($q) => $q->where('is_active', true)])
            ->withAvg(['reviews' => fn($q) => $q->where('is_active', true)], 'rating');
    }

    public function marketDetail(Request $request, string $market): View
    {
        $market = Market::findOrFail($market);
        $farmerQuery = $this->discoveryFarmers()->whereIn('user_id', $market->marketFarmers()->where('is_active', true)->select('farmer_id'));
        $farmerCount = (clone $farmerQuery)->count();
        $farmerIds = (clone $farmerQuery)->pluck('id');
        $farmers = $farmerQuery->orderBy('stall_name')->take(6)->get();
        $products = Product::where('is_active', true)->whereIn('farmer_id', $farmerIds)
            ->with(['farmer.user', 'category'])->orderByDesc('stock_quantity')->latest()->take(6)->get();
        $productCount = Product::where('is_active', true)->whereIn('farmer_id', $farmerIds)->count();
        $pickupSlots = $market->pickupSlots()->whereIn('farmer_id', $farmerIds)->with('farmer')
            ->where('is_available', true)->where(fn($q) => $q->where('date', '>', today())
                ->orWhere(fn($today) => $today->where('date', today())->where('start_time', '>', now()->format('H:i:s'))))
            ->orderBy('date')->orderBy('start_time')->take(6)->get();

        return view('Website.Markets.view', compact('market', 'farmers', 'farmerCount', 'products', 'productCount', 'pickupSlots'));
    }

    public function farmers(Request $request): View
    {
        $request->validate([
            'q' => 'nullable|string|max:120',
            'city' => 'nullable|string|max:100',
            'market_id' => 'nullable|integer|exists:markets,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'day' => 'nullable|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'min_rating' => 'nullable|numeric|between:1,5'
        ]);
        $query = $this->discoveryFarmers();
        if ($request->filled('q')) {
            $search = $request->string('q')->trim()->toString();
            $query->where(fn($q) => $q->where('stall_name', 'like', "%{$search}%")->orWhere('business_name', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")->orWhereHas('user', fn($user) => $user->where('name', 'like', "%{$search}%")));
        }
        $query->when($request->filled('city'), fn($q) => $q->where('city', $request->input('city')))
            ->when($request->filled('day'), fn($q) => $q->where('operating_days', 'like', '%' . substr($request->input('day'), 0, 3) . '%'))
            ->when($request->filled('market_id'), fn($q) => $q->whereIn('user_id', MarketFarmer::where('market_id', $request->input('market_id'))->where('is_active', true)->select('farmer_id')))
            ->when($request->filled('category_id') || $request->boolean('in_stock_only'), fn($q) => $q->whereHas('products', fn($products) => $products->where('is_active', true)
                ->when($request->filled('category_id'), fn($p) => $p->where('category_id', $request->input('category_id')))
                ->when($request->boolean('in_stock_only'), fn($p) => $p->where('stock_quantity', '>', 0))));
        if ($request->filled('min_rating')) {
            $query->whereIn('id', Review::where('is_active', true)->select('farmer_id')->groupBy('farmer_id')->havingRaw('AVG(rating) >= CAST(? AS DECIMAL(3, 2))', [$request->input('min_rating')]));
        }
        match ($request->input('sort', 'top_rated')) {
            'name' => $query->orderBy('stall_name'),
            'newest' => $query->latest(),
            'most_products' => $query->orderByDesc('products_count'),
            default => $query->orderByDesc('reviews_avg_rating'),
        };
        $farmers = $query->paginate(8)->withQueryString();
        $cities = FarmerProfile::where('approval_status', 'approved')->distinct()->orderBy('city')->pluck('city');
        $markets = Market::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $selectedMarket = $request->filled('market_id') ? $markets->firstWhere('id', $request->integer('market_id')) : null;

        return view('Website.Farmers.index', compact('farmers', 'cities', 'markets', 'categories', 'selectedMarket'));
    }

    public function farmerDetail(Request $request, string $farmer): View
    {
        $request->validate(['q' => 'nullable|string|max:120', 'category_id' => 'nullable|integer|exists:categories,id', 'max_price' => 'nullable|numeric|min:0']);
        $farmer = $this->discoveryFarmers()->findOrFail($farmer);
        $query = $farmer->products()->where('is_active', true)->with(['category', 'farmer.user']);
        $query->when($request->filled('q'), fn($q) => $q->where('name', 'like', '%' . $request->input('q') . '%'))
            ->when($request->filled('category_id'), fn($q) => $q->where('category_id', $request->input('category_id')))
            ->when($request->filled('max_price'), fn($q) => $q->where('price', '<=', $request->input('max_price')))
            ->when($request->boolean('in_stock_only'), fn($q) => $q->where('stock_quantity', '>', 0));
        match ($request->input('sort')) {
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            default => $query->latest(),
        };
        $products = $query->paginate(9)->withQueryString();
        $categories = Category::whereIn('id', $farmer->products()->where('is_active', true)->select('category_id'))->orderBy('name')->get();
        $reviews = $farmer->reviews()->where('is_active', true)->with(['user', 'reply'])->latest()->take(6)->get();
        $ratingAverage = round((float) $farmer->reviews_avg_rating, 1);
        $ratingCount = $farmer->reviews_count;
        $markets = Market::whereIn('id', MarketFarmer::where('farmer_id', $farmer->user_id)->where('is_active', true)->select('market_id'))->get();
        $weeklyStock = $farmer->weeklyStockTemplates()->where('is_active', true)
            ->where(fn($q) => $q->whereNull('start_date')->orWhere('start_date', '<=', today()))
            ->where(fn($q) => $q->whereNull('end_date')->orWhere('end_date', '>=', today()))
            ->whereHas('product', fn($q) => $q->where('is_active', true)->where('farmer_id', $farmer->id))
            ->with('product')->orderBy('day_of_week')->get();
        $pickupSlots = $farmer->pickupSlots()->with('market')->where('is_available', true)
            ->where(fn($q) => $q->where('date', '>', today())->orWhere(fn($today) => $today->where('date', today())->where('start_time', '>', now()->format('H:i:s'))))
            ->orderBy('date')->orderBy('start_time')->take(6)->get();

        return view('Website.Farmers.view', compact('farmer', 'products', 'categories', 'reviews', 'ratingAverage', 'ratingCount', 'markets', 'weeklyStock', 'pickupSlots'));
    }

    public function products(Request $request): View
    {
        $request->validate(['q' => 'nullable|string|max:120', 'market_id' => 'nullable|integer|exists:markets,id', 'farmer_id' => 'nullable|integer|exists:farmer_profile,id', 'day' => 'nullable|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday']);
        $marketOptions = Market::orderBy('name')->get();
        $query = Product::with(['farmer.user', 'category']);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($inner) use ($search) {
                $inner->where('name', 'like', "%{$search}%")
                    ->orWhereHas('farmer', function ($farmerQuery) use ($search) {
                        $farmerQuery->where('stall_name', 'like', "%{$search}%")
                            ->orWhere('business_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        if ($request->boolean('in_stock_only')) {
            $query->where('stock_quantity', '>', 0);
        }

        $query->when($request->filled('market_id'), fn($q) => $q->whereHas('farmer', fn($farmer) => $farmer->whereIn('user_id', MarketFarmer::where('market_id', $request->input('market_id'))->where('is_active', true)->select('farmer_id'))))
            ->when($request->filled('farmer_id'), fn($q) => $q->where('farmer_id', $request->input('farmer_id')))
            ->when($request->filled('day'), fn($q) => $q->whereHas('farmer', fn($farmer) => $farmer->where('operating_days', 'like', '%' . substr($request->input('day'), 0, 3) . '%')));

        $query->where('is_active', true);

        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            default => $query->latest(),
        };

        $products = $query->paginate(9)->withQueryString();

        $categories = Category::withCount(['products' => function ($inner) {
            $inner->where('is_active', true);
        }])->get();

        return view('Website.Products.index', compact('products', 'marketOptions', 'categories'));
    }

    public function productDetail(Request $request, string $product): View
    {
        $product = Product::with(['farmer.user', 'category'])->where('is_active', true)->findOrFail($product);

        $reviews = $product->reviews()->where('is_active', true)->with(['user', 'reply'])->latest()->take(6)->get();

        $ratingAverage = round((float) $product->reviews()->where('is_active', true)->avg('rating'), 1);
        $ratingCount = $product->reviews()->where('is_active', true)->count();

        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where(function ($inner) use ($product) {
                $inner->where('farmer_id', $product->farmer_id)
                    ->orWhere('category_id', $product->category_id);
            })
            ->with(['farmer.user', 'category'])
            ->take(4)
            ->get();

        $pickupSlots = PickupSlot::where('farmer_id', $product->farmer_id)
            ->where('is_available', true)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->get();

        return view('Website.Products.view', compact('product', 'reviews', 'ratingAverage', 'ratingCount', 'relatedProducts', 'pickupSlots'));
    }

    public function cart(): View
    {
        $cart = new Cart;

        $cartItems = $cart->items();
        $farmerGroups = $cart->groupedByFarmer();
        $cartTotal = $cart->total();

        return view('Website.Cart.index', compact('cartItems', 'farmerGroups', 'cartTotal'));
    }

    public function addToCart(Request $request, string $product): RedirectResponse|JsonResponse
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        (new Cart)->add((int) $product, (int) $request->input('quantity', 1));

        if ($request->expectsJson()) {
            return $this->cartResponse('Added to your basket.');
        }

        return back()->with('success', 'Product added to your cart.');
    }
    public function updateCartItem(Request $request, string $product): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = new Cart();

        $cart->update(
            (int) $product,
            (int) $request->input('quantity')
        );

        $items = $cart->items();

        $updatedItem = null;

        foreach ($items as $item) {
            if ((int) $item['product']->id === (int) $product) {
                $updatedItem = $item;
                break;
            }
        }

        return response()->json([
            'success' => true,
            'quantity' => (int) ($updatedItem['quantity'] ?? 0),
            'subtotal' => (float) ($updatedItem['subtotal'] ?? 0),
            'cart_total' => (float) $cart->total(),
            'cart_count' => (int) $items->sum('quantity'),
        ]);
    }
    public function removeFromCart(Request $request, string $product): RedirectResponse|JsonResponse
    {
        (new Cart)->remove((int) $product);

        if ($request->expectsJson()) {
            return $this->cartResponse('Removed from your basket.');
        }

        return back()->with('success', 'Product removed from your cart.');
    }

    protected function cartResponse(string $message): JsonResponse
    {
        $cart = new Cart;
        $items = $cart->items();

        return response()->json([
            'message' => $message,
            'count' => $items->sum('quantity'),
            'total' => $items->sum('subtotal'),
        ]);
    }

    public function checkout(): View
    {
        $cart = new Cart;

        $cartItems = $cart->items();
        $farmerGroups = $cart->groupedByFarmer();
        $cartTotal = $cart->total();

        $pickupSlotsByFarmer = PickupSlot::whereIn('farmer_id', $farmerGroups->keys())
            ->where('is_available', true)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->get()
            ->groupBy('farmer_id');

        $customer = Auth::user();

        return view('Website.Checkout.index', compact('cartItems', 'farmerGroups', 'pickupSlotsByFarmer', 'cartTotal', 'customer'));
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login'));
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
            'pickup_slot' => 'nullable|array',
            'pickup_slot.*' => 'nullable|integer|exists:pickup_slots,id',
        ]);

        $cart = new Cart;
        $itemsByFarmer = $cart->groupedByFarmer();

        if ($itemsByFarmer->isEmpty()) {
            return redirect('/cart')->with('warning', 'Your basket is empty. Add a fresh pick first.');
        }

        foreach ($itemsByFarmer as $farmerId => $items) {
            $slot = PickupSlot::where('farmer_id', $farmerId)
                ->where('is_available', true)
                ->where('date', '>=', now()->toDateString())
                ->find($validated['pickup_slot'][$farmerId] ?? null);

            if (! $slot) {
                throw ValidationException::withMessages(['pickup_slot.' . $farmerId => 'Choose an available pickup time for each grower.']);
            }
        }

        if (Auth::check() && $itemsByFarmer->isNotEmpty()) {
            DB::transaction(function () use ($itemsByFarmer, $validated) {
                foreach ($itemsByFarmer as $farmerId => $items) {
                    $totalAmount = $items->sum('subtotal');

                    $order = Order::create([
                        'user_id' => Auth::id(),
                        'farmer_id' => $farmerId,
                        'pickup_slot_id' => $validated['pickup_slot'][$farmerId] ?? null,
                        'total_amount' => $totalAmount,
                        'status' => 'pending',
                        'order_date' => now(),
                        'notes' => $validated['notes'] ?? null,
                    ]);

                    foreach ($items as $item) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $item['product']->id,
                            'quantity' => $item['quantity'],
                            'price' => $item['product']->price,
                            'subtotal' => $item['subtotal'],
                        ]);
                    }
                }
            });

            $cart->clear();
        }

        return redirect('/checkout')->with('success', 'Your pre-order has been placed successfully.');
    }

    public function about(): View
    {
        $markets = Market::latest()->take(3)->get();
        $reviews = $this->communityReviews()->take(8)->get();

        return view('Website.About.index', compact('markets', 'reviews'));
    }

    protected function communityReviews(): Builder
    {
        return Review::with(['user:id,name', 'farmer:id,stall_name'])
            ->where('is_active', true)
            ->where('is_flagged', false)
            ->whereHas('user')
            ->whereHas('farmer', fn(Builder $query) => $query->where('approval_status', 'approved'))
            ->latest('id');
    }

    public function contact(): View
    {
        return view('Website.Contact.index');
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        return redirect('/contact')->with('success', 'Your message has been sent to the MarketLink team.');
    }

    // NOTE: showLogin / login / showRegister / register / logout used to live here,
    // but they were dead code — Laravel Fortify already registers /login, /register
    // and /logout (named 'login', 'register', 'logout') and those routes were
    // matching before these duplicate routes/web.php entries ever could. Auth is
    // now handled by Fortify: it renders the same Website.Auth.login /
    // Website.Auth.register views (see FortifyServiceProvider::boot()) and uses
    // App\Actions\Fortify\CreateNewUser for registration, which also assigns the
    // Spatie role — something this old register() method never did.

    public function index(): View
    {
        $user = Auth::user();

        $stats = [
            'total_orders' => $user->orders()->count(),
            'active_orders' => $user->orders()->whereIn('status', $this->activeStatuses())->count(),
            'completed_orders' => $user->orders()->whereIn('status', $this->completedStatuses())->count(),
            'favorites' => $user->favorites()->count(),
        ];

        $activeOrders = $user->orders()
            ->whereIn('status', $this->activeStatuses())
            ->with(['farmer.user', 'pickupSlot.market', 'items.product'])
            ->orderBy(PickupSlot::select('date')->whereColumn('pickup_slots.id', 'orders.pickup_slot_id')->limit(1))
            ->orderBy(PickupSlot::select('start_time')->whereColumn('pickup_slots.id', 'orders.pickup_slot_id')->limit(1))
            ->take(4)->get();

        $reorderItems = OrderItem::with('product.farmer')
            ->whereHas('order', fn($query) => $query->where('user_id', $user->id)->where('status', 'picked_up'))
            ->whereHas('product', fn($query) => $query->where('is_active', true))
            ->latest('id')->take(40)->get()->unique('product_id')->take(8);

        $savedFavorites = $user->favorites()->with([
            'product.farmer',
            'farmer' => fn($query) => $query->withCount(['products as available_products_count' => fn($products) => $products->where('is_active', true)->where('stock_quantity', '>', 0)]),
        ])->latest()->take(8)->get();

        $markets = Market::orderBy('city')->orderBy('name')->limit(50)->get();
        $stalls = FarmerProfile::where('approval_status', 'approved')->orderBy('city')->orderBy('stall_name')->limit(50)->get();
        $mapPlaces = $markets->map(fn($market) => [
            'name' => $market->name,
            'kind' => 'Market',
            'city' => $market->city,
            'address' => $market->address,
            'latitude' => $market->latitude,
            'longitude' => $market->longitude,
            'url' => url('/markets/' . $market->id),
        ])->concat($stalls->map(fn($stall) => [
            'name' => $stall->stall_name ?: $stall->business_name,
            'kind' => 'Farm stall',
            'city' => $stall->city,
            'address' => $stall->address,
            'latitude' => $stall->latitude,
            'longitude' => $stall->longitude,
            'url' => url('/farmers/' . $stall->id),
        ]))->filter(fn($place) => is_numeric($place['latitude']) && is_numeric($place['longitude'])
            && abs((float) $place['latitude']) <= 90 && abs((float) $place['longitude']) <= 180)->values();

        $unreadUpdates = Notification::where('user_id', $user->id)->where('is_read', false)->latest()->take(3)->get();

        return view('Website.Dashboard.index', compact('user', 'stats', 'activeOrders', 'reorderItems', 'savedFavorites', 'mapPlaces', 'unreadUpdates'));
    }

    public function cancelOrder(string $order): RedirectResponse
    {
        DB::transaction(function () use ($order): void {
            $customerOrder = Auth::user()->orders()->with('pickupSlot')->lockForUpdate()->findOrFail($order);
            if (! $customerOrder->canCancel()) {
                throw ValidationException::withMessages(['order' => 'This pre-order can no longer be cancelled. Contact the grower for help.']);
            }
            $customerOrder->update(['status' => 'cancelled']);
        });

        return back()->with('success', 'Your pre-order has been cancelled.');
    }

    public function reorderItem(string $item, Cart $cart): RedirectResponse
    {
        $orderItem = OrderItem::whereHas('order', fn($query) => $query->where('user_id', Auth::id())->where('status', 'picked_up'))->findOrFail($item);
        $cart->add($orderItem->product_id, $orderItem->quantity);

        return back()->with('success', 'Added to your basket at the current price. Choose a new pickup time at checkout.');
    }

    public function profile(): View
    {
        $user = Auth::user();

        return view('Website.Dashboard.profile', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            if (! $request->filled('current_password') || ! Hash::check($request->input('current_password'), $user->password)) {
                return back()->withErrors(['current_password' => 'Your current password is incorrect.'])->withInput();
            }

            $user->password = Hash::make($validated['password']);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->address = $validated['address'] ?? $user->address;
        $user->save();

        return back()->with('success', 'Your profile has been updated.');
    }

    public function orders(Request $request): View
    {
        $query = Auth::user()->orders()->with(['farmer.user', 'pickupSlot.market', 'items']);

        if ($request->input('status') === 'active') {
            $query->whereIn('status', $this->activeStatuses());
        } elseif (in_array($request->input('status'), ['pending', 'confirmed', 'ready', 'picked_up', 'cancelled'], true)) {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->latest('order_date')->paginate(8)->withQueryString();

        $statusCounts = Auth::user()->orders()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('Website.Dashboard.orders', compact('orders', 'statusCounts'));
    }

    public function orderDetail(string $order): View
    {
        $order = Auth::user()->orders()
            ->with(['farmer.user', 'pickupSlot.market', 'items.product'])
            ->findOrFail($order);

        return view('Website.Dashboard.order-detail', compact('order'));
    }

    public function reviews(): View
    {
        $reviews = Auth::user()->reviews()
            ->with(['farmer.user', 'product', 'reply'])
            ->latest()
            ->paginate(8);

        $reviewableItems = OrderItem::with('product')
            ->whereHas('order', fn($query) => $query->where('user_id', Auth::id())->where('status', 'picked_up'))
            ->whereHas('product')->latest('id')->get()->unique('product_id');

        return view('Website.Dashboard.reviews', compact('reviews', 'reviewableItems'));
    }

    public function storeReview(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => ['required', 'integer'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'max:2000'],
        ]);
        $item = OrderItem::with('product')->whereHas('order', fn($query) => $query
            ->where('user_id', Auth::id())->where('status', 'picked_up'))
            ->whereHas('product')->findOrFail($validated['item_id']);
        Review::updateOrCreate(['user_id' => Auth::id(), 'product_id' => $item->product_id], [
            'farmer_id' => $item->product->farmer_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Thank you. Your review has been saved.');
    }

    public function saveFavorite(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kind' => ['required', 'in:product,farmer'],
            'id' => ['required', 'integer'],
        ]);
        if ($validated['kind'] === 'product') {
            Product::where('is_active', true)->findOrFail($validated['id']);
        } else {
            FarmerProfile::where('approval_status', 'approved')->findOrFail($validated['id']);
        }
        Auth::user()->favorites()->firstOrCreate([$validated['kind'] . '_id' => $validated['id']]);

        return back()->with('success', 'Saved to your favorites. Find it in your dashboard.');
    }

    public function favorites(): View
    {
        $favorites = Auth::user()->favorites()
            ->with(['farmer' => fn($query) => $query->withCount(['products as available_products_count' => fn($products) => $products->where('is_active', true)->where('stock_quantity', '>', 0)]), 'product.farmer'])
            ->latest()
            ->get();

        return view('Website.Dashboard.favorites', compact('favorites'));
    }

    public function removeFavorite(string $favorite): RedirectResponse
    {
        Auth::user()->favorites()->findOrFail($favorite)->delete();

        return back()->with('success', 'Removed from your favorites.');
    }

    public function notifications(): View
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('Website.Dashboard.notifications', compact('notifications'));
    }

    public function markNotificationRead(string $notification): RedirectResponse
    {
        Notification::where('user_id', Auth::id())->findOrFail($notification)->update(['is_read' => true]);

        return back();
    }

    /**
     * Order statuses that count as still "in progress" for a customer.
     */
    protected function activeStatuses(): array
    {
        return ['pending', 'confirmed', 'ready'];
    }

    /**
     * Order statuses that count as finished for a customer.
     */
    protected function completedStatuses(): array
    {
        return ['picked_up'];
    }
}
