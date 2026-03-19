<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
header('Content-Type: application/json');
requireAdmin();

$stats = getStats();
jsonResponse(['success' => true, 'data' => $stats]);
