<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ningrat.Net Pembayaran Online</title>

    <!-- Favicon -->
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">

    @viteReactRefresh
    @routes
    @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
    @inertiaHead
</head>

<body class="font-sans antialiased h-full">
    @inertia
</body>

</html>
