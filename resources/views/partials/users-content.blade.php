<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <div class="flex justify-between items-center mb-6">
            <h2 class="card-title">
                <x-heroicon-o-users class="w-6 h-6 mr-2" />
                Data Users
            </h2>
            <button class="btn btn-primary" onclick="openUserModal('add')">
                <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                Tambah User
            </button>

        </div>

        <!-- Filter Section -->
        <div class="mb-4 flex flex-wrap gap-4">
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Filter Role</span>
                </label>
                <select id="roleFilter" class="select select-bordered">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="operator">Operator</option>
                </select>
            </div>
        </div>

        <!-- DataTable -->
        <div class="overflow-x-auto">
            <table id="usersTable" class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Action</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="userModal" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-4" id="modalTitle">Form User</h3>

        <form id="userForm">
            @csrf
            <input type="hidden" id="userId" name="id">
            <input type="hidden" id="formMethod" name="_method" value="POST">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Username <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="username" id="username" class="input input-bordered" required>
                    <div class="error-message text-error text-sm mt-1 hidden" id="username-error"></div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nama <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="name" id="name" class="input input-bordered" required>
                    <div class="error-message text-error text-sm mt-1 hidden" id="name-error"></div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email <span class="text-error">*</span></span>
                    </label>
                    <input type="email" name="email" id="email" class="input input-bordered" required>
                    <div class="error-message text-error text-sm mt-1 hidden" id="email-error"></div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Role <span class="text-error">*</span></span>
                    </label>
                    <select name="role" id="role" class="select select-bordered" required>
                        <option value="">Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="operator">Operator</option>
                    </select>
                    <div class="error-message text-error text-sm mt-1 hidden" id="role-error"></div>
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text">Password <span class="text-error" id="passwordRequired">*</span></span>
                    </label>
                    <input type="password" name="password" id="password" class="input input-bordered">
                    <div class="label">
                        <span class="label-text-alt">Minimal 8 karakter, kombinasi huruf besar, kecil, dan angka</span>
                    </div>
                    <div class="error-message text-error text-sm mt-1 hidden" id="password-error"></div>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn" onclick="closeModal('userModal')">Batal</button>
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
        let usersData = [];
        let table;

        $(document).ready(function() {
            // Initialize DataTable menggunakan fungsi dari main.js
            table = initDataTable('#usersTable');

            // Load initial data
            loadUsers();

            // Role filter
            $('#roleFilter').on('change', function() {
                updateTable();
            });

            // Event handlers
            $(document).on('click', '.edit-btn', function() {
                let userId = $(this).data('id');
                openUserModal('edit', userId);
            });


            $(document).on('click', '.delete-btn', function() {
                let userId = $(this).data('id');
                showConfirmDialog('Apakah Anda yakin ingin menghapus user ini?', function() {
                    deleteUser(userId);
                });
            });

            $(document).on('click', '.lock-btn', function() {
                let userId = $(this).data('id');
                toggleLockUser(userId);
            });

            $('#userForm').on('submit', function(e) {
                e.preventDefault();
                submitForm();
            });
        });

        function loadUsers() {
            $('#usersTable').addClass('loading-overlay');

            $.ajax({
                url: '{{ route('users.index') }}',
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                success: function(data) {
                    usersData = data;
                    updateTable();
                },
                error: function() {
                    showToast('error', 'Gagal memuat data users');
                },
                complete: function() {
                    // Hapus loading state
                    $('#usersTable').removeClass('loading-overlay');
                }
            });
        }


        function openUserModal(action, userId = null) {
            clearFormErrors();

            if (action === 'add') {
                $('#modalTitle').text('Tambah User');
                $('#userForm')[0].reset();
                $('#userId').val('');
                $('#formMethod').val('POST');
                $('#passwordRequired').show();
                $('#password').prop('required', true);
            } else {
                $('#modalTitle').text('Edit User');
                $('#formMethod').val('PUT');
                $('#passwordRequired').hide();
                $('#password').prop('required', false);

                // Load user data
                $.get('/users/' + userId, function(data) {
                    $('#userId').val(data.id);
                    $('#username').val(data.username);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#role').val(data.role);
                    $('#password').val('');
                });
            }

            openModal('userModal');
        }

        function updateTable() {
            table.clear();

            // Filter data berdasarkan role filter
            const roleFilter = $('#roleFilter').val();
            const filteredUsers = roleFilter ?
                usersData.filter(user => user.role === roleFilter) :
                usersData;

            filteredUsers.forEach(function(user, index) {
                const lockIcon = user.is_locked ? Icons.lock : Icons.unlock;
                const lockClass = user.is_locked ? 'btn-warning' : 'btn-success';
                const lockTitle = user.is_locked ? 'Unlock User' : 'Lock User';

                const actions = `
            <div class="flex gap-1">
                <button class="btn btn-sm btn-primary edit-btn" data-id="${user.id}" title="Edit">
                    ${Icons.edit}
                </button>
                <button class="btn btn-sm ${lockClass} lock-btn" data-id="${user.id}" title="${lockTitle}">
                    ${lockIcon}
                </button>
                <button class="btn btn-sm btn-error delete-btn" data-id="${user.id}" title="Delete">
                    ${Icons.delete}
                </button>
            </div>
        `;

                const roleBadge = user.role === 'admin' ?
                    '<span class="badge badge-primary">Admin</span>' :
                    '<span class="badge badge-secondary">Operator</span>';

                const statusBadge = user.is_locked ?
                    '<span class="badge badge-error">Locked</span>' :
                    '<span class="badge badge-success">Active</span>';

                table.row.add([
                    index + 1,
                    actions,
                    user.username,
                    user.name,
                    user.email,
                    roleBadge,
                    statusBadge
                ]);
            });

            table.draw();
        }


        function submitForm() {
            let formData = new FormData($('#userForm')[0]);
            let url = $('#userId').val() ? '/users/' + $('#userId').val() : '/users';

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
                        closeModal('userModal');
                        loadUsers();
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

        function deleteUser(userId) {
            $.ajax({
                url: '/users/' + userId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        loadUsers();
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

        function toggleLockUser(userId) {
            $.ajax({
                url: '/users/' + userId + '/toggle-lock',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        loadUsers();
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
