<?php
session_start();
$errors = [];
$success = '';

// Get errors from session if they exist
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

// Get success message from session if it exists
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <style>
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
        .error-main {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container" id="addbook">
        <h1 class="form-title">Add Book</h1>

        <?php
        // Display success message
        if (!empty($success)) {
            echo '<div class="success">
                    <p>' . htmlspecialchars($success) . '</p>
                  </div>';
        }

        // Display main error (book already exists)
        if (isset($errors['book_exist'])) {
            echo '<div class="error-main">
                    <p>' . htmlspecialchars($errors['book_exist']) . '</p>
                  </div>';
        }
        ?>

        <form method="POST" action="book-account.php">
            <div class="input-group">
                <input type="text" name="title" id="title" placeholder="Title" required>
                <?php
                if (isset($errors['title'])) {
                    echo '<div class="error">
                            <p>' . htmlspecialchars($errors['title']) . '</p>
                          </div>';
                }
                ?>
            </div>

            <div class="input-group">
                <input type="text" name="author" id="author" placeholder="Author" required>
                <?php
                if (isset($errors['author'])) {
                    echo '<div class="error">
                            <p>' . htmlspecialchars($errors['author']) . '</p>
                          </div>';
                }
                ?>
            </div>

            <div class="input-group">
                <input type="text" name="isbn" id="isbn" placeholder="ISBN" required>
                <?php
                if (isset($errors['isbn'])) {
                    echo '<div class="error">
                            <p>' . htmlspecialchars($errors['isbn']) . '</p>
                          </div>';
                }
                ?>
            </div>

            <div class="input-group">
                <input type="number" name="copies" id="copies" placeholder="Number of Copies" min="1" required>
                <?php
                if (isset($errors['copies'])) {
                    echo '<div class="error">
                            <p>' . htmlspecialchars($errors['copies']) . '</p>
                          </div>';
                }
                ?>
            </div>

            <button type="submit" name="addbook" class="btn">Add Book</button>
        </form>
    </div>
</body>
</html>