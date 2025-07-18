<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <div class="flex justify-between items-center mb-6">
            <h2 class="card-title">
                <x-heroicon-o-tag class="w-6 h-6 mr-2" />
                Data Kategori
            </h2>
            <button class="btn btn-primary" onclick="openKategoriModal('add')">
                <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                Tambah Kategori
            </button>
        </div>

        <!-- DataTable -->
        <div class="overflow-x-auto">
            <table id="kategorisTable" class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Action</th>
                        <th>Kode</th>
                        <th>Nama Kategori</th>
                        <th>Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="kategoriModal" class="modal">
    <div class="modal-box max-w-lg">
        <h3 class="font-bold text-lg mb-4" id="modalTitle">Form Kategori</h3>

        <form id="kategoriForm">
            @csrf
            <input type="hidden" id="kategoriId" name="id">
            <input type="hidden" id="formMethod" name="_method" value="POST">

            <div class="space-y-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Kode Kategori <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="kode" id="kode" class="input input-bordered" maxlength="10"
                        required>
                    <div class="label">
                        <span class="label-text-alt">Maksimal 10 karakter</span>
                    </div>
                    <div class="error-message text-error text-sm mt-1 hidden" id="kode-error"></div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nama Kategori <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="nama" id="nama" class="input input-bordered" maxlength="100"
                        required>
                    <div class="label">
                        <span class="label-text-alt">Maksimal 100 karakter</span>
                    </div>
                    <div class="error-message text-error text-sm mt-1 hidden" id="nama-error"></div>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn" onclick="closeModal('kategoriModal')">Batal</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="loading loading-spinner loading-sm hidden"></span>
                    <span class="button-text">Simpan</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        let kategorisData = [];
        let table;

        $(document).ready(function() {
            // Initialize DataTable menggunakan fungsi dari main.js
            table = initDataTable('#kategorisTable');

            // Load initial data
            loadKategoris();

            // Event handlers
            $(document).on('click', '.edit-btn', function() {
                let kategoriId = $(this).data('id');
                openKategoriModal('edit', kategoriId);
            });

            $(document).on('click', '.delete-btn', function() {
                let kategoriId = $(this).data('id');
                showConfirmDialog('Apakah Anda yakin ingin menghapus kategori ini?', function() {
                    deleteKategori(kategoriId);
                });
            });

            $('#kategoriForm').on('submit', function(e) {
                e.preventDefault();
                submitForm();
            });
        });

        function loadKategoris() {
            $('#kategorisTable').addClass('loading-overlay');

            $.ajax({
                url: '{{ route('kategoris.index') }}',
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                success: function(data) {
                    kategorisData = data;
                    updateTable();
                },
                error: function() {
                    showToast('error', 'Gagal memuat data kategori');
                },
                complete: function() {
                    $('#kategorisTable').removeClass('loading-overlay');
                }
            });
        }

        function openKategoriModal(action, kategoriId = null) {
            clearFormErrors();

            if (action === 'add') {
                $('#modalTitle').text('Tambah Kategori');
                $('#kategoriForm')[0].reset();
                $('#kategoriId').val('');
                $('#formMethod').val('POST');
            } else {
                $('#modalTitle').text('Edit Kategori');
                $('#formMethod').val('PUT');

                // Load kategori data
                $.get('/kategoris/' + kategoriId, function(data) {
                    $('#kategoriId').val(data.id);
                    $('#kode').val(data.kode);
                    $('#nama').val(data.nama);
                });
            }

            openModal('kategoriModal');
        }

        function updateTable() {
            table.clear();

            kategorisData.forEach(function(kategori, index) {
                const actions = `
                    <div class="flex gap-1">
                        <button class="btn btn-sm btn-primary edit-btn" data-id="${kategori.id}" title="Edit">
                            ${Icons.edit}
                        </button>
                        <button class="btn btn-sm btn-error delete-btn" data-id="${kategori.id}" title="Delete">
                            ${Icons.delete}
                        </button>
                    </div>
                `;

                const createdAt = new Date(kategori.created_at).toLocaleDateString('id-ID');

                table.row.add([
                    index + 1,
                    actions,
                    kategori.kode,
                    kategori.nama,
                    createdAt
                ]);
            });

            table.draw();
        }

        function submitForm() {
            let formData = new FormData($('#kategoriForm')[0]);
            let url = $('#kategoriId').val() ? '/kategoris/' + $('#kategoriId').val() : '/kategoris';

            setLoadingState('#submitBtn', true, 'Simpan');
            clearFormErrors();

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        closeModal('kategoriModal');
                        loadKategoris();
                        showToast('success', response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        showFormErrors(errors);
                    } else {
                        showToast('error', 'Terjadi kesalahan sistem');
                    }
                },
                complete: function() {
                    setLoadingState('#submitBtn', false, 'Simpan');
                }
            });
        }

        function deleteKategori(kategoriId) {
            $.ajax({
                url: '/kategoris/' + kategoriId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        loadKategoris();
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
