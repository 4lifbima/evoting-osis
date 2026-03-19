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
            $stmt = $conn->prepare("SELECT * FROM candidates WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            jsonResponse(['success' => true, 'data' => $result]);
        } else {
            $result = $conn->query("SELECT * FROM candidates ORDER BY candidate_number ASC");
            $data = [];
            while ($row = $result->fetch_assoc()) { $data[] = $row; }
            jsonResponse(['success' => true, 'data' => $data]);
        }
        break;

    case 'POST':
        $candidateNumber = (int)($_POST['candidate_number'] ?? 0);
        $name = sanitize($_POST['name'] ?? '');
        $vision = sanitize($_POST['vision'] ?? '');
        $mission = sanitize($_POST['mission'] ?? '');
        $photo = null;

        if (empty($name) || $candidateNumber < 1) {
            jsonResponse(['success' => false, 'message' => 'Data tidak lengkap'], 400);
        }

        // Handle photo upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($ext, $allowed)) {
                jsonResponse(['success' => false, 'message' => 'Format foto tidak didukung'], 400);
            }
            $photo = 'candidate_' . time() . '_' . uniqid() . '.' . $ext;
            $uploadDir = __DIR__ . '/../uploads/candidates/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photo);
        }

        if ($id) {
            // Update
            if ($photo) {
                // Delete old photo
                $old = $conn->query("SELECT photo FROM candidates WHERE id = $id")->fetch_assoc();
                if ($old && $old['photo'] && file_exists(__DIR__ . '/../uploads/candidates/' . $old['photo'])) {
                    unlink(__DIR__ . '/../uploads/candidates/' . $old['photo']);
                }
                $stmt = $conn->prepare("UPDATE candidates SET candidate_number=?, name=?, vision=?, mission=?, photo=? WHERE id=?");
                $stmt->bind_param("issssi", $candidateNumber, $name, $vision, $mission, $photo, $id);
            } else {
                $stmt = $conn->prepare("UPDATE candidates SET candidate_number=?, name=?, vision=?, mission=? WHERE id=?");
                $stmt->bind_param("isssi", $candidateNumber, $name, $vision, $mission, $id);
            }
            $stmt->execute();
            jsonResponse(['success' => true, 'message' => 'Kandidat berhasil diperbarui']);
        } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO candidates (candidate_number, name, vision, mission, photo) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $candidateNumber, $name, $vision, $mission, $photo);
            $stmt->execute();
            jsonResponse(['success' => true, 'message' => 'Kandidat berhasil ditambahkan']);
        }
        break;

    case 'DELETE':
        if (!$id) jsonResponse(['success' => false, 'message' => 'ID tidak valid'], 400);
        $old = $conn->query("SELECT photo FROM candidates WHERE id = $id")->fetch_assoc();
        if ($old && $old['photo'] && file_exists(__DIR__ . '/../uploads/candidates/' . $old['photo'])) {
            unlink(__DIR__ . '/../uploads/candidates/' . $old['photo']);
        }
        $stmt = $conn->prepare("DELETE FROM candidates WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        jsonResponse(['success' => true, 'message' => 'Kandidat berhasil dihapus']);
        break;

    default:
        jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}
