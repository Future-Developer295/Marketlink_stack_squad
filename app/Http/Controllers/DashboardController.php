<?php

namespace App\Http\Controllers;


use App\Mail\FarmerApprovedMail;
use App\Mail\FarmerRejectedMail;
use App\Models\ContactMessage;
use App\Models\Market;
use App\Models\MarketFarmer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\FarmerProfile;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use App\Models\Report;
use App\Models\Announcement;
use App\Models\PickupSlot;
use App\Models\WeeklyStockTemplate;
use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Mail;
use App\Support\SimpleXlsxWriter;
use App\Services\SafeMail;

class DashboardController extends Controller
{
    /** Rows per page on every dashboard list. */
    protected int $perPage = 15;

    /**
     * The trimmed ?q= search term, or null when empty. Capped so a huge string
     * can never be turned into an expensive LIKE.
     */
    protected function searchTerm(Request $request): ?string
    {
        $term = trim((string) $request->query('q', ''));

        return $term === '' ? null : mb_substr($term, 0, 100);
    }

    /**
     * Revenue and order count for each of the last 7 days, using ONE grouped
     * query instead of loading every order of every day.
     *
     * @return array{0: array<int,string>, 1: array<int,float>, 2: array<int,int>} labels, revenue, orders
     */
    protected function lastSevenDaysTrend($orderQuery): array
    {
        $rows = $orderQuery
            ->whereBetween('order_date', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->selectRaw('DATE(order_date) as day, COUNT(*) as orders_count, COALESCE(SUM(total_amount), 0) as revenue')
            ->groupByRaw('DATE(order_date)')
            ->toBase()
            ->get()
            ->keyBy('day');

        $labels = [];
        $revenue = [];
        $orders = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $row = $rows->get($day->toDateString());

            $labels[] = $day->format('D');
            $revenue[] = (float) ($row->revenue ?? 0);
            $orders[] = (int) ($row->orders_count ?? 0);
        }

        return [$labels, $revenue, $orders];
    }

    /**
     * Email a farmer that the admin has approved (activated) their account.
     * Returns the flash [type, message] so the caller can show whether the email went out.
     *
     * @return array{0: string, 1: string}
     */
    protected function notifyFarmerApproved(User $user): array
    {
        $sent = SafeMail::send($user->email, new FarmerApprovedMail($user));

        return $sent
            ? ['success', 'Farmer approved and notified by email.']
            : ['warning', 'Farmer approved, but the email could not be sent. Check the mail settings (storage/logs/laravel.log).'];
    }

