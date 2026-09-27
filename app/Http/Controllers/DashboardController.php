<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\FarmerProfile;
use App\Models\Market;
use App\Models\MarketFarmer;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PickupSlot;
use App\Models\Product;
use App\Models\Report;
use App\Models\Review;
use App\Models\ReviewReply;
use App\Models\User;
use App\Models\WeeklyStockTemplate;
use App\Support\ActivityLogger;
use App\Support\SimpleXlsxWriter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin_dashboard');
        }

        if (auth()->user()->hasRole('farmer')) {
            return redirect()->route('farmer.dashboard');
        }

        return redirect()->route('customer_dashboard');
    }

    protected function currentFarmer()
    {
        return FarmerProfile::where('user_id', Auth::id())->firstOrFail();
    }

    protected function savePublicImage($file, string $directory): string
    {
        $path = public_path($directory);

        if (! file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $name = time() . '_' . uniqid() . '.' . $file->extension();
        $file->move($path, $name);

        return $name;
    }

    protected function deletePublicImage(?string $file, string $directory): void
    {
        if (! $file) {
            return;
        }

        $path = public_path($directory . '/' . $file);

        if (file_exists($path)) {
            unlink($path);
        }
    }

    protected function ensureFarmerProfile(User $user): FarmerProfile
    {
        return FarmerProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'stall_name' => $user->name,
                'business_name' => $user->name,
                'description' => 'Farmer profile pending completion.',
                'address' => $user->address ?: 'N/A',
                'city' => 'N/A',
                'state' => 'N/A',
                'country' => 'N/A',
                'latitude' => 0,
                'longitude' => 0,
                'operating_days' => 'Mon',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'approval_status' => 'pending',
            ]
        );
    }

    public function farmerDashboard()
    {
        $farmer = FarmerProfile::where('user_id', Auth::id())->first();
        $products = 0;
        $markets = 0;
        $pendingOrders = 0;
        $openSlots = 0;
        $averageRating = 0;
        $totalReviews = 0;
        $recentOrders = collect();
        $revenueTrendLabels = [];
        $revenueTrendData = [];
        $ordersTrendData = [];
        $orderStatusLabels = ['Pending', 'Confirmed', 'Ready', 'Picked Up', 'Cancelled'];
        $orderStatusData = [0, 0, 0, 0, 0];
        $topProductLabels = [];
        $topProductData = [];

        if ($farmer) {
            $products = Product::where('farmer_id', $farmer->id)
                ->where('is_active', true)
                ->count();

            $markets = MarketFarmer::where('farmer_id', Auth::id())
                ->where('is_active', true)
                ->count();

            $pendingOrders = Order::where('farmer_id', $farmer->id)
                ->where('status', 'pending')
                ->count();

            $openSlots = PickupSlot::where('farmer_id', $farmer->id)
                ->where('is_available', true)
                ->where('date', '>=', now()->toDateString())
                ->count();

            $averageRating = (float) (Review::where('farmer_id', $farmer->id)
                ->where('is_active', true)
                ->avg('rating') ?? 0);

            $totalReviews = Review::where('farmer_id', $farmer->id)
                ->where('is_active', true)
                ->count();

            $recentOrders = Order::with(['user', 'pickupSlot', 'items.product'])
                ->where('farmer_id', $farmer->id)
                ->latest('order_date')
                ->take(5)
                ->get();

            for ($i = 6; $i >= 0; $i--) {
                $day = now()->subDays($i);
                $dayOrders = Order::where('farmer_id', $farmer->id)
                    ->whereDate('order_date', $day->toDateString())
                    ->get();

                $revenueTrendLabels[] = $day->format('D');
                $revenueTrendData[] = (float) $dayOrders->sum('total_amount');
                $ordersTrendData[] = $dayOrders->count();
            }

            $statusKeys = ['pending', 'confirmed', 'ready', 'picked_up', 'cancelled'];
            $statusCounts = Order::where('farmer_id', $farmer->id)
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $orderStatusData = array_map(
                fn($key) => (int) ($statusCounts[$key] ?? 0),
                $statusKeys
            );

            $topProducts = OrderItem::select('product_id')
                ->selectRaw('SUM(quantity) as units_sold')
                ->whereHas('order', function ($query) use ($farmer) {
                    $query->where('farmer_id', $farmer->id)
                        ->where('status', '!=', 'cancelled');
                })
                ->groupBy('product_id')
                ->orderByDesc('units_sold')
                ->take(5)
                ->with('product')
                ->get();

            $topProductLabels = $topProducts
                ->map(fn($item) => $item->product->name ?? 'Unknown')
                ->values()
                ->all();

            $topProductData = $topProducts
                ->map(fn($item) => (int) $item->units_sold)
                ->values()
                ->all();
        }

        return view('Dashboard.farmer-dashboard', compact(
            'farmer',
            'products',
            'markets',
            'pendingOrders',
            'openSlots',
            'averageRating',
            'totalReviews',
            'recentOrders',
            'revenueTrendLabels',
            'revenueTrendData',
            'ordersTrendData',
            'orderStatusLabels',
            'orderStatusData',
            'topProductLabels',
            'topProductData'
        ));
    }

    public function roles()
    {
        $roles = Role::with('permissions')->get();

        return view('Dashboard.Roles.index', compact('roles'));
    }

    public function roleAdd()
    {
        $permissions = Permission::orderBy('name')->get();

        $permissionGroups = [];

        foreach ($permissions as $permission) {
            if (str_contains($permission->name, ' ')) {
                $parts = explode(' ', $permission->name, 2);
                $module = ucfirst($parts[1]);
            } else {
                $module = 'Dashboard';
            }

            $permissionGroups[$module][] = $permission;
        }

        return view(
            'Dashboard.Roles.add',
            compact('permissionGroups')
        );
    }

    public function roleStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()
            ->route('roles')
            ->with('success', 'Role created successfully.');
    }

    public function roleEdit($id)
    {
        $role = Role::findOrFail($id);

        $permissions = Permission::orderBy('name')->get();

        $permissionGroups = [];

        foreach ($permissions as $permission) {
            if (str_contains($permission->name, ' ')) {
                $parts = explode(' ', $permission->name, 2);
                $module = ucfirst($parts[1]);
            } else {
                $module = 'Dashboard';
            }

            $permissionGroups[$module][] = $permission;
        }

        return view(
            'Dashboard.Roles.edit',
            compact('role', 'permissionGroups')
        );
    }

    public function roleView($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        return view('Dashboard.Roles.view', compact('role'));
    }

    public function roleUpdate(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()
            ->route('roles')
            ->with('success', 'Role updated successfully.');
    }

    public function roleDelete($id)
    {
        $role = Role::findOrFail($id);

        $roleName = $role->name;

        $role->delete();

        ActivityLogger::log(
            'deleted',
            "Deleted role \"{$roleName}\"",
            Role::class,
            $id
        );

        return redirect()
            ->route('roles')
            ->with('success', 'Role deleted successfully.');
    }

    public function permissions()
    {
        $permissions = Permission::orderBy('name')->get();

        return view(
            'Dashboard.Permissions.index',
            compact('permissions')
        );
    }

    public function permissionAdd()
    {
        return view('Dashboard.Permissions.add');
    }

    public function permissionStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        return redirect()
            ->route('permissions')
            ->with('success', 'Permission created successfully.');
    }

    public function permissionEdit($id)
    {
        $permission = Permission::findOrFail($id);

        return view(
            'Dashboard.Permissions.edit',
            compact('permission')
        );
    }

    public function permissionView($id)
    {
        $permission = Permission::with('roles')
            ->findOrFail($id);

        return view(
            'Dashboard.Permissions.view',
            compact('permission')
        );
    }

    public function permissionUpdate(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);

        $permission->update([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('permissions')
            ->with('success', 'Permission updated successfully.');
    }

    public function permissionDelete($id)
    {
        $permission = Permission::findOrFail($id);

        $permissionName = $permission->name;

        $permission->delete();

        ActivityLogger::log(
            'deleted',
            "Deleted permission \"{$permissionName}\"",
            Permission::class,
            $id
        );

        return redirect()
            ->route('permissions')
            ->with('success', 'Permission deleted successfully.');
    }

    public function adminDashboard()
    {
        $totalUsers = User::count();

        $pendingFarmers = FarmerProfile::where(
            'approval_status',
            'pending'
        )->count();

        $activeMarkets = Market::count();
        $totalProducts = Product::count();

        $flaggedReviews = Review::where(
            'is_flagged',
            true
        )->count();

        $totalFarmers = User::where('role', 'farmer')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalOrders = Order::count();

        $pendingFarmerList = FarmerProfile::with('user')
            ->where('approval_status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $revenueTrendLabels = [];
        $revenueTrendData = [];
        $ordersTrendData = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);

            $dayOrders = Order::whereDate(
                'order_date',
                $day->toDateString()
            )->get();

            $revenueTrendLabels[] = $day->format('D');
            $revenueTrendData[] = (float) $dayOrders->sum('total_amount');
            $ordersTrendData[] = $dayOrders->count();
        }

        $orderStatusLabels = [
            'Pending',
            'Confirmed',
            'Ready',
            'Picked Up',
            'Cancelled',
        ];

        $statusKeys = [
            'pending',
            'confirmed',
            'ready',
            'picked_up',
            'cancelled',
        ];

        $statusCounts = Order::selectRaw(
            'status, count(*) as total'
        )
            ->groupBy('status')
            ->pluck('total', 'status');

        $orderStatusData = array_map(
            fn($key) => (int) ($statusCounts[$key] ?? 0),
            $statusKeys
        );

        $topMarkets = Market::withCount([
            'marketFarmers' => function ($query) {
                $query->where('is_active', true);
            },
        ])
            ->orderByDesc('market_farmers_count')
            ->take(5)
            ->get();

        $topMarketLabels = $topMarkets
            ->pluck('name')
            ->values()
            ->all();

        $topMarketData = $topMarkets
            ->pluck('market_farmers_count')
            ->values()
            ->all();

        $userRoleLabels = [
            'Customer',
            'Farmer',
            'Admin',
        ];

        $userRoleData = [
            User::where('role', 'customer')->count(),
            User::where('role', 'farmer')->count(),
            User::where('role', 'admin')->count(),
        ];

        $totalFarmerProfiles = FarmerProfile::count();

        $approvedFarmers = FarmerProfile::where(
            'approval_status',
            'approved'
        )->count();

        $approvalRate = $totalFarmerProfiles > 0
            ? round(($approvedFarmers / $totalFarmerProfiles) * 100)
            : 0;

        $lowStockCount = Product::lowStock()->count();

        $lowStockList = Product::with(['farmer', 'category'])
            ->lowStock()
            ->orderBy('stock_quantity')
            ->take(5)
            ->get();

        return view('Dashboard.admin-dashboard', compact(
            'totalUsers',
            'pendingFarmers',
            'activeMarkets',
            'totalProducts',
            'flaggedReviews',
            'totalFarmers',
            'totalCustomers',
            'totalOrders',
            'pendingFarmerList',
            'revenueTrendLabels',
            'revenueTrendData',
            'ordersTrendData',
            'orderStatusLabels',
            'orderStatusData',
            'topMarketLabels',
            'topMarketData',
            'userRoleLabels',
            'userRoleData',
            'approvalRate',
            'lowStockCount',
            'lowStockList'
        ));
    }

    /**
     * Platform-wide low-stock alerts: every product (any farmer) whose
     * stock_quantity has fallen to/below its low_stock_threshold.
     */
    public function lowStockAlerts(Request $request)
    {
        $query = Product::with(['farmer.user', 'category'])
            ->lowStock();

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('farmer', function ($query) use ($search) {
                        $query->where('stall_name', 'like', '%' . $search . '%')
                            ->orWhere('business_name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->get('status') === 'out') {
            $query->where('stock_quantity', '<=', 0);
        }

        $lowStockProducts = $query
            ->orderBy('stock_quantity')
            ->get();

        $outOfStockCount = $lowStockProducts
            ->where('stock_quantity', '<=', 0)
            ->count();

        return view(
            'Dashboard.LowStock.index',
            compact('lowStockProducts', 'outOfStockCount')
        );
    }

    public function myProfile()
    {
        $farmer = FarmerProfile::where(
            'user_id',
            Auth::id()
        )->first();

        return view(
            'Dashboard.Profile.profile',
            compact('farmer')
        );
    }

    public function myProfileUpdate(Request $request)
    {
        $request->validate([
            'stall_name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'operating_days' => 'nullable|array',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'farmer_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $farmer = FarmerProfile::firstOrNew([
            'user_id' => Auth::id(),
        ]);

        if (! $farmer->exists) {
            $farmer->approval_status = 'pending';
        }

        $farmer->stall_name = $request->stall_name;
        $farmer->business_name = $request->business_name;
        $farmer->description = $request->description;
        $farmer->address = $request->address;
        $farmer->country = $request->country;
        $farmer->state = $request->state;
        $farmer->city = $request->city;
        $farmer->latitude = $request->latitude;
        $farmer->longitude = $request->longitude;
        $farmer->operating_days = implode(
            ', ',
            $request->operating_days ?? []
        );
        $farmer->start_time = $request->start_time;
        $farmer->end_time = $request->end_time;

        if ($request->hasFile('farmer_image')) {
            if (
                $farmer->farmer_image &&
                file_exists(
                    public_path(
                        'farmer_images/' .
                            $farmer->farmer_image
                    )
                )
            ) {
                unlink(
                    public_path(
                        'farmer_images/' .
                            $farmer->farmer_image
                    )
                );
            }

            $directory = public_path('farmer_images');

            if (! file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $imageName = time() .
                '_' .
                uniqid() .
                '.' .
                $request
                ->file('farmer_image')
                ->extension();

            $request
                ->file('farmer_image')
                ->move(
                    $directory,
                    $imageName
                );

            $farmer->farmer_image = $imageName;
        }

        $farmer->save();

        return redirect()
            ->route('my_profile')
            ->with(
                'success',
                'Profile updated successfully.'
            );
    }

    public function myMarkets(Request $request)
    {
        $query = MarketFarmer::with('market')
            ->where(
                'farmer_id',
                Auth::id()
            );

        if ($request->filled('q')) {
            $search = $request->q;

            $query->whereHas(
                'market',
                function ($query) use ($search) {
                    $query
                        ->where(
                            'name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'city',
                            'like',
                            '%' . $search . '%'
                        );
                }
            );
        }

        $myMarkets = $query->get();

        return view(
            'Dashboard.Markets.my-markets',
            compact('myMarkets')
        );
    }

    public function myMarketsJoin()
    {
        $markets = Market::orderBy('name')->get();

        return view(
            'Dashboard.Markets.join-market',
            compact('markets')
        );
    }
    public function myMarketsJoinStore(Request $request)
    {
        $request->validate([
            'market_id' => 'required|exists:markets,id',
        ]);

        MarketFarmer::updateOrCreate(
            [
                'market_id' => $request->market_id,
                'farmer_id' => Auth::id(),
            ],
            [
                'is_active' => true,
            ]
        );

        return redirect()
            ->route('my_markets')
            ->with('success', 'Market joined successfully.');
    }

    public function myMarketsLeave($id)
    {
        $marketFarmer = MarketFarmer::where(
            'market_id',
            $id
        )
            ->where(
                'farmer_id',
                Auth::id()
            )
            ->firstOrFail();

        $marketFarmer->delete();

        return redirect()
            ->route('my_markets')
            ->with(
                'success',
                'Market left successfully.'
            );
    }

    public function categories(Request $request)
    {
        $query = Category::query();

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        $categories = $query
            ->orderBy('name')
            ->get();

        return view(
            'Dashboard.Categories.categories',
            compact('categories')
        );
    }

    public function categoryAdd()
    {
        return view(
            'Dashboard.Categories.add-category'
        );
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            'slug' => 'nullable|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'icon' => $request->icon,
        ]);

        return redirect()
            ->route('categories')
            ->with(
                'success',
                'Category added successfully.'
            );
    }

    public function categoryEdit($id)
    {
        $category = Category::findOrFail($id);

        return view(
            'Dashboard.Categories.edit-category',
            compact('category')
        );
    }

    public function categoryUpdate(
        Request $request,
        $id
    ) {
        $request->validate([
            'name' => 'required|string|max:255',

            'slug' => 'nullable|string|max:255',
        ]);

        $category = Category::findOrFail($id);

        $category->update([
            'name' => $request->name,

            'slug' => $request->slug,

            'icon' => $request->icon,
        ]);

        return redirect()
            ->route('categories')
            ->with(
                'success',
                'Category updated successfully.'
            );
    }

    public function categoryDelete($id)
    {
        $category = Category::findOrFail($id);

        $categoryName = $category->name;

        $category->delete();

        ActivityLogger::log(
            'deleted',
            "Deleted category \"{$categoryName}\"",
            Category::class,
            $id
        );

        return redirect()
            ->route('categories')
            ->with(
                'success',
                'Category deleted successfully.'
            );
    }

    public function products(Request $request)
    {
        $query = Product::with([
            'farmer',
            'category',
        ]);

        if (auth()->user()->hasRole('farmer')) {
            $farmer = $this->currentFarmer();

            $query->where(
                'farmer_id',
                $farmer->id
            );
        }

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('farmer', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $products = $query
            ->latest()
            ->get();

        return view(
            'Dashboard.Products.products',
            compact('products')
        );
    }

    public function productAdd()
    {
        $categories = Category::orderBy(
            'name'
        )->get();

        return view(
            'Dashboard.Products.add-product',
            compact('categories')
        );
    }

    public function productStore(Request $request)
    {
        $farmer = $this->currentFarmer();

        $request->validate([
            'category_id' => 'required|exists:categories,id',

            'name' => 'required|string|max:100',

            'description' => 'required|string',

            'price' => 'required|numeric|min:0',

            'stock_quantity' => 'required|integer|min:0',

            'unit' => 'required|string|max:500',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $directory = public_path(
                'product_images'
            );

            if (! file_exists($directory)) {
                mkdir(
                    $directory,
                    0755,
                    true
                );
            }

            $imageName = time() .
                '_' .
                uniqid() .
                '.' .
                $request
                ->file('image')
                ->extension();

            $request
                ->file('image')
                ->move(
                    $directory,
                    $imageName
                );
        }

        Product::create([
            'farmer_id' => $farmer->id,

            'category_id' => $request->category_id,

            'name' => $request->name,

            'description' => $request->description,

            'price' => $request->price,

            'stock_quantity' => $request->stock_quantity,

            'unit' => $request->unit,

            'image' => $imageName,

            'is_active' => $request->boolean(
                'is_active'
            ),
        ]);

        return redirect()
            ->route('products')
            ->with(
                'success',
                'Product added successfully.'
            );
    }

    public function productEdit($id)
    {
        $farmer = $this->currentFarmer();

        $product = Product::where(
            'farmer_id',
            $farmer->id
        )->findOrFail($id);

        $categories = Category::orderBy(
            'name'
        )->get();

        return view(
            'Dashboard.Products.edit-product',
            compact(
                'product',
                'categories'
            )
        );
    }

    public function productUpdate(
        Request $request,
        $id
    ) {
        $farmer = $this->currentFarmer();

        $product = Product::where(
            'farmer_id',
            $farmer->id
        )->findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',

            'name' => 'required|string|max:100',

            'description' => 'required|string',

            'price' => 'required|numeric|min:0',

            'stock_quantity' => 'required|integer|min:0',

            'unit' => 'required|string|max:500',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imageName = $product->image;

        if ($request->hasFile('image')) {
            if (
                $product->image &&
                file_exists(
                    public_path(
                        'product_images/' .
                            $product->image
                    )
                )
            ) {
                unlink(
                    public_path(
                        'product_images/' .
                            $product->image
                    )
                );
            }

            $directory = public_path(
                'product_images'
            );

            if (! file_exists($directory)) {
                mkdir(
                    $directory,
                    0755,
                    true
                );
            }

            $imageName = time() .
                '_' .
                uniqid() .
                '.' .
                $request
                ->file('image')
                ->extension();

            $request
                ->file('image')
                ->move(
                    $directory,
                    $imageName
                );
        }

        $product->update([
            'category_id' => $request->category_id,

            'name' => $request->name,

            'description' => $request->description,

            'price' => $request->price,

            'stock_quantity' => $request->stock_quantity,

            'unit' => $request->unit,

            'image' => $imageName,

            'is_active' => $request->boolean(
                'is_active'
            ),
        ]);

        return redirect()
            ->route('products')
            ->with(
                'success',
                'Product updated successfully.'
            );
    }

    public function productView($id)
    {
        $farmer = $this->currentFarmer();

        $product = Product::with([
            'farmer',
            'category',
        ])
            ->where(
                'farmer_id',
                $farmer->id
            )
            ->findOrFail($id);

        return view(
            'Dashboard.Products.view-product',
            compact('product')
        );
    }

    public function productDelete($id)
    {
        $farmer = $this->currentFarmer();

        $product = Product::where(
            'farmer_id',
            $farmer->id
        )->findOrFail($id);

        if (
            $product->image &&
            file_exists(
                public_path(
                    'product_images/' .
                        $product->image
                )
            )
        ) {
            unlink(
                public_path(
                    'product_images/' .
                        $product->image
                )
            );
        }

        $product->delete();

        return redirect()
            ->route('products')
            ->with(
                'success',
                'Product deleted successfully.'
            );
    }

    public function stock(Request $request)
    {
        $farmer = $this->currentFarmer();

        $query = WeeklyStockTemplate::with(
            'product'
        )
            ->where(
                'farmer_id',
                $farmer->id
            );

        if ($request->filled('q')) {
            $search = $request->q;

            $query->whereHas('product', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }

        $weekly_stock = $query
            ->latest()
            ->get();

        return view(
            'Dashboard.WeeklyStock.weekly-stock',
            compact('weekly_stock')
        );
    }

    public function stockAdd()
    {
        $farmer = $this->currentFarmer();

        $products = Product::where(
            'farmer_id',
            $farmer->id
        )
            ->orderBy('name')
            ->get();

        return view(
            'Dashboard.WeeklyStock.add-stock',
            compact('products')
        );
    }

    public function stockStore(Request $request)
    {
        $farmer = $this->currentFarmer();

        $request->validate([
            'product_id' => 'required|exists:products,id',

            'day_of_week' => 'required',

            'quantity' => 'required|numeric|min:0',

            'unit' => 'required|string|max:100',

            'start_date' => 'nullable|date',

            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $product = Product::where(
            'farmer_id',
            $farmer->id
        )->findOrFail(
            $request->product_id
        );

        WeeklyStockTemplate::create([
            'farmer_id' => $farmer->id,

            'product_id' => $product->id,

            'day_of_week' => $request->day_of_week,

            'quantity' => $request->quantity,

            'unit' => $request->unit,

            'start_date' => $request->start_date,

            'end_date' => $request->end_date,

            'is_active' => $request->boolean(
                'is_active'
            ),
        ]);

        return redirect()
            ->route('stock')
            ->with(
                'success',
                'Weekly stock added successfully.'
            );
    }

    public function stockEdit($id)
    {
        $farmer = $this->currentFarmer();

        $stock = WeeklyStockTemplate::where(
            'farmer_id',
            $farmer->id
        )->findOrFail($id);

        $products = Product::where(
            'farmer_id',
            $farmer->id
        )
            ->orderBy('name')
            ->get();

        return view(
            'Dashboard.WeeklyStock.edit-stock',
            compact(
                'stock',
                'products'
            )
        );
    }

    public function stockUpdate(
        Request $request,
        $id
    ) {
        $farmer = $this->currentFarmer();

        $stock = WeeklyStockTemplate::where(
            'farmer_id',
            $farmer->id
        )->findOrFail($id);

        $request->validate([
            'product_id' => 'required|exists:products,id',

            'day_of_week' => 'required',

            'quantity' => 'required|numeric|min:0',

            'unit' => 'required|string|max:100',

            'start_date' => 'nullable|date',

            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $product = Product::where(
            'farmer_id',
            $farmer->id
        )->findOrFail(
            $request->product_id
        );

        $stock->update([
            'product_id' => $product->id,

            'day_of_week' => $request->day_of_week,

            'quantity' => $request->quantity,

            'unit' => $request->unit,

            'start_date' => $request->start_date,

            'end_date' => $request->end_date,

            'is_active' => $request->boolean(
                'is_active'
            ),
        ]);

        return redirect()
            ->route('stock')
            ->with(
                'success',
                'Weekly stock updated successfully.'
            );
    }

    public function stockDelete($id)
    {
        $farmer = $this->currentFarmer();

        $stock = WeeklyStockTemplate::where(
            'farmer_id',
            $farmer->id
        )->findOrFail($id);

        $stock->delete();

        return redirect()
            ->route('stock')
            ->with(
                'success',
                'Weekly stock deleted successfully.'
            );
    }

    public function orders(Request $request)
    {
        $query = Order::with([
            'user',
            'pickupSlot',
            'items.product',
        ]);

        if (auth()->user()->hasRole('farmer')) {
            $farmer = $this->currentFarmer();

            $query->where(
                'farmer_id',
                $farmer->id
            );
        }

        if ($request->filled('q')) {
            $search = $request->q;

            $query->whereHas(
                'user',
                function ($query) use ($search) {
                    $query->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    );
                }
            );
        }

        $orders = $query
            ->latest('order_date')
            ->get();

        return view(
            'Dashboard.Orders.orders',
            compact('orders')
        );
    }

    public function orderView($id)
    {
        $query = Order::with([
            'user',
            'pickupSlot',
            'items.product',
        ]);

        if (auth()->user()->hasRole('farmer')) {
            $farmer = $this->currentFarmer();

            $query->where(
                'farmer_id',
                $farmer->id
            );
        }

        $order = $query->findOrFail($id);

        return view(
            'Dashboard.Orders.view-order',
            compact('order')
        );
    }

    public function orderStatusUpdate(
        Request $request,
        $id
    ) {
        $request->validate([
            'status' => 'required|in:pending,confirmed,ready,picked_up,cancelled',
        ]);

        $query = Order::query();

        if (auth()->user()->hasRole('farmer')) {
            $farmer = $this->currentFarmer();

            $query->where(
                'farmer_id',
                $farmer->id
            );
        }

        $order = $query->findOrFail($id);

        $oldStatus = $order->status;

        $order->status = $request->status;

        $order->save();

<<<<<<< HEAD
        if ($request->status === 'ready') {
            Notification::create([
                'user_id' => $order->user_id,
                'type' => 'ready_for_pickup',
                'title' => 'Order Ready for Pickup',
                'message' => 'Your order #'.$order->id.' is ready for pickup.',
                'is_read' => false,
            ]);
=======
        if (auth()->user()->hasRole('admin') && $oldStatus !== $order->status) {
            ActivityLogger::log(
                'updated',
                "Updated order #{$order->id} status from \"{$oldStatus}\" to \"{$order->status}\"",
                Order::class,
                $order->id
            );
>>>>>>> 281ee3e (Dashbord Improvement And New Feature Add)
        }

        return redirect()
            ->route('orders')
            ->with(
                'success',
                'Order status updated successfully.'
            );
    }

    public function slots(Request $request)
    {
        $query = PickupSlot::with('market');

        if (auth()->user()->hasRole('farmer')) {
            $farmer = $this->currentFarmer();

            $query->where(
                'farmer_id',
                $farmer->id
            );
        }

        if ($request->filled('q')) {
            $search = $request->q;

            $query->whereHas('market', function ($query) use ($search) {
                $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        $slots = $query
            ->latest('date')
            ->get();

        return view(
            'Dashboard.PickupSlots.pickup-slots',
            compact('slots')
        );
    }

    public function slotAdd()
    {
        $markets = Market::whereIn(
            'id',
            MarketFarmer::where(
                'farmer_id',
                Auth::id()
            )
                ->where(
                    'is_active',
                    true
                )
                ->pluck('market_id')
        )
            ->orderBy('name')
            ->get();

        return view(
            'Dashboard.PickupSlots.add-slot',
            compact('markets')
        );
    }

    public function slotStore(Request $request)
    {
        $farmer = $this->currentFarmer();

        $request->validate([
            'market_id' => 'required|exists:markets,id',

            'date' => 'required|date',

            'start_time' => 'required',

            'end_time' => 'required',

            'capacity' => 'required|integer|min:1',
        ]);

        MarketFarmer::where(
            'market_id',
            $request->market_id
        )
            ->where(
                'farmer_id',
                Auth::id()
            )
            ->where(
                'is_active',
                true
            )
            ->firstOrFail();

        PickupSlot::create([
            'farmer_id' => $farmer->id,

            'market_id' => $request->market_id,

            'date' => $request->date,

            'start_time' => $request->start_time,

            'end_time' => $request->end_time,

            'capacity' => $request->capacity,

            'is_available' => $request->boolean(
                'is_available'
            ),
        ]);

        return redirect()
            ->route('slots')
            ->with(
                'success',
                'Pickup slot added successfully.'
            );
    }

    public function slotEdit($id)
    {
        $farmer = $this->currentFarmer();

        $slot = PickupSlot::where(
            'farmer_id',
            $farmer->id
        )->findOrFail($id);

        $markets = Market::whereIn(
            'id',
            MarketFarmer::where(
                'farmer_id',
                Auth::id()
            )
                ->where(
                    'is_active',
                    true
                )
                ->pluck('market_id')
        )
            ->orderBy('name')
            ->get();

        return view(
            'Dashboard.PickupSlots.edit-slot',
            compact(
                'slot',
                'markets'
            )
        );
    }

    public function slotUpdate(
        Request $request,
        $id
    ) {
        $farmer = $this->currentFarmer();

        $slot = PickupSlot::where(
            'farmer_id',
            $farmer->id
        )->findOrFail($id);

        $request->validate([
            'market_id' => 'required|exists:markets,id',

            'date' => 'required|date',

            'start_time' => 'required',

            'end_time' => 'required',

            'capacity' => 'required|integer|min:1',
        ]);

        MarketFarmer::where(
            'market_id',
            $request->market_id
        )
            ->where(
                'farmer_id',
                Auth::id()
            )
            ->where(
                'is_active',
                true
            )
            ->firstOrFail();

        $slot->update([
            'market_id' => $request->market_id,

            'date' => $request->date,

            'start_time' => $request->start_time,

            'end_time' => $request->end_time,

            'capacity' => $request->capacity,

            'is_available' => $request->boolean(
                'is_available'
            ),
        ]);

        return redirect()
            ->route('slots')
            ->with(
                'success',
                'Pickup slot updated successfully.'
            );
    }

    public function slotDelete($id)
    {
        $farmer = $this->currentFarmer();

        $slot = PickupSlot::where(
            'farmer_id',
            $farmer->id
        )->findOrFail($id);

        $slot->delete();

        return redirect()
            ->route('slots')
            ->with(
                'success',
                'Pickup slot deleted successfully.'
            );
    }

    public function markets(Request $request)
    {
        $query = Market::query();

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%')
                    ->orWhere('state', 'like', '%' . $search . '%');
            });
        }

        $markets = $query
            ->orderBy('name')
            ->get();

        return view(
            'Dashboard.Markets.markets',
            compact('markets')
        );
    }

    public function marketAdd()
    {
        return view(
            'Dashboard.Markets.add-market'
        );
    }

    public function marketStore(Request $request)
    {
<<<<<<< HEAD
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'operating_days' => ['nullable', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
        ]);

        Market::create($validated);
=======
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'operating_days' => 'nullable|array',
            'operating_days.*' => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
        ]);

        Market::create([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'operating_days' => implode(', ', $request->operating_days ?? []),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
>>>>>>> 281ee3e (Dashbord Improvement And New Feature Add)

        return redirect()
            ->route('markets')
            ->with('success', 'Market added successfully.');
    }

    public function marketEdit($id)
    {
        $market = Market::findOrFail($id);

        return view(
            'Dashboard.Markets.edit-market',
            compact('market')
        );
    }

    public function marketUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'operating_days' => 'nullable|array',
            'operating_days.*' => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
        ]);

        $market = Market::findOrFail($id);

<<<<<<< HEAD
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'operating_days' => ['nullable', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
        ]);

        $market->update($validated);
=======
        $market->update([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'operating_days' => implode(', ', $request->operating_days ?? []),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
>>>>>>> 281ee3e (Dashbord Improvement And New Feature Add)

        return redirect()
            ->route('markets')
            ->with('success', 'Market updated successfully.');
    }

    public function marketDelete($id)
    {
        $market = Market::findOrFail($id);

        $marketName = $market->name;

        $market->delete();

        ActivityLogger::log(
            'deleted',
            "Deleted market \"{$marketName}\"",
            Market::class,
            $id
        );

        return redirect()
            ->route('markets');
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query
            ->latest()
            ->get();

        return view(
            'Dashboard.Users.users',
            compact('users')
        );
    }

    public function userAdd()
    {
        return view(
            'Dashboard.Users.add-user'
        );
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            'email' => 'required|email|max:255|unique:users,email',

            'phone' => 'nullable|string|max:20',

            'address' => 'required|string',

            'role' => 'required|in:admin,farmer,customer',

            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,

            'email' => $request->email,

            'phone' => $request->phone,

            'address' => $request->address,

            'role' => $request->role,

            'password' => Hash::make(
                $request->password
            ),

            'is_active' => $request->boolean(
                'is_active'
            ),
        ]);

        $user->syncRoles([
            $request->role,
        ]);

        if ($request->role === 'farmer') {
            $this->ensureFarmerProfile($user);
        }

        return redirect()
            ->route('users')
            ->with(
                'success',
                'User created successfully.'
            );
    }

    public function userEdit($id)
    {
        $user = User::findOrFail($id);

        return view(
            'Dashboard.Users.edit-user',
            compact('user')
        );
    }

    public function userUpdate(
        Request $request,
        $id
    ) {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',

            'email' => 'required|email|max:255|unique:users,email,' .
                $user->id,

            'phone' => 'nullable|string|max:20',

            'address' => 'required|string',

            'role' => 'required|in:admin,farmer,customer',

            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->role = $request->role;
        $user->is_active =
            $request->boolean('is_active');

        if ($request->filled('password')) {
            $user->password = Hash::make(
                $request->password
            );
        }

        $user->save();

        $user->syncRoles([
            $request->role,
        ]);

        if ($request->role === 'farmer') {
            $this->ensureFarmerProfile($user);
        }

        return redirect()
            ->route('users')
            ->with(
                'success',
                'User updated successfully.'
            );
    }

    public function userDelete($id)
    {
        $user = User::findOrFail($id);

        $userName = $user->name;

        $user->delete();

        ActivityLogger::log(
            'deleted',
            "Deleted user \"{$userName}\"",
            User::class,
            $id
        );

        return redirect()
            ->route('users')
            ->with(
                'success',
                'User deleted successfully.'
            );
    }

    public function farmers(Request $request)
    {
        $query = MarketFarmer::with([
            'farmer',
            'market',
        ]);

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($query) use ($search) {
                $query
                    ->whereHas('farmer', function ($query) use ($search) {
                        $query
                            ->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('market', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $farmers = $query->get();

        $pendingFarmerApplications = FarmerProfile::where('approval_status', 'pending')
            ->with('user')
            ->get();

        return view(
            'Dashboard.Farmers.farmers',
            compact('farmers', 'pendingFarmerApplications')
        );
    }

    public function farmerApprove($id)
    {
        $profile = FarmerProfile::findOrFail($id);

        $profile->approval_status = 'approved';
        $profile->approved_by = Auth::id();
        $profile->approved_at = now();
        $profile->save();

        return redirect()
            ->route('farmers')
            ->with('success', 'Farmer application approved.');
    }

    public function farmerReject($id)
    {
        $profile = FarmerProfile::findOrFail($id);

        $profile->approval_status = 'rejected';
        $profile->save();

        return redirect()
            ->route('farmers')
            ->with('success', 'Farmer application rejected.');
    }

    public function farmerEdit($id)
    {
        $farmer = MarketFarmer::findOrFail($id);

        $markets = Market::orderBy(
            'name'
        )->get();

        $users = User::where(
            'role',
            'farmer'
        )
            ->orderBy('name')
            ->get();

        return view(
            'Dashboard.Farmers.edit-farmer',
            compact(
                'farmer',
                'markets',
                'users'
            )
        );
    }

    public function farmerUpdate(
        Request $request,
        $id
    ) {
        $request->validate([
            'market_id' => 'required|exists:markets,id',

            'farmer_id' => 'required|exists:users,id',

            'is_active' => 'nullable|boolean',
        ]);

        $farmer = MarketFarmer::findOrFail(
            $id
        );

        $farmer->market_id =
            $request->market_id;

        $farmer->farmer_id =
            $request->farmer_id;

        $farmer->is_active =
            $request->boolean(
                'is_active'
            );

        $farmer->save();

        if ($farmer->is_active) {
            $profile = FarmerProfile::where(
                'user_id',
                $farmer->farmer_id
            )->first();

            if ($profile) {
                $wasPending = $profile->approval_status !== 'approved';

                $profile->approval_status =
                    'approved';

                $profile->approved_by =
                    Auth::id();

                $profile->approved_at =
                    now();

                $profile->save();

                if ($wasPending) {
                    ActivityLogger::log(
                        'approved',
                        "Approved farmer \"{$profile->stall_name}\"",
                        FarmerProfile::class,
                        $profile->id
                    );
                }
            }
        }

        return redirect()
            ->route('farmers')
            ->with(
                'success',
                'Farmer updated successfully.'
            );
    }

    public function farmerDelete($id)
    {
        $farmer = MarketFarmer::findOrFail(
            $id
        );

        $farmerName = optional($farmer->farmer)->name;

        $farmer->delete();

        ActivityLogger::log(
            'deleted',
            "Removed farmer \"{$farmerName}\" from market",
            MarketFarmer::class,
            $id
        );

        return redirect()
            ->route('farmers')
            ->with(
                'success',
                'Farmer removed from market successfully.'
            );
    }

    public function customers(Request $request)
    {
<<<<<<< HEAD
        $customers = User::where(
            'role',
            'customer'
        )
            ->withCount('orders')
=======
        $query = User::where('role', 'customer')
            ->withCount('orders');

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $customers = $query
>>>>>>> 281ee3e (Dashbord Improvement And New Feature Add)
            ->latest()
            ->get();

        return view('Dashboard.Customers.customers', compact('customers'));
    }

    public function customerView($id)
    {
        $customer = User::where('role', 'customer')
            ->withCount('orders')
            ->with([
                'orders' => function ($q) {
                    $q->latest();
                },
                'reviews',
                'favorites',
            ])
            ->findOrFail($id);

        return view('Dashboard.Customers.view-customer', compact('customer'));
    }

    public function customerToggleStatus($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);

        $customerName = $customer->name;

        $customer->is_active = ! $customer->is_active;
        $customer->save();

<<<<<<< HEAD
        return redirect()
            ->route('customers')
            ->with(
                'success',
                $customer->is_active
                    ? 'Customer activated successfully.'
                    : 'Customer deactivated successfully.'
            );
=======
        ActivityLogger::log(
            'deleted',
            "Deleted customer \"{$customerName}\"",
            User::class,
            $id
        );

        return redirect()->route('customers')
            ->with('success', 'Customer deleted successfully.');
>>>>>>> 281ee3e (Dashbord Improvement And New Feature Add)
    }

    public function reviews()
    {
        $query = Review::with([
            'user',
            'farmer.user',
            'product',
            'reply',
        ]);

        if (auth()->user()->hasRole('farmer')) {
            $farmer = $this->currentFarmer();

            $query->where(
                'farmer_id',
                $farmer->id
            );
        }

        $reviews = $query
            ->latest()
            ->get();

        return view(
            'Dashboard.Reviews.reviews',
            compact('reviews')
        );
    }

    public function reviewReplyStore(Request $request, $id)
    {
        $request->validate([
            'response' => ['required', 'string'],
        ]);

        $query = Review::query();

        if (auth()->user()->hasRole('farmer')) {
            $farmer = $this->currentFarmer();

            $query->where(
                'farmer_id',
                $farmer->id
            );
        }

        $review = $query->findOrFail($id);

        ReviewReply::updateOrCreate(
            ['review_id' => $review->id],
            ['response' => $request->response]
        );

        return redirect()
            ->route('reviews')
            ->with(
                'success',
                'Reply saved.'
            );
    }

    public function reviewFlag($id)
    {
        $query = Review::query();

        if (auth()->user()->hasRole('farmer')) {
            $farmer = $this->currentFarmer();

            $query->where(
                'farmer_id',
                $farmer->id
            );
        }

        $review = $query->findOrFail($id);

        $review->is_flagged =
            ! $review->is_flagged;

        $review->save();

        ActivityLogger::log(
            $review->is_flagged ? 'flagged' : 'unflagged',
            ($review->is_flagged ? 'Flagged' : 'Unflagged') . " review #{$review->id}",
            Review::class,
            $review->id
        );

        return redirect()
            ->route('reviews')
            ->with(
                'success',
                'Review flag status updated.'
            );
    }

    public function reviewDelete($id)
    {
        $query = Review::query();

        if (auth()->user()->hasRole('farmer')) {
            $farmer = $this->currentFarmer();

            $query->where(
                'farmer_id',
                $farmer->id
            );
        }

        $review = $query->findOrFail($id);

        $review->delete();

        ActivityLogger::log(
            'deleted',
            "Deleted review #{$id}",
            Review::class,
            $id
        );

        return redirect()
            ->route('reviews')
            ->with(
                'success',
                'Review deleted successfully.'
            );
    }

    public function reports(Request $request)
    {
        $query = Report::orderBy(
            'id',
            'asc'
        );

        if (
            $request->report_type &&
            $request->report_type != 'all'
        ) {
            $query->where(
                'report_type',
                $request->report_type
            );
        }

        // Date range filter: show any generated report whose own
        // date_from -> date_to period overlaps the selected window.
        // Leaving both blank means "all dates".
        if ($request->filled('date_from')) {
            $query->where(
                'date_to',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {
            $query->where(
                'date_from',
                '<=',
                $request->date_to
            );
        }

        $reports = $query->get();

        return view(
            'Dashboard.Reports.reports',
            compact('reports')
        );
    }

    /**
     * Export the REAL underlying data (Orders/Sales, Farmers, Products) for
     * the selected date range and type — not just the "Generated Reports"
     * list metadata. This is the same data that goes into an individual
     * report's PDF, just covering the whole filtered range/type at once.
     *
     *   - PDF   -> everything combined into a single file (one section per type)
     *   - XLSX  -> a separate sheet/table per type
     */
    public function reportsExport(Request $request)
    {
        $request->validate([
            'format' => 'required|in:pdf,xlsx',
            'report_type' => 'nullable|in:all,sales,orders,farmers,products',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $type = $request->report_type ?: 'all';
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $sections = [];

        // Sales / Orders
        if (in_array($type, ['all', 'sales', 'orders'])) {
            $query = Order::with(['user', 'farmer', 'pickupSlot']);

            if ($dateFrom) {
                $query->where('order_date', '>=', $dateFrom . ' 00:00:00');
            }

            if ($dateTo) {
                $query->where('order_date', '<=', $dateTo . ' 23:59:59');
            }

            $orders = $query->orderBy('order_date', 'asc')->get();

            $sections['orders'] = [
                'label' => $type === 'sales' ? 'Sales Summary' : 'Orders',
                'data' => $orders,
            ];
        }

        // Farmer Activity
        if (in_array($type, ['all', 'farmers'])) {
            $query = FarmerProfile::with('user');

            if ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom . ' 00:00:00');
            }

            if ($dateTo) {
                $query->where('created_at', '<=', $dateTo . ' 23:59:59');
            }

            $farmers = $query->orderBy('created_at', 'asc')->get();

            $sections['farmers'] = [
                'label' => 'Farmer Activity',
                'data' => $farmers,
            ];
        }

        // Product Inventory
        if (in_array($type, ['all', 'products'])) {
            $query = Product::with(['farmer', 'category']);

            if ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom . ' 00:00:00');
            }

            if ($dateTo) {
                $query->where('created_at', '<=', $dateTo . ' 23:59:59');
            }

            $products = $query->orderBy('created_at', 'asc')->get();

            $sections['products'] = [
                'label' => 'Product Inventory',
                'data' => $products,
            ];
        }

        $filters = [
            'report_type' => $type,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];

        if ($request->format === 'xlsx') {
            return $this->reportsExportXlsx($sections, $filters);
        }

        // format === 'pdf': everything combined into one file, one section per type
        $pdf = Pdf::loadView(
            'Dashboard.Reports.reports-export-pdf',
            [
                'sections' => $sections,
                'filters' => $filters,
            ]
        );

        return $pdf->download(
            'MarketLink_Reports_Export_' . now()->format('Ymd_His') . '.pdf'
        );
    }

    /**
     * Build the multi-sheet Excel export: one sheet/table per data type
     * (only the types actually included), using the real detailed rows
     * (same columns as the single-report Excel/PDF download), styled to
     * match the PDF export (green title banner, date-range subtitle,
     * bordered/zebra-striped table).
     */
    protected function reportsExportXlsx(array $sections, array $filters)
    {
        $xlsx = new SimpleXlsxWriter;

        $subtitle = 'Date Range: ' .
            ($filters['date_from'] ?? 'Any') . ' - ' . ($filters['date_to'] ?? 'Any') .
            '   |   Generated: ' . now()->format('d M Y H:i');

        if (empty($sections)) {
            $xlsx->addSheet('Data', ['No data'], [], 'Reports Data Export', $subtitle);
        }

        foreach ($sections as $key => $section) {
            if ($key === 'orders') {
                [$headers, $rows] = $this->buildOrdersRows($section['data']);
            } elseif ($key === 'farmers') {
                [$headers, $rows] = $this->buildFarmersRows($section['data']);
            } else {
                [$headers, $rows] = $this->buildProductsRows($section['data']);
            }

            $xlsx->addSheet($section['label'], $headers, $rows, $section['label'], $subtitle);
        }

        $fileName = 'MarketLink_Reports_Export_' . now()->format('Ymd_His') . '.xlsx';

        return response($xlsx->output(), 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Shared row-builders, used by both the single-report download
     * and the bulk reports export, so PDF/Excel/single/bulk all show
     * the exact same columns for the same data type.
     */
    protected function buildOrdersRows($orders): array
    {
        $headers = ['ID', 'Customer', 'Farmer', 'Pickup Slot', 'Total Amount', 'Status', 'Order Date', 'Notes'];

        $rows = $orders->map(fn($order) => [
            $order->id,
            $order->user->name ?? 'N/A',
            $order->farmer->stall_name ?? 'N/A',
            $order->pickupSlot->id ?? 'N/A',
            number_format($order->total_amount, 2),
            ucfirst($order->status),
            $order->order_date->format('d M Y H:i'),
            $order->notes ?? 'N/A',
        ])->all();

        return [$headers, $rows];
    }

    protected function buildFarmersRows($farmers): array
    {
        $headers = ['ID', 'Farmer', 'Stall Name', 'Business', 'Description', 'Address', 'City', 'State', 'Country', 'Operating Days', 'Start', 'End', 'Approval'];

        $rows = $farmers->map(fn($farmer) => [
            $farmer->id,
            $farmer->user->name ?? 'N/A',
            $farmer->stall_name,
            $farmer->business_name,
            $farmer->description,
            $farmer->address,
            $farmer->city,
            $farmer->state,
            $farmer->country,
            $farmer->operating_days,
            $farmer->start_time,
            $farmer->end_time,
            ucfirst($farmer->approval_status),
        ])->all();

        return [$headers, $rows];
    }

    protected function buildProductsRows($products): array
    {
        $headers = ['ID', 'Product', 'Farmer', 'Category', 'Description', 'Price', 'Stock', 'Unit', 'Active'];

        $rows = $products->map(fn($product) => [
            $product->id,
            $product->name,
            $product->farmer->stall_name ?? 'N/A',
            $product->category->name ?? 'N/A',
            $product->description,
            number_format($product->price, 2),
            $product->stock_quantity,
            $product->unit,
            $product->is_active ? 'Yes' : 'No',
        ])->all();

        return [$headers, $rows];
    }

    public function reportGenerate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:sales,orders,farmers,products',

            'date_from' => 'required|date',

            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $report = Report::create([
            'admin_id' => Auth::id() ?? 1,

            'report_type' => $request->report_type,

            'date_from' => $request->date_from,

            'date_to' => $request->date_to,

            'file_path' => '',

            'generated_at' => now(),
        ]);

        if (
            $request->report_type === 'sales' ||
            $request->report_type === 'orders'
        ) {
            $data = Order::with([
                'user',
                'farmer',
                'pickupSlot',
            ])
                ->whereBetween(
                    'order_date',
                    [
                        $request->date_from .
                            ' 00:00:00',

                        $request->date_to .
                            ' 23:59:59',
                    ]
                )
                ->get();
        } elseif (
            $request->report_type === 'farmers'
        ) {
            $data = FarmerProfile::with(
                'user'
            )
                ->whereBetween(
                    'created_at',
                    [
                        $request->date_from .
                            ' 00:00:00',

                        $request->date_to .
                            ' 23:59:59',
                    ]
                )
                ->get();
        } else {
            $data = Product::with([
                'farmer',
                'category',
            ])
                ->whereBetween(
                    'created_at',
                    [
                        $request->date_from .
                            ' 00:00:00',

                        $request->date_to .
                            ' 23:59:59',
                    ]
                )
                ->get();
        }

        $pdf = Pdf::loadView(
            'Dashboard.Reports.report-pdf',
            [
                'report' => $report,
                'data' => $data,
            ]
        );

        $directory = storage_path(
            'app/reports'
        );

        if (! file_exists($directory)) {
            mkdir(
                $directory,
                0755,
                true
            );
        }

        $fileName =
            'report_' .
            $report->id .
            '.pdf';

        $path =
            $directory .
            '/' .
            $fileName;

        file_put_contents(
            $path,
            $pdf->output()
        );

        $report->update([
            'file_path' => 'reports/' .
                $fileName,
        ]);

        return response()->download(
            $path,
            'MarketLink_Report_' .
                $report->id .
                '.pdf',
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }

    public function reportDownload(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        if (
            $report->report_type === 'sales' ||
            $report->report_type === 'orders'
        ) {
            $data = Order::with([
                'user',
                'farmer',
                'pickupSlot',
            ])
                ->whereBetween(
                    'order_date',
                    [
                        $report->date_from .
                            ' 00:00:00',

                        $report->date_to .
                            ' 23:59:59',
                    ]
                )
                ->get();
        } elseif (
            $report->report_type === 'farmers'
        ) {
            $data = FarmerProfile::with(
                'user'
            )
                ->whereBetween(
                    'created_at',
                    [
                        $report->date_from .
                            ' 00:00:00',

                        $report->date_to .
                            ' 23:59:59',
                    ]
                )
                ->get();
        } else {
            $data = Product::with([
                'farmer',
                'category',
            ])
                ->whereBetween(
                    'created_at',
                    [
                        $report->date_from .
                            ' 00:00:00',

                        $report->date_to .
                            ' 23:59:59',
                    ]
                )
                ->get();
        }

        if ($request->query('format') === 'xlsx') {
            return $this->reportDownloadXlsx($report, $data);
        }

        $pdf = Pdf::loadView(
            'Dashboard.Reports.download-pdf',
            [
                'report' => $report,
                'data' => $data,
            ]
        );

        return $pdf->download(
            'MarketLink_Report_' .
                $report->id .
                '_Download.pdf'
        );
    }

    /**
     * Download a single generated report's underlying data as a one-sheet
     * Excel file (same rows/columns as the PDF version, just in xlsx form).
     */
    protected function reportDownloadXlsx(Report $report, $data)
    {
        $xlsx = new SimpleXlsxWriter;

        if ($report->report_type === 'sales' || $report->report_type === 'orders') {
            [$headers, $rows] = $this->buildOrdersRows($data);
        } elseif ($report->report_type === 'farmers') {
            [$headers, $rows] = $this->buildFarmersRows($data);
        } else {
            [$headers, $rows] = $this->buildProductsRows($data);
        }

        $title = ucfirst($report->report_type) . ' Report #' . $report->id;

        $subtitle = 'Date Range: ' . $report->date_from->format('d M Y') . ' - ' . $report->date_to->format('d M Y') .
            '   |   Generated: ' . $report->generated_at->format('d M Y H:i');

        $xlsx->addSheet(ucfirst($report->report_type), $headers, $rows, $title, $subtitle);

        $fileName = 'MarketLink_Report_' . $report->id . '_Download.xlsx';

        return response($xlsx->output(), 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function announcements(Request $request)
    {
        $query = Announcement::with('admin');

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($query) use ($search) {
                $query
                    ->where('title', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%');
            });
        }

        $announcements = $query
            ->latest()
            ->get();

        return view(
            'Dashboard.Announcements.announcements',
            compact('announcements')
        );
    }

    public function announcementAdd()
    {
        return view(
            'Dashboard.Announcements.add-announcement'
        );
    }

    public function announcementStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',

            'message' => 'required|string',

            'published_at' => 'nullable|date',
        ]);

        Announcement::create([
            'admin_id' => Auth::id(),

            'title' => $request->title,

            'message' => $request->message,

            'published_at' => $request->published_at,

            'is_active' => $request->boolean(
                'is_active'
            ),
        ]);

        return redirect()
            ->route('announcements')
            ->with(
                'success',
                'Announcement published successfully.'
            );
    }

    public function announcementEdit($id)
    {
        $announcement = Announcement::findOrFail(
            $id
        );

        return view(
            'Dashboard.Announcements.edit-announcement',
            compact('announcement')
        );
    }

    public function announcementUpdate(
        Request $request,
        $id
    ) {
        $announcement = Announcement::findOrFail(
            $id
        );

        $request->validate([
            'title' => 'required|string|max:150',

            'message' => 'required|string',

            'published_at' => 'nullable|date',
        ]);

        $announcement->title =
            $request->title;

        $announcement->message =
            $request->message;

        $announcement->published_at =
            $request->published_at;

        $announcement->is_active =
            $request->boolean(
                'is_active'
            );

        $announcement->save();

        return redirect()
            ->route('announcements')
            ->with(
                'success',
                'Announcement updated successfully.'
            );
    }

    public function announcementDelete($id)
    {
        $announcement = Announcement::findOrFail(
            $id
        );

        $title = $announcement->title ?? "#{$id}";

        $announcement->delete();

        ActivityLogger::log(
            'deleted',
            "Deleted announcement \"{$title}\"",
            Announcement::class,
            $id
        );

        return redirect()
            ->route('announcements')
            ->with(
                'success',
                'Announcement deleted successfully.'
            );
    }

    /**
     * Admin accountability screen: who did what and when.
     */
    public function activityLog(Request $request)
    {
        $query = ActivityLog::with('user')
            ->action($request->get('action'));

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($query) use ($search) {
                $query
                    ->where('description', 'like', '%' . $search . '%')
                    ->orWhere('actor_name', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $logs = $query
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view(
            'Dashboard.ActivityLog.index',
            compact('logs', 'actions')
        );
    }

    /**
     * Top-selling farmers by revenue/orders (platform-wide leaderboard).
     */
    public function farmerLeaderboard(Request $request)
    {
        $sort = $request->get('sort', 'revenue');
        $period = $request->get('period', 'all');

        $dateFrom = match ($period) {
            '7days' => now()->subDays(7),
            '30days' => now()->subDays(30),
            'month' => now()->startOfMonth(),
            default => null,
        };

        $ordersFilter = function ($query) use ($dateFrom) {
            $query->where('status', '!=', 'cancelled');

            if ($dateFrom) {
                $query->where('order_date', '>=', $dateFrom);
            }
        };

        $farmers = FarmerProfile::with('user')
            ->where('approval_status', 'approved')
            ->withCount(['orders as orders_count' => $ordersFilter])
            ->withSum(['orders as total_revenue' => $ordersFilter], 'total_amount')
            ->withCount('products')
            ->withAvg('reviews', 'rating')
            ->get();

        $farmers = $farmers->map(function ($farmer) {
            $farmer->total_revenue = (float) ($farmer->total_revenue ?? 0);
            $farmer->orders_count = (int) ($farmer->orders_count ?? 0);
            $farmer->avg_rating = round((float) ($farmer->reviews_avg_rating ?? 0), 1);

            return $farmer;
        });

        $farmers = $sort === 'orders'
            ? $farmers->sortByDesc('orders_count')->values()
            : $farmers->sortByDesc('total_revenue')->values();

        return view(
            'Dashboard.Leaderboard.index',
            compact('farmers', 'sort', 'period')
        );
    }

    /**
     * Platform-wide commission / fee tracking: how much the platform earns
     * (and how much each farmer is owed) on their order revenue, based on
     * each farmer's commission_rate override or the platform default.
     */
    public function commissionTracking(Request $request)
    {
        $period = $request->get('period', 'all');
        $farmerId = $request->get('farmer_id');

        $dateFrom = match ($period) {
            '7days' => now()->subDays(7),
            '30days' => now()->subDays(30),
            'month' => now()->startOfMonth(),
            default => null,
        };

        $ordersFilter = function ($query) use ($dateFrom) {
            $query->where('status', '!=', 'cancelled');

            if ($dateFrom) {
                $query->where('order_date', '>=', $dateFrom);
            }
        };

        $farmersQuery = FarmerProfile::with('user')
            ->withCount(['orders as orders_count' => $ordersFilter])
            ->withSum(['orders as total_revenue' => $ordersFilter], 'total_amount');

        if ($farmerId) {
            $farmersQuery->where('id', $farmerId);
        }

        $farmers = $farmersQuery->get()
            ->map(function ($farmer) {
                $farmer->total_revenue = (float) ($farmer->total_revenue ?? 0);
                $farmer->orders_count = (int) ($farmer->orders_count ?? 0);
                $farmer->rate = $farmer->effective_commission_rate;
                $farmer->commission_amount = round($farmer->total_revenue * $farmer->rate / 100, 2);
                $farmer->payout_amount = round($farmer->total_revenue - $farmer->commission_amount, 2);

                return $farmer;
            })
            ->filter(fn($farmer) => $farmer->orders_count > 0 || $farmerId)
            ->sortByDesc('total_revenue')
            ->values();

        $totals = [
            'revenue' => round($farmers->sum('total_revenue'), 2),
            'commission' => round($farmers->sum('commission_amount'), 2),
            'payout' => round($farmers->sum('payout_amount'), 2),
            'orders' => (int) $farmers->sum('orders_count'),
        ];

        $allFarmers = FarmerProfile::with('user')
            ->where('approval_status', 'approved')
            ->orderBy('stall_name')
            ->get();

        return view(
            'Dashboard.Commission.index',
            compact('farmers', 'totals', 'period', 'farmerId', 'allFarmers')
        );
    }

    /**
     * Set (or clear) a single farmer's commission_rate override.
     * Leaving the field blank resets them to the platform default.
     */
    public function commissionRateUpdate(Request $request, $id)
    {
        $request->validate([
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $farmer = FarmerProfile::findOrFail($id);

        $farmer->commission_rate = $request->filled('commission_rate')
            ? $request->commission_rate
            : null;

        $farmer->save();

        $label = $request->filled('commission_rate')
            ? $farmer->commission_rate . '%'
            : 'platform default';

        ActivityLogger::log(
            'updated',
            "Set commission rate for \"{$farmer->stall_name}\" to {$label}",
            FarmerProfile::class,
            $farmer->id
        );

        return redirect()
            ->back()
            ->with('success', 'Commission rate updated.');
    }

    /**
     * Per-product sales analytics for the logged-in farmer: units sold,
     * revenue, order count and a fast/slow-mover tag relative to this
     * farmer's own average, so slow-moving stock stands out at a glance.
     */
    public function productSalesAnalytics(Request $request)
    {
        $farmer = $this->currentFarmer();
        $period = $request->get('period', 'all');
        $sort = $request->get('sort', 'revenue');

        $dateFrom = match ($period) {
            '7days' => now()->subDays(7),
            '30days' => now()->subDays(30),
            'month' => now()->startOfMonth(),
            default => null,
        };

        $sales = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.farmer_id', $farmer->id)
            ->where('orders.status', '!=', 'cancelled')
            ->when($dateFrom, fn($query) => $query->where('orders.order_date', '>=', $dateFrom))
            ->selectRaw('order_items.product_id')
            ->selectRaw('SUM(order_items.quantity) as units_sold')
            ->selectRaw('SUM(order_items.subtotal) as revenue')
            ->selectRaw('COUNT(DISTINCT order_items.order_id) as orders_count')
            ->selectRaw('MAX(orders.order_date) as last_sold_at')
            ->groupBy('order_items.product_id')
            ->get()
            ->keyBy('product_id');

        $rows = Product::where('farmer_id', $farmer->id)
            ->orderBy('name')
            ->get()
            ->map(function ($product) use ($sales) {
                $s = $sales->get($product->id);

                $product->units_sold = (int) ($s->units_sold ?? 0);
                $product->revenue = (float) ($s->revenue ?? 0);
                $product->orders_count = (int) ($s->orders_count ?? 0);
                $product->last_sold_at = $s && $s->last_sold_at
                    ? \Carbon\Carbon::parse($s->last_sold_at)
                    : null;

                return $product;
            });

        $avgUnits = $rows->where('units_sold', '>', 0)->avg('units_sold') ?? 0;

        $rows = $rows->map(function ($product) use ($avgUnits) {
            $product->movement = match (true) {
                $product->units_sold <= 0 => 'no_sales',
                $avgUnits > 0 && $product->units_sold >= $avgUnits * 1.5 => 'fast',
                $avgUnits > 0 && $product->units_sold <= $avgUnits * 0.5 => 'slow',
                default => 'steady',
            };

            return $product;
        });

        $rows = $sort === 'units'
            ? $rows->sortByDesc('units_sold')->values()
            : $rows->sortByDesc('revenue')->values();

        $totals = [
            'revenue' => round($rows->sum('revenue'), 2),
            'units' => (int) $rows->sum('units_sold'),
            'slow_count' => $rows->where('movement', 'slow')->count(),
            'no_sales_count' => $rows->where('movement', 'no_sales')->count(),
        ];

        return view(
            'Dashboard.ProductAnalytics.index',
            compact('rows', 'totals', 'period', 'sort')
        );
    }
}
