<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = getDB();
$user_id = $_SESSION['user_id'];
$file_id = $_GET['id'] ?? 0;

// VULNERABILITY: IDOR - checks user_id but can be bypassed with SQL injection
// Also has path traversal vulnerability
$stmt = $db->prepare("SELECT filepath, filename FROM files WHERE id = ? AND user_id = ?");
$stmt->execute([$file_id, $user_id]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);

if ($file && file_exists($file['filepath'])) {
    // VULNERABILITY: Path traversal - no validation of filepath
    // Could allow reading files outside upload directory if combined with other vulnerabilities
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file['filename']) . '"');
    readfile($file['filepath']);
    exit;
} else {
    header('HTTP/1.0 404 Not Found');
    echo 'File not found';
    exit;
}

