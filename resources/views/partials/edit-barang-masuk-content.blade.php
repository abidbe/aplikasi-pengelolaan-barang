<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="card-title">
                    <x-heroicon-o-pencil class="w-6 h-6 mr-2" />
                    Edit Barang Masuk
                </h2>
                <p class="text-sm text-gray-600 mt-2">Ubah data barang masuk #{{ $barangMasuk->id }}</p>
            </div>
            <a href="{{ route('barang-masuks.index') }}" class="btn btn-outline">
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Kembali
            </a>
        </div>

        <form id="barangMasukForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- INFORMASI UMUM -->
            <div class="card bg-base-200 mb-6">
                <div class="card-body">
                    <h3 class="card-title text-lg mb-4">Informasi Umum</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Operator <span class="text-error">*</span></span>
                            </label>
                            <select name="operator_id" id="operator_id" class="select select-bordered" required>
                                @if (auth()->user()->isAdmin())
                                    <option value="">Pilih Operator</option>
                                    @foreach ($operators as $operator)
                                        <option value="{{ $operator->id }}"
                                            {{ $barangMasuk->operator_id == $operator->id ? 'selected' : '' }}>
                                            {{ $operator->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="{{ auth()->id() }}" selected>{{ auth()->user()->name }}</option>
                                @endif
                            </select>
                            <div class="error-message text-error text-sm mt-1 hidden" id="operator_id-error"></div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Kategori <span class="text-error">*</span></span>
                            </label>
                            <select name="kategori_id" id="kategori_id" class="select select-bordered" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}"
                                        {{ $barangMasuk->subKategori->kategori_id == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->kode }} - {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="error-message text-error text-sm mt-1 hidden" id="kategori_id-error"></div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Sub Kategori <span class="text-error">*</span></span>
                            </label>
                            <select name="sub_kategori_id" id="sub_kategori_id" class="select select-bordered" required>
                                <option value="{{ $barangMasuk->sub_kategori_id }}" selected>
                                    {{ $barangMasuk->subKategori->nama }}
                                </option>
                            </select>
                            <div class="error-message text-error text-sm mt-1 hidden" id="sub_kategori_id-error"></div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Batas Harga</span>
                            </label>
                            <input type="text" id="batas_harga_display" class="input input-bordered" readonly
                                value="{{ $barangMasuk->subKategori->batas_harga_format }}">
                            <div class="label">
                                <span class="label-text-alt">Informasi batas harga sub kategori</span>
                            </div>
                        </div>

                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text">Asal Barang <span class="text-error">*</span></span>
                            </label>
                            <input type="text" name="asal_barang" id="asal_barang" class="input input-bordered"
                                maxlength="200" required value="{{ $barangMasuk->asal_barang }}">
                            <div class="label">
                                <span class="label-text-alt">Maksimal 200 karakter</span>
                            </div>
                            <div class="error-message text-error text-sm mt-1 hidden" id="asal_barang-error"></div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Nomor Surat</span>
                            </label>
                            <input type="text" name="nomor_surat" id="nomor_surat" class="input input-bordered"
                                maxlength="100" value="{{ $barangMasuk->nomor_surat }}">
                            <div class="label">
                                <span class="label-text-alt">Maksimal 100 karakter</span>
                            </div>
                            <div class="error-message text-error text-sm mt-1 hidden" id="nomor_surat-error"></div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Lampiran File</span>
                            </label>
                            <input type="file" name="lampiran" id="lampiran" class="file-input file-input-bordered"
                                accept=".doc,.docx,.zip">
                            @if ($barangMasuk->lampiran)
                                <div class="label">
                                    <span class="label-text-alt">File saat ini:
                                        <a href="{{ Storage::url($barangMasuk->lampiran) }}" target="_blank"
                                            class="link link-primary">
                                            {{ basename($barangMasuk->lampiran) }}
                                        </a>
                                    </span>
                                </div>
                            @endif
                            <div class="label">
                                <span class="label-text-alt">Format: DOC, DOCX, ZIP (Maks: 10MB)</span>
                            </div>
                            <div class="error-message text-error text-sm mt-1 hidden" id="lampiran-error"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFORMASI BARANG -->
            <div class="card bg-base-200 mb-6">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="card-title text-lg">Informasi Barang</h3>
                        <button type="button" id="addItemBtn" class="btn btn-primary btn-sm">
                            <x-heroicon-o-plus class="w-4 h-4 mr-1" />
                            Tambah Item
                        </button>
                    </div>

                    <div id="itemsContainer">
                        @foreach ($barangMasuk->items as $index => $item)
                            <div class="item-row border rounded-lg p-4 mb-4" data-item="{{ $index + 1 }}">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="font-semibold">Item #{{ $index + 1 }}</h4>
                                    <button type="button" class="btn btn-error btn-sm remove-item"
                                        {{ $index === 0 ? 'style=display:none' : '' }}>
                                        <x-heroicon-o-x-mark class="w-4 h-4" />
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div class="form-control md:col-span-2">
                                        <label class="label">
                                            <span class="label-text">Nama Barang <span
                                                    class="text-error">*</span></span>
                                        </label>
                                        <input type="text" name="items[{{ $index + 1 }}][nama_barang]"
                                            class="input input-bordered" maxlength="200" required
                                            value="{{ $item->nama_barang }}">
                                        <div class="error-message text-error text-sm mt-1 hidden"></div>
                                    </div>

                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text">Harga <span class="text-error">*</span></span>
                                        </label>
                                        <input type="text" name="items[{{ $index + 1 }}][harga]"
                                            class="input input-bordered harga-input" required
                                            value="{{ $item->harga_format }}">
                                        <div class="error-message text-error text-sm mt-1 hidden"></div>
                                    </div>

                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text">Jumlah <span class="text-error">*</span></span>
                                        </label>
                                        <input type="number" name="items[{{ $index + 1 }}][jumlah]"
                                            class="input input-bordered jumlah-input" min="1" required
                                            value="{{ $item->jumlah }}">
                                        <div class="error-message text-error text-sm mt-1 hidden"></div>
                                    </div>

                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text">Satuan <span class="text-error">*</span></span>
                                        </label>
                                        <input type="text" name="items[{{ $index + 1 }}][satuan]"
                                            class="input input-bordered" maxlength="40" required
                                            value="{{ $item->satuan }}">
                                        <div class="error-message text-error text-sm mt-1 hidden"></div>
                                    </div>

                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text">Total</span>
                                        </label>
                                        <input type="text" class="input input-bordered total-display" readonly
                                            value="{{ $item->total_format }}">
                                    </div>

                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text">Tanggal Expired</span>
                                        </label>
                                        <input type="date" name="items[{{ $index + 1 }}][tgl_expired]"
                                            class="input input-bordered"
                                            value="{{ $item->tgl_expired ? $item->tgl_expired->format('Y-m-d') : '' }}">
                                        <div class="error-message text-error text-sm mt-1 hidden"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total Display -->
                    <div class="divider"></div>
                    <div class="flex justify-end">
                        <div class="stats shadow">
                            <div class="stat">
                                <div class="stat-title">Total Harga Keseluruhan</div>
                                <div class="stat-value text-2xl" id="grandTotal">
                                    {{ $barangMasuk->total_harga_format }}</div>
                                <div class="stat-desc" id="totalValidation"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUBMIT BUTTONS -->
            <div class="card-actions justify-end">
                <a href="{{ route('barang-masuks.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="loading loading-spinner loading-sm hidden"></span>
                    <span class="button-text">Update Barang Masuk</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        let itemCounter = {{ count($barangMasuk->items) }};
        let batasHarga = {{ $barangMasuk->subKategori->batas_harga }};
        let subKategorisData = [];

        $(document).ready(function() {
            // Initialize calculations
            calculateGrandTotal();

            // Event handlers
            $('#kategori_id').on('change', loadSubKategoris);
            $('#sub_kategori_id').on('change', loadBatasHarga);
            $('#addItemBtn').on('click', addItem);
            $('#barangMasukForm').on('submit', submitForm);

            // Apply currency mask to existing inputs
            $('.harga-input').each(function() {
                applyCurrencyMask(this);
            });

            // Add calculation listeners to existing items
            $('.harga-input, .jumlah-input').on('input', function() {
                const itemRow = $(this).closest('.item-row');
                const itemNum = itemRow.data('item');
                calculateItemTotal(itemNum);
                calculateGrandTotal();
            });

            // Remove item handlers
            $('.remove-item').on('click', function() {
                $(this).closest('.item-row').remove();
                calculateGrandTotal();
                updateItemNumbers();
            });

            if (!{{ auth()->user()->isAdmin() ? 'true' : 'false' }}) {
                $('#operator_id').prop('disabled', true);
            }
        });

        function loadSubKategoris() {
            const kategoriId = $('#kategori_id').val();

            if (!kategoriId) return;

            $.ajax({
                url: '{{ route('api.sub-kategoris') }}',
                method: 'GET',
                data: {
                    kategori_id: kategoriId
                },
                success: function(data) {
                    subKategorisData = data;
                    const select = $('#sub_kategori_id');
                    select.empty().append('<option value="">Pilih Sub Kategori</option>');

                    data.forEach(function(subKategori) {
                        const selected = subKategori.id === {{ $barangMasuk->sub_kategori_id }} ?
                            'selected' : '';
                        select.append(
                            `<option value="${subKategori.id}" ${selected}>${subKategori.nama}</option>`
                            );
                    });
                },
                error: function() {
                    showToast('error', 'Gagal memuat data sub kategori');
                }
            });
        }

        function loadBatasHarga() {
            const subKategoriId = $('#sub_kategori_id').val();

            if (!subKategoriId) return;

            $.ajax({
                url: '{{ route('api.batas-harga') }}',
                method: 'GET',
                data: {
                    sub_kategori_id: subKategoriId
                },
                success: function(data) {
                    batasHarga = parseFloat(data.batas_harga);
                    $('#batas_harga_display').val(formatCurrency(batasHarga));
                    calculateGrandTotal();
                },
                error: function() {
                    showToast('error', 'Gagal memuat batas harga');
                }
            });
        }

        function addItem() {
            itemCounter++;
            const itemHtml = `
                    <div class="item-row border rounded-lg p-4 mb-4" data-item="${itemCounter}">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-semibold">Item #${itemCounter}</h4>
                            <button type="button" class="btn btn-error btn-sm remove-item">
                                <x-heroicon-o-x-mark class="w-4 h-4" />
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="form-control md:col-span-2">
                                <label class="label">
                                    <span class="label-text">Nama Barang <span class="text-error">*</span></span>
                                </label>
                                <input type="text" name="items[${itemCounter}][nama_barang]" class="input input-bordered" maxlength="200" required placeholder="Nama barang">
                                <div class="error-message text-error text-sm mt-1 hidden"></div>
                            </div>
                            
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Harga <span class="text-error">*</span></span>
                                </label>
                                <input type="text" name="items[${itemCounter}][harga]" class="input input-bordered harga-input" required placeholder="Rp 0">
                                <div class="error-message text-error text-sm mt-1 hidden"></div>
                            </div>
                            
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Jumlah <span class="text-error">*</span></span>
                                </label>
                                <input type="number" name="items[${itemCounter}][jumlah]" class="input input-bordered jumlah-input" min="1" required placeholder="0">
                                <div class="error-message text-error text-sm mt-1 hidden"></div>
                            </div>
                            
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Satuan <span class="text-error">*</span></span>
                                </label>
                                <input type="text" name="items[${itemCounter}][satuan]" class="input input-bordered" maxlength="40" required placeholder="pcs, kg, liter, dll">
                                <div class="error-message text-error text-sm mt-1 hidden"></div>
                            </div>
                            
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Total</span>
                                </label>
                                <input type="text" class="input input-bordered total-display" readonly placeholder="Rp 0">
                            </div>
                            
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Tanggal Expired</span>
                                </label>
                                <input type="date" name="items[${itemCounter}][tgl_expired]" class="input input-bordered">
                                <div class="error-message text-error text-sm mt-1 hidden"></div>
                            </div>
                        </div>
                    </div>
                `;

            $('#itemsContainer').append(itemHtml);
            applyCurrencyMask(`input[name="items[${itemCounter}][harga]"]`);

            $(`input[name="items[${itemCounter}][harga]"], input[name="items[${itemCounter}][jumlah]"]`).on('input',
                function() {
                    calculateItemTotal(itemCounter);
                    calculateGrandTotal();
                });

            $('.remove-item').off('click').on('click', function() {
                $(this).closest('.item-row').remove();
                calculateGrandTotal();
                updateItemNumbers();
            });
        }

        function calculateItemTotal(itemNum) {
            const hargaInput = $(`input[name="items[${itemNum}][harga]"]`);
            const jumlahInput = $(`input[name="items[${itemNum}][jumlah]"]`);
            const totalDisplay = hargaInput.closest('.item-row').find('.total-display');

            const harga = parseRupiah(hargaInput.val());
            const jumlah = parseInt(jumlahInput.val()) || 0;
            const total = harga * jumlah;

            totalDisplay.val(formatCurrency(total));
        }

        function calculateGrandTotal() {
            let grandTotal = 0;

            $('.total-display').each(function() {
                const value = $(this).val();
                if (value) {
                    grandTotal += parseRupiah(value);
                }
            });

            $('#grandTotal').text(formatCurrency(grandTotal));

            const validation = $('#totalValidation');
            if (batasHarga > 0) {
                if (grandTotal > batasHarga) {
                    validation.text('⚠️ Melebihi batas harga!').addClass('text-error');
                } else {
                    const remaining = batasHarga - grandTotal;
                    validation.text(`✓ Sisa budget: ${formatCurrency(remaining)}`).removeClass('text-error').addClass(
                        'text-success');
                }
            } else {
                validation.text('');
            }
        }

        function updateItemNumbers() {
            $('.item-row').each(function(index) {
                $(this).find('h4').text(`Item #${index + 1}`);

                if (index === 0) {
                    $(this).find('.remove-item').hide();
                } else {
                    $(this).find('.remove-item').show();
                }
            });
        }

        function submitForm(e) {
            e.preventDefault();

            if ($('.item-row').length === 0) {
                showToast('error', 'Minimal harus ada 1 item barang');
                return;
            }

            const grandTotal = parseRupiah($('#grandTotal').text());
            if (batasHarga > 0 && grandTotal > batasHarga) {
                showToast('error', 'Total harga melebihi batas harga sub kategori');
                return;
            }

            $('.harga-input').each(function() {
                const rupiah = $(this).val();
                const number = parseRupiah(rupiah);
                $(this).val(number);
            });

            const formData = new FormData($('#barangMasukForm')[0]);

            setLoadingState('#submitBtn', true, 'Update Barang Masuk');
            clearFormErrors();

            $.ajax({
                url: '{{ route('barang-masuks.update', $barangMasuk->id) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                        setTimeout(() => {
                            window.location.href = '{{ route('barang-masuks.index') }}';
                        }, 1500);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        showFormErrors(errors);
                    } else {
                        showToast('error', 'Terjadi kesalahan sistem');
                    }
                },
                complete: function() {
                    setLoadingState('#submitBtn', false, 'Update Barang Masuk');

                    $('.harga-input').each(function() {
                        const number = $(this).val();
                        $(this).val(formatRupiah(number));
                    });
                }
            });
        }
    </script>
@endpush
