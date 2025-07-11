<?php
session_start();

/**if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];

if ($user['role'] !== 'admin') {
    header('Location: home.php');
    exit(); 
}**/


require_once 'dbconnect.php';


// Get user info
try {
    $stmt = $pdo->prepare('SELECT name, email FROM users WHERE ID = :ID');
    $stmt->execute(['ID' => $_SESSION['user_id']]);
    $current_user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $current_user = ['name' => 'Unknown', 'email' => ''];
}

// Get statistics
try {
    // Count total books
    $stmt = $pdo->prepare('SELECT COUNT(*) as total_books FROM books');
    $stmt->execute();
    $total_books = $stmt->fetchColumn();

    // Count total users
    $stmt = $pdo->prepare('SELECT COUNT(*) as total_users FROM users');
    $stmt->execute();
    $total_users = $stmt->fetchColumn();

    // Count total copies
    $stmt = $pdo->prepare('SELECT SUM(copies) as total_copies FROM books');
    $stmt->execute();
    $total_copies = $stmt->fetchColumn() ?: 0;

    // Get recent books
    $stmt = $pdo->prepare('SELECT title, author, created_at FROM books ORDER BY created_at DESC LIMIT 5');
    $stmt->execute();
    $recent_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get recent users
    $stmt = $pdo->prepare('SELECT name, email, created_at FROM users ORDER BY created_at DESC LIMIT 5');
    $stmt->execute();
    $recent_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $total_books = 0;
    $total_users = 0;
    $total_copies = 0;
    $recent_books = [];
    $recent_users = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .welcome {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-info {
            color: #666;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #4CAF50;
        }
        .stat-label {
            color: #666;
            margin-top: 10px;
        }
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .content-card {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .content-card h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        .recent-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .recent-item:last-child {
            border-bottom: none;
        }
        .recent-title {
            font-weight: bold;
            color: #333;
        }
        .recent-subtitle {
            color: #666;
            font-size: 0.9em;
        }
        .recent-date {
            color: #999;
            font-size: 0.8em;
        }
        .actions {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .actions h3 {
            margin-top: 0;
            color: #333;
        }
        .btn-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .btn {
            padding: 15px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-secondary {
            background-color: #2196F3;
        }
        .btn-secondary:hover {
            background-color: #1976D2;
        }
        .btn-warning {
            background-color: #ff9800;
        }
        .btn-warning:hover {
            background-color: #e68900;
        }
        .btn-danger {
            background-color: #f44336;
        }
        .btn-danger:hover {
            background-color: #da190b;
        }
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }
        .btn-add{
            background-color: #e68900;
        }
        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="welcome">
                <div>
                    <h1>Dashboard</h1>
                    <div class="user-info">
                        Welcome, <?php echo htmlspecialchars($current_user['name']); ?>
                        <br>
                        <?php echo htmlspecialchars($current_user['email']); ?>
                    </div>
                </div>
                <div>
                    <a href="create-admin.php" class="btn btn-add">Create Admin</a>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_books; ?></div>
                <div class="stat-label">Total Books</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_users; ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_copies; ?></div>
                <div class="stat-label">Total Copies</div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="content-grid">
            <div class="content-card">
                <h3>Recent Books</h3>
                <?php if (empty($recent_books)): ?>
                    <div class="no-data">No books found</div>
                <?php else: ?>
                    <?php foreach ($recent_books as $book): ?>
                        <div class="recent-item">
                            <div class="recent-title"><?php echo htmlspecialchars($book['title']); ?></div>
                            <div class="recent-subtitle">by <?php echo htmlspecialchars($book['author']); ?></div>
                            <div class="recent-date">Added: <?php echo htmlspecialchars($book['created_at']); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="content-card">
                <h3>Recent Users</h3>
                <?php if (empty($recent_users)): ?>
                    <div class="no-data">No users found</div>
                <?php else: ?>
                    <?php foreach ($recent_users as $user): ?>
                        <div class="recent-item">
                            <div class="recent-title"><?php echo htmlspecialchars($user['name']); ?></div>
                            <div class="recent-subtitle"><?php echo htmlspecialchars($user['email']); ?></div>
                            <div class="recent-date">Joined: <?php echo htmlspecialchars($user['created_at']); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="actions">
            <h3>Quick Actions</h3>
            <div class="btn-grid">
                <a href="add-book.php" class="btn">Add New Book</a>
                <a href="view-books.php" class="btn btn-secondary">View All Books</a>
                <a href="add-user.php" class="btn btn-warning">Add New User</a>
                <a href="view-users.php" class="btn btn-secondary">View All Users</a>
            </div>
        </div>
    </div>
</body>
</html>