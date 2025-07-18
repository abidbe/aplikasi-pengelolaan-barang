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
                <a href="{{ route('barang-masuks.index') }}"
                    class="flex items-center {{ request()->routeIs('barang-masuks.*') ? 'active' : '' }} tooltip tooltip-right"
                    data-tip="Barang Masuk">
                    <x-heroicon-o-cube class="w-5 h-5 flex-shrink-0" />
                    <span class="sidebar-text ml-2 transition-all duration-300">Barang Masuk</span>
                </a>
            </li>
            @if (auth()->user()->isAdmin())
                <li>
                    <details class="group"
                        {{ request()->routeIs('kategoris.*') || request()->routeIs('sub-kategoris.*') ? 'open' : '' }}>
                        <summary class="flex items-center cursor-pointer tooltip tooltip-right" data-tip="Master Data">
                            <x-heroicon-o-clipboard-document-list class="w-5 h-5 flex-shrink-0" />
                            <span class="sidebar-text ml-2 transition-all duration-300">Master Data</span>
                            <x-heroicon-o-chevron-down
                                class="w-4 h-4 ml-auto transition-transform duration-200 group-open:rotate-180" />
                        </summary>
                        <ul class="menu-compact">
                            <li>
                                <a href="{{ route('kategoris.index') }}"
                                    class="flex items-center pl-8 tooltip tooltip-right {{ request()->routeIs('kategoris.*') ? 'active' : '' }}"
                                    data-tip="Kategori">
                                    <x-heroicon-o-tag class="w-4 h-4 flex-shrink-0" />
                                    <span class="sidebar-text ml-2 transition-all duration-300">Kategori</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('sub-kategoris.index') }}"
                                    class="flex items-center pl-8 tooltip tooltip-right {{ request()->routeIs('sub-kategoris.*') ? 'active' : '' }}"
                                    data-tip="Sub Kategori">
                                    <x-heroicon-o-bookmark class="w-4 h-4 flex-shrink-0" />
                                    <span class="sidebar-text ml-2 transition-all duration-300">Sub Kategori</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>
            @endif
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
