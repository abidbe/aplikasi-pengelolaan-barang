<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <div class="flex justify-between items-center mb-6">
            <h2 class="card-title">
                <x-heroicon-o-cube class="w-6 h-6 mr-2" />
                Data Barang Masuk
            </h2>
            <a href="{{ route('barang-masuks.create') }}" class="btn btn-primary">
                <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                Tambah Barang Masuk
            </a>
        </div>

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Kategori</span>
                </label>
                <select id="filter-kategori" class="select select-bordered">
                    <option value="">Semua Kategori</option>
                </select>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Sub Kategori</span>
                </label>
                <select id="filter-sub-kategori" class="select select-bordered">
                    <option value="">Semua Sub Kategori</option>
                </select>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Tahun</span>
                </label>
                <select id="filter-tahun" class="select select-bordered">
                    <option value="">Semua Tahun</option>
                    @for ($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Pencarian</span>
                </label>
                <input type="text" id="filter-search" class="input input-bordered"
                    placeholder="Asal barang atau nama item">
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Aksi</span>
                </label>
                <div class="flex gap-2">
                    <button id="btn-filter" class="btn btn-primary flex-1">Filter</button>
                    <button id="btn-export" class="btn btn-success">
                        <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Sorting -->
        <div class="flex gap-2 mb-4">
            <span class="text-sm font-medium">Urutkan berdasarkan:</span>
            <button class="btn btn-xs btn-outline sort-btn" data-sort="tanggal" data-direction="desc">
                Tanggal <x-heroicon-o-arrow-down class="w-3 h-3" />
            </button>
            <button class="btn btn-xs btn-outline sort-btn" data-sort="asal_barang" data-direction="asc">
                Asal Barang <x-heroicon-o-arrow-up class="w-3 h-3" />
            </button>
            <button class="btn btn-xs btn-outline sort-btn" data-sort="total_harga" data-direction="desc">
                Total Harga <x-heroicon-o-arrow-down class="w-3 h-3" />
            </button>
        </div>

        <!-- DataTable -->
        <div class="overflow-x-auto">
            <table id="barangMasuksTable" class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Action</th>
                        <th>Tanggal</th>
                        <th>Asal Barang</th>
                        <th>Penerima</th>
                        <th>Unit</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let barangMasuksData = [];
        let kategorisData = [];
        let subKategorisData = [];
        let currentFilters = {};
        let currentSort = {
            sort: 'tanggal',
            direction: 'desc'
        };

        $(document).ready(function() {
            // Load initial data
            loadBarangMasuks();
            loadKategoris();

            // Event handlers
            $('#filter-kategori').on('change', function() {
                const kategoriId = $(this).val();
                loadSubKategoris(kategoriId);
            });

            $('#btn-filter').on('click', applyFilters);
            $('#btn-export').on('click', exportData);

            $('.sort-btn').on('click', function() {
                const sort = $(this).data('sort');
                let direction = $(this).data('direction');

                // Toggle direction
                direction = direction === 'asc' ? 'desc' : 'asc';
                $(this).data('direction', direction);

                // Update UI
                $('.sort-btn').removeClass('btn-primary').addClass('btn-outline');
                $(this).removeClass('btn-outline').addClass('btn-primary');

                const icon = direction === 'asc' ? 'arrow-up' : 'arrow-down';
                $(this).find('svg').remove();
                $(this).append(`<x-heroicon-o-${icon} class="w-3 h-3" />`);

                currentSort = {
                    sort,
                    direction
                };
                loadBarangMasuks();
            });

            // Event handlers for actions
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                window.location.href = `/barang-masuks/${id}/edit`;
            });

            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                showConfirmDialog('Apakah Anda yakin ingin menghapus barang masuk ini?', function() {
                    deleteBarangMasuk(id);
                });
            });

            $(document).on('click', '.print-btn', function() {
                const id = $(this).data('id');
                window.open(`/barang-masuks/${id}/print`, '_blank');
            });

            $(document).on('change', '.verify-toggle', function() {
                const id = $(this).data('id');
                toggleVerification(id);
            });
        });

        function loadBarangMasuks() {
            $('#barangMasuksTable tbody').html('<tr><td colspan="12" class="text-center">Loading...</td></tr>');

            const params = {
                ...currentFilters,
                ...currentSort
            };

            $.ajax({
                url: '{{ route('barang-masuks.index') }}',
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                data: params,
                success: function(data) {
                    barangMasuksData = data;
                    updateTable();
                },
                error: function() {
                    showToast('error', 'Gagal memuat data barang masuk');
                    $('#barangMasuksTable tbody').html(
                        '<tr><td colspan="12" class="text-center text-error">Error loading data</td></tr>');
                }
            });
        }

        function loadKategoris() {
            $.ajax({
                url: '{{ route('api.kategoris') }}',
                method: 'GET',
                success: function(data) {
                    kategorisData = data;
                    updateKategoriSelect();
                },
                error: function() {
                    showToast('error', 'Gagal memuat data kategori');
                }
            });
        }

        function loadSubKategoris(kategoriId) {
            if (!kategoriId) {
                $('#filter-sub-kategori').empty().append('<option value="">Semua Sub Kategori</option>');
                return;
            }

            $.ajax({
                url: '{{ route('api.sub-kategoris') }}',
                method: 'GET',
                data: {
                    kategori_id: kategoriId
                },
                success: function(data) {
                    subKategorisData = data;
                    updateSubKategoriSelect();
                },
                error: function() {
                    showToast('error', 'Gagal memuat data sub kategori');
                }
            });
        }

        function updateKategoriSelect() {
            const select = $('#filter-kategori');
            select.empty();
            select.append('<option value="">Semua Kategori</option>');

            kategorisData.forEach(function(kategori) {
                select.append(`<option value="${kategori.id}">${kategori.kode} - ${kategori.nama}</option>`);
            });
        }

        function updateSubKategoriSelect() {
            const select = $('#filter-sub-kategori');
            select.empty();
            select.append('<option value="">Semua Sub Kategori</option>');

            subKategorisData.forEach(function(subKategori) {
                select.append(`<option value="${subKategori.id}">${subKategori.nama}</option>`);
            });
        }

        function applyFilters() {
            currentFilters = {
                kategori_id: $('#filter-kategori').val(),
                sub_kategori_id: $('#filter-sub-kategori').val(),
                tahun: $('#filter-tahun').val(),
                search: $('#filter-search').val()
            };
            loadBarangMasuks();
        }

        function exportData() {
            const params = new URLSearchParams({
                ...currentFilters,
                ...currentSort
            });
            window.location.href = `{{ route('barang-masuks.export') }}?${params}`;
        }

        function updateTable() {
            const tbody = $('#barangMasuksTable tbody');
            tbody.empty();

            if (barangMasuksData.length === 0) {
                tbody.append('<tr><td colspan="12" class="text-center text-gray-500">Tidak ada data</td></tr>');
                return;
            }

            let rowNum = 1;

            barangMasuksData.forEach(function(barangMasuk) {
                const itemsCount = barangMasuk.items ? barangMasuk.items.length : 0;

                if (itemsCount === 0) {
                    // Jika tidak ada items, tampilkan row kosong
                    tbody.append(`
                        <tr>
                            <td>${rowNum}</td>
                            <td>
                                <div class="flex gap-1">
                                    <button class="btn btn-sm btn-primary edit-btn" data-id="${barangMasuk.id}" title="Edit">
                                        ${Icons.edit}
                                    </button>
                                    <button class="btn btn-sm btn-success print-btn" data-id="${barangMasuk.id}" title="Print PDF">
                                        <x-heroicon-o-printer class="w-4 h-4" />
                                    </button>
                                    <button class="btn btn-sm btn-error delete-btn" data-id="${barangMasuk.id}" title="Delete">
                                        ${Icons.delete}
                                    </button>
                                </div>
                            </td>
                            <td>${new Date(barangMasuk.created_at).toLocaleString('id-ID')}</td>
                            <td>${barangMasuk.asal_barang}</td>
                            <td>${barangMasuk.operator.name}</td>
                            <td>${barangMasuk.sub_kategori.kategori.nama}</td>
                            <td colspan="5" class="text-center text-gray-500">Tidak ada items</td>
                            <td>
                                <input type="checkbox" class="toggle toggle-success verify-toggle" 
                                       data-id="${barangMasuk.id}" 
                                       ${barangMasuk.is_verified ? 'checked' : ''}>
                            </td>
                        </tr>
                    `);
                    rowNum++;
                } else {
                    // Loop items dan buat rows
                    barangMasuk.items.forEach(function(item, itemIndex) {
                        const isFirstItem = itemIndex === 0;

                        let row = '<tr>';

                        if (isFirstItem) {
                            // Kolom yang di-merge (rowspan)
                            row += `
                                <td rowspan="${itemsCount}">${rowNum}</td>
                                <td rowspan="${itemsCount}">
                                    <div class="flex gap-1">
                                        <button class="btn btn-sm btn-primary edit-btn" data-id="${barangMasuk.id}" title="Edit">
                                            ${Icons.edit}
                                        </button>
                                        <button class="btn btn-sm btn-success print-btn" data-id="${barangMasuk.id}" title="Print PDF">
                                            <x-heroicon-o-printer class="w-4 h-4" />
                                        </button>
                                        <button class="btn btn-sm btn-error delete-btn" data-id="${barangMasuk.id}" title="Delete">
                                            ${Icons.delete}
                                        </button>
                                    </div>
                                </td>
                                <td rowspan="${itemsCount}">${new Date(barangMasuk.created_at).toLocaleString('id-ID')}</td>
                                <td rowspan="${itemsCount}">${barangMasuk.asal_barang}</td>
                                <td rowspan="${itemsCount}">${barangMasuk.operator.name}</td>
                                <td rowspan="${itemsCount}">${barangMasuk.sub_kategori.kategori.nama}</td>
                            `;
                        }

                        // Kolom item (tidak di-merge)
                        row += `
                            <td>ITEM-${String(itemIndex + 1).padStart(3, '0')}</td>
                            <td>${item.nama_barang}</td>
                            <td>${formatCurrency(item.harga)}</td>
                            <td>${item.jumlah} ${item.satuan}</td>
                            <td>${formatCurrency(item.total)}</td>
                        `;

                        if (isFirstItem) {
                            // Status verification hanya di row pertama
                            row += `
                                <td rowspan="${itemsCount}">
                                    <input type="checkbox" class="toggle toggle-success verify-toggle" 
                                           data-id="${barangMasuk.id}" 
                                           ${barangMasuk.is_verified ? 'checked' : ''}>
                                </td>
                            `;
                        }

                        row += '</tr>';
                        tbody.append(row);
                    });
                    rowNum++;
                }
            });
        }

        function deleteBarangMasuk(id) {
            $.ajax({
                url: `/barang-masuks/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        loadBarangMasuks();
                        showToast('success', response.message);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function() {
                    showToast('error', 'Terjadi kesalahan sistem');
                }
            });
        }

        function toggleVerification(id) {
            $.ajax({
                url: `/barang-masuks/${id}/toggle-verification`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function() {
                    showToast('error', 'Terjadi kesalahan sistem');
                }
            });
        }
    </script>
@endpush
