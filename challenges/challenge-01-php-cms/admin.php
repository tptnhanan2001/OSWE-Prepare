<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// VULNERABILITY: Business Logic Flaw - checks role from session, not database
// If we can manipulate session or bypass authentication, we can access admin panel
if ($_SESSION['role'] !== 'admin') {
    die('Access denied. Admin only.');
}

$db = getDB();

// Get flag from database
$stmt = $db->query("SELECT secret_flag FROM flags LIMIT 1");
$flag = $stmt->fetch(PDO::FETCH_ASSOC);

// Get all users
$users = $db->query("SELECT id, username, role FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - CMS</title>
    <style>
        body { font-family: Arial; max-width: 1000px; margin: 50px auto; padding: 20px; }
        .flag { background: #f0f0f0; padding: 20px; margin: 20px 0; border-radius: 5px; font-family: monospace; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Admin Panel</h1>
    <a href="index.php">← Back to Dashboard</a>
    
    <h2>Secret Flag</h2>
    <div class="flag">
        <?php echo htmlspecialchars($flag['secret_flag']); ?>
    </div>
    
    <h2>All Users</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

