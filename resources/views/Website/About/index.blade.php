@extends('Website._master')

@section('page_title', 'About Us')

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <span class="active">About Us</span></nav>
</div>

<section class="pb-4">
    <div class="ml-container">
        <div class="ml-hero">
            <span class="ml-eyebrow"><i class="fa-solid fa-circle-info"></i> About MarketLink &middot; Direct Farm-to-Market Platform</span>
            <h1 class="display-6">Connecting Local Farmers With Their Community</h1>
            <p class="text-muted mt-2" style="max-width:640px;">MarketLink is a web platform designed to connect local farmers-market farmers with customers and make local market shopping more convenient and predictable.</p>
            <div class="d-flex gap-3 mt-4 flex-wrap">
                <a href="{{ url('/markets') }}" class="btn ml-btn-primary">Explore Markets <i class="fa-solid fa-arrow-right"></i></a>
                <a href="{{ url('/farmers') }}" class="btn ml-btn-secondary">Meet Our Farmers</a>
            </div>
            <div class="d-flex flex-wrap gap-3 mt-4 small text-muted">
                <span class="ml-badge ml-badge-mint"><i class="fa-solid fa-seedling"></i> Weekly Harvest Sync</span>
                <span class="ml-badge ml-badge-mint"><i class="fa-solid fa-store"></i> Pavilion Stall Pickup</span>
                <span class="ml-badge ml-badge-mint"><i class="fa-solid fa-shield-halved"></i> Pay-At-Stall Inspection</span>
            </div>
        </div>
    </div>
</section>

<section class="ml-section pt-2">
    <div class="ml-container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="ml-eyebrow">The Platform</span>
                <h2>What is MarketLink?</h2>
                <p class="text-muted mt-3">MarketLink brings farmers and customers together on one platform. Farmers can publish their weekly stock and prices and manage customer pre-orders for their market pickup. Customers can discover nearby markets, reserve items and plan their pickup.</p>
                <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mt-3">
                    <li><i class="fa-solid fa-circle-check text-success"></i> Real-time weekly harvest availability, live updates from registered growers</li>
                    <li><i class="fa-solid fa-circle-check text-success"></i> Zero courier markups or shipping fees. Settle face-to-face with the grower</li>
                    <li><i class="fa-solid fa-circle-check text-success"></i> Guaranteed morning market stall pickup, freshly tagged with your name</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1595855759920-86582396756c?auto=format&fit=crop&w=900&q=60" class="rounded-4 w-100" style="height:320px;object-fit:cover;" alt="Farmer with produce">
            </div>
        </div>
    </div>
</section>

