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
            color: #172033;
            background: #eef3f8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                linear-gradient(180deg, rgba(238, 243, 248, 0.94), rgba(246, 248, 251, 0.98)),
                url("https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1800&q=80") center/cover fixed;
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
            border-bottom: 1px solid rgba(136, 153, 171, 0.3);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(14px);
        }

        .topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            max-width: 1180px;
            margin: 0 auto;
            padding: 14px 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
        }

        .brand-mark {
            display: grid;
            width: 36px;
            height: 36px;
            place-items: center;
            border-radius: 8px;
            background: #0f766e;
            color: #ffffff;
            font-size: 14px;
            letter-spacing: 0;
        }

        .nav {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .nav a {
            border-radius: 8px;
            padding: 9px 12px;
            color: #475569;
            font-weight: 700;
        }

        .nav a.active,
        .nav a:hover {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .page {
            max-width: 1180px;
            margin: 0 auto;
            padding: 28px 24px 44px;
        }

        .page-header {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: end;
            gap: 20px;
            margin-bottom: 22px;
        }

        .eyebrow {
            margin: 0 0 8px;
            color: #0f766e;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.08em;
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
            min-height: 40px;
            border: 0;
            border-radius: 8px;
            padding: 10px 14px;
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
            background: #0f766e;
            color: #ffffff;
            box-shadow: 0 12px 24px rgba(15, 118, 110, 0.18);
        }

        .button-secondary,
        .btn-secondary {
            background: #e6edf5;
            color: #243044;
        }

        .btn-danger {
            background: #dc2626;
            color: #ffffff;
        }

        .panel {
            border: 1px solid rgba(137, 153, 171, 0.34);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 18px 45px rgba(31, 41, 55, 0.08);
        }

        .panel-pad {
            padding: 22px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 22px;
        }

        .stat-card {
            padding: 18px;
        }

        .stat-card span {
            display: block;
            margin-bottom: 8px;
            color: #64748b;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .stat-card strong {
            display: block;
            color: #0f172a;
            font-size: 30px;
            line-height: 1;
        }

        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 0.92fr) minmax(0, 1.08fr);
            gap: 18px;
        }

        .hero-band {
            min-height: 260px;
            display: grid;
            align-content: end;
            padding: 28px;
            overflow: hidden;
            color: #ffffff;
            background:
                linear-gradient(90deg, rgba(15, 23, 42, 0.82), rgba(15, 118, 110, 0.5)),
                url("https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=1600&q=80") center/cover;
        }

        .hero-band h2 {
            max-width: 620px;
            margin-bottom: 10px;
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
            gap: 12px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 12px;
        }

        .list-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .muted {
            color: #64748b;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            min-height: 26px;
            border-radius: 999px;
            padding: 4px 10px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
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
            padding-left: 38px;
        }

        .search-wrap::before {
            content: "Cari";
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            color: #334155;
            font-size: 13px;
            font-weight: 800;
        }

        input,
        textarea {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #ffffff;
            color: #172033;
            padding: 11px 12px;
            font: inherit;
            outline: none;
            transition: border-color 0.16s ease, box-shadow 0.16s ease;
        }

        input:focus,
        textarea:focus {
            border-color: #0f766e;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.12);
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
        }

        table {
            width: 100%;
            min-width: 760px;
            border-collapse: collapse;
        }

        th,
        td {
            border-bottom: 1px solid #e5e7eb;
            padding: 14px 16px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f8fafc;
            color: #475569;
            font-size: 12px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
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
            padding: 12px 14px;
            font-weight: 700;
        }

        .message.success {
            display: block;
            background: #dcfce7;
            color: #14532d;
            border: 1px solid #86efac;
        }

        .message.error {
            display: block;
            background: #fee2e2;
            color: #7f1d1d;
            border: 1px solid #fca5a5;
        }

        @media (max-width: 900px) {
            .page-header,
            .content-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
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

            h1 {
                font-size: 28px;
            }

            .hero-band {
                min-height: 300px;
                padding: 22px;
            }

            .stats-grid,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .toolbar {
                align-items: stretch;
                flex-direction: column;
            }

            .search-wrap {
                width: 100%;
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
    </style>
</head>
<body>
    <div class="app-shell">
        <header class="topbar">
            <div class="topbar-inner">
                <a href="{{ url('/') }}" class="brand">
                    <span class="brand-mark">SC</span>
                    <span>Smart City Citizen</span>
                </a>

                <nav class="nav" aria-label="Navigasi utama">
                    <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ url('/warga') }}" class="{{ request()->is('warga') ? 'active' : '' }}">Data Warga</a>
                    <a href="{{ url('/berita-kota') }}" class="{{ request()->is('berita-kota') ? 'active' : '' }}">Berita Kota</a>
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
