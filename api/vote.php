<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
header('Content-Type: application/json');
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$userId = getCurrentUserId();
$candidateId = (int)($_POST['candidate_id'] ?? 0);

// Check if already voted
$user = $conn->query("SELECT has_voted FROM users WHERE id = $userId")->fetch_assoc();
if ($user['has_voted']) {
    jsonResponse(['success' => false, 'message' => 'Anda sudah melakukan voting']);
}

// Check voting active
if (!isVotingActive()) {
    jsonResponse(['success' => false, 'message' => 'Voting belum dibuka atau sudah ditutup']);
}

// Check candidate exists
$candidate = $conn->prepare("SELECT id FROM candidates WHERE id = ?");
$candidate->bind_param("i", $candidateId);
$candidate->execute();
if ($candidate->get_result()->num_rows === 0) {
    jsonResponse(['success' => false, 'message' => 'Kandidat tidak valid'], 400);
}

// Insert vote
$conn->begin_transaction();
try {
    $stmt = $conn->prepare("INSERT INTO votes (user_id, candidate_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $candidateId);
    $stmt->execute();

    $conn->query("UPDATE users SET has_voted = 1 WHERE id = $userId");

    // Log for timeline
    $voteCount = $conn->query("SELECT COUNT(*) as c FROM votes WHERE candidate_id = $candidateId")->fetch_assoc()['c'];
    $stmt2 = $conn->prepare("INSERT INTO vote_logs (candidate_id, vote_count) VALUES (?, ?)");
    $stmt2->bind_param("ii", $candidateId, $voteCount);
    $stmt2->execute();

    $conn->commit();
    $_SESSION['has_voted'] = 1;
    jsonResponse(['success' => true, 'message' => 'Terima kasih! Suara Anda telah tercatat.']);
} catch (Exception $e) {
    $conn->rollback();
    jsonResponse(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan vote'], 500);
}