<section class="ml-section ml-section--muted">
    <div class="ml-container">
        <div class="ml-heading-block mx-auto text-center">
            <span class="ml-eyebrow">Addressing Market Friction</span>
            <h2>Why MarketLink?</h2>
            <p class="mx-auto">Solving the core friction points of traditional weekend farmers market shopping without altering the authentic in-person experience.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="ml-card h-100">
                    <div class="ml-step__icon"><i class="fa-regular fa-calendar-xmark"></i></div>
                    <h6 class="mt-3">Unclear Weekly Availability</h6>
                    <p class="small text-muted mb-0">Customers often cannot know which farmers will appear on upcoming market mornings, or what specific vegetables were out of season.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ml-card h-100">
                    <div class="ml-step__icon"><i class="fa-solid fa-car-side"></i></div>
                    <h6 class="mt-3">Unplanned Market Trips</h6>
                    <p class="small text-muted mb-0">Customers may arrive early on Saturday only to discover prized organic greens sold out within minutes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ml-card h-100">
                    <div class="ml-step__icon"><i class="fa-solid fa-eye-slash"></i></div>
                    <h6 class="mt-3">Limited Farmer Visibility</h6>
                    <p class="small text-muted mb-0">Growers have historically lacked an accessible digital platform to broadcast inventory and confirm crate counts.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ml-section">
    <div class="ml-container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <span class="ml-eyebrow">The System</span>
                <h2>Our Solution</h2>
                <p class="text-muted mt-2">A transparent, 4-phase pre-order workflow built strictly around the operational realities of weekly community agriculture.</p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3 text-center">
                    <div class="col-6 col-md-3"><div class="ml-card"><span class="ml-badge ml-badge-mint">Step 01</span><strong class="d-block mt-2 small">1. Discover</strong></div></div>
                    <div class="col-6 col-md-3"><div class="ml-card"><span class="ml-badge ml-badge-mint">Step 02</span><strong class="d-block mt-2 small">2. Browse</strong></div></div>
                    <div class="col-6 col-md-3"><div class="ml-card"><span class="ml-badge ml-badge-mint">Step 03</span><strong class="d-block mt-2 small">3. Reserve</strong></div></div>
                    <div class="col-6 col-md-3"><div class="ml-card"><span class="ml-badge ml-badge-mint">Step 04</span><strong class="d-block mt-2 small">4. Pickup</strong></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ml-section ml-section--muted">
    <div class="ml-container">
        <div class="ml-heading-block mx-auto text-center">
            <span class="ml-eyebrow">Direct Traceability Loop</span>
            <h2>From Farmer to Customer</h2>
            <p class="mx-auto">How MarketLink creates a direct transparent loop between field harvests and your kitchen.</p>
        </div>
        <div class="row g-3 text-center">
            @foreach(['Farmer' => 'Harvests Crop', 'Weekly Stock' => 'Publishes Counts', 'MarketLink' => 'Syncs Listings', 'Customer' => 'Finds Pavilion', 'Pre-Order' => 'Reserves Basket', 'Pickup' => 'Inspects &amp; Settles'] as $step => $desc)
            <div class="col-6 col-md-2">
                <div class="ml-card {{ $step == 'MarketLink' ? 'text-white' : '' }}" style="{{ $step == 'MarketLink' ? 'background:var(--ml-primary-darker);' : '' }}">
                    <strong class="d-block">{{ $step }}</strong>
                    <span class="small {{ $step == 'MarketLink' ? '' : 'text-muted' }}">{!! $desc !!}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="ml-section">
    <div class="ml-container">
        <div class="ml-heading-block">
            <span class="ml-eyebrow">SRS Engineering &amp; Research</span>
            <h2>Meet Our Team</h2>
            <p>Building MarketLink as a robust, full-stack open web solution for sustainable community commerce.</p>
        </div>
        <div class="row g-4">
            @foreach([
                ['name' => 'Asim Khan', 'role' => 'Full-Stack Lead', 'sub' => 'Full-Stack &amp; System Integration', 'desc' => 'Engineered the role-based auth framework, session control, and core platform structures.'],
                ['name' => 'Sarah Ahmed', 'role' => 'UI / UX Lead', 'sub' => 'Frontend &amp; UI/UX Experience', 'desc' => 'Implemented semantic Tailwind design tokens, responsive grid layouts, and UI standards.'],
                ['name' => 'Bilal Hassan', 'role' => 'Database Architect', 'sub' => 'Backend Architecture &amp; Database', 'desc' => 'Designed relational schemas in MySQL, optimized queries, and developed RESTful pre-order workflows.'],
                ['name' => 'Fatima Noor', 'role' => 'QA &amp; Verification', 'sub' => 'QA &amp; Product Research', 'desc' => 'Verified functional compliance with SRS specifications, conducted user scenario tests.'],
            ] as $member)
            <div class="col-md-6 col-lg-3">
                <div class="ml-card text-center h-100">
                    <i class="fa-solid fa-circle-user fs-1 text-success"></i>
                    <strong class="d-block mt-2">{{ $member['name'] }}</strong>
                    <span class="small text-success d-block">{!! $member['sub'] !!}</span>
                    <p class="small text-muted mt-2 mb-0">{!! $member['desc'] !!}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="ml-section ml-section--muted">
    <div class="ml-container">
        <div class="ml-heading-block mx-auto text-center">
            <span class="ml-eyebrow">Core SRS Specifications</span>
            <h2>What MarketLink Provides</h2>
            <p class="mx-auto">Strictly engineered according to the functional requirements for regional produce distribution.</p>
        </div>
        <div class="row g-4">
            @foreach([
                ['icon' => 'fa-map-location-dot', 'title' => 'Local Market Discovery', 'desc' => 'Browse nearby farmers markets filtered by municipal zone, active days of the week, and operating hours.'],
                ['icon' => 'fa-user-check', 'title' => 'Farmer Discovery', 'desc' => 'Examine verified grower profiles, farming methods, crop histories, and organized pavilion stall identification.'],
                ['icon' => 'fa-boxes-stacked', 'title' => 'Weekly Stock', 'desc' => 'Access transparent live counts by weight or bundle, ensuring buyers always see current field stock prior to market arrival.'],
                ['icon' => 'fa-filter', 'title' => 'Product Search &amp; Filters', 'desc' => 'Sort through fruits, root vegetables, greens, honey, and dairy with precision filters for price ranges and specific hubs.'],
                ['icon' => 'fa-bookmark', 'title' => 'Pre-Orders', 'desc' => 'Reserve seasonal items ahead of time with instant reservation confirmation, eliminating stock-out disappointment at crowded stalls.'],
                ['icon' => 'fa-basket-shopping', 'title' => 'Pickup', 'desc' => 'Organized pickup windows give buyers convenient retrieval from stallholders with in-person inspection and direct settlement.'],
            ] as $feature)
            <div class="col-md-4">
                <div class="ml-card h-100">
                    <div class="ml-step__icon"><i class="fa-solid {{ $feature['icon'] }}"></i></div>
                    <h6 class="mt-3">{!! $feature['title'] !!}</h6>
                    <p class="small text-muted mb-0">{!! $feature['desc'] !!}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="ml-section">
    <div class="ml-container">
        <div class="row g-4">
            <div class="col-lg-6">
                <span class="ml-eyebrow">User Experience</span>
                <h2>Designed Around the Customer</h2>
                <p class="text-muted mt-2">A friction-free 6-step path from market discovery to in-person crate collection.</p>
                <div class="row g-2 mt-2">
                    @foreach(['1 Discover Markets', '2 Find Farmers', '3 Browse Products', '4 Save Favorites', '5 Add to Cart', '6 Select Pickup Slot'] as $step)
                    <div class="col-6"><div class="ml-card small py-2 px-3"><i class="fa-solid fa-check text-success"></i> {{ $step }}</div></div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <span class="ml-eyebrow">Grower Operations</span>
                <h2>Supporting Local Farmers</h2>
                <p class="text-muted mt-2">Empowering growers with digital tools tailored to market schedules, harvest peaks, and pre-order management.</p>
                <div class="row g-2 mt-2">
                    @foreach(['1 Create Profile', '2 Add Products', '3 Publish Weekly Stock', '4 Manage Pre-Orders', '5 Manage Pickup Slots', '6 Respond to Reviews'] as $step)
                    <div class="col-6"><div class="ml-card small py-2 px-3"><i class="fa-solid fa-check text-success"></i> {{ $step }}</div></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ml-section ml-section--muted">
    <div class="ml-container">
        <div class="ml-heading-block mx-auto text-center">
            <span class="ml-eyebrow">Our Guiding Ethos</span>
            <h2>Built For Local Market Shopping</h2>
            <p class="mx-auto">Purity, clarity, and simplicity form the baseline of everything we develop.</p>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="ml-step__icon mx-auto"><i class="fa-solid fa-magnifying-glass-dollar"></i></div>
                <h6 class="mt-3">Clear Information</h6>
                <p class="small text-muted">Make product availability, accurate weights, fixed prices, and market logistics readily accessible on any device.</p>
            </div>
            <div class="col-md-4">
                <div class="ml-step__icon mx-auto"><i class="fa-regular fa-calendar-check"></i></div>
                <h6 class="mt-3">Convenient Planning</h6>
                <p class="small text-muted">Help conscious families plan weekday meal prep prior to Sunday morning trips to avoid sold-out specialty produce.</p>
            </div>
            <div class="col-md-4">
                <div class="ml-step__icon mx-auto"><i class="fa-solid fa-handshake-angle"></i></div>
                <h6 class="mt-3">Direct Local Connection</h6>
                <p class="small text-muted">Foster genuine mutual relationships between regional agricultural families and healthy local households.</p>
            </div>
        </div>
    </div>
