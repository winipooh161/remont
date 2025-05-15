<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Days+One&family=Onest:wght@100..900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap JS с проверкой загрузки -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" 
            onload="console.log('Bootstrap JS loaded successfully')" 
            onerror="console.error('Failed to load Bootstrap JS')"></script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/mask.js', 'resources/js/sidebar.js'])
</head>
<body>
    <div class="wrapper">
        <!-- Боковая навигационная панель -->
        @include('layouts.partials.sidebar')

        <!-- Основной контент -->
        <div id="content">
            <!-- Верхняя навигационная панель -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapseShow" class="btn btn-light d-inline-block d-md-none me-2">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a class="navbar-brand d-none d-md-inline" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link" href="#" role="button">
                                    <i class="fas fa-bell"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="py-4 px-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
