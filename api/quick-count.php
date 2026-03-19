<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
header('Content-Type: application/json');

// Get all candidates with current vote counts
$candidates = $conn->query("
    SELECT c.id, c.candidate_number, c.name, COALESCE(v.total, 0) as total_votes
    FROM candidates c
    LEFT JOIN (SELECT candidate_id, COUNT(*) as total FROM votes GROUP BY candidate_id) v ON c.id = v.candidate_id
    ORDER BY c.candidate_number ASC
");

$data = [];
while ($row = $candidates->fetch_assoc()) {
    // Get timeline data from vote_logs
    $logs = $conn->query("
        SELECT vote_count, DATE_FORMAT(logged_at, '%H:%i:%s') as time 
        FROM vote_logs 
        WHERE candidate_id = {$row['id']} 
        ORDER BY logged_at ASC 
        LIMIT 50
    ");
    $timeline = [];
    while ($log = $logs->fetch_assoc()) {
        $timeline[] = ['time' => $log['time'], 'count' => (int)$log['vote_count']];
    }
    // Always include current count as last point
    if (empty($timeline) && $row['total_votes'] > 0) {
        $timeline[] = ['time' => date('H:i:s'), 'count' => (int)$row['total_votes']];
    }
    $data[] = [
        'id' => $row['id'],
        'candidate_number' => $row['candidate_number'],
        'name' => $row['name'],
        'total_votes' => (int)$row['total_votes'],
        'timeline' => $timeline
    ];
}

$stats = getStats();
jsonResponse([
    'success' => true,
    'candidates' => $data,
    'stats' => $stats,
    'timestamp' => date('Y-m-d H:i:s')
]);
