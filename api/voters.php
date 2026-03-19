<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/functions.php';
header('Content-Type: application/json');
requireAdmin();

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

switch ($method) {
    case 'GET':
        if ($id) {
            $stmt = $conn->prepare("SELECT id, username, full_name, class, has_voted FROM users WHERE id = ? AND role = 'user'");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            jsonResponse(['success' => true, 'data' => $result]);
        } else {
            $result = $conn->query("SELECT id, username, full_name, class, has_voted, created_at FROM users WHERE role = 'user' ORDER BY full_name ASC");
            $data = [];
            while ($row = $result->fetch_assoc()) { $data[] = $row; }
            jsonResponse(['success' => true, 'data' => $data]);
        }
        break;

    case 'POST':
        // Check for reset action
        if (isset($_POST['action']) && $_POST['action'] === 'reset_votes') {
            $conn->query("UPDATE users SET has_voted = 0 WHERE role = 'user'");
            $conn->query("DELETE FROM votes");
            $conn->query("DELETE FROM vote_logs");
            jsonResponse(['success' => true, 'message' => 'Semua data voting berhasil direset']);
            break;
        }

        $username = sanitize($_POST['username'] ?? '');
        $fullName = sanitize($_POST['full_name'] ?? '');
        $password = $_POST['password'] ?? '';
        $class = sanitize($_POST['class'] ?? '');

        if (empty($username) || empty($fullName)) {
            jsonResponse(['success' => false, 'message' => 'Username dan nama harus diisi'], 400);
        }

        if ($id) {
            // Check unique username (exclude self)
            $chk = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
            $chk->bind_param("si", $username, $id);
            $chk->execute();
            if ($chk->get_result()->num_rows > 0) {
                jsonResponse(['success' => false, 'message' => 'Username sudah digunakan'], 400);
            }
            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET username=?, full_name=?, password=?, class=? WHERE id=?");
                $stmt->bind_param("ssssi", $username, $fullName, $hash, $class, $id);
            } else {
                $stmt = $conn->prepare("UPDATE users SET username=?, full_name=?, class=? WHERE id=?");
                $stmt->bind_param("sssi", $username, $fullName, $class, $id);
            }
            $stmt->execute();
            jsonResponse(['success' => true, 'message' => 'Pemilih berhasil diperbarui']);
        } else {
            if (empty($password)) {
                jsonResponse(['success' => false, 'message' => 'Password harus diisi'], 400);
            }
            // Check unique username
            $chk = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $chk->bind_param("s", $username);
            $chk->execute();
            if ($chk->get_result()->num_rows > 0) {
                jsonResponse(['success' => false, 'message' => 'Username sudah digunakan'], 400);
            }
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';
            $stmt = $conn->prepare("INSERT INTO users (username, full_name, password, role, class) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $fullName, $hash, $role, $class);
            $stmt->execute();
            jsonResponse(['success' => true, 'message' => 'Pemilih berhasil ditambahkan']);
        }
        break;

    case 'DELETE':
        if (!$id) jsonResponse(['success' => false, 'message' => 'ID tidak valid'], 400);
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'user'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        jsonResponse(['success' => true, 'message' => 'Pemilih berhasil dihapus']);
        break;

    default:
        jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}
