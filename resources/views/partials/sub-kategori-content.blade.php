<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <div class="flex justify-between items-center mb-6">
            <h2 class="card-title">
                <x-heroicon-o-bookmark class="w-6 h-6 mr-2" />
                Data Sub Kategori
            </h2>
            <button class="btn btn-primary" onclick="openSubKategoriModal('add')">
                <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                Tambah Sub Kategori
            </button>
        </div>

        <!-- DataTable -->
        <div class="overflow-x-auto">
            <table id="subKategorisTable" class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Action</th>
                        <th>Kategori</th>
                        <th>Nama Sub Kategori</th>
                        <th>Batas Harga</th>
                        <th>Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="subKategoriModal" class="modal">
    <div class="modal-box max-w-lg">
        <h3 class="font-bold text-lg mb-4" id="modalTitle">Form Sub Kategori</h3>

        <form id="subKategoriForm">
            @csrf
            <input type="hidden" id="subKategoriId" name="id">
            <input type="hidden" id="formMethod" name="_method" value="POST">

            <div class="space-y-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Kategori <span class="text-error">*</span></span>
                    </label>
                    <select name="kategori_id" id="kategori_id" class="select select-bordered" required>
                        <option value="">Pilih Kategori</option>
                    </select>
                    <div class="error-message text-error text-sm mt-1 hidden" id="kategori_id-error"></div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nama Sub Kategori <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="nama" id="nama" class="input input-bordered" maxlength="100"
                        required>
                    <div class="label">
                        <span class="label-text-alt">Maksimal 100 karakter</span>
                    </div>
                    <div class="error-message text-error text-sm mt-1 hidden" id="nama-error"></div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Batas Harga <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="batas_harga" id="batas_harga" class="input input-bordered" required
                        placeholder="Rp 0,00">
                    <div class="label">
                        <span class="label-text-alt">Format: Rp 1.000.000,50 (bisa dengan desimal)</span>
                    </div>
                    <div class="error-message text-error text-sm mt-1 hidden" id="batas_harga-error"></div>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn" onclick="closeModal('subKategoriModal')">Batal</button>
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
        let subKategorisData = [];
        let kategorisData = [];
        let table;

        $(document).ready(function() {
            // Initialize DataTable
            table = initDataTable('#subKategorisTable');

            // Apply currency masking
            applyCurrencyMask('#batas_harga');

            // Load initial data
            loadSubKategoris();
            loadKategoris();

            // Event handlers
            $(document).on('click', '.edit-btn', function() {
                let subKategoriId = $(this).data('id');
                openSubKategoriModal('edit', subKategoriId);
            });

            $(document).on('click', '.delete-btn', function() {
                let subKategoriId = $(this).data('id');
                showConfirmDialog('Apakah Anda yakin ingin menghapus sub kategori ini?', function() {
                    deleteSubKategori(subKategoriId);
                });
            });

            $('#subKategoriForm').on('submit', function(e) {
                e.preventDefault();
                submitForm();
            });
        });

        function loadSubKategoris() {
            $('#subKategorisTable').addClass('loading-overlay');

            $.ajax({
                url: '{{ route('sub-kategoris.index') }}',
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                success: function(data) {
                    subKategorisData = data;
                    updateTable();
                },
                error: function() {
                    showToast('error', 'Gagal memuat data sub kategori');
                },
                complete: function() {
                    $('#subKategorisTable').removeClass('loading-overlay');
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

        function updateKategoriSelect() {
            const select = $('#kategori_id');
            select.empty();
            select.append('<option value="">Pilih Kategori</option>');

            kategorisData.forEach(function(kategori) {
                select.append(`<option value="${kategori.id}">${kategori.kode} - ${kategori.nama}</option>`);
            });
        }

        function openSubKategoriModal(action, subKategoriId = null) {
            clearFormErrors();

            if (action === 'add') {
                $('#modalTitle').text('Tambah Sub Kategori');
                $('#subKategoriForm')[0].reset();
                $('#subKategoriId').val('');
                $('#formMethod').val('POST');
                updateKategoriSelect();
            } else {
                $('#modalTitle').text('Edit Sub Kategori');
                $('#formMethod').val('PUT');

                // Load sub kategori data
                $.get('/sub-kategoris/' + subKategoriId, function(data) {
                    $('#subKategoriId').val(data.id);
                    $('#kategori_id').val(data.kategori_id);
                    $('#nama').val(data.nama);
                    $('#batas_harga').val(formatRupiah(data.batas_harga.toString()));
                });
            }

            openModal('subKategoriModal');
        }

        function updateTable() {
            table.clear();

            subKategorisData.forEach(function(subKategori, index) {
                const actions = `
                    <div class="flex gap-1">
                        <button class="btn btn-sm btn-primary edit-btn" data-id="${subKategori.id}" title="Edit">
                            ${Icons.edit}
                        </button>
                        <button class="btn btn-sm btn-error delete-btn" data-id="${subKategori.id}" title="Delete">
                            ${Icons.delete}
                        </button>
                    </div>
                `;

                const createdAt = new Date(subKategori.created_at).toLocaleDateString('id-ID');
                const batasHarga = formatCurrency(subKategori.batas_harga);

                table.row.add([
                    index + 1,
                    actions,
                    subKategori.kategori.kode + ' - ' + subKategori.kategori.nama,
                    subKategori.nama,
                    batasHarga,
                    createdAt
                ]);
            });

            table.draw();
        }

        function submitForm() {
            let formData = new FormData($('#subKategoriForm')[0]);
            let url = $('#subKategoriId').val() ? '/sub-kategoris/' + $('#subKategoriId').val() : '/sub-kategoris';

            // Convert rupiah to number
            const batasHarga = parseRupiah($('#batas_harga').val());
            formData.set('batas_harga', batasHarga);

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
                        closeModal('subKategoriModal');
                        loadSubKategoris();
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

        function deleteSubKategori(subKategoriId) {
            $.ajax({
                url: '/sub-kategoris/' + subKategoriId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        loadSubKategoris();
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
