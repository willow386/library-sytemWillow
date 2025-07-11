<?php
session_start();
require_once 'dbconnect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("
        SELECT 
            books.title, 
            books.author,
            borrowed_books.borrowed_at, 
            borrowed_books.returned_at
        FROM borrowed_books
        JOIN books ON borrowed_books.book_id = books.ID
        WHERE borrowed_books.user_id = :user_id
        ORDER BY borrowed_books.borrowed_at DESC
    ");
    $stmt->execute(['user_id' => $user_id]);
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $history = [];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Borrowing History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .status-returned {
            color: green;
            font-weight: bold;
        }

        .status-pending {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Borrowing History</h2>

<table>
    <thead>
        <tr>
            <th>Book Title</th>
            <th>Author</th>
            <th>Borrowed At</th>
            <th>Returned At</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($history)): ?>
            <tr><td colspan="5" style="text-align:center;">No history found.</td></tr>
        <?php else: ?>
            <?php foreach ($history as $entry): ?>
                <tr>
                    <td><?= htmlspecialchars($entry['title']) ?></td>
                    <td><?= htmlspecialchars($entry['author']) ?></td>
                    <td><?= htmlspecialchars($entry['borrowed_at']) ?></td>
                    <td><?= $entry['returned_at'] ? htmlspecialchars($entry['returned_at']) : '—' ?></td>
                    <td class="<?= $entry['returned_at'] ? 'status-returned' : 'status-pending' ?>">
                        <?= $entry['returned_at'] ? 'Returned' : 'Not Returned' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
</body>
</html>