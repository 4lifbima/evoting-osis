<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/functions.php';
$settings = getVotingSettings();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quick Count - <?= APP_NAME ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config={darkMode:'class',theme:{extend:{colors:{primary:{DEFAULT:'#81f224',50:'#f3fee5',100:'#e4fdc6',200:'#c9fb93',300:'#a6f554',400:'#81f224',500:'#6ad40f',600:'#50a808',700:'#3f800b',800:'#35650f',900:'#2d5512'}},fontFamily:{sans:['Plus Jakarta Sans','sans-serif']}}}}
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<style>*{font-family:'Plus Jakarta Sans',sans-serif}</style>
</head>
<body class="min-h-screen bg-gray-50 dark:bg-slate-900 transition-colors duration-300">

<!-- Navbar -->
<nav class="bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 sticky top-0 z-30">
<div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
<div class="flex items-center gap-3">
<div class="w-9 h-9 bg-primary-50 dark:bg-primary-900/30 rounded-xl flex items-center justify-center">
<span class="iconify" data-icon="lucide:activity" data-width="18" style="color:#81f224"></span>
</div>
<div>
<h1 class="text-sm font-bold text-gray-900 dark:text-white">Quick Count</h1>
<p class="text-[10px] text-gray-400"><?= htmlspecialchars($settings['voting_name'] ?? APP_NAME) ?> - <?= htmlspecialchars($settings['period'] ?? '') ?></p>
</div>
</div>
<div class="flex items-center gap-2">
<div class="flex items-center gap-1.5 px-2.5 py-1 bg-green-50 dark:bg-green-900/20 rounded-lg">
<span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
<span class="text-[10px] font-semibold text-green-600 dark:text-green-400">LIVE</span>
</div>
<button onclick="toggleTheme()" class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-slate-700 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-slate-600 transition">
<span class="iconify dark:hidden" data-icon="lucide:moon" data-width="16" style="color:#64748b"></span>
<span class="iconify hidden dark:inline-block" data-icon="lucide:sun" data-width="16" style="color:#81f224"></span>
</button>
</div>
</div>
</nav>

<div class="max-w-5xl mx-auto px-4 py-6 space-y-6">

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4" id="statsCards"></div>

<!-- Chart -->
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 p-5">
<div class="flex items-center justify-between mb-4">
<h3 class="text-sm font-bold text-gray-900 dark:text-white">Perolehan Suara Real-Time</h3>
<span class="text-xs text-gray-400" id="lastUpdate">-</span>
</div>
<div id="barChart"></div>
</div>

<!-- Candidate Summary -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="candidateCards"></div>

</div>

<footer class="text-center py-4 border-t border-gray-100 dark:border-slate-700 mt-8">
<p class="text-xs text-gray-400">&copy; <?= date('Y') ?> <?= APP_NAME ?></p>
</footer>

<script>
function toggleTheme(){const h=document.documentElement;if(h.classList.contains('dark')){h.classList.remove('dark');localStorage.setItem('theme','light')}else{h.classList.add('dark');localStorage.setItem('theme','dark')}}
(function(){if(localStorage.getItem('theme')==='dark')document.documentElement.classList.add('dark')})();

let chart;
const barColors = ['#81f224','#3b82f6','#f59e0b','#a855f7','#ef4444','#06b6d4','#ec4899','#14b8a6'];

