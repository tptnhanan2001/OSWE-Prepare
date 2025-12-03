<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = getDB();
$user_id = $_SESSION['user_id'];

// Get user's files
$stmt = $db->prepare("SELECT id, filename, filepath, uploaded_at FROM files WHERE user_id = ? ORDER BY uploaded_at DESC");
$stmt->execute([$user_id]);
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Files - CMS</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        a { color: #0066cc; text-decoration: none; }
    </style>
</head>
<body>
    <h1>My Files</h1>
    <a href="index.php">← Back to Dashboard</a>
    
    <?php if (empty($files)): ?>
        <p>No files uploaded yet. <a href="upload.php">Upload a file</a></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Filename</th>
                    <th>Uploaded</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($file['filename']); ?></td>
                        <td><?php echo htmlspecialchars($file['uploaded_at']); ?></td>
                        <td>
                            <a href="download.php?id=<?php echo $file['id']; ?>">Download</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>

