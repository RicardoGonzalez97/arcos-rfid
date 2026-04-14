<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
</head>
<body>
    <div class="layout">
        @include('components.sidebar')

        <div class="main">
            @include('components.topbar')
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>