</section>

<section class="ml-section">
    <div class="ml-container">
        <div class="ml-heading-block mx-auto text-center">
            <span class="ml-eyebrow">Technical Architecture</span>
            <h2>Technology Behind MarketLink</h2>
            <p class="mx-auto">Engineered using dependable, production-tested open web technologies for fast and accessible performance.</p>
        </div>
        <div class="row g-3 text-center">
            @foreach(['MySQL' => 'Relational Store', 'PHP' => 'Backend Engine', 'Laravel' => 'Web Framework', 'HTML5 / CSS3' => 'Semantic Views', 'JavaScript' => 'Client Micro-UI', 'Maps API' => 'OSM &amp; Google'] as $tech => $desc)
            <div class="col-6 col-md-2">
                <div class="ml-card py-3">
                    <strong class="d-block">{{ $tech }}</strong>
                    <span class="small text-muted">{!! $desc !!}</span>
                </div>
            </div>
            @endforeach
        </div>
        <p class="small text-muted text-center mt-3 mb-0"><i class="fa-solid fa-circle-info"></i> MarketLink utilizes Google Maps API and OpenStreetMap integration for accurate market pavilion and farmer stall geo-location.</p>
    </div>
</section>

<section class="ml-section ml-section--muted">
    <div class="ml-container">
        <div class="ml-heading-block">
            <span class="ml-eyebrow">Active Regional Footprint</span>
            <h2>Local Markets, Connected</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="ml-map">
                    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1200&q=60" alt="Map of Karachi market hubs">
                </div>
            </div>
            <div class="col-lg-6 d-flex flex-column gap-3">
                @forelse($markets as $market)
                <div class="ml-card d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $market->name }}</strong>
                        <span class="small text-muted d-block">{{ $market->address }}, {{ $market->city }}</span>
                        <span class="small text-muted">Pickup: {{ $market->start_time }} - {{ $market->end_time }}</span>
                    </div>
                    <a href="{{ url('/markets/'.$market->id) }}" class="ml-badge ml-badge-mint">View</a>
                </div>
                @empty
                <p class="text-muted small mb-0">No markets have been added yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="ml-section">
    <div class="ml-container">
        <div class="ml-cta-banner text-center">
            <h2>Discover Your Local Market</h2>
            <p class="mt-2 mb-4" style="color: rgba(255,255,255,0.85);">Explore nearby markets, discover verified local farmers, and reserve your weekly crate before market morning arrives.</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ url('/markets') }}" class="btn ml-btn-primary">Browse Markets</a>
                <a href="{{ url('/products') }}" class="btn ml-btn-secondary">Explore Products</a>
            </div>
        </div>
    </div>
</section>

@endsection
