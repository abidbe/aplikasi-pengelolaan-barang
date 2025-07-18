// ======================
// REUSABLE FUNCTIONS
// ======================

// Toast/Alert dengan DaisyUI
function showToast(type, message) {
    const alertClass = type === "success" ? "alert-success" : "alert-error";
    const icon =
        type === "success"
            ? '<svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
            : '<svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';

    const toastId = "toast-" + Date.now();
    const toastHtml = `
        <div id="${toastId}" class="toast toast-top toast-end z-50">
            <div class="alert ${alertClass}">
                ${icon}
                <span>${message}</span>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML("beforeend", toastHtml);

    setTimeout(() => {
        const toast = document.getElementById(toastId);
        if (toast) toast.remove();
    }, 3000);
}

// Loading State Manager
function setLoadingState(buttonSelector, isLoading, originalText = "Submit") {
    const btn = document.querySelector(buttonSelector);
    if (!btn) return;

    if (isLoading) {
        btn.disabled = true;
        btn.classList.add("loading");
        const spinner = btn.querySelector(".loading-spinner");
        const text = btn.querySelector(".button-text");
        if (spinner) spinner.classList.remove("hidden");
        if (text) text.textContent = "Loading...";
    } else {
        btn.disabled = false;
        btn.classList.remove("loading");
        const spinner = btn.querySelector(".loading-spinner");
        const text = btn.querySelector(".button-text");
        if (spinner) spinner.classList.add("hidden");
        if (text) text.textContent = originalText;
    }
}

// Modal Manager
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add("modal-open");
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.remove("modal-open");
}

// Form Error Handler
function showFormErrors(errors) {
    clearFormErrors();

    for (let field in errors) {
        const errorElement = document.getElementById(field + "-error");
        const inputElement = document.getElementById(field);

        if (errorElement && inputElement) {
            errorElement.textContent = errors[field][0];
            errorElement.classList.remove("hidden");
            inputElement.classList.add("input-error");
        }
    }
}

function clearFormErrors() {
    document.querySelectorAll(".error-message").forEach((el) => {
        el.classList.add("hidden");
        el.textContent = "";
    });

    document.querySelectorAll(".input-error").forEach((el) => {
        el.classList.remove("input-error");
    });
}

// Confirmation Dialog dengan DaisyUI
function showConfirmDialog(message, onConfirm, confirmText = "Ya, Hapus") {
    const dialogId = "confirm-dialog-" + Date.now();
    const dialogHtml = `
        <div id="${dialogId}" class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg">Konfirmasi</h3>
                <p class="py-4">${message}</p>
                <div class="modal-action">
                    <button class="btn" onclick="closeConfirmDialog('${dialogId}')">Batal</button>
                    <button class="btn btn-error" onclick="confirmAction('${dialogId}')">${confirmText}</button>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML("beforeend", dialogHtml);

    window.confirmAction = function (dialogId) {
        onConfirm();
        closeConfirmDialog(dialogId);
    };

    window.closeConfirmDialog = function (dialogId) {
        document.getElementById(dialogId).remove();
    };
}

// AJAX Helper
function makeRequest(url, method = "GET", data = null, options = {}) {
    const config = {
        method: method,
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            Accept: "application/json",
            ...options.headers,
        },
    };

    if (data) {
        if (data instanceof FormData) {
            config.body = data;
        } else {
            config.headers["Content-Type"] = "application/json";
            config.body = JSON.stringify(data);
        }
    }

    return fetch(url, config)
        .then((response) => response.json())
        .then((data) => {
            if (data.success === false) {
                throw new Error(data.message || "Request failed");
            }
            return data;
        });
}

// DataTable Helper
function initDataTable(tableId, options = {}) {
    const defaultOptions = {
        pageLength: 10,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya",
            },
        },
        columnDefs: [
            {
                targets: [0, 1], // No dan Action columns
                orderable: false,
            },
        ],
    };

    return $(tableId).DataTable({ ...defaultOptions, ...options });
}

// Currency Formatter
function formatCurrency(amount) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(amount);
}

// Currency Input Masking
function applyCurrencyMask(inputSelector) {
    const input = document.querySelector(inputSelector);
    if (!input) return;

    input.addEventListener("input", function (e) {
        let value = e.target.value.replace(/[^\d,]/g, "");
        if (value) {
            e.target.value = formatRupiah(value);
        }
    });

    // Prevent non-numeric input
    input.addEventListener("keypress", function (e) {
        const char = String.fromCharCode(e.which);
        if (!/[\d,]/.test(char)) {
            e.preventDefault();
        }
    });
}

function formatRupiah(angka) {
    let number_string = angka.replace(/[^,\d]/g, "").toString();
    let split = number_string.split(",");
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return "Rp " + rupiah;
}

function parseRupiah(rupiahString) {
    // Handle decimal separator
    let cleanString = rupiahString.replace(/[^\d,]/g, "");
    if (cleanString.includes(",")) {
        let parts = cleanString.split(",");
        return parseFloat(parts[0] + "." + parts[1]) || 0;
    }
    return parseInt(cleanString) || 0;
}

// Icons Helper - SVG Heroicons
const Icons = {
    edit: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>',
    delete: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>',
    lock: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>',
    unlock: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>',
    users: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path></svg>',
    plus: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>',
};
