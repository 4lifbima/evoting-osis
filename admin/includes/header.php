<?php
/**
 * Admin Header Include
 * Contains <head> section with all CSS/JS dependencies
 */
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../helpers/functions.php';

requireAdmin();

$currentUser = getCurrentUser();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Dashboard' ?> - Admin <?= APP_NAME ?></title>
    
    <!-- TailwindCSS CDN -->
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

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Iconify -->
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }

        /* DataTables Override */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            outline: none;
        }
        .dark .dataTables_wrapper .dataTables_length select,
        .dark .dataTables_wrapper .dataTables_filter input {
            background-color: #1e293b;
            border-color: #334155;
            color: #e2e8f0;
        }
        .dark table.dataTable tbody tr { background-color: #0f172a; }
        .dark table.dataTable tbody tr:hover { background-color: #1e293b !important; }
        .dark table.dataTable thead th { background-color: #1e293b; color: #e2e8f0; border-bottom-color: #334155; }
        .dark .dataTables_wrapper .dataTables_info,
        .dark .dataTables_wrapper .dataTables_length label,
        .dark .dataTables_wrapper .dataTables_filter label { color: #94a3b8; }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button { color: #94a3b8 !important; }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current { 
            background: #81f224 !important; 
            border-color: #81f224 !important;
            color: #0f172a !important;
        }
        table.dataTable tbody td { border-bottom: 1px solid #f1f5f9; padding: 12px 16px; }
        .dark table.dataTable tbody td { border-bottom-color: #1e293b; }
        table.dataTable thead th { border-bottom: 2px solid #e2e8f0; padding: 12px 16px; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }

        /* Sidebar transitions */
        .sidebar-link.active { background-color: rgba(129, 242, 36, 0.1); color: #81f224; border-right: 3px solid #81f224; }
        .dark .sidebar-link.active { background-color: rgba(129, 242, 36, 0.15); }
        .sidebar-link:hover { background-color: rgba(129, 242, 36, 0.05); }

        /* Mobile overlay */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 40; }
        .sidebar-overlay.active { display: block; }

        /* Animation */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.3s ease-out; }
        @keyframes slideIn { from { transform: translateX(-100%); } to { transform: translateX(0); } }
    </style>
</head>
<body class="bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
