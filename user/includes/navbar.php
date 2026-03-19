<div class="max-w-md mx-auto min-h-screen flex flex-col bg-white dark:bg-slate-800 shadow-xl">
<!-- Top Navbar -->
<header class="sticky top-0 z-30 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 px-4 py-3">
<div class="flex items-center justify-between">
<div class="flex items-center gap-3">
<div class="w-9 h-9 bg-primary-50 dark:bg-primary-900/30 rounded-xl flex items-center justify-center">
<span class="iconify" data-icon="lucide:vote" data-width="18" style="color:#81f224"></span>
</div>
<div>
<h1 class="text-sm font-bold text-gray-900 dark:text-white"><?= APP_NAME ?></h1>
<p class="text-[10px] text-gray-400"><?= htmlspecialchars($settings['voting_name'] ?? '') ?></p>
</div>
</div>
<div class="flex items-center gap-2">
<button onclick="toggleTheme()" class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-slate-700 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-slate-600 transition">
<span class="iconify dark:hidden" data-icon="lucide:moon" data-width="16" style="color:#64748b"></span>
<span class="iconify hidden dark:inline-block" data-icon="lucide:sun" data-width="16" style="color:#81f224"></span>
</button>
<a href="<?= BASE_URL ?>/logout.php" class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/20 flex items-center justify-center hover:bg-red-100 transition">
<span class="iconify" data-icon="lucide:log-out" data-width="16" style="color:#ef4444"></span>
</a>
</div>
</div>
</header>
