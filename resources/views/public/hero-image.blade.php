<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Ticketeke – Hero Visual</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --brand: #105B55;
            --brand-dark: #0a3d39;
            --brand-mid: #1a7a72;
            --accent: #C8450A;
            --gold: #E08C12;
            --warm-50: #ffffff;
            --warm-100: #F4E9DC;
            --muted-200: #D8DED9;
            --ink: #111827;
            --muted: #6b7280;
            --card: #ffffff;
            --shadow-lg: 0 22px 56px rgba(16, 91, 85, 0.16), 0 6px 20px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 10px 28px rgba(0, 0, 0, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--warm-50);
            font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--ink);
        }

        .scene {
            position: relative;
            width: 790px;
            height: 510px;
        }

        /* ═══════════════════════════════════
       PHONE MOCKUP
    ═══════════════════════════════════ */
        .phone {
            position: absolute;
            left: 254px;
            top: 6px;
            width: 264px;
            height: 490px;
            border-radius: 25px;
            background: #fff;
            box-shadow: var(--shadow-lg), inset 0 0 0 1px rgba(0, 0, 0, 0.08);
        }

        .phone-screen {
            position: absolute;
            inset: 0px;
            border-radius: 25px;
            overflow: hidden;
            background: #f8faf9;
            display: flex;
            flex-direction: column;
        }

        /* Event poster */
        .phone-poster {
            flex: 1;
            border-radius: 22px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background: var(--brand-dark);
        }

        .poster-bg {
            position: absolute;
            inset: 0;
            background-image: url('https://images.unsplash.com/photo-1728472731419-6a1107f20ec6?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
            background-size: cover;
            background-position: center;
        }

        .poster-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom,
                rgba(0, 0, 0, 0.04) 0%,
                rgba(0, 0, 0, 0.18) 50%,
                rgba(0, 0, 0, 0.60) 78%,
                rgba(0, 0, 0, 0.78) 100%);
        }

        .poster-date-chip {
            position: absolute;
            top: 14px;
            right: 12px;
            z-index: 3;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            padding: 4px 9px 4px 6px;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 9.5px;
            font-weight: 600;
            color: var(--brand);
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }

        .poster-date-chip .cal-box {
            width: 13px;
            height: 13px;
            border-radius: 3px;
            background: var(--brand);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .poster-body {
            position: relative;
            z-index: 2;
            padding: 14px 14px 8px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
            justify-content: flex-end;
        }

        .category-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            align-self: flex-start;
            background: rgba(224, 140, 18, 0.88);
            backdrop-filter: blur(4px);
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.09em;
        }

        .poster-title {
            font-size: 19px;
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            letter-spacing: -0.025em;
        }

        .poster-sub {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.58);
            font-weight: 500;
        }

        /* Bottom event info card (overlaid inside poster) */
        .ev-card {
            background: rgba(255, 255, 255, 0.96);
            margin: 0 9px 9px;
            border-radius: 16px;
            padding: 11px 13px;
            flex-shrink: 0;
            position: relative;
            z-index: 2;
            backdrop-filter: blur(6px);
        }

        .ev-name {
            font-size: 12px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ev-meta {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            color: var(--muted);
            margin-bottom: 9px;
        }

        .ev-sep {
            width: 2px;
            height: 2px;
            border-radius: 50%;
            background: var(--muted-200);
            flex-shrink: 0;
        }

        .ev-price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ev-price {
            font-size: 15px;
            font-weight: 800;
            color: var(--gold);
            letter-spacing: -0.02em;
        }

        .get-tickets-btn {
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 20px;
            font-size: 10.5px;
            font-weight: 700;
            padding: 6px 14px;
            cursor: pointer;
            font-family: inherit;
            letter-spacing: 0.01em;
        }

        /* ═══════════════════════════════════
       FLOATING CARDS — shared base
    ═══════════════════════════════════ */
        .fc {
            position: absolute;
            background: var(--card);
            border-radius: 18px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--muted-200);
        }

        .fc-label {
            font-size: 9.5px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.09em;
        }

        .check-circle {
            width: 17px;
            height: 17px;
            border-radius: 50%;
            background: var(--brand);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Pure CSS checkmark */
        .checkmark {
            width: 8px;
            height: 5px;
            border-left: 1.5px solid #fff;
            border-bottom: 1.5px solid #fff;
            transform: rotate(-45deg) translateY(-1px);
        }

        /* ─── Feature pills (right side, overlapping phone) ─── */
        .feature-pill {
            position: absolute;
            left: 494px;
            background: #fff;
            border-radius: 24px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--muted-200);
            padding: 8px 14px 8px 9px;
            display: flex;
            align-items: center;
            gap: 9px;
            white-space: nowrap;
            font-size: 12px;
            font-weight: 600;
            color: var(--ink);
            z-index: 20;
        }

        /* ─── Card 2: Trending event (top left) ─── */
        .card-trending {
            left: 12px;
            top: 12px;
            width: 204px;
            padding: 10px;
        }

        .trending-thumb {
            width: 100%;
            height: 82px;
            border-radius: 10px;
            background: linear-gradient(138deg, #d47810 0%, var(--accent) 55%, #7a1c04 100%);
            position: relative;
            overflow: hidden;
            margin-bottom: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Geometric weave over thumbnail */
        .trending-thumb::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: repeating-linear-gradient(60deg, transparent, transparent 9px,
                    rgba(255, 255, 255, 0.045) 9px, rgba(255, 255, 255, 0.045) 10px);
        }

        /* Abstract concert stage visual */
        .stage-visual {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .light-beams {
            display: flex;
            align-items: flex-start;
            gap: 9px;
        }

        .beam {
            width: 2px;
            height: 26px;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.72), transparent);
            border-radius: 1px;
        }

        .stage-bar {
            width: 44px;
            height: 3px;
            background: rgba(255, 255, 255, 0.45);
            border-radius: 2px;
        }

        .trending-badge {
            position: absolute;
            top: 7px;
            left: 7px;
            background: var(--accent);
            color: #fff;
            font-size: 8.5px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            z-index: 2;
        }

        .trending-title {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 7px;
            line-height: 1.2;
        }

        .trending-stats {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sold-text {
            font-size: 10.5px;
            color: var(--muted);
        }

        .sold-text strong {
            color: var(--brand);
            font-weight: 700;
        }

        .rating {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 700;
            color: var(--ink);
        }

        /* CSS star */
        .star-icon {
            width: 11px;
            height: 11px;
            background: var(--gold);
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
        }

        /* ─── Card 3: Event categories (bottom left) ─── */
        .card-categories {
            left: 12px;
            top: 312px;
            width: 228px;
            padding: 13px 14px;
        }

        .categories-title {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 9px;
        }

        .pill-row {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .ev-pill {
            background: var(--warm-100);
            color: var(--brand);
            font-size: 11px;
            font-weight: 600;
            padding: 4px 11px;
            border-radius: 20px;
            border: 1px solid rgba(16, 91, 85, 0.15);
            white-space: nowrap;
        }

        /* ─── Card 4: Stats (right middle) ─── */
        .card-stats {
            position: absolute;
            right: 12px;
            top: 220px;
            width: 174px;
            background: linear-gradient(148deg, var(--brand) 0%, var(--brand-dark) 100%);
            border: none;
            padding: 18px 18px 20px;
            color: #fff;
        }

        .stats-eyebrow {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.58);
            margin-bottom: 5px;
        }

        .stats-number {
            font-size: 42px;
            font-weight: 800;
            color: var(--gold);
            line-height: 1;
            letter-spacing: -0.03em;
            margin-bottom: 5px;
        }

        .stats-desc {
            font-size: 11.5px;
            color: rgba(255, 255, 255, 0.78);
            line-height: 1.4;
            margin-bottom: 13px;
        }

        .stats-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.11);
            margin-bottom: 11px;
        }

        .stats-sub {
            font-size: 10.5px;
            color: rgba(255, 255, 255, 0.52);
            line-height: 1.4;
        }

        /* ─── Card 5: Organiser analytics (bottom right) ─── */
        .card-analytics {
            position: absolute;
            right: 12px;
            top: 420px;
            width: 174px;
            padding: 13px 15px;
        }

        .analytics-revenue {
            font-size: 19px;
            font-weight: 800;
            color: var(--ink);
            letter-spacing: -0.03em;
            line-height: 1;
            margin: 8px 0 3px;
        }

        .analytics-growth {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 700;
            color: #16a34a;
            margin-bottom: 10px;
        }

        .arrow-up {
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-bottom: 6px solid #16a34a;
            flex-shrink: 0;
        }

        .mini-chart {
            display: flex;
            align-items: flex-end;
            gap: 4px;
            height: 34px;
        }

        .bar {
            flex: 1;
            border-radius: 3px 3px 0 0;
            background: var(--warm-100);
        }

        .bar.active {
            background: var(--brand);
        }

        /* ═══════════════════════════════════
       DECORATIVE ELEMENTS
    ═══════════════════════════════════ */

        /* Floating QR chip — between phone and right cards */
        .deco-qr {
            position: absolute;
            top: 14px;
            right: 12px;
            z-index: 3;
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-radius: 9px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.6);
            padding: 5px;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1.5px;
        }

        .qr-px {
            border-radius: 1px;
        }

        .qr-px.b {
            background: var(--ink);
        }

        .qr-px.w {
            background: transparent;
        }

        /* Calendar chip — floating just above phone left edge */
        .deco-cal {
            position: absolute;
            left: 226px;
            top: -6px;
            background: var(--card);
            border-radius: 22px;
            padding: 6px 12px 6px 9px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--muted-200);
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            color: var(--brand);
            white-space: nowrap;
        }

        /* CSS calendar icon */
        .cal-box {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            background: var(--brand);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .cal-grid-mini {
            display: grid;
            grid-template-columns: repeat(3, 3px);
            grid-template-rows: repeat(2, 3px);
            gap: 1px;
        }

        .cal-dot-mini {
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.7);
        }

        /* Location chip — left side, between trending and categories */
        .deco-location {
            position: absolute;
            left: 8px;
            top: 220px;
            background: var(--card);
            border-radius: 22px;
            padding: 5px 12px 5px 9px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--muted-200);
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            color: var(--ink);
            white-space: nowrap;
        }

        /* CSS map pin */
        .pin-dot {
            width: 8px;
            height: 8px;
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            background: var(--accent);
            position: relative;
            flex-shrink: 0;
        }

        .pin-dot::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            transform: translateX(-50%);
            border-left: 3.5px solid transparent;
            border-right: 3.5px solid transparent;
            border-top: 5px solid var(--accent);
        }

        /* Scatter / confetti accents */
        .scatter {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        /* ═══════════════════════════════════
       RESPONSIVE
    ═══════════════════════════════════ */
        @media (max-width: 820px) {
            .scene {
                transform: scale(0.5);
                transform-origin: top center;
            }

            body {
                align-items: flex-start;
                padding-top: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="scene">

        <!-- ════════════════ PHONE ════════════════ -->
        <div class="phone">
            <div class="phone-screen">

                <!-- Event poster -->
                <div class="phone-poster">
                    <div class="poster-bg"></div>
                    <div class="poster-overlay"></div>

                    <!-- QR chip top-right -->
                    <div class="deco-qr">
                        <div class="qr-px b"></div><div class="qr-px b"></div><div class="qr-px w"></div><div class="qr-px b"></div><div class="qr-px b"></div>
                        <div class="qr-px b"></div><div class="qr-px w"></div><div class="qr-px b"></div><div class="qr-px w"></div><div class="qr-px b"></div>
                        <div class="qr-px w"></div><div class="qr-px b"></div><div class="qr-px w"></div><div class="qr-px b"></div><div class="qr-px w"></div>
                        <div class="qr-px b"></div><div class="qr-px w"></div><div class="qr-px b"></div><div class="qr-px b"></div><div class="qr-px b"></div>
                        <div class="qr-px b"></div><div class="qr-px b"></div><div class="qr-px w"></div><div class="qr-px b"></div><div class="qr-px b"></div>
                    </div>

                    <div class="poster-body">
                        <div class="category-pill">&#9836; Music</div>
                        <div class="poster-title">City Sounds Festival</div>
                        <div class="poster-sub">Feat. Top Artists &amp; Special Guests</div>
                    </div>

                    <!-- Event detail card — overlaid inside the image -->
                    <div class="ev-card">
                        <div class="ev-name">City Sounds Festival 2026</div>
                        <div class="ev-meta">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#6b7280"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <path d="M16 2v4M8 2v4M3 10h18" />
                            </svg>
                            <span>Sat, Jul 12</span>
                            <span class="ev-sep"></span>
                            <span>7:00 PM</span>
                            <span class="ev-sep"></span>
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#6b7280"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 10c0 6-8 13-8 13S4 16 4 10a8 8 0 0 1 16 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            <span>City Arena, CBD</span>
                        </div>
                        <div class="ev-price-row">
                            <div class="ev-price">KES 2,500</div>
                            <button class="get-tickets-btn">Get Tickets</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ════════════════ CARD 2: Trending event (top left) ════════════════ -->
        <div class="fc card-trending">
            <div class="trending-thumb">
                <div class="trending-badge">&#128293; Trending</div>
                <div class="stage-visual">
                    <div class="light-beams">
                        <div class="beam" style="transform:rotate(-16deg)"></div>
                        <div class="beam" style="height:20px"></div>
                        <div class="beam" style="transform:rotate(16deg)"></div>
                    </div>
                    <div class="stage-bar"></div>
                </div>
            </div>
            <div class="trending-title">Summer Beats Festival 2026</div>
            <div class="trending-stats">
                <div class="sold-text"><strong>1,847</strong> tickets sold</div>
                <div class="rating">
                    <div class="star-icon"></div> 4.9
                </div>
            </div>
        </div>

        <!-- ════════════════ CARD 3: Event categories (bottom left) ════════════════ -->
        <div class="fc card-categories">
            <div class="categories-title">Explore Events</div>
            <div class="pill-row">
                <span class="ev-pill">Music</span>
                <span class="ev-pill">Festivals</span>
                <span class="ev-pill">Sports</span>
                <span class="ev-pill">Business</span>
                <span class="ev-pill">Culture</span>
                <span class="ev-pill">Nightlife</span>
            </div>
        </div>

        <!-- ════════════════ FEATURE PILLS (right side of phone) ════════════════ -->
        <div class="feature-pill" style="top: 30px;">
            <div class="check-circle"><div class="checkmark"></div></div>
            Sell Tickets Online
        </div>
        <div class="feature-pill" style="top: 78px;">
            <div class="check-circle"><div class="checkmark"></div></div>
            QR Code Check-In
        </div>
        <div class="feature-pill" style="top: 126px;">
            <div class="check-circle"><div class="checkmark"></div></div>
            Promo Codes
        </div>
        <div class="feature-pill" style="top: 174px;">
            <div class="check-circle"><div class="checkmark"></div></div>
            Instant Payments
        </div>
        <div class="feature-pill" style="top: 222px;">
            <div class="check-circle"><div class="checkmark"></div></div>
            Referrals
        </div>

        <!-- Stats -->
        <div class="fc card-stats">
            <div class="stats-eyebrow">Tickets Issued</div>
            <div class="stats-number">50K+</div>
            <div class="stats-desc">across 500+ events nationwide</div>
            <div class="stats-divider"></div>
            <div class="stats-sub">Kenya's #1 event ticketing platform</div>
        </div>

        <!-- Analytics -->
        <div class="fc card-analytics">
            <div class="fc-label">Organiser Revenue</div>
            <div class="analytics-revenue">KES 480K</div>
            <div class="analytics-growth">
                <div class="arrow-up"></div>
                +32% this month
            </div>
            <div class="mini-chart">
                <div class="bar" style="height:44%"></div>
                <div class="bar" style="height:60%"></div>
                <div class="bar" style="height:48%"></div>
                <div class="bar" style="height:76%"></div>
                <div class="bar active" style="height:92%"></div>
            </div>
        </div>

        <!-- ════════════════ DECORATIVE: Calendar chip ════════════════ -->
        <div class="deco-cal">
            <div class="cal-box">
                <div class="cal-grid-mini">
                    <div class="cal-dot-mini"></div>
                    <div class="cal-dot-mini"></div>
                    <div class="cal-dot-mini"></div>
                    <div class="cal-dot-mini"></div>
                    <div class="cal-dot-mini"></div>
                    <div class="cal-dot-mini"></div>
                </div>
            </div>
            Jul 12, 2026
        </div>

        <!-- ════════════════ DECORATIVE: Location chip ════════════════ -->
        <div class="deco-location">
            <div class="pin-dot"></div>
            Nairobi, Kenya
        </div>

        <!-- Confetti scatter -->
        <div class="scatter" style="width:8px;height:8px;background:var(--gold);opacity:0.55;top:16px;left:246px;">
        </div>
        <div class="scatter"
            style="width:5px;height:5px;background:var(--accent);opacity:0.42;top:54px;left:232px;border-radius:2px;transform:rotate(30deg);">
        </div>
        <div class="scatter" style="width:6px;height:6px;background:var(--brand);opacity:0.35;top:38px;right:204px;">
        </div>
        <div class="scatter"
            style="width:5px;height:5px;background:var(--gold);opacity:0.44;bottom:80px;right:198px;border-radius:1px;transform:rotate(45deg);">
        </div>
        <div class="scatter"
            style="width:7px;height:7px;background:var(--accent);opacity:0.33;bottom:32px;left:238px;"></div>

    </div>
</body>

</html>
