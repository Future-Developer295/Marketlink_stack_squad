<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FarmerProfile;
use App\Models\Favorite;
use App\Models\Market;
use App\Models\MarketFarmer;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PickupSlot;
use App\Models\Product;
use App\Models\Review;
use App\Services\Cart;
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
        $stats = [
            'markets' => Market::count(),
            'farmers' => FarmerProfile::where('approval_status', 'approved')->count(),
            'products' => Product::where('is_active', true)->count(),
            'orders' => Order::count(),
        ];

        $markets = Market::withCount('marketFarmers')->latest()->take(3)->get();

        $farmers = FarmerProfile::where('approval_status', 'approved')
            ->with('user')
            ->withCount('products')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(3)
            ->get();

        $products = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->with(['farmer.user', 'category'])
            ->latest()
            ->take(4)
            ->get();

        return view('Website.Home.index', compact('stats', 'markets', 'farmers', 'products'));
    }

    public function markets(Request $request): View
    {
        $query = Market::withCount('marketFarmers');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($inner) use ($search) {
                $inner->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'name' => $query->orderBy('name'),
            'most_farmers' => $query->orderByDesc('market_farmers_count'),
            default => $query->latest(),
        };

        $markets = $query->paginate(9)->withQueryString();

        $cities = Market::whereNotNull('city')->distinct()->orderBy('city')->pluck('city');

        return view('Website.Markets.index', compact('markets', 'cities'));
    }

    public function marketDetail(Request $request, string $market): View
    {
        $market = Market::withCount('marketFarmers')->findOrFail($market);

        $farmerUserIds = $market->marketFarmers()->where('is_active', true)->pluck('farmer_id');

        $farmers = FarmerProfile::where('approval_status', 'approved')
            ->whereIn('user_id', $farmerUserIds)
            ->with('user')
            ->withCount('products')
            ->get();

        $farmerProfileIds = $farmers->pluck('id');

        $products = Product::where('is_active', true)
            ->whereIn('farmer_id', $farmerProfileIds)
            ->with(['farmer.user', 'category'])
            ->latest()
            ->take(8)
            ->get();

        $reviews = Review::whereIn('farmer_id', $farmerProfileIds)
            ->where('is_active', true)
            ->with('user')
            ->latest()
            ->take(6)
            ->get();

        $ratingAverage = round((float) Review::whereIn('farmer_id', $farmerProfileIds)->where('is_active', true)->avg('rating'), 1);
        $ratingCount = Review::whereIn('farmer_id', $farmerProfileIds)->where('is_active', true)->count();

        $pickupSlots = PickupSlot::where('market_id', $market->id)
            ->where('is_available', true)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->take(6)
            ->get();

        return view('Website.Markets.view', compact('market', 'farmers', 'products', 'reviews', 'ratingAverage', 'ratingCount', 'pickupSlots'));
    }

    public function farmers(Request $request): View
    {
        $query = FarmerProfile::where('approval_status', 'approved')
            ->with('user')
            ->withCount('products')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($inner) use ($search) {
                $inner->where('stall_name', 'like', "%{$search}%")
                    ->orWhere('business_name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        $sort = $request->input('sort', 'top_rated');
        match ($sort) {
            'newest' => $query->latest(),
            'most_products' => $query->orderByDesc('products_count'),
            default => $query->orderByDesc('reviews_avg_rating'),
        };

        $farmers = $query->paginate(9)->withQueryString();

        $cities = FarmerProfile::where('approval_status', 'approved')
            ->whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('Website.Farmers.index', compact('farmers', 'cities'));
    }

    public function farmerDetail(Request $request, string $farmer): View
    {
        $farmer = FarmerProfile::where('approval_status', 'approved')
            ->with('user')
            ->withCount('products')
            ->findOrFail($farmer);

        $products = $farmer->products()->where('is_active', true)->with('category')->latest()->get();

        $reviews = $farmer->reviews()->where('is_active', true)->with(['user', 'reply'])->latest()->take(6)->get();

        $ratingAverage = round((float) $farmer->reviews()->where('is_active', true)->avg('rating'), 1);
        $ratingCount = $farmer->reviews()->where('is_active', true)->count();

        $marketIds = MarketFarmer::where('farmer_id', $farmer->user_id)->where('is_active', true)->pluck('market_id');
        $markets = Market::whereIn('id', $marketIds)->get();

        $weeklyStock = $farmer->weeklyStockTemplates()
            ->where('is_active', true)
            ->with('product')
            ->orderBy('day_of_week')
            ->get();

        return view('Website.Farmers.view', compact('farmer', 'products', 'reviews', 'ratingAverage', 'ratingCount', 'markets', 'weeklyStock'));
    }

    public function products(Request $request): View
    {
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

        return view('Website.Products.index', compact('products', 'categories'));
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

    public function updateCartItem(Request $request, string $product): RedirectResponse|JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        (new Cart)->update((int) $product, (int) $request->input('quantity'));

        if ($request->expectsJson()) {
            return $this->cartResponse('Basket updated.');
        }

        return back()->with('success', 'Cart updated.');
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
                throw ValidationException::withMessages(['pickup_slot.'.$farmerId => 'Choose an available pickup time for each grower.']);
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

        return view('Website.About.index', compact('markets'));
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
            ->whereHas('order', fn ($query) => $query->where('user_id', $user->id)->where('status', 'picked_up'))
            ->whereHas('product', fn ($query) => $query->where('is_active', true))
            ->latest('id')->take(40)->get()->unique('product_id')->take(8);

        $savedFavorites = $user->favorites()->with([
            'product.farmer',
            'farmer' => fn ($query) => $query->withCount(['products as available_products_count' => fn ($products) => $products->where('is_active', true)->where('stock_quantity', '>', 0)]),
        ])->latest()->take(8)->get();

        $markets = Market::orderBy('city')->orderBy('name')->limit(50)->get();
        $stalls = FarmerProfile::where('approval_status', 'approved')->orderBy('city')->orderBy('stall_name')->limit(50)->get();
        $mapPlaces = $markets->map(fn ($market) => [
            'name' => $market->name, 'kind' => 'Market', 'city' => $market->city,
            'address' => $market->address, 'latitude' => $market->latitude, 'longitude' => $market->longitude,
            'url' => url('/markets/'.$market->id),
        ])->concat($stalls->map(fn ($stall) => [
            'name' => $stall->stall_name ?: $stall->business_name, 'kind' => 'Farm stall', 'city' => $stall->city,
            'address' => $stall->address, 'latitude' => $stall->latitude, 'longitude' => $stall->longitude,
            'url' => url('/farmers/'.$stall->id),
        ]))->filter(fn ($place) => is_numeric($place['latitude']) && is_numeric($place['longitude'])
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
        $orderItem = OrderItem::whereHas('order', fn ($query) => $query->where('user_id', Auth::id())->where('status', 'picked_up'))->findOrFail($item);
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
            'email' => 'required|email|unique:users,email,'.$user->id,
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
        $query = Auth::user()->orders()->with(['farmer.user', 'pickupSlot', 'items']);

        if ($request->filled('status')) {
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

        return view('Website.Dashboard.reviews', compact('reviews'));
    }

    public function favorites(): View
    {
        $favorites = Auth::user()->favorites()
            ->with(['farmer.user', 'product.farmer'])
            ->latest()
            ->get();

        return view('Website.Dashboard.favorites', compact('favorites'));
    }

    public function removeFavorite(string $favorite): RedirectResponse
    {
        Favorite::where('id', $favorite)->where('user_id', Auth::id())->delete();

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
        Notification::where('id', $notification)->where('user_id', Auth::id())->update(['is_read' => true]);

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
