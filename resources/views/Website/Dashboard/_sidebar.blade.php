<div class="ml-card ml-dash-sidebar">
    <div class="d-flex align-items-center gap-3 mb-3">
        <div class="ml-avatar-lg d-flex align-items-center justify-content-center bg-light">
            <i class="fa-solid fa-user text-success fs-4"></i>
        </div>
        <div>
            <strong class="d-block">{{ auth()->user()->name }}</strong>
            <span class="small text-muted">{{ auth()->user()->email }}</span>
        </div>
    </div>
    <nav class="ml-dash-nav">
        <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'is-active' : '' }}"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <a href="{{ url('/dashboard/orders') }}" class="{{ request()->is('dashboard/orders*') ? 'is-active' : '' }}"><i class="fa-regular fa-rectangle-list"></i> My Orders</a>
        <a href="{{ url('/dashboard/reviews') }}" class="{{ request()->is('dashboard/reviews*') ? 'is-active' : '' }}"><i class="fa-regular fa-star"></i> My Reviews</a>
        <a href="{{ url('/dashboard/favorites') }}" class="{{ request()->is('dashboard/favorites*') ? 'is-active' : '' }}"><i class="fa-regular fa-heart"></i> Favorites</a>
        <a href="{{ url('/dashboard/notifications') }}" class="{{ request()->is('dashboard/notifications*') ? 'is-active' : '' }}"><i class="fa-regular fa-bell"></i> Notifications</a>
        <a href="{{ url('/dashboard/profile') }}" class="{{ request()->is('dashboard/profile*') ? 'is-active' : '' }}"><i class="fa-regular fa-id-card"></i> Profile Settings</a>
    </nav>
    <form action="{{ url('/logout') }}" method="POST" class="mt-3">
        @csrf
        <button type="submit" class="btn ml-btn-secondary ml-btn-block ml-btn-sm"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
    </form>
</div>
