<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Desa Malangjiwan' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    @include('layouts.navbar')
    <main id="main-content">{{ $slot }}</main>
    @include('layouts.footer')
    @livewireScripts
    @stack('scripts')
</body>
</html>
