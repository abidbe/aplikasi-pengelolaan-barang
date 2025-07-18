<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <!-- Additional Styles -->
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="drawer lg:drawer-open">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />

        <div class="drawer-content flex flex-col">
            @yield('content')
        </div>

        @include('layouts.sidebar')
    </div>

    <!-- External Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- Custom Scripts -->
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        // Sidebar collapse functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            const sidebarTitle = document.getElementById('sidebar-title');

            // Load saved state
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

            function applySidebarState(collapsed) {
                if (collapsed) {
                    sidebar.classList.add('collapsed');
                    sidebarTitle.textContent = 'APB';
                } else {
                    sidebar.classList.remove('collapsed');
                    sidebarTitle.textContent = '{{ config('app.name', 'Laravel') }}';
                }
            }

            // Apply initial state
            applySidebarState(isCollapsed);

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    const isCurrentlyCollapsed = sidebar.classList.contains('collapsed');
                    const newState = !isCurrentlyCollapsed;

                    applySidebarState(newState);
                    localStorage.setItem('sidebarCollapsed', newState.toString());
                });
            }
        });
    </script>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>

</html>
