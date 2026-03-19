    </main>
    
    <!-- Footer -->
    <footer class="px-4 lg:px-6 py-4 border-t border-gray-100 dark:border-slate-700">
        <p class="text-xs text-gray-400 dark:text-gray-500 text-center">&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
    </footer>
</div>

<script>
    // Toggle sidebar
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('active');
    }

    // Theme toggle
    function toggleTheme() {
        const html = document.documentElement;
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    }

    // Load saved theme
    (function() {
        const theme = localStorage.getItem('theme');
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        }
    })();

    // Close dropdown on outside click
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('userDropdown');
        const menu = document.getElementById('userMenu');
        if (dropdown && !dropdown.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });

    // Toast notification helper
    function showToast(type, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        Toast.fire({ icon: type, title: message });
    }

    <?php
    $flash = getFlash();
    if ($flash):
    ?>
    showToast('<?= $flash['type'] ?>', '<?= addslashes($flash['message']) ?>');
    <?php endif; ?>
</script>
</body>
</html>
