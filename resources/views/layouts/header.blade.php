<!-- filepath: c:\laragon\www\aplikasi-pengelolaan-barang\resources\views\layouts\header.blade.php -->
@props(['breadcrumbs' => []])

<div class="bg-base-100 border-b border-base-300 ">
    <!-- Mobile Header -->
    <div class="navbar bg-base-100 lg:hidden">
        <div class="flex-none">
            <label for="drawer-toggle" class="btn btn-square btn-ghost">
                <x-heroicon-o-bars-3 class="w-6 h-6" />
            </label>
        </div>
        <!-- Breadcrumbs -->
        @if (!empty($breadcrumbs))
            <div class="breadcrumbs text-sm flex-1">
                <ul>
                    <li><a href="{{ route('dashboard') }}">Home</a></li>
                    @foreach ($breadcrumbs as $breadcrumb)
                        <li>
                            @if (isset($breadcrumb['url']))
                                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                            @else
                                {{ $breadcrumb['title'] }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="flex-none">
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                    <div class="avatar placeholder">
                        <div class="bg-primary text-primary-content w-10 rounded-full">
                            <span class="text-sm font-semibold">
                                @php
                                    $initials = collect(explode(' ', Auth::user()->name))
                                        ->map(function ($name) {
                                            return strtoupper(substr($name, 0, 1));
                                        })
                                        ->take(2)
                                        ->join('');
                                @endphp
                                {{ $initials }}
                            </span>
                        </div>
                    </div>
                </div>
                <ul tabindex="0"
                    class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                    <li>
                        <a href="{{ route('profile.edit') }}" class="justify-between">
                            <span class="flex items-center">
                                <x-heroicon-o-user class="w-4 h-4 mr-2" />
                                Profile
                            </span>
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center text-left">
                                <x-heroicon-o-arrow-right-start-on-rectangle class="w-4 h-4 mr-2" />
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden lg:flex justify-between items-center px-4">
        <div class="flex items-center space-x-4">
            <!-- Hamburger Button - Paling Kiri -->
            <button id="sidebar-toggle" class="btn btn-ghost btn-sm">
                <x-heroicon-o-bars-3 class="w-5 h-5" />
            </button>

            <!-- Breadcrumbs -->
            @if (!empty($breadcrumbs))
                <div class="breadcrumbs text-sm">
                    <ul>
                        <li><a href="{{ route('dashboard') }}">Home</a></li>
                        @foreach ($breadcrumbs as $breadcrumb)
                            <li>
                                @if (isset($breadcrumb['url']))
                                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                                @else
                                    {{ $breadcrumb['title'] }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="flex-none">
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-lg">
                    <div class="flex items-center space-x-3">

                        <div class="avatar placeholder">
                            <div class="bg-primary text-primary-content w-10 rounded-full">
                                <span class="text-sm font-semibold">
                                    @php
                                        $initials = collect(explode(' ', Auth::user()->name))
                                            ->map(function ($name) {
                                                return strtoupper(substr($name, 0, 1));
                                            })
                                            ->take(2)
                                            ->join('');
                                    @endphp
                                    {{ $initials }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right hidden sm:block">
                            <div class="font-semibold text-sm text-start">{{ Auth::user()->name }}</div>
                            <div class="text-xs opacity-70">{{ Auth::user()->email }}</div>
                        </div>
                        <x-heroicon-o-chevron-down class="w-4 h-4" />
                    </div>
                </div>
                <ul tabindex="0"
                    class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                    <li>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2">
                            <x-heroicon-o-user class="w-4 h-4" />
                            Profile
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 text-left">
                                <x-heroicon-o-arrow-right-start-on-rectangle class="w-4 h-4" />
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
