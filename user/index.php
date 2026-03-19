<?php
$pageTitle = 'Voting';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Check if already voted
$userId = getCurrentUserId();
$userCheck = $conn->query("SELECT has_voted FROM users WHERE id = $userId")->fetch_assoc();
$hasVoted = $userCheck['has_voted'];
$votingActive = isVotingActive();

// Get candidates
$candidates = $conn->query("SELECT * FROM candidates ORDER BY candidate_number ASC");
$candidateList = [];
while ($row = $candidates->fetch_assoc()) { $candidateList[] = $row; }
?>

<!-- Content -->
<div class="flex-1 overflow-y-auto">

<?php if ($hasVoted): ?>
<!-- Already Voted Screen -->
<div class="flex flex-col items-center justify-center min-h-[70vh] px-6 animate-fade-in-up">
    <div class="w-20 h-20 bg-red-50 dark:bg-red-900/20 rounded-full flex items-center justify-center mb-5">
        <span class="iconify" data-icon="lucide:shield-x" data-width="40" style="color:#ef4444"></span>
    </div>
    <h2 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-2">Maaf, Anda Sudah Melakukan Voting</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">Anda hanya dapat memilih satu kali. Terima kasih atas partisipasi Anda.</p>
    <div class="text-center">
        <p class="text-xs text-gray-400 mb-2">Anda akan otomatis keluar dalam</p>
        <div class="w-16 h-16 bg-red-50 dark:bg-red-900/20 rounded-2xl flex items-center justify-center mx-auto">
            <span id="countdown" class="text-2xl font-bold text-red-500">3</span>
        </div>
        <p class="text-[10px] text-gray-400 mt-2">detik</p>
    </div>
</div>
<script>
let c = 3;
let timer = setInterval(function(){
    c--;
    document.getElementById('countdown').textContent = c;
    if(c <= 0){
        clearInterval(timer);
        window.location.href = '<?= BASE_URL ?>/logout.php';
    }
}, 1000);
</script>

<?php elseif (!$votingActive): ?>
<!-- Voting Not Active -->
<div class="flex flex-col items-center justify-center min-h-[70vh] px-6 animate-fade-in-up">
    <div class="w-20 h-20 bg-amber-50 dark:bg-amber-900/20 rounded-full flex items-center justify-center mb-5">
        <span class="iconify" data-icon="lucide:clock" data-width="40" style="color:#f59e0b"></span>
    </div>
    <h2 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-2">Voting Belum Dibuka</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-4">Voting belum dimulai atau sudah selesai. Silakan hubungi panitia untuk informasi lebih lanjut.</p>
    <?php if ($settings): ?>
    <div class="bg-gray-50 dark:bg-slate-700 rounded-xl p-4 w-full max-w-xs text-center">
        <p class="text-xs text-gray-400 mb-1">Jadwal Voting</p>
        <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= $settings['start_time'] ? formatDate($settings['start_time']) : '-' ?></p>
        <p class="text-xs text-gray-400">s.d.</p>
        <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= $settings['end_time'] ? formatDate($settings['end_time']) : '-' ?></p>
    </div>
    <?php endif; ?>
</div>

