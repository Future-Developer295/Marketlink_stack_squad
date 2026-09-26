<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\FarmerProfile;
use App\Models\Market;
use App\Models\MarketFarmer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PickupSlot;
use App\Models\Product;
use App\Models\Report;
use App\Models\Review;
use App\Models\User;
use App\Models\WeeklyStockTemplate;
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

        $name = time().'_'.uniqid().'.'.$file->extension();
        $file->move($path, $name);

        return $name;
    }

    protected function deletePublicImage(?string $file, string $directory): void
    {
        if (! $file) {
            return;
        }

        $path = public_path($directory.'/'.$file);

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
                fn ($key) => (int) ($statusCounts[$key] ?? 0),
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
                ->map(fn ($item) => $item->product->name ?? 'Unknown')
                ->values()
                ->all();

            $topProductData = $topProducts
                ->map(fn ($item) => (int) $item->units_sold)
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

    public function farmerProfile()
    {
        $farmer = FarmerProfile::where('user_id', Auth::id())->first();

        return view('Dashboard.Farmer.Profile.profile', compact('farmer'));
    }

    public function farmerProfileUpdate(Request $request)
    {
        $validated = $request->validate([
            'stall_name' => 'required|string|max:200',
            'business_name' => 'required|string|max:150',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'operating_days' => 'required|array|min:1',
            'operating_days.*' => 'required|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'farmer_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $farmer = FarmerProfile::firstOrNew(['user_id' => Auth::id()]);
        $farmer->stall_name = $validated['stall_name'];
        $farmer->business_name = $validated['business_name'];
        $farmer->description = $validated['description'];
        $farmer->address = $validated['address'];
        $farmer->city = $validated['city'];
        $farmer->state = $validated['state'];
        $farmer->country = $validated['country'];
        $farmer->latitude = $validated['latitude'];
        $farmer->longitude = $validated['longitude'];
        $farmer->operating_days = implode(', ', $validated['operating_days']);
        $farmer->start_time = $validated['start_time'];
        $farmer->end_time = $validated['end_time'];

        if (! $farmer->exists) {
            $farmer->approval_status = 'pending';
        }

        if ($request->hasFile('farmer_image')) {
            $this->deletePublicImage($farmer->farmer_image, 'farmer_images');
            $farmer->farmer_image = $this->savePublicImage($request->file('farmer_image'), 'farmer_images');
        }

        $farmer->save();

        return redirect()->route('farmer.profile')->with('success', 'Profile updated successfully.');
    }

    public function farmerMarkets(Request $request)
    {
        $query = MarketFarmer::with('market')->where('farmer_id', Auth::id());

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->whereHas('market', function ($marketQuery) use ($search) {
                $marketQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $myMarkets = $query->latest()->get();

        return view('Dashboard.Farmer.Markets.my-markets', compact('myMarkets'));
    }

    public function farmerMarketCreate()
    {
        $joinedIds = MarketFarmer::where('farmer_id', Auth::id())->pluck('market_id');
        $markets = Market::whereNotIn('id', $joinedIds)->orderBy('name')->get();

        return view('Dashboard.Farmer.Markets.join-market', compact('markets'));
    }

    public function farmerMarketStore(Request $request)
    {
        $validated = $request->validate([
            'market_id' => 'required|exists:markets,id',
        ]);

        MarketFarmer::updateOrCreate(
            [
                'market_id' => $validated['market_id'],
                'farmer_id' => Auth::id(),
            ],
            [
                'is_active' => false,
            ]
        );

        return redirect()->route('farmer.markets.index')->with('success', 'Join request sent successfully.');
    }

    public function farmerMarketLeave($id)
    {
        $marketFarmer = MarketFarmer::where('farmer_id', Auth::id())->findOrFail($id);
        $marketFarmer->delete();

        return redirect()->route('farmer.markets.index')->with('success', 'Market removed successfully.');
    }

    public function farmerProducts(Request $request)
    {
        $farmer = $this->currentFarmer();
        $query = Product::with('category')->where('farmer_id', $farmer->id);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($productQuery) use ($search) {
                $productQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->get();

        return view('Dashboard.Farmer.Products.products', compact('products'));
    }

    public function farmerProductCreate()
    {
        $categories = Category::orderBy('name')->get();

        return view('Dashboard.Farmer.Products.add-product', compact('categories'));
    }

    public function farmerProductStore(Request $request)
    {
        $farmer = $this->currentFarmer();
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imageName = $request->hasFile('image')
            ? $this->savePublicImage($request->file('image'), 'product_images')
            : null;

        Product::create([
            'farmer_id' => $farmer->id,
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock_quantity' => $validated['stock_quantity'],
            'unit' => $validated['unit'],
            'image' => $imageName,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('farmer.products.index')->with('success', 'Product added successfully.');
    }

    public function farmerProductShow($id)
    {
        $farmer = $this->currentFarmer();
        $product = Product::with('category')
            ->where('farmer_id', $farmer->id)
            ->findOrFail($id);

        return view('Dashboard.Farmer.Products.view-product', compact('product'));
    }

    public function farmerProductEdit($id)
    {
        $farmer = $this->currentFarmer();
        $product = Product::where('farmer_id', $farmer->id)->findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('Dashboard.Farmer.Products.edit-product', compact('product', 'categories'));
    }

    public function farmerProductUpdate(Request $request, $id)
    {
        $farmer = $this->currentFarmer();
        $product = Product::where('farmer_id', $farmer->id)->findOrFail($id);
        $validated = $request->validate([
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
            $this->deletePublicImage($product->image, 'product_images');
            $imageName = $this->savePublicImage($request->file('image'), 'product_images');
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock_quantity' => $validated['stock_quantity'],
            'unit' => $validated['unit'],
            'image' => $imageName,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('farmer.products.index')->with('success', 'Product updated successfully.');
    }

    public function farmerProductDestroy($id)
    {
        $farmer = $this->currentFarmer();
        $product = Product::where('farmer_id', $farmer->id)->findOrFail($id);
        $this->deletePublicImage($product->image, 'product_images');
        $product->delete();

        return redirect()->route('farmer.products.index')->with('success', 'Product deleted successfully.');
    }

    public function farmerStock(Request $request)
    {
        $farmer = $this->currentFarmer();
        $query = WeeklyStockTemplate::with('product')->where('farmer_id', $farmer->id);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->whereHas('product', function ($productQuery) use ($search) {
                $productQuery->where('name', 'like', "%{$search}%");
            });
        }

        $weeklyStock = $query->orderBy('day_of_week')->latest('id')->get();

        return view('Dashboard.Farmer.WeeklyStock.weekly-stock', compact('weeklyStock'));
    }

    public function farmerStockCreate()
    {
        $farmer = $this->currentFarmer();
        $products = Product::where('farmer_id', $farmer->id)->orderBy('name')->get();

        return view('Dashboard.Farmer.WeeklyStock.add-stock', compact('products'));
    }

    public function farmerStockStore(Request $request)
    {
        $farmer = $this->currentFarmer();
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'day_of_week' => 'required|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $product = Product::where('farmer_id', $farmer->id)->findOrFail($validated['product_id']);

        WeeklyStockTemplate::create([
            'farmer_id' => $farmer->id,
            'product_id' => $product->id,
            'day_of_week' => $validated['day_of_week'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('farmer.stock.index')->with('success', 'Weekly stock added successfully.');
    }

    public function farmerStockEdit($id)
    {
        $farmer = $this->currentFarmer();
        $stock = WeeklyStockTemplate::where('farmer_id', $farmer->id)->findOrFail($id);
        $products = Product::where('farmer_id', $farmer->id)->orderBy('name')->get();

        return view('Dashboard.Farmer.WeeklyStock.edit-stock', compact('stock', 'products'));
    }

    public function farmerStockUpdate(Request $request, $id)
    {
        $farmer = $this->currentFarmer();
        $stock = WeeklyStockTemplate::where('farmer_id', $farmer->id)->findOrFail($id);
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'day_of_week' => 'required|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $product = Product::where('farmer_id', $farmer->id)->findOrFail($validated['product_id']);

        $stock->update([
            'product_id' => $product->id,
            'day_of_week' => $validated['day_of_week'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('farmer.stock.index')->with('success', 'Weekly stock updated successfully.');
    }

    public function farmerStockDestroy($id)
    {
        $farmer = $this->currentFarmer();
        $stock = WeeklyStockTemplate::where('farmer_id', $farmer->id)->findOrFail($id);
        $stock->delete();

        return redirect()->route('farmer.stock.index')->with('success', 'Weekly stock deleted successfully.');
    }

    public function farmerOrders(Request $request)
    {
        $farmer = $this->currentFarmer();
        $query = Order::with(['user', 'pickupSlot', 'items.product'])
            ->where('farmer_id', $farmer->id);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->latest('order_date')->get();

        return view('Dashboard.Farmer.Orders.orders', compact('orders'));
    }

    public function farmerOrderShow($id)
    {
        $farmer = $this->currentFarmer();
        $order = Order::with(['user', 'pickupSlot.market', 'items.product'])
            ->where('farmer_id', $farmer->id)
            ->findOrFail($id);

        return view('Dashboard.Farmer.Orders.view-order', compact('order'));
    }

    public function farmerOrderUpdateStatus(Request $request, $id)
    {
        $farmer = $this->currentFarmer();
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,ready,picked_up,cancelled',
        ]);

        $order = Order::where('farmer_id', $farmer->id)->findOrFail($id);
        $order->status = $validated['status'];
        $order->save();

        return redirect()->route('farmer.orders.show', $order->id)->with('success', 'Order status updated successfully.');
    }

    public function farmerSlots(Request $request)
    {
        $farmer = $this->currentFarmer();
        $query = PickupSlot::with('market')->where('farmer_id', $farmer->id);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->whereHas('market', function ($marketQuery) use ($search) {
                $marketQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $slots = $query->orderByDesc('date')->orderBy('start_time')->get();

        return view('Dashboard.Farmer.PickupSlots.pickup-slots', compact('slots'));
    }

    public function farmerSlotCreate()
    {
        $marketIds = MarketFarmer::where('farmer_id', Auth::id())
            ->where('is_active', true)
            ->pluck('market_id');
        $markets = Market::whereIn('id', $marketIds)->orderBy('name')->get();

        return view('Dashboard.Farmer.PickupSlots.add-slot', compact('markets'));
    }

    public function farmerSlotStore(Request $request)
    {
        $farmer = $this->currentFarmer();
        $validated = $request->validate([
            'market_id' => 'required|exists:markets,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1',
        ]);

        MarketFarmer::where('farmer_id', Auth::id())
            ->where('market_id', $validated['market_id'])
            ->where('is_active', true)
            ->firstOrFail();

        PickupSlot::create([
            'farmer_id' => $farmer->id,
            'market_id' => $validated['market_id'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'capacity' => $validated['capacity'],
            'is_available' => $request->boolean('is_available'),
        ]);

        return redirect()->route('farmer.slots.index')->with('success', 'Pickup slot added successfully.');
    }

    public function farmerSlotEdit($id)
    {
        $farmer = $this->currentFarmer();
        $slot = PickupSlot::where('farmer_id', $farmer->id)->findOrFail($id);
        $marketIds = MarketFarmer::where('farmer_id', Auth::id())
            ->where('is_active', true)
            ->pluck('market_id');
        $markets = Market::whereIn('id', $marketIds)->orderBy('name')->get();

        return view('Dashboard.Farmer.PickupSlots.edit-slot', compact('slot', 'markets'));
    }

    public function farmerSlotUpdate(Request $request, $id)
    {
        $farmer = $this->currentFarmer();
        $slot = PickupSlot::where('farmer_id', $farmer->id)->findOrFail($id);
        $validated = $request->validate([
            'market_id' => 'required|exists:markets,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1',
        ]);

        MarketFarmer::where('farmer_id', Auth::id())
            ->where('market_id', $validated['market_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $slot->update([
            'market_id' => $validated['market_id'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'capacity' => $validated['capacity'],
            'is_available' => $request->boolean('is_available'),
        ]);

        return redirect()->route('farmer.slots.index')->with('success', 'Pickup slot updated successfully.');
    }

    public function farmerSlotDestroy($id)
    {
        $farmer = $this->currentFarmer();
        $slot = PickupSlot::where('farmer_id', $farmer->id)->findOrFail($id);
        $slot->delete();

        return redirect()->route('farmer.slots.index')->with('success', 'Pickup slot deleted successfully.');
    }

    public function farmerReviews()
    {
        $farmer = $this->currentFarmer();
        $reviews = Review::with(['user', 'product', 'reply'])
            ->where('farmer_id', $farmer->id)
            ->latest()
            ->get();

        return view('Dashboard.Farmer.Reviews.reviews', compact('reviews'));
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
            'name' => 'required|string|max:255|unique:roles,name,'.$id,
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

        $role->delete();

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
            'name' => 'required|string|max:255|unique:permissions,name,'.$id,
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
            fn ($key) => (int) ($statusCounts[$key] ?? 0),
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
                        'farmer_images/'.
                        $farmer->farmer_image
                    )
                )
            ) {
                unlink(
                    public_path(
                        'farmer_images/'.
                        $farmer->farmer_image
                    )
                );
            }

            $directory = public_path('farmer_images');

            if (! file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $imageName = time().
                '_'.
                uniqid().
                '.'.
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
                            '%'.$search.'%'
                        )
                        ->orWhere(
                            'city',
                            'like',
                            '%'.$search.'%'
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

        $marketFarmer = MarketFarmer::where(
            'market_id',
            $request->market_id
        )
            ->where(
                'farmer_id',
                Auth::id()
            )
            ->first();

        if ($marketFarmer) {
            $marketFarmer->update([
                'is_active' => false,
            ]);
        } else {
            MarketFarmer::create([
                'market_id' => $request->market_id,

                'farmer_id' => Auth::id(),

                'is_active' => false,
            ]);
        }

        return redirect()
            ->route('my_markets')
            ->with(
                'success',
                'Join request sent. Waiting for admin approval.'
            );
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

    public function categories()
    {
        $categories = Category::orderBy(
            'name'
        )->get();

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

        $category->delete();

        return redirect()
            ->route('categories')
            ->with(
                'success',
                'Category deleted successfully.'
            );
    }

    public function products()
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

            $imageName = time().
                '_'.
                uniqid().
                '.'.
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
                        'product_images/'.
                        $product->image
                    )
                )
            ) {
                unlink(
                    public_path(
                        'product_images/'.
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

            $imageName = time().
                '_'.
                uniqid().
                '.'.
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
                    'product_images/'.
                    $product->image
                )
            )
        ) {
            unlink(
                public_path(
                    'product_images/'.
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

    public function stock()
    {
        $farmer = $this->currentFarmer();

        $weekly_stock = WeeklyStockTemplate::with(
            'product'
        )
            ->where(
                'farmer_id',
                $farmer->id
            )
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
                        '%'.$search.'%'
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

        $order->status = $request->status;

        $order->save();

        return redirect()
            ->route('orders')
            ->with(
                'success',
                'Order status updated successfully.'
            );
    }

    public function slots()
    {
        $query = PickupSlot::with('market');

        if (auth()->user()->hasRole('farmer')) {
            $farmer = $this->currentFarmer();

            $query->where(
                'farmer_id',
                $farmer->id
            );
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

    public function markets()
    {
        $markets = Market::all();

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
        Market::create(
            $request->all()
        );

        return redirect()
            ->route('markets');
    }

    public function marketEdit($id)
    {
        $market = Market::findOrFail($id);

        return view(
            'Dashboard.Markets.edit-market',
            compact('market')
        );
    }

    public function marketUpdate(
        Request $request,
        $id
    ) {
        $market = Market::findOrFail($id);

        $market->update(
            $request->all()
        );

        return redirect()
            ->route('markets');
    }

    public function marketDelete($id)
    {
        $market = Market::findOrFail($id);

        $market->delete();

        return redirect()
            ->route('markets');
    }

    public function users()
    {
        $users = User::latest()->get();

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

            'email' => 'required|email|max:255|unique:users,email,'.
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

        $user->delete();

        return redirect()
            ->route('users')
            ->with(
                'success',
                'User deleted successfully.'
            );
    }

    public function farmers()
    {
        $farmers = MarketFarmer::with([
            'farmer',
            'market',
        ])->get();

        return view(
            'Dashboard.Farmers.farmers',
            compact('farmers')
        );
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
                $profile->approval_status =
                    'approved';

                $profile->approved_by =
                    Auth::id();

                $profile->approved_at =
                    now();

                $profile->save();
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

        $farmer->delete();

        return redirect()
            ->route('farmers')
            ->with(
                'success',
                'Farmer removed from market successfully.'
            );
    }

    public function customers()
    {
        $customers = User::where(
            'role',
            'customer'
        )
            ->latest()
            ->get();

        return view(
            'Dashboard.Customers.customers',
            compact('customers')
        );
    }

    public function customerView($id)
    {
        $customer = User::where(
            'role',
            'customer'
        )
            ->with([
                'orders',
                'reviews',
                'favorites',
            ])
            ->findOrFail($id);

        return view(
            'Dashboard.Customers.view-customer',
            compact('customer')
        );
    }

    public function customerDelete($id)
    {
        $customer = User::where(
            'role',
            'customer'
        )->findOrFail($id);

        $customer->delete();

        return redirect()
            ->route('customers')
            ->with(
                'success',
                'Customer deleted successfully.'
            );
    }

    public function reviews()
    {
        $query = Review::with([
            'user',
            'farmer.user',
            'product',
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

        $reports = $query->get();

        return view(
            'Dashboard.Reports.reports',
            compact('reports')
        );
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
                        $request->date_from.
                        ' 00:00:00',

                        $request->date_to.
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
                        $request->date_from.
                        ' 00:00:00',

                        $request->date_to.
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
                        $request->date_from.
                        ' 00:00:00',

                        $request->date_to.
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
            'report_'.
            $report->id.
            '.pdf';

        $path =
            $directory.
            '/'.
            $fileName;

        file_put_contents(
            $path,
            $pdf->output()
        );

        $report->update([
            'file_path' => 'reports/'.
                $fileName,
        ]);

        return response()->download(
            $path,
            'MarketLink_Report_'.
            $report->id.
            '.pdf',
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }

    public function reportDownload($id)
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
                        $report->date_from.
                        ' 00:00:00',

                        $report->date_to.
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
                        $report->date_from.
                        ' 00:00:00',

                        $report->date_to.
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
                        $report->date_from.
                        ' 00:00:00',

                        $report->date_to.
                        ' 23:59:59',
                    ]
                )
                ->get();
        }

        $pdf = Pdf::loadView(
            'Dashboard.Reports.download-pdf',
            [
                'report' => $report,
                'data' => $data,
            ]
        );

        return $pdf->download(
            'MarketLink_Report_'.
            $report->id.
            '_Download.pdf'
        );
    }

    public function announcements()
    {
        $announcements = Announcement::with(
            'admin'
        )
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

        $announcement->delete();

        return redirect()
            ->route('announcements')
            ->with(
                'success',
                'Announcement deleted successfully.'
            );
    }
}