function initChart(candidates){
    let isDark=document.documentElement.classList.contains('dark');
    chart=new ApexCharts(document.querySelector("#barChart"),{
        series:[{name:'Jumlah Suara',data:candidates.map(c=>c.total_votes)}],
        chart:{type:'bar',height:380,toolbar:{show:false},fontFamily:'Plus Jakarta Sans',background:'transparent',
            animations:{enabled:true,easing:'easeinout',speed:800,dynamicAnimation:{enabled:true,speed:500}}},
        colors: barColors.slice(0, candidates.length),
        plotOptions:{bar:{borderRadius:12,columnWidth:'55%',distributed:true,dataLabels:{position:'top'}}},
        dataLabels:{enabled:true,formatter:function(val){return val+' suara'},offsetY:-24,
            style:{fontSize:'13px',fontWeight:700,colors:[isDark?'#e2e8f0':'#1e293b']}},
        xaxis:{categories:candidates.map(c=>c.name),
            labels:{style:{colors:isDark?'#94a3b8':'#64748b',fontSize:'12px',fontWeight:600},trim:true,maxHeight:60},
            axisBorder:{show:false},axisTicks:{show:false}},
        yaxis:{labels:{style:{colors:isDark?'#94a3b8':'#64748b'},formatter:function(val){return Math.floor(val)}},min:0},
        grid:{borderColor:isDark?'#1e293b':'#f1f5f9',strokeDashArray:4,yaxis:{lines:{show:true}},xaxis:{lines:{show:false}}},
        tooltip:{theme:isDark?'dark':'light',y:{formatter:function(val){return val+' suara'}}},
        legend:{show:false},
        states:{hover:{filter:{type:'darken',value:0.15}}}
    });
    chart.render();
}

function fetchData(){
    $.get('<?= BASE_URL ?>/api/quick-count.php',function(res){
        if(!res.success)return;
        if(!chart)initChart(res.candidates);
        // Update chart with latest totals
        chart.updateSeries([{name:'Jumlah Suara',data:res.candidates.map(c=>c.total_votes)}]);
        // Update stats
        let s=res.stats;
        document.getElementById('statsCards').innerHTML=
            '<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-gray-100 dark:border-slate-700"><div class="flex items-center gap-2 mb-2"><span class="iconify" data-icon="lucide:users" data-width="16" style="color:#3b82f6"></span><span class="text-xs text-gray-400">Total Pemilih</span></div><p class="text-xl font-bold text-gray-900 dark:text-white">'+s.total_voters+'</p></div>'+
            '<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-gray-100 dark:border-slate-700"><div class="flex items-center gap-2 mb-2"><span class="iconify" data-icon="lucide:check-circle-2" data-width="16" style="color:#81f224"></span><span class="text-xs text-gray-400">Sudah Memilih</span></div><p class="text-xl font-bold text-gray-900 dark:text-white">'+s.total_voted+'</p></div>'+
            '<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-gray-100 dark:border-slate-700"><div class="flex items-center gap-2 mb-2"><span class="iconify" data-icon="lucide:clock" data-width="16" style="color:#f59e0b"></span><span class="text-xs text-gray-400">Belum Memilih</span></div><p class="text-xl font-bold text-gray-900 dark:text-white">'+s.total_not_voted+'</p></div>'+
            '<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-gray-100 dark:border-slate-700"><div class="flex items-center gap-2 mb-2"><span class="iconify" data-icon="lucide:trending-up" data-width="16" style="color:#a855f7"></span><span class="text-xs text-gray-400">Partisipasi</span></div><p class="text-xl font-bold text-gray-900 dark:text-white">'+s.participation+'%</p></div>';
        // Update candidate cards
        let totalVotes=res.candidates.reduce((a,c)=>a+c.total_votes,0);
        let html='';
        res.candidates.forEach(c=>{
            let pct=totalVotes>0?((c.total_votes/totalVotes)*100).toFixed(1):0;
            html+='<div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 p-5">';
            html+='<div class="flex items-center gap-3 mb-3">';
            html+='<span class="inline-flex items-center justify-center w-8 h-8 bg-primary-50 dark:bg-primary-900/20 rounded-lg text-sm font-bold text-primary-700 dark:text-primary">'+c.candidate_number+'</span>';
            html+='<div><p class="text-sm font-bold text-gray-900 dark:text-white">'+c.name+'</p></div></div>';
            html+='<div class="text-center py-3"><p class="text-3xl font-bold" style="color:#81f224">'+c.total_votes+'</p><p class="text-xs text-gray-400">suara ('+pct+'%)</p></div>';
            html+='<div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-2"><div class="h-2 rounded-full bg-primary transition-all duration-500" style="width:'+pct+'%"></div></div>';
            html+='</div>';
        });
        document.getElementById('candidateCards').innerHTML=html;
        document.getElementById('lastUpdate').textContent='Update: '+res.timestamp;
    });
}

fetchData();
setInterval(fetchData,3000);
</script>
</body>
</html>
