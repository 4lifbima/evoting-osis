<?php
/**
 * Admin Sidebar
 */
$menuItems = [
    ['page' => 'index', 'icon' => 'lucide:layout-dashboard', 'label' => 'Dashboard'],
    ['page' => 'candidates', 'icon' => 'lucide:users', 'label' => 'Kandidat'],
    ['page' => 'voters', 'icon' => 'lucide:user-check', 'label' => 'Pemilih'],
    ['page' => 'settings', 'icon' => 'lucide:settings', 'label' => 'Pengaturan'],
    ['page' => 'results', 'icon' => 'lucide:bar-chart-3', 'label' => 'Hasil Voting'],
    ['page' => 'quick-count', 'icon' => 'lucide:activity', 'label' => 'Quick Count'],
];
?>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed top-0 left-0 z-50 h-full w-64 bg-white dark:bg-slate-800 border-r border-gray-100 dark:border-slate-700 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
    
    <!-- Logo -->
    <div class="h-16 flex items-center gap-3 px-6 border-b border-gray-100 dark:border-slate-700 flex-shrink-0">
        <div class="w-9 h-9 bg-primary-50 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
            <span class="iconify" data-icon="lucide:vote" data-width="20" style="color: #81f224"></span>
        </div>
        <div>
            <h1 class="text-sm font-bold text-gray-900 dark:text-white"><?= APP_NAME ?></h1>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-medium">Admin Panel</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-4 px-3 overflow-y-auto">
        <p class="px-3 mb-3 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Menu</p>
        <ul class="space-y-1">
            <?php foreach ($menuItems as $item): ?>
            <li>
                <a href="<?= BASE_URL ?>/admin/<?= $item['page'] ?>.php"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 <?= $currentPage === $item['page'] ? 'active text-primary-600 dark:text-primary' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' ?>">
                    <span class="iconify flex-shrink-0" data-icon="<?= $item['icon'] ?>" data-width="20"></span>
                    <span><?= $item['label'] ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <!-- Bottom -->
    <div class="p-3 border-t border-gray-100 dark:border-slate-700 flex-shrink-0">
        <a href="<?= BASE_URL ?>/quick-count.php" target="_blank"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-700 transition-all duration-200">
            <span class="iconify" data-icon="lucide:external-link" data-width="20"></span>
            <span>Quick Count Publik</span>
        </a>
        <a href="<?= BASE_URL ?>/logout.php"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
            <span class="iconify" data-icon="lucide:log-out" data-width="20"></span>
            <span>Keluar</span>
        </a>
    </div>
</aside>
