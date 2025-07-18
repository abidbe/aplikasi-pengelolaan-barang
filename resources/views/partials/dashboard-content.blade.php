<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="stat bg-base-100 shadow-xl rounded-lg">
        <div class="stat-figure text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="inline-block w-8 h-8 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </div>
        <div class="stat-title">Total Barang</div>
        <div class="stat-value text-primary">150</div>
        <div class="stat-desc">21% lebih dari bulan lalu</div>
    </div>

    <div class="stat bg-base-100 shadow-xl rounded-lg">
        <div class="stat-figure text-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="inline-block w-8 h-8 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                </path>
            </svg>
        </div>
        <div class="stat-title">Barang Masuk</div>
        <div class="stat-value text-secondary">25</div>
        <div class="stat-desc">Hari ini</div>
    </div>

    <div class="stat bg-base-100 shadow-xl rounded-lg">
        <div class="stat-figure text-accent">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="inline-block w-8 h-8 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
            </svg>
        </div>
        <div class="stat-title">Barang Keluar</div>
        <div class="stat-value text-accent">12</div>
        <div class="stat-desc">Hari ini</div>
    </div>

    <div class="stat bg-base-100 shadow-xl rounded-lg">
        <div class="stat-figure text-warning">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="inline-block w-8 h-8 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z">
                </path>
            </svg>
        </div>
        <div class="stat-title">Stok Menipis</div>
        <div class="stat-value text-warning">8</div>
        <div class="stat-desc">Perlu diperhatikan</div>
    </div>
</div>

<!-- Recent Activity & Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Activity -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="inline-block w-5 h-5 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Aktivitas Terkini
            </h2>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 bg-base-200 rounded-lg">
                    <div class="w-2 h-2 bg-success rounded-full"></div>
                    <div class="flex-1">
                        <p class="font-medium">Barang Masuk</p>
                        <p class="text-sm opacity-70">Laptop Dell Inspiron - 5 unit</p>
                    </div>
                    <div class="text-sm opacity-70">10:30</div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-base-200 rounded-lg">
                    <div class="w-2 h-2 bg-error rounded-full"></div>
                    <div class="flex-1">
                        <p class="font-medium">Barang Keluar</p>
                        <p class="text-sm opacity-70">Mouse Logitech - 2 unit</p>
                    </div>
                    <div class="text-sm opacity-70">09:15</div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-base-200 rounded-lg">
                    <div class="w-2 h-2 bg-warning rounded-full"></div>
                    <div class="flex-1">
                        <p class="font-medium">Stok Menipis</p>
                        <p class="text-sm opacity-70">Printer HP - 3 unit tersisa</p>
                    </div>
                    <div class="text-sm opacity-70">08:45</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="inline-block w-5 h-5 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Aksi Cepat
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="#" class="btn btn-primary btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="inline-block w-5 h-5 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Barang
                </a>
                <a href="#" class="btn btn-secondary btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="inline-block w-5 h-5 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                        </path>
                    </svg>
                    Barang Masuk
                </a>
                <a href="#" class="btn btn-accent btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="inline-block w-5 h-5 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                        </path>
                    </svg>
                    Barang Keluar
                </a>
                <a href="#" class="btn btn-info btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="inline-block w-5 h-5 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Laporan
                </a>
            </div>
        </div>
    </div>
</div>
