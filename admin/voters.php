<?php
/**
 * Admin - Manage Voters
 */
$pageTitle = 'Pemilih';
$pageSubtitle = 'Kelola data pemilih / siswa';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
require_once __DIR__ . '/includes/topbar.php';
?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 animate-fade-in-up">
    <div>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola data pemilih yang terdaftar dalam sistem</p>
    </div>
    <div class="flex items-center gap-2">
        <button onclick="resetAllVotes()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 font-semibold rounded-xl text-sm transition">
            <span class="iconify" data-icon="lucide:rotate-ccw" data-width="16"></span>
            Reset Vote
        </button>
        <button onclick="openModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-500 text-gray-900 font-semibold rounded-xl text-sm transition-all hover:shadow-lg hover:shadow-primary/25 active:scale-[0.98]">
            <span class="iconify" data-icon="lucide:plus" data-width="18"></span>
            Tambah Pemilih
        </button>
    </div>
</div>

<!-- Table -->
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden animate-fade-in-up transition-colors">
    <div class="p-5 overflow-x-auto">
        <table id="votersTable" class="w-full text-sm">
            <thead>
                <tr>
                    <th class="text-left text-gray-500 dark:text-gray-400">No</th>
                    <th class="text-left text-gray-500 dark:text-gray-400">Username</th>
                    <th class="text-left text-gray-500 dark:text-gray-400">Nama Lengkap</th>
                    <th class="text-left text-gray-500 dark:text-gray-400">Kelas</th>
                    <th class="text-left text-gray-500 dark:text-gray-400">Status</th>
                    <th class="text-left text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="voterModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/50" onclick="closeModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-lg shadow-2xl border border-gray-100 dark:border-slate-700">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-slate-700">
                <h3 id="modalTitle" class="text-base font-bold text-gray-900 dark:text-white">Tambah Pemilih</h3>
                <button onclick="closeModal()" class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-slate-700 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-slate-600 transition">
                    <span class="iconify" data-icon="lucide:x" data-width="16"></span>
                </button>
            </div>
            <form id="voterForm" class="p-5 space-y-4">
                <input type="hidden" name="id" id="voterId">
                
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">Username</label>
                    <input type="text" name="username" id="voterUsername" required
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" name="full_name" id="voterFullName" required
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">Password <span id="pwdHint" class="normal-case text-gray-400 font-normal">(kosongkan jika tidak ingin mengubah)</span></label>
                    <input type="password" name="password" id="voterPassword"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">Kelas</label>
                    <input type="text" name="class" id="voterClass"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        placeholder="cth: XII RPL 1">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeModal()" class="flex-1 py-2.5 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl text-sm hover:bg-gray-200 dark:hover:bg-slate-600 transition">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-primary hover:bg-primary-500 text-gray-900 font-semibold rounded-xl text-sm transition-all hover:shadow-lg hover:shadow-primary/25 active:scale-[0.98] flex items-center justify-center gap-2">
                        <span class="iconify" data-icon="lucide:save" data-width="16"></span>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let table;
let rowNum = 0;

