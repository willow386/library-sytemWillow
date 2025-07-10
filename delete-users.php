<?php
session_start();
require_once 'dbconnect.php';

// Check if user is logged in
if ($_SESSION['user']['role'] !== 'admin') {
    $_SESSION['errors'] = ['unauthorized' => 'Only admins can delete users.'];
    header('Location: view-users.php');
    exit();
}


// Check if user ID is provided
if (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) {
    $_SESSION['errors'] = ['invalid_id' => 'Invalid user ID'];
    header('Location: view-users.php');
    exit();
}

$user_id = $_GET['ID'];

// Prevent user from deleting themselves
if ($user_id == $_SESSION['user_id']) {
    $_SESSION['errors'] = ['self_delete' => 'You cannot delete your own account'];
    header('Location: view-users.php');
    exit();
}

// First, check if user exists
try {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE ID = :ID');
    $stmt->execute(['ID' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        $_SESSION['errors'] = ['not_found' => 'User not found'];
        header('Location: view-users.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['errors'] = ['database' => 'Failed to fetch user details'];
    header('Location: view-users.php');
    exit();
}

// Handle deletion confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    try {
        // Start transaction (in case you want to delete related data later)
        $pdo->beginTransaction();
        
        // Delete the user
        $stmt = $pdo->prepare('DELETE FROM users WHERE ID = :ID');
        $stmt->execute(['ID' => $user_id]);
        
        // Commit transaction
        $pdo->commit();
        
        $_SESSION['success'] = 'User "' . $user['name'] . '" has been deleted successfully!';
        header('Location: view-users.php');
        exit();
    } catch (PDOException $e) {
        // Rollback transaction on error
        $pdo->rollback();
        $_SESSION['errors'] = ['database' => 'Failed to delete user'];
        header('Location: view-users.php');
        exit();
    }
}

// Handle cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_delete'])) {
    header('Location: view-users.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .warning-header {
            text-align: center;
            color: #721c24;
            margin-bottom: 30px;
        }
        .warning-icon {
            font-size: 48px;
            color: #f44336;
            margin-bottom: 20px;
        }
        .user-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #f44336;
        }
        .user-details h3 {
            margin-top: 0;
            color: #333;
        }
        .detail-row {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        .warning-message {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .danger-message {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-danger {
            background-color: #f44336;
            color: white;
        }
        .btn-danger:hover {
            background-color: #da190b;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="warning-header">
            <div class="warning-icon">⚠️</div>
            <h1>Delete User</h1>
        </div>

        <div class="danger-message">
            <strong>Danger:</strong> This action cannot be undone. The user will be permanently deleted from the system.
        </div>

        <div class="warning-message">
            <strong>Note:</strong> If this user has any associated data (borrowed books, etc.), consider the impact before deletion.
        </div>

        <div class="user-details">
            <h3>User Details</h3>
            <div class="detail-row">
                <span class="detail-label">ID:</span> 
                <?php echo htmlspecialchars($user['ID']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Name:</span> 
                <?php echo htmlspecialchars($user['name']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span> 
                <?php echo htmlspecialchars($user['email']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Created:</span> 
                <?php echo htmlspecialchars($user['created_at']); ?>
            </div>
        </div>

        <form method="POST">
            <div class="form-actions">
                <button type="submit" name="cancel_delete" class="btn btn-secondary">
                    Cancel
                </button>
                <button type="submit" name="confirm_delete" class="btn btn-danger">
                    Yes, Delete User
                </button>
            </div>
        </form>
    </div>
</body>
</html>