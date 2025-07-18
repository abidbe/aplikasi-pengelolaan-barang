<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center mb-6">
                        <x-heroicon-o-squares-2x2 class="w-8 h-8 text-primary mr-3" />
                        <h1 class="text-3xl font-bold">{{ __('Dashboard') }}</h1>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="stat bg-primary text-primary-content rounded-lg">
                            <div class="stat-figure text-primary-content">
                                <x-heroicon-o-cube class="w-8 h-8" />
                            </div>
                            <div class="stat-title text-primary-content">Total Barang</div>
                            <div class="stat-value">31K</div>
                            <div class="stat-desc text-primary-content">Jan 1st - Feb 1st</div>
                        </div>

                        <div class="stat bg-secondary text-secondary-content rounded-lg">
                            <div class="stat-figure text-secondary-content">
                                <x-heroicon-o-arrow-trending-up class="w-8 h-8" />
                            </div>
                            <div class="stat-title text-secondary-content">Barang Masuk</div>
                            <div class="stat-value">4,200</div>
                            <div class="stat-desc text-secondary-content">↗︎ 400 (22%)</div>
                        </div>

                        <div class="stat bg-accent text-accent-content rounded-lg">
                            <div class="stat-figure text-accent-content">
                                <x-heroicon-o-arrow-trending-down class="w-8 h-8" />
                            </div>
                            <div class="stat-title text-accent-content">Barang Keluar</div>
                            <div class="stat-value">1,200</div>
                            <div class="stat-desc text-accent-content">↘︎ 90 (14%)</div>
                        </div>

                        <div class="stat bg-info text-info-content rounded-lg">
                            <div class="stat-figure text-info-content">
                                <x-heroicon-o-exclamation-triangle class="w-8 h-8" />
                            </div>
                            <div class="stat-title text-info-content">Stok Rendah</div>
                            <div class="stat-value">86</div>
                            <div class="stat-desc text-info-content">Perlu perhatian</div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <h2 class="card-title">
                                <x-heroicon-o-clock class="w-5 h-5" />
                                Aktivitas Terbaru
                            </h2>
                            <div class="overflow-x-auto">
                                <table class="table table-zebra w-full">
                                    <thead>
                                        <tr>
                                            <th>Waktu</th>
                                            <th>Aktivitas</th>
                                            <th>Barang</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>10:30</td>
                                            <td>Barang Masuk</td>
                                            <td>Laptop Dell</td>
                                            <td><span class="badge badge-success">Selesai</span></td>
                                        </tr>
                                        <tr>
                                            <td>09:15</td>
                                            <td>Barang Keluar</td>
                                            <td>Mouse Wireless</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                        </tr>
                                        <tr>
                                            <td>08:45</td>
                                            <td>Update Stok</td>
                                            <td>Keyboard Mechanical</td>
                                            <td><span class="badge badge-info">Proses</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
