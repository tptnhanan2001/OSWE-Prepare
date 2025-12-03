<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = getDB();
$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    
    if (!empty($title) && !empty($content)) {
        $stmt = $db->prepare("INSERT INTO posts (user_id, title, content, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$user_id, $title, $content]);
        $message = 'Post created successfully!';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Post - CMS</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        textarea { height: 200px; }
        button { background: #0066cc; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .success { color: green; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Create Post</h1>
    <a href="index.php">← Back to Dashboard</a>
    
    <?php if ($message): ?>
        <div class="success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Title:</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label>Content:</label>
            <textarea name="content" required></textarea>
        </div>
        <button type="submit">Create Post</button>
    </form>
</body>
</html>

