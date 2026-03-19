<?php
/**
 * Admin Top Bar
 */
?>
<!-- Main Content Wrapper -->
<div class="lg:ml-64 min-h-screen flex flex-col">
    
    <!-- Top Bar -->
    <header class="h-16 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between px-4 lg:px-6 sticky top-0 z-30 transition-colors duration-300">
        
        <!-- Left: Hamburger + Page Title -->
        <div class="flex items-center gap-3">
            <button onclick="toggleSidebar()" class="lg:hidden w-9 h-9 rounded-lg bg-gray-50 dark:bg-slate-700 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-slate-600 transition">
                <span class="iconify" data-icon="lucide:menu" data-width="20"></span>
            </button>
            <div>
                <h2 class="text-base font-bold text-gray-900 dark:text-white"><?= $pageTitle ?? 'Dashboard' ?></h2>
                <p class="text-xs text-gray-400 dark:text-gray-500 hidden sm:block"><?= $pageSubtitle ?? 'Admin Panel' ?></p>
            </div>
        </div>

        <!-- Right: Theme + User -->
        <div class="flex items-center gap-2">
            <!-- Theme Toggle -->
            <button onclick="toggleTheme()" class="w-9 h-9 rounded-lg bg-gray-50 dark:bg-slate-700 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-slate-600 transition">
                <span class="iconify dark:hidden" data-icon="lucide:moon" data-width="18" style="color: #64748b"></span>
                <span class="iconify hidden dark:inline-block" data-icon="lucide:sun" data-width="18" style="color: #81f224"></span>
            </button>

            <!-- User Dropdown -->
            <div class="relative" id="userDropdown">
                <button onclick="document.getElementById('userMenu').classList.toggle('hidden')" class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                    <div class="w-8 h-8 bg-primary-50 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                        <span class="iconify" data-icon="lucide:user" data-width="16" style="color: #81f224"></span>
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white leading-tight"><?= $currentUser['full_name'] ?></p>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">Administrator</p>
                    </div>
                    <span class="iconify hidden sm:block" data-icon="lucide:chevron-down" data-width="14" style="color: #94a3b8"></span>
                </button>

                <!-- Dropdown Menu -->
                <div id="userMenu" class="hidden absolute right-0 top-full mt-1 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-100 dark:border-slate-700 py-1 z-50">
                    <a href="<?= BASE_URL ?>/logout.php" class="flex items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                        <span class="iconify" data-icon="lucide:log-out" data-width="16"></span>
                        Keluar
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content Start -->
    <main class="flex-1 p-4 lg:p-6">
