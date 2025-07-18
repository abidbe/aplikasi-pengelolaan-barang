<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="card-title">
                    <x-heroicon-o-plus class="w-6 h-6 mr-2" />
                    Tambah Barang Masuk
                </h2>
                <p class="text-sm text-gray-600 mt-2">Masukkan data barang masuk baru ke dalam sistem</p>
            </div>
            <a href="{{ route('barang-masuks.index') }}" class="btn btn-outline">
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Kembali
            </a>
        </div>

        <form id="barangMasukForm" enctype="multipart/form-data">
            @csrf

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
                                        <option value="{{ $operator->id }}">{{ $operator->name }}</option>
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
                                    <option value="{{ $kategori->id }}">{{ $kategori->kode }} - {{ $kategori->nama }}
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
                                <option value="">Pilih Sub Kategori</option>
                            </select>
                            <div class="error-message text-error text-sm mt-1 hidden" id="sub_kategori_id-error"></div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Batas Harga</span>
                            </label>
                            <input type="text" id="batas_harga_display" class="input input-bordered" readonly
                                placeholder="Pilih sub kategori dulu">
                            <div class="label">
                                <span class="label-text-alt">Informasi batas harga sub kategori</span>
                            </div>
                        </div>

                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text">Asal Barang <span class="text-error">*</span></span>
                            </label>
                            <input type="text" name="asal_barang" id="asal_barang" class="input input-bordered"
                                maxlength="200" required placeholder="Masukkan asal barang">
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
                                maxlength="100" placeholder="Nomor surat (opsional)">
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
                        <!-- Items will be added here dynamically -->
                    </div>

                    <!-- Total Display -->
                    <div class="divider"></div>
                    <div class="flex justify-end">
                        <div class="stats shadow">
                            <div class="stat">
                                <div class="stat-title">Total Harga Keseluruhan</div>
                                <div class="stat-value text-2xl" id="grandTotal">Rp 0</div>
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
                    <span class="button-text">Simpan Barang Masuk</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        let itemCounter = 0;
        let batasHarga = 0;
        let subKategorisData = [];

        $(document).ready(function() {
            // Add first item on load
            addItem();

            // Event handlers
            $('#kategori_id').on('change', loadSubKategoris);
            $('#sub_kategori_id').on('change', loadBatasHarga);
            $('#addItemBtn').on('click', addItem);
            $('#barangMasukForm').on('submit', submitForm);

            // Apply currency mask to operator readonly field
            if (!{{ auth()->user()->isAdmin() ? 'true' : 'false' }}) {
                $('#operator_id').prop('disabled', true);
            }
        });

        function loadSubKategoris() {
            const kategoriId = $('#kategori_id').val();

            $('#sub_kategori_id').empty().append('<option value="">Pilih Sub Kategori</option>');
            $('#batas_harga_display').val('');

            if (!kategoriId) return;

            $.ajax({
                url: '{{ route('api.sub-kategoris') }}',
                method: 'GET',
                data: {
                    kategori_id: kategoriId
                },
                success: function(data) {
                    subKategorisData = data;
                    data.forEach(function(subKategori) {
                        $('#sub_kategori_id').append(
                            `<option value="${subKategori.id}">${subKategori.nama}</option>`);
                    });
                },
                error: function() {
                    showToast('error', 'Gagal memuat data sub kategori');
                }
            });
        }

        function loadBatasHarga() {
            const subKategoriId = $('#sub_kategori_id').val();

            if (!subKategoriId) {
                $('#batas_harga_display').val('');
                batasHarga = 0;
                return;
            }

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
                            <button type="button" class="btn btn-error btn-sm remove-item" ${itemCounter === 1 ? 'style="display:none"' : ''}>
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

            // Apply currency mask to new harga input
            applyCurrencyMask(`input[name="items[${itemCounter}][harga]"]`);

            // Add event listeners for calculation
            $(`input[name="items[${itemCounter}][harga]"], input[name="items[${itemCounter}][jumlah]"]`).on('input',
                function() {
                    calculateItemTotal(itemCounter);
                    calculateGrandTotal();
                });

            // Remove item handler
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

            // Validate against batas harga
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

                // Show/hide remove button
                if (index === 0) {
                    $(this).find('.remove-item').hide();
                } else {
                    $(this).find('.remove-item').show();
                }
            });
        }

        function submitForm(e) {
            e.preventDefault();

            // Validate items
            if ($('.item-row').length === 0) {
                showToast('error', 'Minimal harus ada 1 item barang');
                return;
            }

            // Check batas harga
            const grandTotal = parseRupiah($('#grandTotal').text());
            if (batasHarga > 0 && grandTotal > batasHarga) {
                showToast('error', 'Total harga melebihi batas harga sub kategori');
                return;
            }

            // Convert rupiah values to numbers
            $('.harga-input').each(function() {
                const rupiah = $(this).val();
                const number = parseRupiah(rupiah);
                $(this).val(number);
            });

            const formData = new FormData($('#barangMasukForm')[0]);

            setLoadingState('#submitBtn', true, 'Simpan Barang Masuk');
            clearFormErrors();

            $.ajax({
                url: '{{ route('barang-masuks.store') }}',
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
                    setLoadingState('#submitBtn', false, 'Simpan Barang Masuk');

                    // Convert back to rupiah format
                    $('.harga-input').each(function() {
                        const number = $(this).val();
                        $(this).val(formatRupiah(number));
                    });
                }
            });
        }
    </script>
@endpush
