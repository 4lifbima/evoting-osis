<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
header('Content-Type: application/json');
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $votingName = sanitize($_POST['voting_name'] ?? '');
    $period = sanitize($_POST['period'] ?? '');
    $startTime = $_POST['start_time'] ?? '';
    $endTime = $_POST['end_time'] ?? '';
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if (empty($votingName) || empty($period)) {
        jsonResponse(['success' => false, 'message' => 'Data tidak lengkap'], 400);
    }

    $existing = $conn->query("SELECT id FROM voting_settings LIMIT 1")->fetch_assoc();
    if ($existing) {
        $stmt = $conn->prepare("UPDATE voting_settings SET voting_name=?, period=?, start_time=?, end_time=?, is_active=? WHERE id=?");
        $stmt->bind_param("ssssii", $votingName, $period, $startTime, $endTime, $isActive, $existing['id']);
    } else {
        $stmt = $conn->prepare("INSERT INTO voting_settings (voting_name, period, start_time, end_time, is_active) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $votingName, $period, $startTime, $endTime, $isActive);
    }
    $stmt->execute();
    jsonResponse(['success' => true, 'message' => 'Pengaturan berhasil disimpan']);
} else {
    $settings = getVotingSettings();
    jsonResponse(['success' => true, 'data' => $settings]);
}
