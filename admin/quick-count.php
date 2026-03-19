<?php
$pageTitle = 'Quick Count';
$pageSubtitle = 'Pemantauan suara real-time';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
require_once __DIR__ . '/includes/topbar.php';
?>
<div class="space-y-6 animate-fade-in-up">
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 p-5">
<div class="flex items-center justify-between mb-4">
<h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
<span class="iconify" data-icon="lucide:activity" data-width="18" style="color:#81f224"></span>
Quick Count Real-Time</h3>
<div class="flex items-center gap-2">
<span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
<span class="text-xs text-gray-400">Live</span>
</div>
</div>
<div id="quickCountChart"></div>
</div>
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 p-5">
<h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Ringkasan Suara</h3>
<div id="summaryCards" class="grid grid-cols-2 lg:grid-cols-4 gap-4"></div>
</div>
</div>
<script>
let qcChart;
function initChart(candidates) {
    let series = candidates.map(c => ({ name: c.name, data: [] }));
    let isDark = document.documentElement.classList.contains('dark');
    let opts = {
        series: series,
        chart: { type: 'line', height: 350, toolbar: { show: false }, fontFamily: 'Plus Jakarta Sans', background: 'transparent',
            animations: { enabled: true, easing: 'easeinout', speed: 800, dynamicAnimation: { enabled: true, speed: 350 } }
        },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: { type: 'category', labels: { style: { colors: isDark ? '#94a3b8' : '#64748b', fontSize: '11px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
        yaxis: { labels: { style: { colors: isDark ? '#94a3b8' : '#64748b' } }, min: 0 },
        grid: { borderColor: isDark ? '#1e293b' : '#f1f5f9', strokeDashArray: 4 },
        tooltip: { theme: isDark ? 'dark' : 'light' },
        legend: { position: 'top', fontWeight: 600, labels: { colors: isDark ? '#e2e8f0' : '#334155' } },
        markers: { size: 4, hover: { size: 6 } }
    };
    qcChart = new ApexCharts(document.querySelector("#quickCountChart"), opts);
    qcChart.render();
}
function fetchData() {
    $.get('<?= BASE_URL ?>/api/quick-count.php', function(res) {
        if (res.success) {
            if (!qcChart) initChart(res.candidates);
            let series = res.candidates.map(c => ({ name: c.name, data: c.timeline.map(t => ({ x: t.time, y: t.count })) }));
            qcChart.updateSeries(series);
            let html = '';
            res.candidates.forEach(c => {
                html += '<div class="bg-gray-50 dark:bg-slate-700 rounded-xl p-4 text-center">';
                html += '<p class="text-xs text-gray-400 mb-1">No. ' + c.candidate_number + '</p>';
                html += '<p class="text-sm font-bold text-gray-900 dark:text-white">' + c.name + '</p>';
                html += '<p class="text-2xl font-bold mt-2" style="color:#81f224">' + c.total_votes + '</p>';
                html += '<p class="text-xs text-gray-400">suara</p></div>';
            });
            document.getElementById('summaryCards').innerHTML = html;
        }
    });
}
fetchData();
setInterval(fetchData, 5000);
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
