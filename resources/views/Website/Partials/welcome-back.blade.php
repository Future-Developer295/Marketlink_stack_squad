@php($welcome = session()->pull('welcome_back'))
@if ($welcome)
    <style>
        .wb-modal .modal-content { border: 0; border-radius: 24px; overflow: hidden; }
        .wb-head { background: #304a36; color: #fff; padding: 26px 28px 22px; }
        .wb-head h2 { font-size: 26px; margin: 0 0 4px; }
        .wb-head p { margin: 0; opacity: .85; font-size: 14px; }
        .wb-body { padding: 20px 28px 8px; max-height: 320px; overflow-y: auto; }
        .wb-item { display: flex; align-items: center; gap: 14px; padding: 10px 0; border-bottom: 1px solid #e5eadf; }
        .wb-item:last-child { border-bottom: 0; }
        .wb-item img { width: 52px; height: 52px; object-fit: cover; border-radius: 12px; background: #eef1e7; }
        .wb-item div { flex: 1; min-width: 0; }
        .wb-item strong { display: block; font-size: 15px; }
        .wb-item small { color: #68765f; }
        .wb-item b { white-space: nowrap; font-size: 14px; }
        .wb-total { display: flex; justify-content: space-between; padding: 14px 28px; font-weight: 700; border-top: 1px dashed #dfe6d4; }
        .wb-foot { display: flex; gap: 10px; flex-wrap: wrap; padding: 6px 28px 24px; }
        .wb-foot a, .wb-foot button { flex: 1; text-align: center; border-radius: 30px; padding: 12px 18px; font-size: 14px; font-weight: 600; text-decoration: none; border: 1px solid #304a36; }
        .wb-foot a { background: #304a36; color: #fff; }
        .wb-foot button { background: #fff; color: #304a36; }
    </style>
    <div class="modal fade wb-modal" id="welcomeBackModal" tabindex="-1" aria-labelledby="welcomeBackTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="wb-head">
                    <h2 id="welcomeBackTitle">Welcome back, {{ $welcome['name'] }}! 👋</h2>
                    <p>
                        @if (count($welcome['items']))
                            You still have {{ count($welcome['items']) }}
                            {{ \Illuminate\Support\Str::plural('item', count($welcome['items'])) }} in your basket.
                        @else
                            Good to see you again. Your basket is empty right now.
                        @endif
                    </p>
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
                    <div class="wb-total"><span>Basket total</span><span>Rs. {{ number_format($welcome['total'], 0) }}</span></div>
                @endif
                <div class="wb-foot">
                    @if (count($welcome['items']))
                        <a href="{{ url('/cart') }}">View my basket</a>
                    @else
                        <a href="{{ url('/products') }}">Explore the harvest</a>
                    @endif
                    <button type="button" data-bs-dismiss="modal">Keep browsing</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('welcomeBackModal');
            if (el && window.bootstrap) { new bootstrap.Modal(el).show(); }
        });
    </script>
@endif