<?php else: ?>
<!-- Voting Active - Show Candidates -->
<div class="px-4 py-5">
    <!-- Welcome -->
    <div class="mb-5 animate-fade-in-up">
        <p class="text-xs text-gray-400 dark:text-gray-500">Selamat datang,</p>
        <h2 class="text-lg font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($currentUser['full_name']) ?></h2>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5"><?= htmlspecialchars($currentUser['class'] ?? '') ?></p>
    </div>

    <!-- Info -->
    <div class="bg-primary-50 dark:bg-primary-900/20 rounded-xl p-3 mb-5 flex items-start gap-3 animate-fade-in-up animate-delay-1">
        <span class="iconify flex-shrink-0 mt-0.5" data-icon="lucide:info" data-width="16" style="color:#81f224"></span>
        <p class="text-xs text-gray-700 dark:text-gray-300">Silakan pilih <strong>satu kandidat</strong> di bawah ini. Pilihan Anda bersifat rahasia dan tidak dapat diubah.</p>
    </div>

    <!-- Candidate Cards -->
    <div class="space-y-4">
        <?php foreach ($candidateList as $i => $c): ?>
        <div class="candidate-card bg-white dark:bg-slate-700 rounded-2xl border-2 border-gray-100 dark:border-slate-600 overflow-hidden cursor-pointer hover:border-primary transition-all duration-300 animate-fade-in-up animate-delay-<?= min($i + 1, 3) ?>"
             data-id="<?= $c['id'] ?>" onclick="selectCandidate(this, <?= $c['id'] ?>)">
            <div class="flex items-start p-4 gap-4">
                <!-- Photo -->
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 dark:bg-slate-600">
                        <?php if ($c['photo']): ?>
                        <img src="<?= BASE_URL ?>/uploads/candidates/<?= $c['photo'] ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($c['name']) ?>">
                        <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="iconify" data-icon="lucide:user" data-width="28" style="color:#94a3b8"></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-primary-50 dark:bg-primary-900/20 rounded-lg text-xs font-bold text-primary-700 dark:text-primary"><?= $c['candidate_number'] ?></span>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white truncate"><?= htmlspecialchars($c['name']) ?></h3>
                    </div>
                    <?php if ($c['vision']): ?>
                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2"><?= htmlspecialchars($c['vision']) ?></p>
                    <?php endif; ?>
                </div>
                <!-- Radio -->
                <div class="flex-shrink-0 mt-1">
                    <div class="radio-dot w-6 h-6 rounded-full border-2 border-gray-300 dark:border-slate-500 flex items-center justify-center transition-all">
                        <div class="w-3 h-3 rounded-full bg-primary scale-0 transition-transform duration-200"></div>
                    </div>
                </div>
            </div>
            <!-- Expandable Detail -->
            <div class="candidate-detail hidden border-t border-gray-100 dark:border-slate-600 px-4 py-3 bg-gray-50 dark:bg-slate-800">
                <?php if ($c['vision']): ?>
                <div class="mb-2">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">Visi</p>
                    <p class="text-xs text-gray-600 dark:text-gray-300"><?= nl2br(htmlspecialchars($c['vision'])) ?></p>
                </div>
                <?php endif; ?>
                <?php if ($c['mission']): ?>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">Misi</p>
                    <p class="text-xs text-gray-600 dark:text-gray-300"><?= nl2br(htmlspecialchars($c['mission'])) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Submit Button -->
    <div class="mt-6 mb-4 animate-fade-in-up">
        <button id="voteBtn" onclick="submitVote()" disabled
            class="w-full py-3.5 bg-gray-200 dark:bg-slate-700 text-gray-400 font-bold rounded-2xl text-sm transition-all duration-300 flex items-center justify-center gap-2 disabled:cursor-not-allowed">
            <span class="iconify" data-icon="lucide:check-circle" data-width="18"></span>
            Pilih Kandidat Terlebih Dahulu
        </button>
    </div>
</div>
<?php endif; ?>

</div>

<?php if (!$hasVoted && $votingActive): ?>
<script>
let selectedId = null;

function selectCandidate(el, id) {
    selectedId = id;
    // Reset all
    document.querySelectorAll('.candidate-card').forEach(card => {
        card.classList.remove('border-primary','ring-2','ring-primary/20');
        card.classList.add('border-gray-100','dark:border-slate-600');
        card.querySelector('.radio-dot').classList.remove('border-primary');
        card.querySelector('.radio-dot').classList.add('border-gray-300','dark:border-slate-500');
        card.querySelector('.radio-dot .w-3').classList.remove('scale-100');
        card.querySelector('.radio-dot .w-3').classList.add('scale-0');
        card.querySelector('.candidate-detail').classList.add('hidden');
    });
    // Activate selected
    el.classList.remove('border-gray-100','dark:border-slate-600');
    el.classList.add('border-primary','ring-2','ring-primary/20');
    el.querySelector('.radio-dot').classList.remove('border-gray-300','dark:border-slate-500');
    el.querySelector('.radio-dot').classList.add('border-primary');
    el.querySelector('.radio-dot .w-3').classList.remove('scale-0');
    el.querySelector('.radio-dot .w-3').classList.add('scale-100');
    el.querySelector('.candidate-detail').classList.remove('hidden');
    // Enable button
    let btn = document.getElementById('voteBtn');
    btn.disabled = false;
    btn.classList.remove('bg-gray-200','dark:bg-slate-700','text-gray-400','disabled:cursor-not-allowed');
    btn.classList.add('bg-primary','hover:bg-primary-500','text-gray-900','hover:shadow-lg','hover:shadow-primary/25','active:scale-[0.98]');
    btn.innerHTML = '<span class="iconify" data-icon="lucide:check-circle" data-width="18"></span> Konfirmasi Pilihan';
}

function submitVote() {
    if (!selectedId) return;
    Swal.fire({
        title: 'Konfirmasi Pilihan',
        text: 'Apakah Anda yakin dengan pilihan Anda? Pilihan tidak dapat diubah.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#81f224',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Pilih!',
        cancelButtonText: 'Batal',
        customClass: { confirmButton: 'text-gray-900 font-bold' }
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= BASE_URL ?>/api/vote.php', { candidate_id: selectedId }, function(res) {
                if (res.success) {
                    Swal.fire({ icon:'success', title:'Berhasil!', text: res.message, showConfirmButton:false, timer:2000 });
                    setTimeout(function(){ location.reload(); }, 2000);
                } else {
                    Swal.fire({ icon:'error', title:'Gagal', text: res.message });
                }
            }).fail(function(){
                Swal.fire({ icon:'error', title:'Error', text:'Terjadi kesalahan. Silakan coba lagi.' });
            });
        }
    });
}
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
