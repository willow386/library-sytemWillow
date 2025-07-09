<?php
session_start();
require_once 'dbconnect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if book ID is provided
if (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) {
    $_SESSION['errors'] = ['invalid_id' => 'Invalid book ID'];
    header('Location: view-books.php');
    exit();
}

$book_id = $_GET['ID'];

// First, check if book exists
try {
    $stmt = $pdo->prepare('SELECT * FROM books WHERE ID = :ID');
    $stmt->execute(['ID' => $book_id]);
    $book = $stmt->fetch();
    
    if (!$book) {
        $_SESSION['errors'] = ['not_found' => 'Book not found'];
        header('Location: view-books.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['errors'] = ['database' => 'Failed to fetch book details'];
    header('Location: view-books.php');
    exit();
}

// Handle deletion confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    try {
        $stmt = $pdo->prepare('DELETE FROM books WHERE ID = :ID');
        $stmt->execute(['ID' => $book_id]);
        
        $_SESSION['success'] = 'Book "' . $book['title'] . '" has been deleted successfully!';
        header('Location: view-books.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['errors'] = ['database' => 'Failed to delete book'];
        header('Location: view-books.php');
        exit();
    }
}

// Handle cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_delete'])) {
    header('Location: view-books.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Book</title>
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
        .book-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #f44336;
        }
        .book-details h3 {
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
            <h1>Delete Book</h1>
        </div>

        <div class="warning-message">
            <strong>Warning:</strong> This action cannot be undone. The book will be permanently deleted from the system.
        </div>

        <div class="book-details">
            <h3>Book Details</h3>
            <div class="detail-row">
                <span class="detail-label">Title:</span> 
                <?php echo htmlspecialchars($book['title']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Author:</span> 
                <?php echo htmlspecialchars($book['author']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">ISBN:</span> 
                <?php echo htmlspecialchars($book['isbn']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Copies:</span> 
                <?php echo htmlspecialchars($book['copies']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Created:</span> 
                <?php echo htmlspecialchars($book['created_at']); ?>
            </div>
        </div>

        <form method="POST">
            <div class="form-actions">
                <button type="submit" name="cancel_delete" class="btn btn-secondary">
                    Cancel
                </button>
                <button type="submit" name="confirm_delete" class="btn btn-danger">
                    Yes, Delete Book
                </button>
            </div>
        </form>
    </div>
</body>
</html>