$(document).ready(function() {
    table = $('#votersTable').DataTable({
        ajax: {
            url: '<?= BASE_URL ?>/api/voters.php',
            dataSrc: 'data'
        },
        columns: [
            { data: null, width: '40px', className: 'text-center',
              render: function(data, type, row, meta) { return meta.row + 1; }
            },
            { data: 'username', className: 'font-medium text-gray-900 dark:text-white' },
            { data: 'full_name', className: 'text-gray-700 dark:text-gray-300' },
            { data: 'class',
              render: function(data) {
                return data ? '<span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-gray-100 dark:bg-slate-700 text-xs font-medium text-gray-600 dark:text-gray-300">' + data + '</span>' : '<span class="text-gray-400">-</span>';
              }
            },
            { data: 'has_voted', width: '80px',
              render: function(data) {
                return data == 1
                    ? '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 dark:bg-green-900/20 text-xs font-semibold text-green-700 dark:text-green-400"><span class="iconify" data-icon="lucide:check" data-width="12"></span>Sudah</span>'
                    : '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gray-100 dark:bg-slate-700 text-xs font-semibold text-gray-500 dark:text-gray-400"><span class="iconify" data-icon="lucide:minus" data-width="12"></span>Belum</span>';
              }
            },
            { data: null, orderable: false, width: '100px',
              render: function(data) {
                return `
                    <div class="flex items-center gap-1">
                        <button onclick="editVoter(${data.id})" class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center hover:bg-blue-100 dark:hover:bg-blue-900/40 transition" title="Edit">
                            <span class="iconify" data-icon="lucide:pencil" data-width="14" style="color:#3b82f6"></span>
                        </button>
                        <button onclick="deleteVoter(${data.id}, '${data.full_name}')" class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/20 flex items-center justify-center hover:bg-red-100 dark:hover:bg-red-900/40 transition" title="Hapus">
                            <span class="iconify" data-icon="lucide:trash-2" data-width="14" style="color:#ef4444"></span>
                        </button>
                    </div>`;
              }
            }
        ],
        responsive: true,
        language: {
            search: '',
            searchPlaceholder: 'Cari pemilih...',
            lengthMenu: 'Tampil _MENU_',
            info: 'Menampilkan _START_-_END_ dari _TOTAL_',
            infoEmpty: 'Tidak ada data',
            emptyTable: 'Belum ada pemilih terdaftar',
            paginate: { previous: '<span class="iconify" data-icon="lucide:chevron-left" data-width="16"></span>', next: '<span class="iconify" data-icon="lucide:chevron-right" data-width="16"></span>' }
        }
    });
});

function openModal(id = null) {
    document.getElementById('voterModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = id ? 'Edit Pemilih' : 'Tambah Pemilih';
    document.getElementById('pwdHint').style.display = id ? 'inline' : 'none';
    if (!id) {
        document.getElementById('voterForm').reset();
        document.getElementById('voterId').value = '';
        document.getElementById('voterPassword').required = true;
    } else {
        document.getElementById('voterPassword').required = false;
    }
}

function closeModal() {
    document.getElementById('voterModal').classList.add('hidden');
}

function editVoter(id) {
    $.get('<?= BASE_URL ?>/api/voters.php?id=' + id, function(res) {
        if (res.success) {
            let v = res.data;
            document.getElementById('voterId').value = v.id;
            document.getElementById('voterUsername').value = v.username;
            document.getElementById('voterFullName').value = v.full_name;
            document.getElementById('voterClass').value = v.class || '';
            document.getElementById('voterPassword').value = '';
            openModal(id);
        }
    });
}

function deleteVoter(id, name) {
    Swal.fire({
        title: 'Hapus Pemilih?',
        text: '"' + name + '" akan dihapus dari sistem.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= BASE_URL ?>/api/voters.php?id=' + id,
                type: 'DELETE',
                success: function(res) {
                    if (res.success) {
                        showToast('success', res.message);
                        table.ajax.reload();
                    } else {
                        showToast('error', res.message);
                    }
                }
            });
        }
    });
}

function resetAllVotes() {
    Swal.fire({
        title: 'Reset Semua Vote?',
        text: 'Semua data voting akan dihapus dan pemilih akan bisa memilih kembali.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Reset',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= BASE_URL ?>/api/voters.php', { action: 'reset_votes' }, function(res) {
                if (res.success) {
                    showToast('success', res.message);
                    table.ajax.reload();
                } else {
                    showToast('error', res.message);
                }
            });
        }
    });
}

$('#voterForm').on('submit', function(e) {
    e.preventDefault();
    let formData = $(this).serialize();
    let id = document.getElementById('voterId').value;
    
    $.ajax({
        url: '<?= BASE_URL ?>/api/voters.php' + (id ? '?id=' + id : ''),
        type: 'POST',
        data: formData,
        success: function(res) {
            if (res.success) {
                showToast('success', res.message);
                closeModal();
                table.ajax.reload();
            } else {
                showToast('error', res.message);
            }
        },
        error: function() {
            showToast('error', 'Terjadi kesalahan server');
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
