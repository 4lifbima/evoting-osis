<?php
$pageTitle = 'Hasil Voting';
$pageSubtitle = 'Rekapitulasi hasil pemilihan';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
require_once __DIR__ . '/includes/topbar.php';

$stats = getStats();
$candidates = $conn->query("
    SELECT c.*, COALESCE(v.vote_count, 0) as vote_count 
    FROM candidates c 
    LEFT JOIN (SELECT candidate_id, COUNT(*) as vote_count FROM votes GROUP BY candidate_id) v ON c.id = v.candidate_id 
    ORDER BY vote_count DESC
");
$candidateData = [];
while ($row = $candidates->fetch_assoc()) { $candidateData[] = $row; }
$maxVotes = !empty($candidateData) ? max(array_column($candidateData, 'vote_count')) : 0;
?>
<div class="space-y-6 animate-fade-in-up">
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 p-5">
<div class="flex items-center justify-between mb-4">
<h3 class="text-sm font-bold text-gray-900 dark:text-white">Perolehan Suara</h3>
<span class="text-xs text-gray-400"><?= $stats['total_voted'] ?> dari <?= $stats['total_voters'] ?> suara (<?= $stats['participation'] ?>%)</span>
</div>
<div class="space-y-4">
<?php foreach ($candidateData as $c): 
$pct = $stats['total_voted'] > 0 ? round(($c['vote_count'] / $stats['total_voted']) * 100, 1) : 0;
$isWinner = $c['vote_count'] == $maxVotes && $maxVotes > 0;
?>
<div class="flex items-center gap-4">
<div class="flex-shrink-0 w-12 h-12 rounded-xl overflow-hidden bg-gray-100 dark:bg-slate-700 flex items-center justify-center">
<?php if ($c['photo']): ?>
<img src="<?= BASE_URL ?>/uploads/candidates/<?= $c['photo'] ?>" class="w-full h-full object-cover">
<?php else: ?>
<span class="iconify" data-icon="lucide:user" data-width="20" style="color:#94a3b8"></span>
<?php endif; ?>
</div>
<div class="flex-1 min-w-0">
<div class="flex items-center gap-2 mb-1">
<span class="text-sm font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($c['name']) ?></span>
<span class="text-xs text-gray-400">No. <?= $c['candidate_number'] ?></span>
<?php if ($isWinner): ?>
<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary-50 dark:bg-primary-900/20 text-xs font-bold text-primary-700 dark:text-primary">
<span class="iconify" data-icon="lucide:crown" data-width="12"></span>Unggul</span>
<?php endif; ?>
</div>
<div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-2.5">
<div class="h-2.5 rounded-full transition-all duration-500 <?= $isWinner ? 'bg-primary' : 'bg-gray-300 dark:bg-slate-500' ?>" style="width:<?= $pct ?>%"></div>
</div>
<div class="flex justify-between mt-1">
<span class="text-xs text-gray-400"><?= $c['vote_count'] ?> suara</span>
<span class="text-xs font-semibold <?= $isWinner ? 'text-primary-600 dark:text-primary' : 'text-gray-500' ?>"><?= $pct ?>%</span>
</div>
</div>
</div>
<?php endforeach; ?>
<?php if (empty($candidateData)): ?>
<p class="text-sm text-gray-400 text-center py-8">Belum ada data kandidat</p>
<?php endif; ?>
</div>
</div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
