<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Smart City Citizen')</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            --bg: #f1f5f9;
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --surface-strong: #e8eef6;
            --text: #14213d;
            --muted: #5f6f86;
            --line: #d7dee9;
            --primary: #163b66;
            --primary-dark: #0b2441;
            --primary-soft: #dce8f5;
            --accent: #b8860b;
            --accent-soft: #fff4d6;
            --national: #b91c1c;
            --national-soft: #fee2e2;
            --warning: #a16207;
            --warning-soft: #fef3c7;
            --danger: #b91c1c;
            --danger-soft: #fee2e2;
            --success: #166534;
            --success-soft: #dcfce7;
            --shadow-sm: 0 8px 22px rgba(15, 23, 42, 0.06);
            --shadow-md: 0 18px 42px rgba(15, 23, 42, 0.1);
            color: var(--text);
            background: var(--bg);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                linear-gradient(90deg, rgba(185, 28, 28, 0.06), transparent 28%, rgba(184, 134, 11, 0.06)),
                linear-gradient(180deg, #f8fafc 0%, var(--bg) 52%, #e8eef6 100%);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .app-shell {
            min-height: 100vh;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 1px solid rgba(148, 163, 184, 0.28);
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(18px);
        }

        .topbar::before {
            content: "";
            display: block;
            height: 4px;
            background: linear-gradient(90deg, var(--national) 0 33%, #ffffff 33% 66%, var(--primary) 66% 100%);
        }

        .topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            max-width: 1240px;
            margin: 0 auto;
            padding: 12px 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: max-content;
            color: #0f172a;
            font-weight: 800;
        }

        .brand-mark {
            position: relative;
            display: grid;
            width: 40px;
            height: 40px;
            place-items: center;
            border-radius: 8px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: #ffffff;
            font-size: 14px;
            letter-spacing: 0;
            box-shadow: 0 10px 22px rgba(11, 36, 65, 0.24);
        }

        .brand-mark:not(.has-logo)::after {
            content: "";
            position: absolute;
            inset: auto 0 0 0;
            height: 5px;
            background: linear-gradient(90deg, var(--national), #ffffff);
        }

        .brand-logo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .nav {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .nav a {
            border-radius: 8px;
            padding: 9px 11px;
            color: #526174;
            font-size: 14px;
            font-weight: 700;
            transition: background 0.16s ease, color 0.16s ease, box-shadow 0.16s ease;
        }

        .nav a.active,
        .nav a:hover {
            background: var(--primary-soft);
            color: var(--primary-dark);
            box-shadow: inset 0 0 0 1px rgba(22, 59, 102, 0.1);
        }

        .page {
            max-width: 1240px;
            margin: 0 auto;
            padding: 30px 24px 48px;
        }

        .page-header {
            position: relative;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: end;
            gap: 20px;
            margin-bottom: 24px;
            padding-bottom: 22px;
            border-bottom: 1px solid var(--line);
        }

        .page-header::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -1px;
            width: min(260px, 45%);
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--national), #ffffff, var(--accent));
        }

        .eyebrow {
            margin: 0 0 8px;
            color: var(--primary);
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        h1,
        h2,
        h3,
        p {
            margin-top: 0;
        }

        h1 {
            margin-bottom: 8px;
            color: #0f172a;
            font-size: 34px;
            line-height: 1.15;
            letter-spacing: 0;
        }

        h2 {
            margin-bottom: 10px;
            color: #0f172a;
            font-size: 21px;
            line-height: 1.25;
            letter-spacing: 0;
        }

        h3 {
            color: #0f172a;
            letter-spacing: 0;
        }

        .lead {
            max-width: 680px;
            margin-bottom: 0;
            color: #526174;
            line-height: 1.65;
        }

        .button-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .button,
        button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 40px;
            border: 1px solid transparent;
            border-radius: 8px;
            padding: 10px 15px;
            font: inherit;
            font-weight: 800;
            cursor: pointer;
            transition: transform 0.16s ease, box-shadow 0.16s ease, background 0.16s ease;
        }

        .button:hover,
        button:hover {
            transform: translateY(-1px);
        }

        .button-primary,
        .btn-primary {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 12px 24px rgba(22, 59, 102, 0.2);
        }

        .button-primary:hover,
        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .button-secondary,
        .btn-secondary {
            border-color: var(--line);
            background: #ffffff;
            color: var(--primary-dark);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
        }

        .button-secondary:hover,
        .btn-secondary:hover {
            border-color: rgba(22, 59, 102, 0.24);
            background: #f8fafc;
        }

        .btn-danger {
            background: var(--danger);
            color: #ffffff;
        }

        .panel {
            border: 1px solid rgba(148, 163, 184, 0.32);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: var(--shadow-sm);
        }

        .panel-pad {
            padding: 24px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            padding: 18px;
        }

        .stat-card::after {
            content: "";
            position: absolute;
            inset: auto 16px 0 16px;
            height: 3px;
            border-radius: 999px 999px 0 0;
            background: linear-gradient(90deg, var(--primary), var(--national), var(--accent));
            opacity: 0.78;
        }

        .stat-card span {
            display: block;
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .stat-card strong {
            display: block;
            color: #0f172a;
            font-size: 29px;
            line-height: 1;
        }

        .content-grid {
            display: grid;
            grid-template-columns: minmax(360px, 0.88fr) minmax(0, 1.12fr);
            gap: 18px;
            align-items: start;
        }

        .hero-band {
            position: relative;
            min-height: 260px;
            display: flex;
            align-items: flex-end;
            padding: 28px;
            overflow: hidden;
            color: #ffffff;
            background: var(--primary-dark);
            box-shadow: var(--shadow-md);
        }

        .hero-band::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 1;
            background:
                linear-gradient(90deg, rgba(11, 36, 65, 0.94), rgba(22, 59, 102, 0.72), rgba(185, 28, 28, 0.34)),
                linear-gradient(0deg, rgba(11, 36, 65, 0.56), transparent 55%);
        }

        .hero-slide-track {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .hero-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transform: scale(1.03);
            transition: opacity 0.7s ease, transform 1.2s ease;
        }

        .hero-slide img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .hero-slide.is-active {
            opacity: 1;
            transform: scale(1);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 660px;
        }

        .hero-slider-controls {
            position: absolute;
            right: 20px;
            bottom: 18px;
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .hero-slider-button {
            width: 36px;
            height: 36px;
            min-height: 36px;
            border: 1px solid rgba(255, 255, 255, 0.45);
            border-radius: 999px;
            padding: 0;
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
            box-shadow: none;
        }

        .hero-slider-button:hover {
            background: rgba(255, 255, 255, 0.28);
        }

        .hero-slider-dots {
            display: flex;
            gap: 7px;
        }

        .hero-slider-dot {
            width: 9px;
            height: 9px;
            min-height: 9px;
            border: 1px solid rgba(255, 255, 255, 0.72);
            border-radius: 999px;
            padding: 0;
            background: rgba(255, 255, 255, 0.28);
            box-shadow: none;
        }

        .hero-slider-dot.is-active {
            width: 22px;
            background: #ffffff;
        }

        .hero-band h2 {
            max-width: 620px;
            margin-bottom: 10px;
            color: #ffffff;
            font-size: 30px;
            line-height: 1.18;
            letter-spacing: 0;
        }

        .hero-band p {
            max-width: 600px;
            margin-bottom: 18px;
            color: rgba(255, 255, 255, 0.86);
            line-height: 1.6;
        }

        .list {
            display: grid;
            gap: 12px;
        }

        .list-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 12px;
        }

        .list-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .muted {
            color: var(--muted);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 26px;
            border-radius: 999px;
            padding: 4px 10px;
            background: var(--primary-soft);
            color: var(--primary-dark);
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .badge-success {
            background: var(--success-soft);
            color: #166534;
        }

        .badge-danger {
            background: var(--danger-soft);
            color: #991b1b;
        }

        .badge-muted {
            background: var(--surface-strong);
            color: var(--primary);
        }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 16px;
        }

        .search-wrap {
            position: relative;
            width: min(100%, 360px);
        }

        .search-wrap input {
            padding-left: 40px;
        }

        .search-wrap::before {
            content: "";
            position: absolute;
            left: 15px;
            top: 50%;
            width: 11px;
            height: 11px;
            border: 2px solid var(--muted);
            border-radius: 999px;
            transform: translateY(-58%);
            opacity: 0.75;
        }

        .search-wrap::after {
            content: "";
            position: absolute;
            left: 27px;
            top: 50%;
            width: 7px;
            height: 2px;
            border-radius: 999px;
            background: var(--muted);
            transform: translateY(4px) rotate(45deg);
            opacity: 0.75;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .field-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            color: #334155;
            font-size: 13px;
            font-weight: 800;
        }

        input,
        textarea,
        select {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #ffffff;
            color: var(--text);
            padding: 11px 12px;
            font: inherit;
            outline: none;
            transition: border-color 0.16s ease, box-shadow 0.16s ease;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(22, 59, 102, 0.12);
        }

        textarea {
            min-height: 96px;
            resize: vertical;
        }

        .full {
            grid-column: 1 / -1;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .table-shell {
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        table {
            width: 100%;
            min-width: 760px;
            border-collapse: collapse;
        }

        th,
        td {
            border-bottom: 1px solid #e5e7eb;
            padding: 13px 16px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: var(--surface-soft);
            color: var(--primary);
            font-size: 12px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        tbody tr:hover {
            background: #fbfdff;
        }

        td {
            color: #243044;
        }

        .table-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .message {
            display: none;
            margin-bottom: 16px;
            border-radius: 8px;
            padding: 13px 14px;
            font-weight: 700;
        }

        .message.success {
            display: block;
            background: var(--success-soft);
            color: #14532d;
            border: 1px solid #86efac;
        }

        .message.error {
            display: block;
            background: var(--danger-soft);
            color: #7f1d1d;
            border: 1px solid #fca5a5;
        }

        .filter-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .filter-label {
            margin-left: auto;
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
        }

        .pill-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pill-btn {
            min-height: 34px;
            border: 1px solid #cbd5e1;
            border-radius: 999px;
            background: #ffffff;
            color: #475569;
            padding: 6px 15px;
            font-size: 13px;
            font-weight: 800;
            box-shadow: none;
        }

        .pill-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: none;
        }

        .pill-btn.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 18px;
            margin-bottom: 40px;
        }

        .berita-card {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 22px;
        }

        .card-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .card-judul {
            margin: 0;
            font-size: 18px;
            line-height: 1.35;
        }

        .card-isi {
            margin: 0;
            color: #526174;
            font-size: 14px;
            line-height: 1.65;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            flex-wrap: wrap;
        }

        .card-penulis {
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
        }

        .empty-state {
            color: var(--muted);
        }

        .compact-action {
            min-height: 32px;
            padding: 6px 12px;
            font-size: 13px;
        }

        .button-on-media {
            border-color: rgba(255, 255, 255, 0.48);
            background: rgba(255, 255, 255, 0.16);
            color: #ffffff;
            box-shadow: none;
        }

        .button-on-media:hover {
            background: rgba(255, 255, 255, 0.28);
            color: #ffffff;
        }

        .stat-subvalue {
            font-size: 18px;
        }

        .mb-16 {
            margin-bottom: 16px;
        }

        .mb-22 {
            margin-bottom: 22px;
        }

        .hidden {
            display: none;
        }

        .textarea-tall {
            min-height: 160px;
        }

        .search-compact {
            width: min(100%, 320px);
        }

        .empty-wide {
            grid-column: 1 / -1;
        }

        .contact-line {
            display: block;
            font-size: 13px;
        }

        .contact-subline {
            display: block;
            color: var(--muted);
            font-size: 12px;
        }

        @media (max-width: 900px) {
            .page-header,
            .content-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .field-grid-3 {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 720px) {
            .topbar-inner,
            .page {
                padding-left: 16px;
                padding-right: 16px;
            }

            .topbar-inner {
                align-items: flex-start;
                flex-direction: column;
            }

            .nav {
                justify-content: flex-start;
            }

            h1 {
                font-size: 28px;
            }

            .hero-band {
                min-height: 300px;
                padding: 22px;
            }

            .hero-slider-controls {
                left: 22px;
                right: auto;
                bottom: 18px;
            }

            .hero-content {
                padding-bottom: 48px;
            }

            .stats-grid,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .toolbar {
                align-items: stretch;
                flex-direction: column;
            }

            .search-wrap,
            .filter-label {
                width: 100%;
                margin-left: 0;
            }

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
                min-width: 0;
            }

            thead {
                display: none;
            }

            tr {
                border-bottom: 1px solid #d1d5db;
                padding: 10px 0;
            }

            td {
                border: 0;
                padding: 8px 14px;
            }

            td::before {
                content: attr(data-label);
                display: block;
                margin-bottom: 4px;
                color: #64748b;
                font-size: 12px;
                font-weight: 800;
                text-transform: uppercase;
            }
        }

        @media print {
            body {
                background: #ffffff !important;
            }

            .topbar,
            .button-row,
            form,
            .actions,
            .filter-bar {
                display: none !important;
            }

            .page {
                max-width: 100%;
                padding: 0;
            }

            .panel,
            .table-shell {
                box-shadow: none;
                border: 1px solid #dddddd;
            }

            table {
                min-width: 100%;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    @php
        $customLogoPath = public_path('images/logo.png');
        $hasCustomLogo = file_exists($customLogoPath);
    @endphp

    <div class="app-shell">
        <header class="topbar">
            <div class="topbar-inner">
                <a href="{{ url('/') }}" class="brand">
                    <span class="brand-mark {{ $hasCustomLogo ? 'has-logo' : '' }}">
                        @if ($hasCustomLogo)
                            <img class="brand-logo" src="{{ asset('images/logo.png') }}" alt="Logo Smart City Citizen">
                        @else
                            SC
                        @endif
                    </span>
                    <span>Smart City Citizen</span>
                </a>

                <nav class="nav" aria-label="Navigasi utama">
                    <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ url('/warga') }}" class="{{ request()->is('warga') ? 'active' : '' }}">Data Warga</a>
                    <a href="{{ url('/instansi') }}" class="{{ request()->is('instansi') ? 'active' : '' }}">Data Instansi</a>
                    <a href="{{ url('/petugas') }}" class="{{ request()->is('petugas') ? 'active' : '' }}">Data Petugas</a>
                    <a href="{{ url('/berita-kota') }}" class="{{ request()->is('berita-kota') ? 'active' : '' }}">Berita Kota</a>
                    <a href="{{ url('/laporan') }}" class="{{ request()->is('laporan') ? 'active' : '' }}">Laporan Warga</a>
                    <a href="{{ url('/laporan-instansi') }}" class="{{ request()->is('laporan-instansi') ? 'active' : '' }}">Laporan Instansi</a>
                </nav>
            </div>
        </header>

        <main class="page">
            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
