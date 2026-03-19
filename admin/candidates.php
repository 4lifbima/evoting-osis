<?php
/**
 * Admin - Manage Candidates
 */
$pageTitle = 'Kandidat';
$pageSubtitle = 'Kelola data kandidat';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
require_once __DIR__ . '/includes/topbar.php';
?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 animate-fade-in-up">
    <div>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola data kandidat untuk pemilihan OSIS</p>
    </div>
    <button onclick="openModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-500 text-gray-900 font-semibold rounded-xl text-sm transition-all hover:shadow-lg hover:shadow-primary/25 active:scale-[0.98]">
        <span class="iconify" data-icon="lucide:plus" data-width="18"></span>
        Tambah Kandidat
    </button>
</div>

<!-- Table -->
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden animate-fade-in-up transition-colors">
    <div class="p-5 overflow-x-auto">
        <table id="candidatesTable" class="w-full text-sm">
            <thead>
                <tr>
                    <th class="text-left text-gray-500 dark:text-gray-400">No. Urut</th>
                    <th class="text-left text-gray-500 dark:text-gray-400">Foto</th>
                    <th class="text-left text-gray-500 dark:text-gray-400">Nama</th>
                    <th class="text-left text-gray-500 dark:text-gray-400">Visi</th>
                    <th class="text-left text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="candidateModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/50" onclick="closeModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl border border-gray-100 dark:border-slate-700">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-slate-700">
                <h3 id="modalTitle" class="text-base font-bold text-gray-900 dark:text-white">Tambah Kandidat</h3>
                <button onclick="closeModal()" class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-slate-700 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-slate-600 transition">
                    <span class="iconify" data-icon="lucide:x" data-width="16"></span>
                </button>
            </div>
            <form id="candidateForm" enctype="multipart/form-data" class="p-5 space-y-4">
                <input type="hidden" name="id" id="candidateId">
                
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">No. Urut</label>
                    <input type="number" name="candidate_number" id="candidateNumber" min="1" required
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">Nama Kandidat</label>
                    <input type="text" name="name" id="candidateName" required
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">Foto</label>
                    <input type="file" name="photo" id="candidatePhoto" accept="image/*"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    <div id="photoPreview" class="mt-2 hidden">
                        <img id="previewImg" src="" class="w-20 h-20 object-cover rounded-xl">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">Visi</label>
                    <textarea name="vision" id="candidateVision" rows="3"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">Misi</label>
                    <textarea name="mission" id="candidateMission" rows="3"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition resize-none"></textarea>
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

$(document).ready(function() {
    table = $('#candidatesTable').DataTable({
        ajax: {
            url: '<?= BASE_URL ?>/api/candidates.php',
            dataSrc: 'data'
        },
        columns: [
            { data: 'candidate_number', className: 'font-bold text-center', width: '60px',
              render: function(data) {
                return '<span class="inline-flex items-center justify-center w-8 h-8 bg-primary-50 dark:bg-primary-900/20 rounded-lg text-sm font-bold text-primary-700 dark:text-primary">' + data + '</span>';
              }
            },
            { data: 'photo', orderable: false, width: '60px',
              render: function(data) {
                if (data) {
                    return '<img src="<?= BASE_URL ?>/uploads/candidates/' + data + '" class="w-10 h-10 rounded-xl object-cover">';
                }
                return '<div class="w-10 h-10 bg-gray-100 dark:bg-slate-700 rounded-xl flex items-center justify-center"><span class="iconify" data-icon="lucide:user" data-width="18" style="color:#94a3b8"></span></div>';
              }
            },
            { data: 'name', className: 'font-semibold text-gray-900 dark:text-white' },
            { data: 'vision',
              render: function(data) {
                if (!data) return '<span class="text-gray-400">-</span>';
                return '<span class="text-gray-600 dark:text-gray-400">' + (data.length > 60 ? data.substring(0, 60) + '...' : data) + '</span>';
              }
            },
            { data: null, orderable: false, width: '100px',
              render: function(data) {
                return `
                    <div class="flex items-center gap-1">
                        <button onclick="editCandidate(${data.id})" class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center hover:bg-blue-100 dark:hover:bg-blue-900/40 transition" title="Edit">
                            <span class="iconify" data-icon="lucide:pencil" data-width="14" style="color:#3b82f6"></span>
                        </button>
                        <button onclick="deleteCandidate(${data.id}, '${data.name}')" class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/20 flex items-center justify-center hover:bg-red-100 dark:hover:bg-red-900/40 transition" title="Hapus">
                            <span class="iconify" data-icon="lucide:trash-2" data-width="14" style="color:#ef4444"></span>
                        </button>
                    </div>`;
              }
            }
        ],
        responsive: true,
        language: {
            search: '',
            searchPlaceholder: 'Cari kandidat...',
            lengthMenu: 'Tampil _MENU_',
            info: 'Menampilkan _START_-_END_ dari _TOTAL_',
            infoEmpty: 'Tidak ada data',
            emptyTable: 'Belum ada kandidat',
            paginate: { previous: '<span class="iconify" data-icon="lucide:chevron-left" data-width="16"></span>', next: '<span class="iconify" data-icon="lucide:chevron-right" data-width="16"></span>' }
        }
    });
});

function openModal(id = null) {
    document.getElementById('candidateModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = id ? 'Edit Kandidat' : 'Tambah Kandidat';
    if (!id) {
        document.getElementById('candidateForm').reset();
        document.getElementById('candidateId').value = '';
        document.getElementById('photoPreview').classList.add('hidden');
    }
}

function closeModal() {
    document.getElementById('candidateModal').classList.add('hidden');
}

function editCandidate(id) {
    $.get('<?= BASE_URL ?>/api/candidates.php?id=' + id, function(res) {
        if (res.success) {
            let c = res.data;
            document.getElementById('candidateId').value = c.id;
            document.getElementById('candidateNumber').value = c.candidate_number;
            document.getElementById('candidateName').value = c.name;
            document.getElementById('candidateVision').value = c.vision || '';
            document.getElementById('candidateMission').value = c.mission || '';
            if (c.photo) {
                document.getElementById('previewImg').src = '<?= BASE_URL ?>/uploads/candidates/' + c.photo;
                document.getElementById('photoPreview').classList.remove('hidden');
            } else {
                document.getElementById('photoPreview').classList.add('hidden');
            }
            openModal(id);
        }
    });
}

function deleteCandidate(id, name) {
    Swal.fire({
        title: 'Hapus Kandidat?',
        text: 'Kandidat "' + name + '" akan dihapus permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= BASE_URL ?>/api/candidates.php?id=' + id,
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

// Photo preview
document.getElementById('candidatePhoto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('photoPreview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

// Form submit
$('#candidateForm').on('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    let id = document.getElementById('candidateId').value;
    
    $.ajax({
        url: '<?= BASE_URL ?>/api/candidates.php' + (id ? '?id=' + id : ''),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
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
