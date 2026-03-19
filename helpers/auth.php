<?php
/**
 * Authentication Helpers
 * E-Voting OSIS Application
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if current user is admin
 */
function isAdmin(): bool
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Get current user ID
 */
function getCurrentUserId(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user data from session
 */
function getCurrentUser(): array
{
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'full_name' => $_SESSION['full_name'] ?? null,
        'role' => $_SESSION['role'] ?? null,
        'class' => $_SESSION['class'] ?? null,
        'has_voted' => $_SESSION['has_voted'] ?? 0
    ];
}

/**
 * Require login - redirect to login if not authenticated
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

/**
 * Require admin role
 */
function requireAdmin(): void
{
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . BASE_URL . '/user/index.php');
        exit;
    }
}

/**
 * Require user role
 */
function requireUser(): void
{
    requireLogin();
    if (isAdmin()) {
        header('Location: ' . BASE_URL . '/admin/index.php');
        exit;
    }
}

/**
 * Login user - set session data
 */
function loginUser(array $user): void
{
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['class'] = $user['class'];
    $_SESSION['has_voted'] = $user['has_voted'];
    $_SESSION['login_time'] = time();
}

/**
 * Logout user
 */
function logoutUser(): void
{
    session_unset();
    session_destroy();
}
