<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../helpers/functions.php';
requireUser();
$currentUser = getCurrentUser();
$settings = getVotingSettings();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title><?= $pageTitle ?? 'Voting' ?> - <?= APP_NAME ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                primary: { DEFAULT:'#81f224',50:'#f3fee5',100:'#e4fdc6',200:'#c9fb93',300:'#a6f554',400:'#81f224',500:'#6ad40f',600:'#50a808',700:'#3f800b',800:'#35650f',900:'#2d5512' }
            },
            fontFamily: { sans: ['Plus Jakarta Sans','sans-serif'] }
        }
    }
}
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
*{font-family:'Plus Jakarta Sans',sans-serif}
@keyframes fadeInUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.animate-fade-in-up{animation:fadeInUp .4s ease-out both}
.animate-delay-1{animation-delay:.1s}
.animate-delay-2{animation-delay:.2s}
.animate-delay-3{animation-delay:.3s}
</style>
</head>
<body class="min-h-screen bg-gray-50 dark:bg-slate-900 transition-colors duration-300">
