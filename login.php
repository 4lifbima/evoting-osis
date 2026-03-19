<?php
/**
 * Login Page
 * E-Voting OSIS Application
 */
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/auth.php';
require_once __DIR__ . '/helpers/functions.php';

// If already logged in, redirect
if (isLoggedIn()) {
    if (isAdmin()) {
        redirect(BASE_URL . '/admin/index.php');
    } else {
        redirect(BASE_URL . '/user/index.php');
    }
}

// Handle login
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                loginUser($user);
                if ($user['role'] === 'admin') {
                    redirect(BASE_URL . '/admin/index.php');
                } else {
                    redirect(BASE_URL . '/user/index.php');
                }
            } else {
                $error = 'Password salah.';
            }
        } else {
            $error = 'Username tidak ditemukan.';
        }
        $stmt->close();
    }
}

$csrfToken = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#81f224',
                            50: '#f3fee5',
                            100: '#e4fdc6',
                            200: '#c9fb93',
                            300: '#a6f554',
                            400: '#81f224',
                            500: '#6ad40f',
                            600: '#50a808',
                            700: '#3f800b',
                            800: '#35650f',
                            900: '#2d5512',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .dark body { background-color: #0f172a; }
    </style>
</head>
<body class="min-h-screen bg-gray-50 dark:bg-slate-900 flex items-center justify-center p-4 transition-colors duration-300">

    <!-- Dark/Light Toggle -->
    <button onclick="toggleTheme()" id="themeToggle" class="fixed top-4 right-4 z-50 w-10 h-10 rounded-full bg-white dark:bg-slate-800 shadow-lg border border-gray-200 dark:border-slate-700 flex items-center justify-center hover:scale-110 transition-transform">
        <span class="iconify dark:hidden" data-icon="lucide:moon" data-width="18" style="color: #334155"></span>
        <span class="iconify hidden dark:inline-block" data-icon="lucide:sun" data-width="18" style="color: #81f224"></span>
    </button>

    <!-- Mobile Frame Container -->
    <div class="w-full max-w-sm mx-auto">
        <!-- Phone Frame -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-slate-700 transition-colors duration-300">
            
            <!-- Status Bar Mockup -->
            <div class="bg-white dark:bg-slate-800 px-6 pt-4 pb-2 flex items-center justify-between transition-colors duration-300">
                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200" id="currentTime"></span>
                <div class="flex items-center gap-1">
                    <span class="iconify" data-icon="lucide:signal" data-width="12" style="color: #64748b"></span>
                    <span class="iconify" data-icon="lucide:wifi" data-width="12" style="color: #64748b"></span>
                    <span class="iconify" data-icon="lucide:battery-full" data-width="12" style="color: #64748b"></span>
                </div>
            </div>

            <!-- Content -->
            <div class="px-8 pb-10 pt-4">
                <!-- Logo / Header -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 mx-auto bg-primary-50 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center mb-4">
                        <span class="iconify" data-icon="lucide:vote" data-width="32" style="color: #81f224"></span>
                    </div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white"><?= APP_NAME ?></h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Silakan masuk untuk melanjutkan</p>
                </div>

                <!-- Error Message -->
                <?php if (!empty($error)): ?>
                <div class="mb-6 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl flex items-center gap-2">
                    <span class="iconify flex-shrink-0" data-icon="lucide:alert-circle" data-width="16" style="color: #ef4444"></span>
                    <span class="text-sm text-red-600 dark:text-red-400"><?= $error ?></span>
                </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" action="" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    
                    <!-- Username -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">Username</label>
                        <div class="relative">
                            <span class="iconify absolute left-3 top-1/2 -translate-y-1/2" data-icon="lucide:user" data-width="18" style="color: #94a3b8"></span>
                            <input type="text" name="username" required autocomplete="username"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                placeholder="Masukkan username">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wide">Password</label>
                        <div class="relative">
                            <span class="iconify absolute left-3 top-1/2 -translate-y-1/2" data-icon="lucide:lock" data-width="18" style="color: #94a3b8"></span>
                            <input type="password" name="password" id="passwordInput" required autocomplete="current-password"
                                class="w-full pl-10 pr-10 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                placeholder="Masukkan password">
                            <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2">
                                <span class="iconify" id="eyeIcon" data-icon="lucide:eye-off" data-width="18" style="color: #94a3b8"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full py-3 bg-primary hover:bg-primary-500 text-gray-900 font-bold rounded-xl text-sm transition-all duration-200 hover:shadow-lg hover:shadow-primary/25 active:scale-[0.98] mt-2 flex items-center justify-center gap-2">
                        <span class="iconify" data-icon="lucide:log-in" data-width="18"></span>
                        Masuk
                    </button>
                </form>

                <!-- Footer -->
                <p class="text-center text-xs text-gray-400 dark:text-gray-600 mt-8">
                    <?= APP_NAME ?> &copy; <?= date('Y') ?>
                </p>
            </div>

            <!-- Home Indicator -->
            <div class="flex justify-center pb-3">
                <div class="w-24 h-1 bg-gray-300 dark:bg-slate-600 rounded-full"></div>
            </div>
        </div>
    </div>

    <script>
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

        // Clock
        function updateClock() {
            const now = new Date();
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Toggle password
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-icon', 'lucide:eye');
            } else {
                input.type = 'password';
                icon.setAttribute('data-icon', 'lucide:eye-off');
            }
        }
    </script>
</body>
</html>
