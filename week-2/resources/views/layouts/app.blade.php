<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Week 2 Product Catalog' }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="catalog-shell min-h-screen text-foreground antialiased">
    <main class="mx-auto grid min-h-screen w-full max-w-7xl gap-6 px-4 py-6 lg:grid-cols-[18rem_minmax(0,1fr)] lg:px-8">
        <aside class="catalog-frame rounded-[2rem] p-6 lg:sticky lg:top-6 lg:h-[calc(100vh-3rem)]">
            <div class="flex h-full flex-col justify-between gap-10">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.34em] text-amber-400">Week 2</p>
                    <a href="{{ route('products.index') }}" class="mt-4 block text-4xl font-black leading-none tracking-tight text-orange-50">
                        Catalog<br>Control
                    </a>
                    <p class="mt-5 text-sm leading-6 text-stone-300">
                        A complete data management system where users can create new entries, view existing ones, make updates, and delete them when no longer needed.
                    </p>
                </div>

                <nav class="grid gap-3">
                    <x-ui.button href="{{ route('products.index') }}" variant="outline">Inventory Board</x-ui.button>
                    <x-ui.button href="{{ route('products.create') }}">Register Product</x-ui.button>
                </nav>
            </div>
        </aside>

        <section class="min-w-0 py-2">
            @if (session('success'))
                <x-ui.alert>{{ session('success') }}</x-ui.alert>
            @endif

            @if ($errors->has('product'))
                <div class="mb-6 border border-red-500/40 bg-red-950/80 px-4 py-3 text-sm font-bold text-red-100 shadow-xl" role="alert">
                    {{ $errors->first('product') }}
                </div>
            @endif

            @yield('content')
        </section>
    </main>
</body>
</html>
