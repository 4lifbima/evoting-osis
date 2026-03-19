<?php
/**
 * Index - Redirect to login
 */
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/auth.php';

if (isLoggedIn()) {
    if (isAdmin()) {
        header('Location: ' . BASE_URL . '/admin/index.php');
    } else {
        header('Location: ' . BASE_URL . '/user/index.php');
    }
} else {
    header('Location: ' . BASE_URL . '/login.php');
}
exit;
