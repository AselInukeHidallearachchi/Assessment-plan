<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Support Desk' }}</title>
    <style>
        body { margin: 0; background: #f4f6f8; color: #1f2937; font-family: Arial, sans-serif; }
        .shell { max-width: 1180px; margin: 0 auto; padding: 24px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 18px; }
        .brand { font-size: 22px; font-weight: 800; }
        .subtle { color: #64748b; font-size: 14px; }
        .panel { background: #fff; border: 1px solid #dfe5ec; border-radius: 8px; padding: 16px; }
        .grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin-bottom: 16px; }
        .stat { background: #fff; border: 1px solid #dfe5ec; border-radius: 8px; padding: 14px; }
        .stat strong { display: block; font-size: 26px; margin-top: 4px; }
        .row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .between { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
        .btn { border: 1px solid #cbd5e1; background: #fff; color: #0f172a; padding: 9px 12px; border-radius: 8px; text-decoration: none; cursor: pointer; }
        .btn-primary { background: #0f172a; border-color: #0f172a; color: #fff; }
        .btn-danger { background: #b91c1c; border-color: #b91c1c; color: #fff; }
        .field { margin-bottom: 14px; }
        label { display: block; font-weight: 700; margin-bottom: 6px; }
        input, select, textarea { width: 100%; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 8px; padding: 9px 10px; background: #fff; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #e2e8f0; padding: 11px 8px; text-align: left; vertical-align: top; }
        th { color: #475569; font-size: 13px; }
        .badge { display: inline-flex; padding: 4px 8px; border-radius: 999px; background: #e2e8f0; font-size: 12px; font-weight: 700; }
        .error { color: #b91c1c; font-size: 13px; margin-top: 4px; }
        .flash { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 10px 12px; border-radius: 8px; margin-bottom: 14px; }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
        @media (max-width: 760px) { .grid, .form-grid { grid-template-columns: 1fr; } .shell { padding: 14px; } }
    </style>
</head>
<body>
<main class="shell">
    <div class="topbar">
        <div>
            <div class="brand">Support Desk</div>
            <div class="subtle">Week 4 mini project</div>
        </div>
        <a class="btn" href="{{ route('tickets.index') }}">Tickets</a>
    </div>

    @if (session('success'))
        <div class="flash">{{ session('success') }}</div>
    @endif

    @yield('content')
</main>
</body>
</html>
