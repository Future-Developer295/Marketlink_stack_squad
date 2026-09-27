<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FarmerProfile;
use App\Models\Market;
use App\Models\MarketFarmer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PickupSlot;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Notification;
use App\Services\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

        $markets = Market::withCount('marketFarmers')
            ->latest()
            ->take(3)
            ->get();

        $farmers = FarmerProfile::where('approval_status', 'approved')
            ->with('user')
            ->withCount([
                'products' => function ($query) {
                    $query->where('is_active', true);
                }
            ])
            ->withAvg([
                'reviews' => function ($query) {
                    $query->where('is_active', true);
                }
            ], 'rating')
            ->latest()
            ->take(3)
            ->get();

        $products = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->whereHas('farmer', function ($query) {
                $query->where('approval_status', 'approved');
            })
            ->with([
                'farmer.user',
                'category'
            ])
            ->latest()
            ->take(4)
            ->get();

        return view(
            'Website.Home.index',
            compact(
                'stats',
                'markets',
                'farmers',
                'products'
            )
        );
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

        $markets = $query
            ->paginate(9)
            ->withQueryString();

        $cities = Market::whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view(
            'Website.Markets.index',
            compact('markets', 'cities')
        );
    }

    public function marketDetail(Request $request, string $market): View
    {
        $market = Market::withCount('marketFarmers')
            ->findOrFail($market);

        $farmerUserIds = $market->marketFarmers()
            ->where('is_active', true)
            ->pluck('farmer_id');

        $farmers = FarmerProfile::where('approval_status', 'approved')
            ->whereIn('user_id', $farmerUserIds)
            ->with('user')
            ->withCount([
                'products' => function ($query) {
                    $query->where('is_active', true);
                }
            ])
            ->get();

        $farmerProfileIds = $farmers->pluck('id');

        $products = Product::where('is_active', true)
            ->whereIn('farmer_id', $farmerProfileIds)
            ->with([
                'farmer.user',
                'category'
            ])
            ->latest()
            ->take(8)
            ->get();

        $reviews = Review::whereIn('farmer_id', $farmerProfileIds)
            ->where('is_active', true)
            ->with('user')
            ->latest()
            ->take(6)
            ->get();

        $ratingAverage = round(
            (float) Review::whereIn('farmer_id', $farmerProfileIds)
                ->where('is_active', true)
                ->avg('rating'),
            1
        );

        $ratingCount = Review::whereIn('farmer_id', $farmerProfileIds)
            ->where('is_active', true)
            ->count();

        $pickupSlots = PickupSlot::where('market_id', $market->id)
            ->where('is_available', true)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->take(6)
            ->get();

        return view(
            'Website.Markets.view',
            compact(
                'market',
                'farmers',
                'products',
                'reviews',
                'ratingAverage',
                'ratingCount',
                'pickupSlots'
            )
        );
    }

    public function farmers(Request $request): View
    {
        $query = FarmerProfile::where('approval_status', 'approved')
            ->with('user')
            ->withCount([
                'products' => function ($query) {
                    $query->where('is_active', true);
                },
                'reviews' => function ($query) {
                    $query->where('is_active', true);
                }
            ])
            ->withAvg([
                'reviews' => function ($query) {
                    $query->where('is_active', true);
                }
            ], 'rating');

        if ($request->filled('q')) {
            $search = $request->input('q');

            $query->where(function ($inner) use ($search) {
                $inner->where('stall_name', 'like', "%{$search}%")
                    ->orWhere('business_name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        $sort = $request->input('sort', 'top_rated');

        match ($sort) {
            'newest' => $query->latest(),
            'most_products' => $query->orderByDesc('products_count'),
            'name' => $query->orderBy('stall_name'),
            default => $query->orderByDesc('reviews_avg_rating'),
        };

        $farmers = $query
            ->paginate(9)
            ->withQueryString();

        $cities = FarmerProfile::where('approval_status', 'approved')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view(
            'Website.Farmers.index',
            compact('farmers', 'cities')
        );
    }

    public function farmerDetail(Request $request, string $farmer): View
    {
        $farmer = FarmerProfile::where('approval_status', 'approved')
            ->with('user')
            ->withCount([
                'products' => function ($query) {
                    $query->where('is_active', true);
                },
                'reviews' => function ($query) {
                    $query->where('is_active', true);
                }
            ])
            ->withAvg([
                'reviews' => function ($query) {
                    $query->where('is_active', true);
                }
            ], 'rating')
            ->findOrFail($farmer);

        $ratingAverage = round((float) ($farmer->reviews_avg_rating ?? 0), 1);
        $ratingCount = (int) ($farmer->reviews_count ?? 0);

        return view(
            'Website.Farmers.view',
            compact(
                'farmer',
                'ratingAverage',
                'ratingCount'
            )
        );
    }

    public function products(Request $request): View
    {
        $query = Product::with([
            'farmer.user',
            'category'
        ])
            ->whereHas('farmer', function ($query) {
                $query->where(
                    'approval_status',
                    'approved'
                );
            });

        if ($request->filled('q')) {
            $search = $request->input('q');

            $query->where(function ($inner) use ($search) {
                $inner->where(
                    'name',
                    'like',
                    "%{$search}%"
                )
                    ->orWhereHas(
                        'farmer',
                        function ($farmerQuery) use ($search) {
                            $farmerQuery
                                ->where(
                                    'stall_name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'business_name',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
            });
        }

        if ($request->filled('category_id')) {
            $query->where(
                'category_id',
                $request->input('category_id')
            );
        }

        if ($request->filled('max_price')) {
            $query->where(
                'price',
                '<=',
                $request->input('max_price')
            );
        }

        if ($request->boolean('in_stock_only')) {
            $query->where(
                'stock_quantity',
                '>',
                0
            );
        }

        $query->where('is_active', true);

        $sort = $request->input(
            'sort',
            'newest'
        );

        match ($sort) {
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            default => $query->latest(),
        };

        $products = $query
            ->paginate(9)
            ->withQueryString();

        $categories = Category::withCount([
            'products' => function ($inner) {
                $inner->where('is_active', true);
            }
        ])->get();

        return view(
            'Website.Products.index',
            compact(
                'products',
                'categories'
            )
        );
    }

    public function productDetail(
        Request $request,
        string $product
    ): View {
        $product = Product::where('is_active', true)
            ->whereHas('farmer', function ($query) {
                $query->where(
                    'approval_status',
                    'approved'
                );
            })
            ->with([
                'farmer.user',
                'category'
            ])
            ->findOrFail($product);

        $reviews = $product->reviews()
            ->where('is_active', true)
            ->with([
                'user',
                'reply'
            ])
            ->latest()
            ->take(6)
            ->get();

        $ratingAverage = round(
            (float) $product->reviews()
                ->where('is_active', true)
                ->avg('rating'),
            1
        );

        $ratingCount = $product->reviews()
            ->where('is_active', true)
            ->count();

        $relatedProducts = Product::where(
            'is_active',
            true
        )
            ->where(
                'id',
                '!=',
                $product->id
            )
            ->whereHas('farmer', function ($query) {
                $query->where(
                    'approval_status',
                    'approved'
                );
            })
            ->where(function ($inner) use ($product) {
                $inner
                    ->where(
                        'farmer_id',
                        $product->farmer_id
                    )
                    ->orWhere(
                        'category_id',
                        $product->category_id
                    );
            })
            ->with('farmer.user')
            ->take(4)
            ->get();

        $pickupSlots = PickupSlot::where(
            'farmer_id',
            $product->farmer_id
        )
            ->where('is_available', true)
            ->where(
                'date',
                '>=',
                now()->toDateString()
            )
            ->orderBy('date')
            ->get();

        return view(
            'Website.Products.view',
            compact(
                'product',
                'reviews',
                'ratingAverage',
                'ratingCount',
                'relatedProducts',
                'pickupSlots'
            )
        );
    }

    public function cart(): View
    {
        $cart = new Cart();

        $cartItems = $cart->items();

        $farmerGroups = $cart
            ->groupedByFarmer();

        $cartTotal = $cart->total();

        return view(
            'Website.Cart.index',
            compact(
                'cartItems',
                'farmerGroups',
                'cartTotal'
            )
        );
    }

    public function addToCart(
        Request $request,
        string $product
    ): RedirectResponse {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        $productModel = Product::where(
            'is_active',
            true
        )
            ->where(
                'stock_quantity',
                '>',
                0
            )
            ->whereHas('farmer', function ($query) {
                $query->where(
                    'approval_status',
                    'approved'
                );
            })
            ->findOrFail($product);

        $quantity = (int) $request->input(
            'quantity',
            1
        );

        if ($quantity > $productModel->stock_quantity) {
            return back()->withErrors([
                'quantity' => 'Requested quantity is not available in stock.'
            ]);
        }

        (new Cart())->add(
            (int) $product,
            $quantity
        );

        return back()->with(
            'success',
            'Product added to your cart.'
        );
    }

    public function updateCartItem(
        Request $request,
        string $product
    ): RedirectResponse {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        if ((int) $request->quantity > 0) {
            $productModel = Product::where(
                'is_active',
                true
            )
                ->findOrFail($product);

            if (
                (int) $request->quantity >
                $productModel->stock_quantity
            ) {
                return back()->withErrors([
                    'quantity' => 'Requested quantity is not available in stock.'
                ]);
            }
        }

        (new Cart())->update(
            (int) $product,
            (int) $request->input('quantity')
        );

        return back()->with(
            'success',
            'Cart updated.'
        );
    }

    public function removeFromCart(
        string $product
    ): RedirectResponse {
        (new Cart())->remove(
            (int) $product
        );

        return back()->with(
            'success',
            'Product removed from your cart.'
        );
    }

    public function checkout(): View
    {
        $cart = new Cart();

        $cartItems = $cart->items();

        $farmerGroups = $cart
            ->groupedByFarmer();

        $cartTotal = $cart->total();

        $pickupSlotsByFarmer = PickupSlot::whereIn(
            'farmer_id',
            $farmerGroups->keys()
        )
            ->where('is_available', true)
            ->where(
                'date',
                '>=',
                now()->toDateString()
            )
            ->orderBy('date')
            ->get()
            ->groupBy('farmer_id');

        $customer = Auth::user();

        return view(
            'Website.Checkout.index',
            compact(
                'cartItems',
                'farmerGroups',
                'pickupSlotsByFarmer',
                'cartTotal',
                'customer'
            )
        );
    }

    public function placeOrder(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
            'pickup_slot' => 'nullable|array',
            'pickup_slot.*' => 'nullable|integer|exists:pickup_slots,id',
        ]);

        $cart = new Cart();

        $itemsByFarmer = $cart
            ->groupedByFarmer();

        if (!Auth::check()) {
            return redirect()
                ->route('login');
        }

        if ($itemsByFarmer->isEmpty()) {
            return redirect()
                ->route('products')
                ->with(
                    'error',
                    'Your cart is empty.'
                );
        }

        DB::transaction(
            function () use (
                $itemsByFarmer,
                $validated
            ) {
                foreach (
                    $itemsByFarmer as
                    $farmerId => $items
                ) {
                    $totalAmount = $items->sum(
                        'subtotal'
                    );

                    $order = Order::create([
                        'user_id' => Auth::id(),
                        'farmer_id' => $farmerId,
                        'pickup_slot_id' =>
                            $validated['pickup_slot'][$farmerId]
                            ?? null,
                        'total_amount' => $totalAmount,
                        'status' => 'pending',
                        'order_date' => now(),
                        'notes' =>
                            $validated['notes']
                            ?? null,
                    ]);

                    foreach (
                        $items as $item
                    ) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' =>
                                $item['product']->id,
                            'quantity' =>
                                $item['quantity'],
                            'price' =>
                                $item['product']->price,
                            'subtotal' =>
                                $item['subtotal'],
                        ]);
                    }
                }
            }
        );

        $cart->clear();

        return redirect()
            ->route('customer_orders')
            ->with(
                'success',
                'Your pre-order has been placed successfully.'
            );
    }

    public function about(): View
    {
        $markets = Market::latest()
            ->take(3)
            ->get();

        $farmers = FarmerProfile::where(
            'approval_status',
            'approved'
        )
            ->with('user')
            ->latest()
            ->take(3)
            ->get();

        return view(
            'Website.About.index',
            compact(
                'markets',
                'farmers'
            )
        );
    }

    public function contact(): View
    {
        return view(
            'Website.Contact.index'
        );
    }

    public function submitContact(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'full_name' =>
                'required|string|max:255',
            'email' =>
                'required|email|max:255',
            'subject' =>
                'required|string|max:255',
            'message' =>
                'required|string|max:2000',
        ]);

        return redirect()
            ->route('contact')
            ->with(
                'success',
                'Your message has been sent to the MarketLink team.'
            );
    }

    public function index(): View
    {
        $user = Auth::user();

        $stats = [
            'total_orders' =>
                $user->orders()->count(),

            'active_orders' =>
                $user->orders()
                    ->whereIn(
                        'status',
                        $this->activeStatuses()
                    )
                    ->count(),

            'completed_orders' =>
                $user->orders()
                    ->whereIn(
                        'status',
                        $this->completedStatuses()
                    )
                    ->count(),

            'favorites' =>
                $user->favorites()->count(),
        ];

        $recentOrders = $user->orders()
            ->with([
                'farmer.user',
                'items.product'
            ])
            ->latest('order_date')
            ->take(5)
            ->get();

        return view(
            'Website.Dashboard.index',
            compact(
                'user',
                'stats',
                'recentOrders'
            )
        );
    }

    public function profile(): View
    {
        $user = User::findOrFail(
            Auth::id()
        );

        return view(
            'Website.Dashboard.profile',
            compact('user')
        );
    }

    public function updateProfile(
        Request $request
    ): RedirectResponse {
        $user = User::findOrFail(
            Auth::id()
        );

        $validated = $request->validate([
            'name' =>
                'required|string|max:255',

            'email' =>
                'required|email|max:255|unique:users,email,' .
                $user->id,

            'phone' =>
                'nullable|string|max:20',

            'address' =>
                'required|string|max:500',

            'profile_photo' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'current_password' =>
                'nullable|string',

            'password' =>
                'nullable|string|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            if (
                !$request->filled(
                    'current_password'
                ) ||
                !Hash::check(
                    $request->input(
                        'current_password'
                    ),
                    $user->password
                )
            ) {
                return back()
                    ->withErrors([
                        'current_password' =>
                            'Your current password is incorrect.'
                    ])
                    ->withInput();
            }

            $user->password = Hash::make(
                $validated['password']
            );
        }

        $user->name =
            $validated['name'];

        $user->email =
            $validated['email'];

        $user->phone =
            $validated['phone']
            ?? null;

        $user->address =
            $validated['address'];

        if (
            $request->hasFile(
                'profile_photo'
            )
        ) {
            $user->updateProfilePhoto(
                $request->file(
                    'profile_photo'
                )
            );
        }

        $user->save();

        return redirect()
            ->route('customer_profile')
            ->with(
                'success',
                'Your profile has been updated.'
            );
    }

    public function orders(
        Request $request
    ): View {
        $query = Auth::user()
            ->orders()
            ->with([
                'farmer.user',
                'pickupSlot',
                'items.product'
            ]);

        if (
            $request->filled(
                'status'
            )
        ) {
            $query->where(
                'status',
                $request->input(
                    'status'
                )
            );
        }

        $orders = $query
            ->latest('order_date')
            ->paginate(8)
            ->withQueryString();

        $statusCounts = Auth::user()
            ->orders()
            ->selectRaw(
                'status, count(*) as total'
            )
            ->groupBy('status')
            ->pluck(
                'total',
                'status'
            );

        return view(
            'Website.Dashboard.orders',
            compact(
                'orders',
                'statusCounts'
            )
        );
    }

    public function orderDetail(
        string $order
    ): View {
        $order = Auth::user()
            ->orders()
            ->with([
                'farmer.user',
                'pickupSlot.market',
                'items.product'
            ])
            ->findOrFail($order);

        return view(
            'Website.Dashboard.order-detail',
            compact('order')
        );
    }

    public function reviews(): View
    {
        $reviews = Auth::user()
            ->reviews()
            ->with([
                'farmer.user',
                'product',
                'reply'
            ])
            ->latest()
            ->paginate(8);

        return view(
            'Website.Dashboard.reviews',
            compact('reviews')
        );
    }

    public function favorites(): View
    {
        $favorites = Auth::user()
            ->favorites()
            ->with([
                'farmer.user',
                'product.farmer'
            ])
            ->latest()
            ->get();

        return view(
            'Website.Dashboard.favorites',
            compact('favorites')
        );
    }

    public function removeFavorite(
        string $favorite
    ): RedirectResponse {
        Favorite::where(
            'id',
            $favorite
        )
            ->where(
                'user_id',
                Auth::id()
            )
            ->delete();

        return back()->with(
            'success',
            'Removed from your favorites.'
        );
    }

    public function notifications(): View
    {
        $notifications = Notification::where(
            'user_id',
            Auth::id()
        )
            ->latest()
            ->paginate(12);

        return view(
            'Website.Dashboard.notifications',
            compact('notifications')
        );
    }

    public function markNotificationRead(
        string $notification
    ): RedirectResponse {
        Notification::where(
            'id',
            $notification
        )
            ->where(
                'user_id',
                Auth::id()
            )
            ->update([
                'is_read' => true
            ]);

        return back();
    }

    protected function activeStatuses(): array
    {
        return [
            'pending',
            'confirmed',
            'ready'
        ];
    }

    protected function completedStatuses(): array
    {
        return [
            'picked_up'
        ];
    }
}