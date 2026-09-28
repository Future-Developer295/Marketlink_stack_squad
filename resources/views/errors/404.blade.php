<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>404 - MarketLink</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Figtree', 'Segoe UI', sans-serif;
            background: #F6F3EB;
            color: #073F3C;
            overflow-x: hidden;
        }

        .ml-404-page {
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            overflow: hidden;
            background:
                radial-gradient(circle at 10% 20%, rgba(107, 143, 113, 0.12), transparent 24%),
                radial-gradient(circle at 90% 80%, rgba(227, 144, 47, 0.10), transparent 24%),
                #F6F3EB;
        }

        .ml-404-page::before {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: rgba(7, 63, 60, 0.04);
            top: -180px;
            left: -140px;
            animation: floatCircle 8s ease-in-out infinite;
        }

        .ml-404-page::after {
            content: "";
            position: absolute;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: rgba(107, 143, 113, 0.06);
            right: -120px;
            bottom: -130px;
            animation: floatCircle 10s ease-in-out infinite reverse;
        }

        .ml-404-card {
            width: 100%;
            max-width: 650px;
            position: relative;
            z-index: 5;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(7, 63, 60, 0.08);
            border-radius: 28px;
            padding: 42px 42px 38px;
            text-align: center;
            box-shadow: 0 30px 90px rgba(7, 63, 60, 0.10);
            animation: cardEnter 0.8s cubic-bezier(.2, .8, .2, 1);
        }

        .ml-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
            font-size: 21px;
            font-weight: 800;
            color: #073F3C;
            letter-spacing: -0.4px;
            animation: brandEnter 0.8s ease 0.15s both;
        }

        .ml-brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #073F3C;
            color: #fff;
            box-shadow: 0 8px 20px rgba(7, 63, 60, 0.16);
        }

        .ml-brand-mark svg {
            width: 23px;
            height: 23px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .ml-brand span {
            color: #6B8F71;
        }

        .ml-404-art {
            position: relative;
            width: 250px;
            height: 185px;
            margin: 0 auto 4px;
            animation: artEnter 0.9s ease 0.25s both;
        }

        .ml-404-number {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 138px;
            line-height: 1;
            font-weight: 800;
            letter-spacing: -9px;
            color: rgba(7, 63, 60, 0.055);
            user-select: none;
        }

        .ml-basket {
            position: absolute;
            width: 145px;
            height: 115px;
            left: 52px;
            top: 32px;
            animation: basketFloat 4s ease-in-out infinite;
        }

        .ml-basket-handle {
            fill: none;
            stroke: #073F3C;
            stroke-width: 4;
            stroke-linecap: round;
        }

        .ml-basket-body {
            fill: rgba(107, 143, 113, 0.04);
            stroke: #073F3C;
            stroke-width: 4;
            stroke-linejoin: round;
        }

        .ml-basket-lines {
            fill: none;
            stroke: #073F3C;
            stroke-width: 2;
            opacity: .35;
        }

        .ml-orange {
            position: absolute;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #E3902F;
            left: 18px;
            bottom: 17px;
            animation: fruitOne 4s ease-in-out infinite;
        }

        .ml-red {
            position: absolute;
            width: 17px;
            height: 17px;
            border-radius: 50%;
            background: #D64545;
            right: 16px;
            bottom: 26px;
            animation: fruitTwo 4.5s ease-in-out infinite;
        }

        .ml-green {
            position: absolute;
            width: 28px;
            height: 13px;
            border-radius: 100% 0 100% 0;
            background: #6B8F71;
            left: 31px;
            top: 32px;
            transform: rotate(-26deg);
            animation: leafFloat 3.5s ease-in-out infinite;
        }

        .ml-dot {
            position: absolute;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #6B8F71;
            opacity: .7;
        }

        .ml-dot.one {
            top: 25px;
            right: 18px;
            animation: pulseDot 2.5s ease-in-out infinite;
        }

        .ml-dot.two {
            bottom: 5px;
            left: 70px;
            animation: pulseDot 3s ease-in-out infinite .4s;
        }

        .ml-dot.three {
            top: 58px;
            left: 3px;
            animation: pulseDot 2.8s ease-in-out infinite .8s;
        }

        .ml-404-code {
            margin: 0 0 9px;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #6B8F71;
            animation: contentEnter .7s ease .45s both;
        }

        .ml-404-title {
            margin: 0 auto 14px;
            max-width: 550px;
            font-size: 33px;
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: -0.8px;
            color: #073F3C;
            animation: contentEnter .7s ease .55s both;
        }

        .ml-404-text {
            max-width: 510px;
            margin: 0 auto 28px;
            font-size: 15px;
            line-height: 1.7;
            color: #5b6b64;
            animation: contentEnter .7s ease .65s both;
        }

        .ml-404-actions {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 11px;
            margin-bottom: 25px;
            animation: contentEnter .7s ease .75s both;
        }

        .ml-404-btn {
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 21px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s ease;
        }

        .ml-404-btn--primary {
            background: #073F3C;
            color: #fff;
            border: 1px solid #073F3C;
            box-shadow: 0 8px 18px rgba(7, 63, 60, 0.12);
        }

        .ml-404-btn--primary:hover {
            transform: translateY(-2px);
            background: #0a504c;
            color: #fff;
            box-shadow: 0 12px 24px rgba(7, 63, 60, 0.18);
        }

        .ml-404-btn--ghost {
            background: #F6F3EB;
            color: #073F3C;
            border: 1px solid #e3ddc9;
        }

        .ml-404-btn--ghost:hover {
            transform: translateY(-2px);
            background: #eee9dd;
            color: #073F3C;
        }

        .ml-404-search {
            width: 100%;
            max-width: 490px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 6px 5px 15px;
            border: 1px solid #e3ddc9;
            border-radius: 13px;
            background: #F6F3EB;
            animation: contentEnter .7s ease .85s both;
        }

        .ml-404-search:focus-within {
            border-color: #6B8F71;
            box-shadow: 0 0 0 3px rgba(107, 143, 113, 0.10);
        }

        .ml-404-search input {
            flex: 1;
            min-width: 0;
            border: none;
            outline: none;
            background: transparent;
            color: #073F3C;
            padding: 10px 0;
            font-family: inherit;
            font-size: 14px;
        }

        .ml-404-search input::placeholder {
            color: #9aa89f;
        }

        .ml-404-search button {
            border: none;
            background: #073F3C;
            color: #fff;
            border-radius: 9px;
            padding: 10px 17px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .ml-404-search button:hover {
            background: #0a504c;
            transform: translateY(-1px);
        }

        .ml-404-footer {
            margin-top: 21px;
            font-size: 12px;
            color: #8a9891;
            animation: contentEnter .7s ease .95s both;
        }

        @keyframes cardEnter {
            from {
                opacity: 0;
                transform: translateY(35px) scale(.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes brandEnter {
            from {
                opacity: 0;
                transform: translateY(-12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes artEnter {
            from {
                opacity: 0;
                transform: scale(.82);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes contentEnter {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes basketFloat {
            0%,
            100% {
                transform: translateY(0) rotate(-3deg);
            }
            50% {
                transform: translateY(-8px) rotate(2deg);
            }
        }

        @keyframes fruitOne {
            0%,
            100% {
                transform: translate(0, 0) rotate(0);
            }
            50% {
                transform: translate(-6px, -10px) rotate(20deg);
            }
        }

        @keyframes fruitTwo {
            0%,
            100% {
                transform: translate(0, 0);
            }
            50% {
                transform: translate(7px, -8px);
            }
        }

        @keyframes leafFloat {
            0%,
            100% {
                transform: rotate(-26deg) translate(0, 0);
            }
            50% {
                transform: rotate(-10deg) translate(5px, -7px);
            }
        }

        @keyframes pulseDot {
            0%,
            100% {
                opacity: .3;
                transform: scale(.8);
            }
            50% {
                opacity: .8;
                transform: scale(1.15);
            }
        }

        @keyframes floatCircle {
            0%,
            100% {
                transform: translate(0, 0);
            }
            50% {
                transform: translate(20px, 15px);
            }
        }

        @media (max-width: 600px) {
            .ml-404-page {
                padding: 20px 14px;
            }

            .ml-404-card {
                padding: 32px 20px 28px;
                border-radius: 21px;
            }

            .ml-brand {
                margin-bottom: 20px;
                font-size: 19px;
            }

            .ml-404-art {
                width: 220px;
                height: 165px;
            }

            .ml-404-number {
                font-size: 112px;
            }

            .ml-basket {
                width: 130px;
                height: 102px;
                left: 45px;
                top: 31px;
            }

            .ml-404-title {
                font-size: 25px;
            }

            .ml-404-text {
                font-size: 14px;
                margin-bottom: 23px;
            }

            .ml-404-actions {
                flex-direction: column;
            }

            .ml-404-btn {
                width: 100%;
            }

            .ml-404-search {
                padding-left: 12px;
            }

            .ml-404-search input {
                font-size: 13px;
            }

            .ml-404-search button {
                padding: 10px 13px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>

<body>

    <main class="ml-404-page">

        <div class="ml-404-card">

            <div class="ml-brand">
                <div class="ml-brand-mark">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 3 4 7l8 4 8-4-8-4Z" />
                        <path d="m4 12 8 4 8-4" />
                        <path d="m4 17 8 4 8-4" />
                    </svg>
                </div>

                Market <span>Link</span>
            </div>

            <div class="ml-404-art">

                <div class="ml-404-number">
                    404
                </div>

                <svg class="ml-basket" viewBox="0 0 140 110">
                    <path
                        class="ml-basket-handle"
                        d="M45 42 Q70 8 95 42"
                    />

                    <path
                        class="ml-basket-body"
                        d="M28 46 L112 46 L100 92 Q70 100 40 92 Z"
                    />

                    <g class="ml-basket-lines">
                        <line x1="37" y1="46" x2="44" y2="93" />
                        <line x1="70" y1="46" x2="70" y2="99" />
                        <line x1="103" y1="46" x2="96" y2="93" />
                        <line x1="32" y1="62" x2="108" y2="62" />
                    </g>
                </svg>

                <div class="ml-orange"></div>
                <div class="ml-red"></div>
                <div class="ml-green"></div>

                <div class="ml-dot one"></div>
                <div class="ml-dot two"></div>
                <div class="ml-dot three"></div>

            </div>

            <p class="ml-404-code">
                Error 404
            </p>

            <h1 class="ml-404-title">
                This page has wandered off the market.
            </h1>

            <p class="ml-404-text">
                The page you're looking for doesn't exist, may have been moved,
                or the link might be broken. Let's get you back to fresh ground.
            </p>

            <div class="ml-404-actions">

                <a
                    href="{{ url('/') }}"
                    class="ml-404-btn ml-404-btn--primary"
                >
                    Back to MarketLink
                </a>

                <a
                    href="{{ url('/markets') }}"
                    class="ml-404-btn ml-404-btn--ghost"
                >
                    Browse Markets
                </a>

            </div>

            <form
                class="ml-404-search"
                action="{{ url('/products') }}"
                method="GET"
            >

                <input
                    type="text"
                    name="q"
                    placeholder="Search products, markets or farmers..."
                >

                <button type="submit">
                    Search
                </button>

            </form>

            <div class="ml-404-footer">
                MarketLink — Fresh connections, local markets.
            </div>

        </div>

    </main>

</body>

</html>