    /**
     * Delete a model but answer with a friendly message (instead of a 500)
     * when something else still depends on it.
     */
    protected function deleteModel($model, string $label, string $route)
    {
        try {
            $model->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            report($e);

            return redirect()->route($route)
                ->with('error', "This {$label} cannot be deleted because it is still in use.");
        }

        return redirect()->route($route)->with('success', ucfirst($label).' deleted successfully.');
    }


    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin_dashboard');
        }

        if (auth()->user()->hasRole('farmer')) {
            return redirect()->route('farmer_dashboard');
        }

        return redirect()->route('customer_dashboard');
    }
    public function farmerDashboard()
    {
        $farmer = FarmerProfile::where('user_id', auth()->id())->first();
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

            $markets = MarketFarmer::where('farmer_id', auth()->id())
                ->where('is_active', true)
                ->count();

            $pendingOrders = Order::where('farmer_id', $farmer->id)
                ->where('status', 'pending')
                ->count();

            $openSlots = PickupSlot::where('farmer_id', $farmer->id)
                ->where('is_available', true)
                ->count();

            $averageRating = Review::where('farmer_id', $farmer->id)
                ->where('is_active', true)
                ->avg('rating') ?? 0;

            $totalReviews = Review::where('farmer_id', $farmer->id)
                ->where('is_active', true)
                ->count();

            $recentOrders = Order::with([
                'user',
                'pickupSlot'
            ])
                ->where('farmer_id', $farmer->id)
                ->latest('order_date')
                ->take(5)
                ->get();

            // Revenue & orders trend for the last 7 days (one grouped query)
            [$revenueTrendLabels, $revenueTrendData, $ordersTrendData] =
                $this->lastSevenDaysTrend(Order::where('farmer_id', $farmer->id));

            // Order status breakdown
            $statusKeys = ['pending', 'confirmed', 'ready', 'picked_up', 'cancelled'];
            $statusCounts = Order::where('farmer_id', $farmer->id)
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $orderStatusData = array_map(
                fn($key) => (int) ($statusCounts[$key] ?? 0),
                $statusKeys
            );

            // Top products by units sold
            $topProducts = OrderItem::select('product_id')
                ->selectRaw('SUM(quantity) as units_sold')
                ->whereHas('order', function ($query) use ($farmer) {
                    $query->where('farmer_id', $farmer->id);
                })
                ->groupBy('product_id')
                ->orderByDesc('units_sold')
                ->take(5)
                ->with('product')
                ->get();

            $topProductLabels = $topProducts->map(fn($item) => $item->product->name ?? 'Unknown')->values()->all();
            $topProductData = $topProducts->map(fn($item) => (int) $item->units_sold)->values()->all();
        }

        return view('Dashboard.farmer-dashboard', compact(
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
    public function roles(Request $request)
    {
        $search = $this->searchTerm($request);

        $roles = Role::withCount('permissions')
            ->when($search !== null, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('id')
            ->paginate($this->perPage)
            ->withQueryString();

        return view('Dashboard.Roles.index', compact('roles'));
    }

    public function roleAdd()
    {
        $permissions = Permission::orderBy('name')->get();

        $permissionGroups = [];

        foreach ($permissions as $permission) {

            $name = $permission->name;

            if (str_contains($name, ' ')) {
                $parts = explode(' ', $name, 2);
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
    public function permissions(Request $request)
    {
        $search = $this->searchTerm($request);

        $permissions = Permission::withCount('roles')
            ->when($search !== null, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate($this->perPage)
            ->withQueryString();

        return view('Dashboard.Permissions.index', compact('permissions'));
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

        if ($role->users()->exists()) {
            return redirect()->route('roles')
                ->with('error', 'This role is still assigned to users, so it cannot be deleted.');
        }

        $role->delete();

        return redirect()
            ->route('roles')
            ->with('success', 'Role deleted successfully.');
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
        $permission = Permission::with('roles')->findOrFail($id);

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
        $permission->delete();

        return redirect()
            ->route('permissions')
            ->with('success', 'Permission deleted successfully.');
    }
    public function adminDashboard()
    {
        $totalUsers = User::count();
        $pendingFarmers = FarmerProfile::where('approval_status', 'pending')->count();
        $activeMarkets = Market::count();
        $totalProducts = Product::count();
        $flaggedReviews = Review::where('is_flagged', true)->count();

        $pendingFarmerList = FarmerProfile::with('user')
            ->where('approval_status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Platform-wide revenue & orders trend for the last 7 days
        [$revenueTrendLabels, $revenueTrendData, $ordersTrendData] =
            $this->lastSevenDaysTrend(Order::query());

        // Platform-wide order status breakdown
        $orderStatusLabels = ['Pending', 'Confirmed', 'Ready', 'Picked Up', 'Cancelled'];
        $statusKeys = ['pending', 'confirmed', 'ready', 'picked_up', 'cancelled'];
        $statusCounts = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $orderStatusData = array_map(
            fn($key) => (int) ($statusCounts[$key] ?? 0),
            $statusKeys
        );

        // Top markets by number of active farmers
        $topMarkets = Market::withCount(['marketFarmers' => function ($query) {
            $query->where('is_active', true);
        }])
            ->orderByDesc('market_farmers_count')
            ->take(5)
            ->get();

        $topMarketLabels = $topMarkets->pluck('name')->values()->all();
        $topMarketData = $topMarkets->pluck('market_farmers_count')->values()->all();

        // Users by role
        $userRoleLabels = ['Customer', 'Farmer', 'Admin'];
        $roleCounts = User::selectRaw('role, count(*) as total')->groupBy('role')->pluck('total', 'role');
        $userRoleData = [
            (int) ($roleCounts['customer'] ?? 0),
            (int) ($roleCounts['farmer'] ?? 0),
            (int) ($roleCounts['admin'] ?? 0),
        ];

        // Farmer approval rate
        $totalFarmerProfiles = FarmerProfile::count();
        $approvedFarmers = FarmerProfile::where('approval_status', 'approved')->count();
        $approvalRate = $totalFarmerProfiles > 0
            ? round(($approvedFarmers / $totalFarmerProfiles) * 100)
            : 0;

        return view('Dashboard.admin-dashboard', compact(
            'totalUsers',
            'pendingFarmers',
            'activeMarkets',
            'totalProducts',
            'flaggedReviews',
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
            'approvalRate'
        ));
    }

    public function myProfile()
    {
        $farmer = FarmerProfile::where('user_id', auth()->id())->first();

        return view('Dashboard.Profile.profile', compact('farmer'));
    }

    public function myProfileUpdate(Request $request)
    {
        $farmer = FarmerProfile::where('user_id', auth()->id())->firstOrFail();

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

        $farmer->stall_name = $request->stall_name;
        $farmer->business_name = $request->business_name;
        $farmer->description = $request->description;
        $farmer->address = $request->address;
        $farmer->country = $request->country;
        $farmer->state = $request->state;
        $farmer->city = $request->city;
        $farmer->latitude = $request->latitude;
        $farmer->longitude = $request->longitude;
        $farmer->operating_days = implode(', ', $request->operating_days ?? []);
        $farmer->start_time = $request->start_time;
        $farmer->end_time = $request->end_time;

        if ($request->hasFile('farmer_image')) {
            $imageName = time() . '.' . $request->file('farmer_image')->extension();

            $request->file('farmer_image')->move(
                public_path('farmer_images'),
                $imageName
            );

            $farmer->farmer_image = $imageName;
        }

        $farmer->save();

        return redirect()
            ->route('my_profile')
            ->with('success', 'Profile updated successfully.');
    }
    public function myMarkets(Request $request)
    {
        $query = MarketFarmer::with('market')
            ->where('farmer_id', auth()->id());

        if (($search = $this->searchTerm($request)) !== null) {
            $query->whereHas('market', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        $myMarkets = $query->orderBy('id')->paginate($this->perPage)->withQueryString();

        return view('Dashboard.Markets.my-markets', compact('myMarkets'));
    }
    public function myMarketsJoin()
    {
        $markets = Market::orderBy('name')->get();

        return view('Dashboard.Markets.join-market', compact('markets'));
    }
    public function myMarketsJoinStore(Request $request)
    {
        $request->validate([
            'market_id' => 'required|exists:markets,id',
        ]);

        $marketFarmer = MarketFarmer::where('market_id', $request->market_id)
            ->where('farmer_id', auth()->id())
            ->first();

        if ($marketFarmer) {
            $marketFarmer->update([
                'is_active' => true,
            ]);
        } else {
            MarketFarmer::create([
                'market_id' => $request->market_id,
                'farmer_id' => auth()->id(),
                'is_active' => true,
            ]);
        }

        return redirect()
            ->route('my_markets')
            ->with('success', 'Market joined successfully.');
    }

    public function myMarketsLeave($id)
    {
        $marketFarmer = MarketFarmer::where('market_id', $id)
            ->where('farmer_id', auth()->id())
            ->firstOrFail();

        $marketFarmer->delete();

        return redirect()
            ->route('my_markets')
            ->with('success', 'Market left successfully.');
    }


    public function categories(Request $request)
    {
        $search = $this->searchTerm($request);

        $categories = Category::query()
            ->when($search !== null, fn ($query) => $query->where(function ($inner) use ($search) {
                $inner->where('name', 'like', "%{$search}%")->orWhere('slug', 'like', "%{$search}%");
            }))
            ->orderBy('id')
            ->paginate($this->perPage)
            ->withQueryString();

        return view('Dashboard.Categories.categories', compact('categories'));
    }

    public function categoryAdd()
    {
        return view('Dashboard.Categories.add-category');
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

        return redirect()->route('categories')
            ->with('success', 'Category added successfully.');
    }

    public function categoryEdit($id)
    {
        $category = Category::findOrFail($id);

        return view('Dashboard.Categories.edit-category', compact('category'));
    }

    public function categoryUpdate(Request $request, $id)
    {
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

        return redirect()->route('categories')
            ->with('success', 'Category updated successfully.');
    }

    public function categoryDelete($id)
    {
        return $this->deleteModel(Category::findOrFail($id), 'category', 'categories');
    }

    public function products(Request $request)
    {
        $query = Product::with(['farmer.user', 'category']);

        if (auth()->user()->hasRole('farmer')) {
            $query->where('farmer_id', auth()->id());
        }

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('farmer.user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $products = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('Dashboard.Products.products', compact('products'));
    }

    public function productAdd()
    {
        return view('Dashboard.Products.add-product');
    }

    public function productStore(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required|max:100',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'unit' => 'required|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();

            $request->image->move(
                public_path('product_images'),
                $imageName
            );
        }

        Product::create([
            'farmer_id' => auth()->id(),
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'unit' => $request->unit,
            'image' => $imageName,
            'is_active' => false,
            'approval_status' => 'pending',
            'rejection_reason' => null,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return redirect()
            ->route('products')
            ->with('success', 'Product added successfully and sent for approval.');
    }

    public function productApprove($id)
    {
        $product = Product::with('farmer.user')->findOrFail($id);

        $product->update([
            'approval_status' => 'approved',
            'is_active' => true,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        $sent = SafeMail::send(
            $product->farmer?->user?->email,
            new \App\Mail\ProductApprovedMail($product)
        );

        return redirect()
            ->route('products')
            ->with(
                $sent ? 'success' : 'warning',
                $sent
                    ? 'Product approved successfully and farmer notified by email.'
                    : 'Product approved, but the farmer could not be notified by email.'
            );
    }

    public function productReject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:2000',
        ]);

        $product = Product::with('farmer.user')->findOrFail($id);

        $product->update([
            'approval_status' => 'rejected',
            'is_active' => false,
            'approved_by' => null,
            'approved_at' => null,
            'rejection_reason' => $request->rejection_reason,
        ]);

        $sent = SafeMail::send(
            $product->farmer?->user?->email,
            new \App\Mail\ProductRejectedMail($product, $request->rejection_reason)
        );

        return redirect()
            ->route('products')
            ->with(
                $sent ? 'success' : 'warning',
                $sent
                    ? 'Product rejected and farmer notified by email.'
                    : 'Product rejected, but the farmer could not be notified by email.'
            );
    }

    public function productEdit($id)
    {
        $product = Product::with(['farmer.user', 'category'])
            ->findOrFail($id);

        return view('Dashboard.Products.edit-product', compact('product'));
    }

    public function productUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required',
            'name' => 'required|max:100',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'unit' => 'required|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imageName = $product->image;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();

            $request->image->move(
                public_path('product_images'),
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
            'is_active' => $product->is_active,
            'approval_status' => $product->approval_status,
            'rejection_reason' => $product->rejection_reason,
            'approved_by' => $product->approved_by,
            'approved_at' => $product->approved_at,
        ]);

        return redirect()
            ->route('products')
            ->with('success', 'Product updated successfully.');
    }

    public function productView($id)
    {
        $product = Product::with(['farmer.user', 'category'])
            ->findOrFail($id);

        return view('Dashboard.Products.view-product', compact('product'));
    }

    public function productDelete($id)
    {
        return $this->deleteModel(Product::findOrFail($id), 'product', 'products');
    }
    public function stock(Request $request)
    {
        $search = $this->searchTerm($request);

        $weekly_stock = WeeklyStockTemplate::with('product')
            ->when($search !== null, fn ($query) => $query->where(function ($inner) use ($search) {
                $inner->where('day_of_week', 'like', "%{$search}%")
                    ->orWhereHas('product', fn ($product) => $product->where('name', 'like', "%{$search}%"));
            }))
            ->orderBy('id')
            ->paginate($this->perPage)
            ->withQueryString();

        return view('Dashboard.WeeklyStock.weekly-stock', compact('weekly_stock'));
    }
    public function stockAdd()
    {
        $products = Product::all();

        return view('Dashboard.WeeklyStock.add-stock', compact('products'));
    }

    public function stockStore(Request $request)
    {
        WeeklyStockTemplate::create([
            'farmer_id' => Auth::id(),
            'product_id' => $request->product_id,
            'day_of_week' => $request->day_of_week,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('stock');
    }



    public function stockEdit($id)
    {
        $stock = WeeklyStockTemplate::findOrFail($id);
        $products = Product::all();

        return view('Dashboard.WeeklyStock.edit-stock', compact('stock', 'products'));
    }

    public function stockUpdate(Request $request, $id)
    {
        $stock = WeeklyStockTemplate::findOrFail($id);

        $stock->update([
            'product_id' => $request->product_id,
            'day_of_week' => $request->day_of_week,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('stock');
    }

    public function stockDelete($id)
    {
        return $this->deleteModel(WeeklyStockTemplate::findOrFail($id), 'weekly stock schedule', 'stock');
    }

    public function orders(Request $request)
    {
        $query = Order::with(['user', 'pickupSlot'])->withSum('items', 'quantity');

        if (auth()->user()->role === 'farmer') {

            $farmer = FarmerProfile::where('user_id', auth()->id())->first();

            if (!$farmer) {
                // No farmer profile yet: an always-empty (but real) paginator.
                $query->whereRaw('1 = 0');
            } else {
                $query->where('farmer_id', $farmer->id);
            }
        }

        if (($search = $this->searchTerm($request)) !== null) {
            $query->where(function ($inner) use ($search) {
                $inner->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });

                if (ctype_digit($search)) {
                    $inner->orWhere('id', (int) $search);
                }
            });
        }

        $orders = $query->latest('order_date')->orderByDesc('id')
            ->paginate($this->perPage)
            ->withQueryString();

        return view('Dashboard.Orders.orders', compact('orders'));
    }
    public function orderView($id)
    {
        $order = Order::with([
            'user',
            'pickupSlot',
            'items.product'
        ])->findOrFail($id);

        return view('Dashboard.Orders.view-order', compact('order'));
    }

    public function orderStatusUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,ready,picked_up,cancelled',
        ]);

        $order = Order::findOrFail($id);

        $order->status = $request->status;
        $order->save();

        return redirect()->route('orders')->with('success', 'Order status updated.');
    }

    public function slots(Request $request)
    {
        $search = $this->searchTerm($request);

        $slots = PickupSlot::with('market')
            ->when($search !== null, fn ($query) => $query->where(function ($inner) use ($search) {
                $inner->where('date', 'like', "%{$search}%")
                    ->orWhereHas('market', fn ($market) => $market->where('name', 'like', "%{$search}%"));
            }))
            ->orderBy('id')
            ->paginate($this->perPage)
            ->withQueryString();

        return view('Dashboard.PickupSlots.pickup-slots', compact('slots'));
    }

    public function slotAdd()
    {
        $markets = Market::all();

        return view('Dashboard.PickupSlots.add-slot', compact('markets'));
    }

    public function slotStore(Request $request)
    {
        PickupSlot::create([
            'farmer_id' => Auth::id(),
            'market_id' => $request->market_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'capacity' => $request->capacity,
            'is_available' => $request->has('is_available'),
        ]);

        return redirect()->route('slots');
    }


    public function slotEdit($id)
    {
        $slot = PickupSlot::findOrFail($id);
        $markets = Market::all();
        return view('Dashboard.PickupSlots.edit-slot', compact('slot', 'markets'));
    }

    public function slotUpdate(Request $request, $id)
    {
        $slot = PickupSlot::findOrFail($id);

        $slot->update([
            'market_id' => $request->market_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'capacity' => $request->capacity,
            'is_available' => $request->has('is_available'),
        ]);

        return redirect()->route('slots');
    }

    public function slotDelete($id)
    {
        return $this->deleteModel(PickupSlot::findOrFail($id), 'pickup slot', 'slots');
    }

    public function markets(Request $request)
    {
        $search = $this->searchTerm($request);

        $markets = Market::withCount('marketFarmers')
            ->when($search !== null, fn ($query) => $query->where(function ($inner) use ($search) {
                $inner->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            }))
            ->orderBy('id')
            ->paginate($this->perPage)
            ->withQueryString();

        return view('Dashboard.Markets.markets', compact('markets'));
    }

    public function marketAdd()
    {
        return view('Dashboard.Markets.add-market');
    }

    public function marketStore(Request $request)
    {
        Market::create($request->all());

        return redirect()->route('markets');
    }

    public function marketEdit($id)
    {
        $market = Market::findOrFail($id);

        return view('Dashboard.Markets.edit-market', compact('market'));
    }

    public function marketUpdate(Request $request, $id)
    {
        $market = Market::findOrFail($id);

        $market->update($request->all());

        return redirect()->route('markets');
    }

    public function marketDelete($id)
    {
        return $this->deleteModel(Market::findOrFail($id), 'market', 'markets');
    }

    public function users(Request $request)
    {
        $search = $this->searchTerm($request);

        $users = User::query()
            ->when($search !== null, fn ($query) => $query->where(function ($inner) use ($search) {
                $inner->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            }))
            ->latest()
            ->orderByDesc('id')
            ->paginate($this->perPage)
            ->withQueryString();

        return view('Dashboard.Users.users', compact('users'));
    }

    public function userAdd()
    {
        return view('Dashboard.Users.add-user');
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
            'password' => $request->password,
            'is_active' => $request->boolean('is_active'),
        ]);

        $user->assignRole($request->role);
        $user->syncRoles([$request->role]);

        return redirect()->route('users')
            ->with('success', 'User created successfully.');
    }

    public function userEdit($id)
    {
        $user = User::findOrFail($id);

        return view('Dashboard.Users.edit-user', compact('user'));
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'role' => 'required|in:admin,farmer,customer',
            'password' => 'nullable|string|min:6',
        ]);

        $wasActive = (bool) $user->is_active;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->role = $request->role;
        $user->is_active = $request->boolean('is_active');

        if ($user->role === 'farmer' && $user->is_active) {
            $user->approval_status = 'approved';
            $user->rejection_reason = null;
        }

        if ($request->filled('password')) {
            $user->password = $request->password;
        }

        $user->save();

        // Inactive -> active for a farmer = the admin just approved them: tell them by email.
        if ($user->role === 'farmer' && ! $wasActive && $user->is_active) {
            [$type, $message] = $this->notifyFarmerApproved($user);

            return redirect()->route('users')->with($type, $message);
        }

        return redirect()->route('users')->with('success', 'User updated successfully.');
    }

    /**
     * One-click approval of a pending farmer from the Users list.
     */
    public function farmerApprove($id)
    {
        $user = User::where('role', 'farmer')->findOrFail($id);

        if ($user->is_active) {
            return redirect()->route('users')->with('warning', 'This farmer is already approved.');
        }

        $user->is_active = true;
        $user->approval_status = 'approved';
        $user->rejection_reason = null;
        $user->save();

        [$type, $message] = $this->notifyFarmerApproved($user);

        return redirect()->route('users')->with($type, $message);
    }

    /**
     * Reject a pending farmer. The admin's comment is stored and emailed.
     */
    public function farmerReject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:2000',
        ]);

        $user = User::where('role', 'farmer')->findOrFail($id);

        $user->is_active = false;
        $user->approval_status = 'rejected';
        $user->rejection_reason = $request->rejection_reason;
        $user->save();

        $sent = SafeMail::send($user->email, new FarmerRejectedMail($user, $request->rejection_reason));

        return redirect()->route('users')->with(
            $sent ? 'success' : 'warning',
            $sent
                ? 'Farmer rejected and notified by email.'
                : 'Farmer rejected, but the email could not be sent. Check the mail settings (storage/logs/laravel.log).'
        );
    }

    public function userDelete($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('users')->with('error', 'You cannot delete your own account.');
        }

        return $this->deleteModel($user, 'user', 'users');
    }
    public function farmers(Request $request)
    {
        $search = $this->searchTerm($request);

        $farmers = MarketFarmer::with(['farmer', 'market'])
            ->when($search !== null, fn ($query) => $query->where(function ($inner) use ($search) {
                $inner->whereHas('farmer', fn ($farmer) => $farmer->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('market', fn ($market) => $market->where('name', 'like', "%{$search}%"));
            }))
            ->orderBy('id')
            ->paginate($this->perPage)
            ->withQueryString();

        return view('Dashboard.Farmers.farmers', compact('farmers'));
    }

    public function farmerEdit($id)
    {
        $farmer = MarketFarmer::findOrFail($id);
        $markets = Market::all();
        $users = User::where('role', 'farmer')->get();

        return view(
            'Dashboard.Farmers.edit-farmer',
            compact('farmer', 'markets', 'users')
        );
    }
    public function farmerUpdate(Request $request, $id)
    {
        $farmer = MarketFarmer::find($id);
        $farmer->market_id = $request->market_id;
        $farmer->farmer_id = $request->farmer_id;
        $farmer->is_active = $request->is_active;
        $farmer->save();
        return redirect()->route('farmers');
    }

    public function farmerDelete($id)
    {
        return redirect()->route('farmers');
    }

    public function customers()
    {
        return view('Dashboard.Customers.customers');
    }

    public function customerView($id)
    {
        return view('Dashboard.Customers.view-customer');
    }

    public function customerDelete($id)
    {
        return redirect()->route('customers');
    }

    public function reviews(Request $request)
    {
        $search = $this->searchTerm($request);

        $reviews = Review::with(['user', 'farmer.user', 'product'])
            ->when($search !== null, fn ($query) => $query->where(function ($inner) use ($search) {
                $inner->where('comment', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('farmer.user', fn ($user) => $user->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('product', fn ($product) => $product->where('name', 'like', "%{$search}%"));
            }))
            ->latest()
            ->orderByDesc('id')
            ->paginate($this->perPage)
            ->withQueryString();

        return view('Dashboard.Reviews.reviews', compact('reviews'));
    }

    public function reviewFlag($id)
    {
        $review = Review::findOrFail($id);

        $review->is_flagged = !$review->is_flagged;
        $review->save();

        return redirect()->route('reviews')->with('success', 'Review flag status updated.');
    }

    public function reviewDelete($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('reviews')->with('success', 'Review deleted successfully.');
    }

    public function reports(Request $request)
    {
        $query = Report::orderBy('id', 'asc');

        if ($request->report_type && $request->report_type != 'all') {
            $query->where('report_type', $request->report_type);
        }

        $reports = $query->paginate($this->perPage)->withQueryString();

        return view('Dashboard.Reports.reports', compact('reports'));
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

        if ($request->report_type == 'sales' || $request->report_type == 'orders') {

            $data = \App\Models\Order::with(['user', 'farmer', 'pickupSlot'])
                ->whereBetween('order_date', [
                    $request->date_from . ' 00:00:00',
                    $request->date_to . ' 23:59:59'
                ])
                ->get();
        } elseif ($request->report_type == 'farmers') {

            $data = \App\Models\FarmerProfile::with('user')
                ->whereBetween('created_at', [
                    $request->date_from . ' 00:00:00',
                    $request->date_to . ' 23:59:59'
                ])
                ->get();
        } else {

            $data = \App\Models\Product::with(['farmer', 'category'])
                ->whereBetween('created_at', [
                    $request->date_from . ' 00:00:00',
                    $request->date_to . ' 23:59:59'
                ])
                ->get();
        }

        $pdf = Pdf::loadView('Dashboard.Reports.report-pdf', [
            'report' => $report,
            'data' => $data,
        ]);

        $directory = storage_path('app/reports');

        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName = 'report_' . $report->id . '.pdf';
        $path = $directory . '/' . $fileName;

        file_put_contents($path, $pdf->output());

        $report->update([
            'file_path' => 'reports/' . $fileName,
        ]);

        return response()->download(
            $path,
            'MarketLink_Report_' . $report->id . '.pdf',
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }



    public function reportDownload(Request $request, $id)
    {
        $request->validate([
            'format' => 'required|in:pdf,xlsx',
        ]);

        $report = Report::findOrFail($id);

        if ($report->report_type == 'sales' || $report->report_type == 'orders') {

            $data = Order::with(['user', 'farmer', 'pickupSlot'])
                ->whereBetween('order_date', [
                    $report->date_from . ' 00:00:00',
                    $report->date_to . ' 23:59:59'
                ])
                ->get();
        } elseif ($report->report_type == 'farmers') {

            $data = FarmerProfile::with('user')
                ->whereBetween('created_at', [
                    $report->date_from . ' 00:00:00',
                    $report->date_to . ' 23:59:59'
                ])
                ->get();
        } else {

            $data = Product::with(['farmer', 'category'])
                ->whereBetween('created_at', [
                    $report->date_from . ' 00:00:00',
                    $report->date_to . ' 23:59:59'
                ])
                ->get();
        }


        if ($request->format === 'xlsx') {

            return $this->reportsExportXlsx(
                [
                    $report->report_type => [
                        'label' => ucfirst($report->report_type),
                        'data' => $data,
                    ],
                ],
                [
                    'report_type' => $report->report_type,
                    'date_from' => $report->date_from,
                    'date_to' => $report->date_to,
                ]
            );
        }


        $pdf = Pdf::loadView(
            'Dashboard.Reports.download-pdf',
            [
                'report' => $report,
                'data' => $data,
            ]
        );

        return $pdf->download(
            'MarketLink_Report_' . $report->id . '_Download.pdf'
        );
    }


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
    public function reportsExportXlsx(array $sections, array $filters)
    {
        $fileName = 'MarketLink_Reports_Export_' . now()->format('Ymd_His') . '.xlsx';
        $filePath = storage_path('app/' . $fileName);

        $rows = [];

        foreach ($sections as $section) {

            $rows[] = [
                'sheet' => $section['label'],
                'data' => $section['data'],
            ];
        }

        $zip = new \ZipArchive();

        if ($zip->open($filePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Unable to create Excel file.');
        }

        $sheetNames = [];
        $sheetIndex = 1;

        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
<Default Extension="xml" ContentType="application/xml"/>
<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>';

        $workbookSheets = '';
        $workbookRels = '';

        foreach ($rows as $index => $section) {

            $sheetName = preg_replace('/[\\\\\/\?\*\[\]\:]/', '', $section['sheet']);
            $sheetName = mb_substr($sheetName ?: 'Report', 0, 31);

            $sheetNames[] = $sheetName;

            $safeSheetName = htmlspecialchars($sheetName, ENT_XML1 | ENT_QUOTES, 'UTF-8');

            $workbookSheets .= '<sheet name="' . $safeSheetName . '" sheetId="' . $sheetIndex . '" r:id="rId' . $sheetIndex . '"/>';

            $contentTypes .= '<Override PartName="/xl/worksheets/sheet' . $sheetIndex . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';

            $workbookRels .= '<Relationship Id="rId' . $sheetIndex . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . $sheetIndex . '.xml"/>';

            $sheetData = [];

            if ($section['sheet'] === 'Sales Summary') {

                $sheetData[] = [
                    'Order ID',
                    'Customer',
                    'Farmer',
                    'Pickup Slot',
                    'Total Amount',
                    'Status',
                    'Order Date'
                ];

                foreach ($section['data'] as $order) {

                    $farmerName = $order->farmer?->user?->name
                        ?? $order->farmer?->name
                        ?? 'N/A';

                    $pickupSlot = $order->pickupSlot
                        ? ($order->pickupSlot->date . ' ' . $order->pickupSlot->start_time . ' - ' . $order->pickupSlot->end_time)
                        : 'N/A';

                    $sheetData[] = [
                        $order->id,
                        $order->user?->name ?? 'N/A',
                        $farmerName,
                        $pickupSlot,
                        $order->total_amount,
                        ucfirst(str_replace('_', ' ', $order->status)),
                        $order->order_date,
                    ];
                }
            } elseif ($section['sheet'] === 'Orders') {

                $sheetData[] = [
                    'Order ID',
                    'Customer',
                    'Farmer',
                    'Pickup Slot',
                    'Total Amount',
                    'Status',
                    'Order Date'
                ];

                foreach ($section['data'] as $order) {

                    $farmerName = $order->farmer?->user?->name
                        ?? $order->farmer?->name
                        ?? 'N/A';

                    $pickupSlot = $order->pickupSlot
                        ? ($order->pickupSlot->date . ' ' . $order->pickupSlot->start_time . ' - ' . $order->pickupSlot->end_time)
                        : 'N/A';

                    $sheetData[] = [
                        $order->id,
                        $order->user?->name ?? 'N/A',
                        $farmerName,
                        $pickupSlot,
                        $order->total_amount,
                        ucfirst(str_replace('_', ' ', $order->status)),
                        $order->order_date,
                    ];
                }
            } elseif ($section['sheet'] === 'Farmer Activity') {

                $sheetData[] = [
                    'Farmer ID',
                    'Farmer Name',
                    'Stall Name',
                    'Business Name',
                    'City',
                    'Approval Status',
                    'Created At'
                ];

                foreach ($section['data'] as $farmer) {

                    $sheetData[] = [
                        $farmer->id,
                        $farmer->user?->name ?? 'N/A',
                        $farmer->stall_name ?? 'N/A',
                        $farmer->business_name ?? 'N/A',
                        $farmer->city ?? 'N/A',
                        ucfirst($farmer->approval_status ?? 'N/A'),
                        $farmer->created_at,
                    ];
                }
            } elseif ($section['sheet'] === 'Product Inventory') {

                $sheetData[] = [
                    'Product ID',
                    'Product Name',
                    'Farmer',
                    'Category',
                    'Price',
                    'Stock Quantity',
                    'Unit',
                    'Active',
                    'Created At'
                ];

                foreach ($section['data'] as $product) {

                    $farmerName = $product->farmer?->user?->name
                        ?? $product->farmer?->name
                        ?? 'N/A';

                    $sheetData[] = [
                        $product->id,
                        $product->name,
                        $farmerName,
                        $product->category?->name ?? 'N/A',
                        $product->price,
                        $product->stock_quantity,
                        $product->unit,
                        $product->is_active ? 'Yes' : 'No',
                        $product->created_at,
                    ];
                }
            }

            $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
<sheetData>';

            $rowNumber = 1;

            foreach ($sheetData as $row) {

                $sheetXml .= '<row r="' . $rowNumber . '">';

                $columnNumber = 1;

                foreach ($row as $value) {

                    $columnLetter = '';

                    $number = $columnNumber;

                    while ($number > 0) {
                        $remainder = ($number - 1) % 26;
                        $columnLetter = chr(65 + $remainder) . $columnLetter;
                        $number = intdiv($number - 1, 26);
                    }

                    $cellReference = $columnLetter . $rowNumber;

                    if (is_null($value)) {
                        $value = '';
                    }

                    if ($value instanceof \Carbon\Carbon) {
                        $value = $value->format('Y-m-d H:i:s');
                    }

                    $value = (string) $value;

                    $escapedValue = htmlspecialchars(
                        $value,
                        ENT_XML1 | ENT_QUOTES,
                        'UTF-8'
                    );

                    $sheetXml .= '<c r="' . $cellReference . '" t="inlineStr">
                    <is><t>' . $escapedValue . '</t></is>
                </c>';

                    $columnNumber++;
                }

                $sheetXml .= '</row>';

                $rowNumber++;
            }

            $sheetXml .= '</sheetData></worksheet>';

            $zip->addFromString(
                'xl/worksheets/sheet' . $sheetIndex . '.xml',
                $sheetXml
            );

            $sheetIndex++;
        }

        $contentTypes .= '</Types>';

        $workbookXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"
xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
<sheets>' . $workbookSheets . '</sheets>
</workbook>';

        $workbookRelsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
' . $workbookRels . '
</Relationships>';

        $rootRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1"
Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument"
Target="xl/workbook.xml"/>
</Relationships>';

        $stylesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
<fonts count="1">
<font>
<sz val="11"/>
<name val="Calibri"/>
</font>
</fonts>
<fills count="2">
<fill><patternFill patternType="none"/></fill>
<fill><patternFill patternType="gray125"/></fill>
</fills>
<borders count="1">
<border/>
</borders>
<cellStyleXfs count="1">
<xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>
</cellStyleXfs>
<cellXfs count="1">
<xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>
</cellXfs>
</styleSheet>';

        $zip->addFromString(
            '[Content_Types].xml',
            $contentTypes
        );

        $zip->addFromString(
            '_rels/.rels',
            $rootRels
        );

        $zip->addFromString(
            'xl/workbook.xml',
            $workbookXml
        );

        $zip->addFromString(
            'xl/_rels/workbook.xml.rels',
            $workbookRelsXml
        );

        $zip->addFromString(
            'xl/styles.xml',
            $stylesXml
        );

        $zip->close();

        return response()
            ->download(
                $filePath,
                $fileName,
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            )
            ->deleteFileAfterSend(true);
    }

    public function announcements(Request $request)
    {
        $search = $this->searchTerm($request);

        $announcements = Announcement::with('admin')
            ->when($search !== null, fn ($query) => $query->where(function ($inner) use ($search) {
                $inner->where('title', 'like', "%{$search}%")->orWhere('message', 'like', "%{$search}%");
            }))
            ->latest()
            ->orderByDesc('id')
            ->paginate($this->perPage)
            ->withQueryString();

        return view('Dashboard.Announcements.announcements', compact('announcements'));
    }

    public function announcementAdd()
    {
        return view('Dashboard.Announcements.add-announcement');
    }

    public function announcementStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'message' => 'required|string',
            'published_at' => 'nullable|date',
        ]);

        Announcement::create([
            'admin_id' => auth()->id(),
            'title' => $request->title,
            'message' => $request->message,
            'published_at' => $request->published_at,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('announcements')->with('success', 'Announcement published successfully.');
    }

    public function announcementEdit($id)
    {
        $announcement = Announcement::findOrFail($id);

        return view('Dashboard.Announcements.edit-announcement', compact('announcement'));
    }

    public function announcementUpdate(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:150',
            'message' => 'required|string',
            'published_at' => 'nullable|date',
        ]);

        $announcement->title = $request->title;
        $announcement->message = $request->message;
        $announcement->published_at = $request->published_at;
        $announcement->is_active = $request->boolean('is_active');
        $announcement->save();

        return redirect()->route('announcements')->with('success', 'Announcement updated successfully.');
    }

    public function announcementDelete($id)
    {
        return $this->deleteModel(Announcement::findOrFail($id), 'announcement', 'announcements');
    }

    /* ---------------------------------------------------------------
     | Contact form inbox (messages sent from the public Contact page)
     * ------------------------------------------------------------- */

    public function contactMessages(Request $request)
    {
        $search = $this->searchTerm($request);

        $messages = ContactMessage::query()
            ->when($search !== null, fn ($query) => $query->where(function ($inner) use ($search) {
                $inner->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            }))
            ->when($request->query('status') === 'unread', fn ($query) => $query->whereNull('read_at'))
            ->latest()
            ->orderByDesc('id')
            ->paginate($this->perPage)
            ->withQueryString();

        return view('Dashboard.ContactMessages.contact-messages', compact('messages'));
    }

    public function contactMessageShow($id)
    {
        $message = ContactMessage::findOrFail($id);

        if ($message->read_at === null) {
            $message->forceFill(['read_at' => now()])->save();
        }

        return view('Dashboard.ContactMessages.view-contact-message', compact('message'));
    }

    public function contactMessageDelete($id)
    {
        return $this->deleteModel(ContactMessage::findOrFail($id), 'message', 'contact_messages');
    }
}
