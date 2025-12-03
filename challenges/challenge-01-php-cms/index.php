<?php
require_once 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = getDB();
$user_id = $_SESSION['user_id'];

// Get user info
$stmt = $db->prepare("SELECT username, role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get user's posts
$stmt = $db->prepare("SELECT id, title, content, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>CMS Dashboard</title>
    <style>
        body { font-family: Arial; max-width: 1200px; margin: 50px auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .post { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .role { color: #666; font-size: 0.9em; }
        .admin { color: #d00; font-weight: bold; }
        a { color: #0066cc; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CMS Dashboard</h1>
        <div>
            <span>Welcome, <?php echo htmlspecialchars($user['username']); ?></span>
            <span class="role <?php echo $user['role'] === 'admin' ? 'admin' : ''; ?>">
                (<?php echo htmlspecialchars($user['role']); ?>)
            </span>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <nav>
        <a href="index.php">Home</a> |
        <a href="create_post.php">Create Post</a> |
        <a href="upload.php">Upload File</a> |
        <a href="files.php">My Files</a>
        <?php if ($user['role'] === 'admin'): ?>
            | <a href="admin.php">Admin Panel</a>
        <?php endif; ?>
    </nav>

    <h2>My Posts</h2>
    <?php if (empty($posts)): ?>
        <p>No posts yet. <a href="create_post.php">Create your first post</a></p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                <small>Created: <?php echo htmlspecialchars($post['created_at']); ?></small>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>

