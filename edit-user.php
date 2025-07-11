<?php
session_start();
require_once 'dbconnect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$errors = [];
$success = '';
$user = null;

// Get user ID from URL
if (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) {
    $_SESSION['errors'] = ['invalid_id' => 'Invalid user ID'];
    header('Location: view-users.php');
    exit();
}

$user_id = $_GET['ID'];

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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validation
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }
    }
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    }

    // Check if email already exists (excluding current user)
    if (empty($errors['email'])) {
        $stmt = $pdo->prepare('SELECT ID FROM users WHERE email = :email AND ID != :ID');
        $stmt->execute(['email' => $email, 'ID' => $user_id]);
        if ($stmt->fetch()) {
            $errors['email'] = 'This email is already registered';
        }
    }

    // Password validation (only if password is provided)
    if (!empty($password) && strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters long';
    }

    // If no errors, update the user
    if (empty($errors)) {
        try {
            if (!empty($password)) {
                // Update with new password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE users SET name = :name, email = :email, password = :password WHERE ID = :ID');
                $stmt->execute([
                    'name' => $name,
                    'email' => $email,
                    'password' => $hashed_password,
                    'ID' => $user_id
                ]);
            } else {
                // Update without changing password
                $stmt = $pdo->prepare('UPDATE users SET name = :name, email = :email WHERE ID = :ID');
                $stmt->execute([
                    'name' => $name,
                    'email' => $email,
                    'ID' => $user_id
                ]);
            }

            $_SESSION['success'] = 'User updated successfully!';
            header('Location: view-users.php');
            exit();
        } catch (PDOException $e) {
            $errors['database'] = 'Failed to update user';
        }
    }


// Fetch user details
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
        input[type="text"], input[type="email"], input[type="password"] {
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
        .password-note {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 10px;
            border-radius: 4px;
            font-size: 14px;
            margin-top: 5px;
        }
        .current-user-badge {
            background-color: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="form-title">
                Edit User
                <?php if ($user['ID'] == $_SESSION['user_id']): ?>
                    <span class="current-user-badge">Current User</span>
                <?php endif; ?>
            </h1>
            <a href="view-users.php" class="btn btn-secondary">Back to Users</a>
        </div>

        <?php if (!empty($success)): ?>
            <div class="success">
                <p><?php echo htmlspecialchars($success); ?></p>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" 
                       value="<?php echo htmlspecialchars($user['name']); ?>" required>
                <?php if (isset($errors['name'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['name']); ?></div>
                <?php endif; ?>
            </div>


            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" 
                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['email']); ?></div>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <label for="password">New Password (optional)</label>
                <input type="password" name="password" id="password" placeholder="Leave blank to keep current password">
                <div class="password-note">
                    Leave this field blank if you don't want to change the password.
                </div>
                <?php if (isset($errors['password'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['password']); ?></div>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <a href="view-users.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="update_user" class="btn">Update User</button>
            </div>
        </form>
    </div>
</body>
</html>