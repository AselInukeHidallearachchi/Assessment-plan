<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Week 2 Product Catalog' }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f7f7fb; color: #1f2937; }
        .container { max-width: 960px; margin: 0 auto; padding: 24px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px; }
        .row { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
        .between { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
        .btn { display: inline-block; border: 1px solid #d1d5db; background: #fff; color: #111827; text-decoration: none; padding: 8px 12px; border-radius: 8px; cursor: pointer; }
        .btn-primary { background: #111827; color: #fff; border-color: #111827; }
        .btn-danger { background: #b91c1c; color: #fff; border-color: #b91c1c; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; border-bottom: 1px solid #e5e7eb; padding: 10px 8px; }
        .field { margin-bottom: 14px; }
        .field label { display: block; margin-bottom: 6px; font-weight: 600; }
        .field input, .field select, .field textarea { width: 100%; box-sizing: border-box; padding: 8px 10px; border: 1px solid #d1d5db; border-radius: 8px; }
        .error { color: #b91c1c; font-size: 13px; margin-top: 4px; }
        .flash { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 10px 12px; border-radius: 8px; margin-bottom: 14px; }
        .hint { color: #6b7280; font-size: 13px; margin-top: 6px; }
        .muted { color: #6b7280; font-size: 14px; }
        .thumb { width: 56px; height: 56px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e7eb; }
        .placeholder-thumb { display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; border: 1px dashed #d1d5db; border-radius: 8px; color: #6b7280; font-size: 12px; text-align: center; }
        .preview-image { display: block; width: 120px; height: 120px; object-fit: cover; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 10px; }
        .product-image { width: min(100%, 420px); max-height: 320px; object-fit: cover; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 14px; }
    </style>
</head>
<body>
<div class="container">
    @if (session('success'))
        <div class="flash">{{ session('success') }}</div>
    @endif

    @yield('content')
</div>
</body>
</html>
