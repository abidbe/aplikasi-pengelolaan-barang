<div class="drawer-side">
    <label for="drawer-toggle" aria-label="close sidebar" class="drawer-overlay"></label>
    <aside id="sidebar" class="min-h-full transition-all duration-300 bg-base-300 w-64">

        <!-- Sidebar Header -->
        <div class="p-4">
            <div class="flex items-center justify-center">
                <h1 id="sidebar-title" class="font-bold text-base-content text-base transition-all duration-300">
                    {{ config('app.name', 'Laravel') }}
                </h1>
            </div>
        </div>

        <!-- Navigation Menu -->
        <ul class="menu p-4 space-y-2">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center {{ request()->routeIs('dashboard') ? 'active' : '' }} tooltip tooltip-right"
                    data-tip="Dashboard">
                    <x-heroicon-o-squares-2x2 class="w-5 h-5 flex-shrink-0" />
                    <span class="sidebar-text ml-2 transition-all duration-300">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center tooltip tooltip-right" data-tip="Barang">
                    <x-heroicon-o-cube class="w-5 h-5 flex-shrink-0" />
                    <span class="sidebar-text ml-2 transition-all duration-300">Barang</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center tooltip tooltip-right" data-tip="Laporan">
                    <x-heroicon-o-clipboard-document-list class="w-5 h-5 flex-shrink-0" />
                    <span class="sidebar-text ml-2 transition-all duration-300">Laporan</span>
                </a>
            </li>
            @if (auth()->user()->isAdmin())
                <li>
                    <a href="{{ route('users.index') }}"
                        class="flex items-center {{ request()->routeIs('users.*') ? 'active' : '' }} tooltip tooltip-right"
                        data-tip="User Management">
                        <x-heroicon-o-users class="w-5 h-5 flex-shrink-0" />
                        <span class="sidebar-text ml-2 transition-all duration-300">User Management</span>
                    </a>
                </li>
            @endif
            <li>
                <a href="#" class="flex items-center tooltip tooltip-right" data-tip="Pengaturan">
                    <x-heroicon-o-cog-6-tooth class="w-5 h-5 flex-shrink-0" />
                    <span class="sidebar-text ml-2 transition-all duration-300">Pengaturan</span>
                </a>
            </li>
        </ul>
    </aside>
</div>

<style>
    /* Sidebar collapsed styles */
    #sidebar.collapsed {
        width: 4rem !important;
    }

    #sidebar.collapsed .menu a {
        justify-content: center !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }

    #sidebar.collapsed .tooltip:before,
    #sidebar.collapsed .tooltip:after {
        display: block !important;
    }

    #sidebar:not(.collapsed) .tooltip:before,
    #sidebar:not(.collapsed) .tooltip:after {
        display: none !important;
    }

    /* Prevent text overflow */
    #sidebar.collapsed .sidebar-text {
        display: none !important;
    }

    #sidebar.collapsed #sidebar-title {
        font-size: 0.875rem !important;
        white-space: nowrap !important;
        overflow: hidden !important;
    }

    /* Smooth transitions */
    #sidebar {
        transition: width 0.3s ease !important;
    }

    #sidebar-title {
        transition: font-size 0.3s ease !important;
    }

    .sidebar-text {
        transition: opacity 0.3s ease !important;
        white-space: nowrap !important;
        overflow: hidden !important;
    }

    /* Menu item styling */
    .menu a {
        transition: all 0.3s ease !important;
        white-space: nowrap !important;
        overflow: hidden !important;
    }

    /* Tooltip positioning */
    .tooltip.tooltip-right:before {
        left: 100% !important;
        margin-left: 0.5rem !important;
    }

    .tooltip.tooltip-right:after {
        left: 100% !important;
        margin-left: 0.5rem !important;
    }
</style>
