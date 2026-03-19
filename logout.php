<?php
/**
 * Logout Page
 * E-Voting OSIS Application
 */
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/auth.php';

logoutUser();
header('Location: ' . BASE_URL . '/login.php');
exit;
