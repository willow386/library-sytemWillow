<?php
session_start();
require_once 'dbconnect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$errors = [];
$success = '';
$book = null;

// Get book ID from URL
if (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) {
    $_SESSION['errors'] = ['invalid_id' => 'Invalid book ID'];
    header('Location: view-books.php');
    exit();
}

$book_id = $_GET['ID'];

// Get errors from session
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

// Get success message from session
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_book'])) {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    $copies = trim($_POST['copies']);

    // Validation
    if (empty($title)) {
        $errors['title'] = 'Title is required';
    }
    if (empty($author)) {
        $errors['author'] = 'Author is required';
    }
    if (empty($isbn)) {
        $errors['isbn'] = 'ISBN is required';
    }
    if (empty($copies) || !is_numeric($copies) || $copies < 1) {
        $errors['copies'] = 'Valid number of copies is required';
    }

    // Check if title already exists (excluding current book)
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id FROM books WHERE title = :title AND ID != :ID');
        $stmt->execute(['title' => $title, 'ID' => $book_id]);
        if ($stmt->fetch()) {
            $errors['title'] = 'A book with this title already exists';
        }
    }

    // If no errors, update the book
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('UPDATE books SET title = :title, author = :author, isbn = :isbn, copies = :copies WHERE ID = :ID');
            $stmt->execute([
                'title' => $title,
                'author' => $author,
                'isbn' => $isbn,
                'copies' => $copies,
                'ID' => $book_id
            ]);

            $_SESSION['success'] = 'Book updated successfully!';
            header('Location: view-books.php');
            exit();
        } catch (PDOException $e) {
            $errors['database'] = 'Failed to update book';
        }
    }
}

// Fetch book details
try {
    $stmt = $pdo->prepare('SELECT * FROM books WHERE ID = :ID');
    $stmt->execute(['ID' => $book_id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
    
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
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
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .input-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .btn {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-secondary {
            background-color: #6c757d;
            margin-right: 10px;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .error {
            color: #721c24;
            background-color: #f8d7da;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 14px;
            margin-top: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="form-title">Edit Book</h1>
            <a href="view-books.php" class="btn btn-secondary">Back to Books</a>
        </div>

        <?php if (!empty($success)): ?>
            <div class="success">
                <p><?php echo htmlspecialchars($success); ?></p>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" 
                       value="<?php echo htmlspecialchars($book['title']); ?>" required>
                <?php if (isset($errors['title'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['title']); ?></div>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <label for="author">Author</label>
                <input type="text" name="author" id="author" 
                       value="<?php echo htmlspecialchars($book['author']); ?>" required>
                <?php if (isset($errors['author'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['author']); ?></div>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <label for="isbn">ISBN</label>
                <input type="text" name="isbn" id="isbn" 
                       value="<?php echo htmlspecialchars($book['isbn']); ?>" required>
                <?php if (isset($errors['isbn'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['isbn']); ?></div>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <label for="copies">Number of Copies</label>
                <input type="number" name="copies" id="copies" min="1" 
                       value="<?php echo htmlspecialchars($book['copies']); ?>" required>
                <?php if (isset($errors['copies'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['copies']); ?></div>
                <?php endif; ?>
            </div>

            <?php if (isset($errors['database'])): ?>
                <div class="error"><?php echo htmlspecialchars($errors['database']); ?></div>
            <?php endif; ?>

            <div class="form-actions">
                <a href="view-books.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="update_book" class="btn">Update Book</button>
            </div>
        </form>
    </div>
</body>
</html>