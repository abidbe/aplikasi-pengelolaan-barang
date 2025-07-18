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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="drawer lg:drawer-open">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col">
            <!-- Navbar -->
            <div class="navbar bg-base-100 lg:hidden">
                <div class="flex-none">
                    <label for="drawer-toggle" class="btn btn-square btn-ghost">
                        <x-heroicon-o-bars-3 class="w-6 h-6" />
                    </label>
                </div>
                <div class="flex-1">
                    <h1 class="text-lg font-bold">{{ config('app.name', 'Laravel') }}</h1>
                </div>
                <div class="flex-none">
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                            <div class="w-10 rounded-full bg-base-300 flex items-center justify-center">
                                <x-heroicon-o-user class="w-6 h-6" />
                            </div>
                        </div>
                        <ul tabindex="0"
                            class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                            <li>
                                <a href="{{ route('profile.edit') }}" class="justify-between">
                                    <x-heroicon-o-user class="w-4 h-4" />
                                    Profile
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left">
                                        <x-heroicon-o-arrow-right-start-on-rectangle class="w-4 h-4" />
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-6">
                {{ $slot }}
            </main>
        </div>

        <!-- Sidebar -->
        <div class="drawer-side">
            <label for="drawer-toggle" aria-label="close sidebar" class="drawer-overlay"></label>
            <aside class="min-h-full w-64 bg-base-200">
                <!-- Sidebar Header -->
                <div class="p-4 border-b border-base-300">
                    <h1 class="text-xl font-bold text-base-content">{{ config('app.name', 'Laravel') }}</h1>
                </div>

                <!-- Navigation Menu -->
                <ul class="menu p-4 space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center space-x-2 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <x-heroicon-o-squares-2x2 class="w-5 h-5" />
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-2">
                            <x-heroicon-o-cube class="w-5 h-5" />
                            <span>Barang</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-2">
                            <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                            <span>Laporan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-2">
                            <x-heroicon-o-cog-6-tooth class="w-5 h-5" />
                            <span>Pengaturan</span>
                        </a>
                    </li>
                </ul>

                <!-- User Info (Desktop) -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-base-300 hidden lg:block">
                    <div class="dropdown dropdown-top dropdown-end w-full">
                        <div tabindex="0" role="button" class="btn btn-ghost w-full justify-start">
                            <div class="avatar">
                                <div class="w-8 rounded-full bg-base-300 flex items-center justify-center">
                                    <x-heroicon-o-user class="w-4 h-4" />
                                </div>
                            </div>
                            <div class="text-left">
                                <div class="font-semibold">{{ Auth::user()->name }}</div>
                                <div class="text-sm opacity-70">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <ul tabindex="0"
                            class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52 mb-2">
                            <li>
                                <a href="{{ route('profile.edit') }}">
                                    <x-heroicon-o-user class="w-4 h-4" />
                                    Profile
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left">
                                        <x-heroicon-o-arrow-right-start-on-rectangle class="w-4 h-4" />
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</body>

</html>
