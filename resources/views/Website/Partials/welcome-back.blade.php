@php($welcome = session()->pull('welcome_back'))
@if ($welcome)
    <style>
        .wb-modal {
            --wb-ink: #263c30;
            --wb-green: #39513b;
            --wb-green-dark: #253d2b;
            --wb-lime: #dcecac;
            --wb-soft: #eef1e5;
            --wb-line: #e2e7dc;
            --wb-muted: #697568;
            font-family: 'DM Sans', 'Poppins', sans-serif;
            color: var(--wb-ink);
        }

        .wb-modal .modal-content {
            border: 0;
            border-radius: 24px;
            overflow: hidden;
            background: #fff;
        }

        .wb-head {
            display: flex;
            gap: 16px;
            align-items: center;
            background: var(--wb-green);
            color: #fff;
            padding: 26px 28px;
        }

        .wb-head-icon {
            flex: 0 0 52px;
            height: 52px;
            border-radius: 16px;
            background: var(--wb-lime);
            color: var(--wb-green);
            display: grid;
            place-items: center;
            font-size: 22px;
        }

        .wb-head h2 {
            font-family: Manrope, 'DM Sans', sans-serif;
            font-size: 24px;
            letter-spacing: -.03em;
            margin: 0 0 4px;
            color: #fff;
        }

        .wb-head p {
            margin: 0;
            color: #d6e2c4;
            font-size: 14px;
        }

        .wb-body {
            padding: 8px 28px 0;
            max-height: 320px;
            overflow-y: auto;
        }

        .wb-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 0;
            border-bottom: 1px solid var(--wb-line);
        }

        .wb-item:last-child {
            border-bottom: 0;
        }

        .wb-item img {
            width: 52px;
            height: 52px;
            object-fit: cover;
            border-radius: 12px;
            background: var(--wb-soft);
        }

        .wb-item div {
            flex: 1;
            min-width: 0;
        }

        .wb-item strong {
            display: block;
            font-size: 15px;
        }

        .wb-item small {
            color: var(--wb-muted);
        }

        .wb-item b {
            white-space: nowrap;
            font-size: 14px;
            color: var(--wb-green);
        }

        .wb-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 28px;
            margin-top: 8px;
            font-weight: 700;
            background: var(--wb-soft);
        }

        .wb-total i {
            margin-right: 8px;
            color: #7b8f60;
        }

        .wb-foot {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            padding: 18px 28px 24px;
        }

        .wb-foot a,
        .wb-foot button {
            flex: 1;
            display: inline-flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
            border-radius: 12px;
            min-height: 48px;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid var(--wb-green);
            transition: background .2s;
        }

        .wb-foot a {
            background: var(--wb-green);
            color: #fff;
        }

        .wb-foot a:hover {
            background: var(--wb-green-dark);
        }

        .wb-foot button {
            background: #fff;
            color: var(--wb-green);
        }

        .wb-foot button:hover {
            background: var(--wb-soft);
        }

        .wb-modal :focus-visible {
            outline: 3px solid #8da465;
            outline-offset: 3px;
        }
    </style>

    <div class="modal fade wb-modal" id="welcomeBackModal" tabindex="-1" aria-labelledby="welcomeBackTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="wb-head">
                    <span class="wb-head-icon"><i class="fa-solid fa-basket-shopping"></i></span>
                    <div>
                        <h2 id="welcomeBackTitle">Welcome back, {{ $welcome['name'] }}!</h2>
                        <p>
                            @if (count($welcome['items']))
                                You still have {{ count($welcome['items']) }}
                                {{ \Illuminate\Support\Str::plural('item', count($welcome['items'])) }} in your basket.
                            @else
                                Good to see you again. Your basket is empty right now.
                            @endif
                        </p>
                    </div>
                </div>

                @if (count($welcome['items']))
                    <div class="wb-body">
                        @foreach ($welcome['items'] as $item)
                            <div class="wb-item">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                                <div>
                                    <strong>{{ $item['name'] }}</strong>
                                    <small>{{ $item['quantity'] }} {{ $item['unit'] }}</small>
                                </div>
                                <b>Rs. {{ number_format($item['subtotal'], 0) }}</b>
                            </div>
                        @endforeach
                    </div>
                    <div class="wb-total">
                        <span><i class="fa-solid fa-receipt"></i>Basket total</span>
                        <span>Rs. {{ number_format($welcome['total'], 0) }}</span>
                    </div>
                @endif

                <div class="wb-foot">
                    @if (count($welcome['items']))
                        <a href="{{ url('/cart') }}"><i class="fa-solid fa-cart-shopping"></i> View my basket</a>
                    @else
                        <a href="{{ url('/products') }}"><i class="fa-solid fa-leaf"></i> Explore the harvest</a>
                    @endif
                    <button type="button" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> Keep
                        browsing</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var el = document.getElementById('welcomeBackModal');
            if (el && window.bootstrap) {
                new bootstrap.Modal(el).show();
            }
        });
    </script>
@endif
