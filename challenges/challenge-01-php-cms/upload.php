<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = getDB();
$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $filename = $file['name'];
        $tmp_name = $file['tmp_name'];
        
        // VULNERABILITY: Insecure file upload
        // Only checks extension, not MIME type or file content
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'php'];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($extension, $allowed_extensions)) {
            // Create user's upload directory
            $user_dir = UPLOAD_DIR . $user_id . '/';
            if (!is_dir($user_dir)) {
                mkdir($user_dir, 0777, true);
            }
            
            // VULNERABILITY: Uses original filename - allows path traversal in filename
            $destination = $user_dir . $filename;
            
            if (move_uploaded_file($tmp_name, $destination)) {
                // Save file info to database
                $stmt = $db->prepare("INSERT INTO files (user_id, filename, filepath, uploaded_at) VALUES (?, ?, ?, NOW())");
                $stmt->execute([$user_id, $filename, $destination]);
                $message = 'File uploaded successfully!';
            } else {
                $error = 'Failed to move uploaded file';
            }
        } else {
            $error = 'File type not allowed. Allowed: ' . implode(', ', $allowed_extensions);
        }
    } else {
        $error = 'File upload error';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload File - CMS</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 50px auto; padding: 20px; }
        .form-group { margin: 15px 0; }
        button { background: #0066cc; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .success { color: green; margin: 10px 0; }
        .error { color: red; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Upload File</h1>
    <a href="index.php">← Back to Dashboard</a>
    
    <?php if ($message): ?>
        <div class="success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Select file:</label>
            <input type="file" name="file" required>
        </div>
        <button type="submit">Upload</button>
    </form>
    
    <p><small>Allowed extensions: jpg, jpeg, png, gif, pdf, txt, php</small></p>
</body>
</html>

