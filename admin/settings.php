<?php
$pageTitle = 'Pengaturan';
$pageSubtitle = 'Konfigurasi voting';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
require_once __DIR__ . '/includes/topbar.php';
$settings = getVotingSettings();
?>
<div class="max-w-2xl animate-fade-in-up">
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden">
<div class="p-5 border-b border-gray-100 dark:border-slate-700">
<h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
<span class="iconify" data-icon="lucide:settings" data-width="18" style="color:#81f224"></span>
Pengaturan Voting</h3>
<p class="text-xs text-gray-400 mt-1">Atur nama, periode, dan waktu pelaksanaan voting</p>
</div>
<form id="settingsForm" class="p-5 space-y-5">
<div>
<label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">Nama Voting</label>
<input type="text" name="voting_name" value="<?= htmlspecialchars($settings['voting_name'] ?? '') ?>" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
</div>
<div>
<label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">Periode</label>
<input type="text" name="period" value="<?= htmlspecialchars($settings['period'] ?? '') ?>" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
<div>
<label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">Waktu Mulai</label>
<input type="datetime-local" name="start_time" value="<?= $settings['start_time'] ? date('Y-m-d\TH:i', strtotime($settings['start_time'])) : '' ?>" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
</div>
<div>
<label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">Waktu Selesai</label>
<input type="datetime-local" name="end_time" value="<?= $settings['end_time'] ? date('Y-m-d\TH:i', strtotime($settings['end_time'])) : '' ?>" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
</div>
</div>
<div>
<label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">Status Voting</label>
<div class="flex items-center gap-3">
<label class="relative inline-flex items-center cursor-pointer">
<input type="checkbox" name="is_active" id="votingActive" value="1" <?= ($settings['is_active'] ?? 0) ? 'checked' : '' ?> class="sr-only peer">
<div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
</label>
<span class="text-sm text-gray-600 dark:text-gray-400" id="statusLabel"><?= ($settings['is_active'] ?? 0) ? 'Voting Aktif' : 'Voting Tidak Aktif' ?></span>
</div>
</div>
<div class="pt-3 border-t border-gray-100 dark:border-slate-700">
<button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary-500 text-gray-900 font-semibold rounded-xl text-sm transition-all hover:shadow-lg hover:shadow-primary/25 active:scale-[0.98]">
<span class="iconify" data-icon="lucide:save" data-width="16"></span>Simpan Pengaturan</button>
</div>
</form>
</div>
</div>
<script>
$('#votingActive').on('change', function(){
    document.getElementById('statusLabel').textContent = this.checked ? 'Voting Aktif' : 'Voting Tidak Aktif';
});
$('#settingsForm').on('submit', function(e){
    e.preventDefault();
    let formData = $(this).serialize();
    if(!$('#votingActive').is(':checked')) formData += '&is_active=0';
    $.post('<?= BASE_URL ?>/api/settings.php', formData, function(res){
        if(res.success) showToast('success', res.message);
        else showToast('error', res.message);
    }).fail(function(){ showToast('error', 'Terjadi kesalahan server'); });
});
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
