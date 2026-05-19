<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Support Desk' }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-background text-foreground antialiased">
    <div class="pointer-events-none fixed inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,0.18),transparent_34rem),linear-gradient(135deg,#f8fafc_0%,#eef2ff_48%,#f8fafc_100%)]"></div>

    <main class="mx-auto flex min-h-screen w-full max-w-7xl flex-col px-4 py-6 sm:px-6 lg:px-8">
        <header class="mb-8 flex flex-col gap-4 rounded-3xl border border-border/80 bg-card/90 p-5 shadow-sm backdrop-blur md:flex-row md:items-center md:justify-between">
            <div>
                <a href="{{ route('tickets.index') }}" class="text-2xl font-black tracking-tight text-foreground">Support Desk</a>
                <p class="mt-1 text-sm text-muted-foreground">Week 4 mini project · independent delivery simulation</p>
            </div>

            <nav class="flex items-center gap-2">
                <x-ui.button href="{{ route('tickets.index') }}" variant="outline">Tickets</x-ui.button>
                <x-ui.button href="{{ route('tickets.create') }}">New Ticket</x-ui.button>
            </nav>
        </header>

        @if (session('success'))
            <x-ui.alert>{{ session('success') }}</x-ui.alert>
        @endif

        @yield('content')
    </main>
</body>
</html>
