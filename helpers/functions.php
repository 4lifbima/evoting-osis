<?php
/**
 * General Helper Functions
 * E-Voting OSIS Application
 */

/**
 * Sanitize input
 */
function sanitize(string $input): string
{
    global $conn;
    return htmlspecialchars(mysqli_real_escape_string($conn, trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token
 */
function generateCSRFToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken(string $token): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Set flash message
 */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get flash message
 */
function getFlash(): ?array
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Redirect to URL
 */
function redirect(string $url): void
{
    header("Location: $url");
    exit;
}

/**
 * JSON response
 */
function jsonResponse(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Format date to Indonesian format
 */
function formatDate(string $date, string $format = 'd M Y H:i'): string
{
    $months = [
        'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Apr',
        'May' => 'Mei', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Agu',
        'Sep' => 'Sep', 'Oct' => 'Okt', 'Nov' => 'Nov', 'Dec' => 'Des'
    ];
    $formatted = date($format, strtotime($date));
    return str_replace(array_keys($months), array_values($months), $formatted);
}

/**
 * Get voting settings
 */
function getVotingSettings(): ?array
{
    global $conn;
    $result = $conn->query("SELECT * FROM voting_settings ORDER BY id DESC LIMIT 1");
    return $result->fetch_assoc();
}

/**
 * Check if voting is currently active
 */
function isVotingActive(): bool
{
    $settings = getVotingSettings();
    if (!$settings || !$settings['is_active']) return false;
    
    $now = time();
    $start = strtotime($settings['start_time']);
    $end = strtotime($settings['end_time']);
    
    return $now >= $start && $now <= $end;
}

/**
 * Get total statistics
 */
function getStats(): array
{
    global $conn;
    
    $totalVoters = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='user'")->fetch_assoc()['c'];
    $totalVoted = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='user' AND has_voted=1")->fetch_assoc()['c'];
    $totalCandidates = $conn->query("SELECT COUNT(*) as c FROM candidates")->fetch_assoc()['c'];
    $participation = $totalVoters > 0 ? round(($totalVoted / $totalVoters) * 100, 1) : 0;
    
    return [
        'total_voters' => $totalVoters,
        'total_voted' => $totalVoted,
        'total_not_voted' => $totalVoters - $totalVoted,
        'total_candidates' => $totalCandidates,
        'participation' => $participation
    ];
}
