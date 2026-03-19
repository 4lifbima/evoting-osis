<?php
/**
 * Admin Dashboard
 */
$pageTitle = 'Dashboard';
$pageSubtitle = 'Ringkasan data voting';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
require_once __DIR__ . '/includes/topbar.php';

$stats = getStats();
$settings = getVotingSettings();

// Get candidate results
$candidates = $conn->query("
    SELECT c.*, COALESCE(v.vote_count, 0) as vote_count 
    FROM candidates c 
    LEFT JOIN (SELECT candidate_id, COUNT(*) as vote_count FROM votes GROUP BY candidate_id) v 
    ON c.id = v.candidate_id 
    ORDER BY c.candidate_number ASC
");
$candidateData = [];
while ($row = $candidates->fetch_assoc()) {
    $candidateData[] = $row;
}
?>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 animate-fade-in-up">
    <!-- Total Pemilih -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 rounded-xl flex items-center justify-center">
                <span class="iconify" data-icon="lucide:users" data-width="20" style="color: #3b82f6"></span>
            </div>
            <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Pemilih</span>
        </div>
        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $stats['total_voters'] ?></p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Total terdaftar</p>
    </div>

    <!-- Sudah Memilih -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-primary-50 dark:bg-primary-900/20 rounded-xl flex items-center justify-center">
                <span class="iconify" data-icon="lucide:check-circle-2" data-width="20" style="color: #81f224"></span>
            </div>
            <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Memilih</span>
        </div>
        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $stats['total_voted'] ?></p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Sudah memilih</p>
    </div>

    <!-- Belum Memilih -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-amber-50 dark:bg-amber-900/20 rounded-xl flex items-center justify-center">
                <span class="iconify" data-icon="lucide:clock" data-width="20" style="color: #f59e0b"></span>
            </div>
            <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Pending</span>
        </div>
        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $stats['total_not_voted'] ?></p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Belum memilih</p>
    </div>

    <!-- Partisipasi -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-purple-50 dark:bg-purple-900/20 rounded-xl flex items-center justify-center">
                <span class="iconify" data-icon="lucide:trending-up" data-width="20" style="color: #a855f7"></span>
            </div>
            <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Partisipasi</span>
        </div>
        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $stats['participation'] ?>%</p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tingkat partisipasi</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up">
    <!-- Chart -->
    <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Perolehan Suara</h3>
            <span class="text-xs text-gray-400 dark:text-gray-500"><?= $stats['total_voted'] ?> suara masuk</span>
        </div>
        <div id="votingChart"></div>
    </div>

    <!-- Voting Info -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 transition-colors">
        <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Info Voting</h3>
        
        <?php if ($settings): ?>
        <div class="space-y-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-primary-50 dark:bg-primary-900/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                    <span class="iconify" data-icon="lucide:tag" data-width="14" style="color: #81f224"></span>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Nama Voting</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($settings['voting_name']) ?></p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                    <span class="iconify" data-icon="lucide:calendar" data-width="14" style="color: #3b82f6"></span>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Periode</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($settings['period']) ?></p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-amber-50 dark:bg-amber-900/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                    <span class="iconify" data-icon="lucide:clock" data-width="14" style="color: #f59e0b"></span>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Waktu</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= $settings['start_time'] ? formatDate($settings['start_time']) : '-' ?></p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">s.d. <?= $settings['end_time'] ? formatDate($settings['end_time']) : '-' ?></p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 <?= $settings['is_active'] ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20' ?>">
                    <span class="iconify" data-icon="<?= $settings['is_active'] ? 'lucide:check-circle' : 'lucide:x-circle' ?>" data-width="14" style="color: <?= $settings['is_active'] ? '#22c55e' : '#ef4444' ?>"></span>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Status</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold <?= $settings['is_active'] ? 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400' ?>">
                        <?= $settings['is_active'] ? 'Aktif' : 'Tidak Aktif' ?>
                    </span>
                </div>
            </div>
        </div>
        <?php else: ?>
        <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada pengaturan voting.</p>
        <?php endif; ?>
    </div>
</div>

<script>
// Voting Chart
var chartOptions = {
    series: [{
        name: 'Jumlah Suara',
        data: [<?= implode(',', array_column($candidateData, 'vote_count')) ?>]
    }],
    chart: {
        type: 'bar',
        height: 280,
        toolbar: { show: false },
        fontFamily: 'Plus Jakarta Sans',
        background: 'transparent'
    },
    colors: ['#81f224'],
    plotOptions: {
        bar: {
            borderRadius: 8,
            columnWidth: '50%',
            distributed: true
        }
    },
    dataLabels: {
        enabled: true,
        style: { fontSize: '12px', fontWeight: 700 }
    },
    xaxis: {
        categories: [<?= implode(',', array_map(fn($c) => "'" . addslashes($c['name']) . "'", $candidateData)) ?>],
        labels: { 
            style: { 
                fontSize: '11px',
                colors: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b'
            } 
        },
        axisBorder: { show: false },
        axisTicks: { show: false }
    },
    yaxis: {
        labels: { 
            style: { 
                colors: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b'
            } 
        }
    },
    grid: {
        borderColor: document.documentElement.classList.contains('dark') ? '#1e293b' : '#f1f5f9',
        strokeDashArray: 4
    },
    tooltip: { theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light' },
    legend: { show: false }
};

var chart = new ApexCharts(document.querySelector("#votingChart"), chartOptions);
chart.render();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
