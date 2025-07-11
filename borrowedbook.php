<?php
session_start();
require_once 'dbconnect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

try {
    $stmt = $pdo->prepare('
        SELECT books.*, borrowed_books.ID AS borrow_id, borrowed_books.returned_at
        FROM books
        LEFT JOIN borrowed_books 
            ON books.ID = borrowed_books.book_id 
            AND borrowed_books.user_id = :user_id 
            AND borrowed_books.returned_at IS NULL
    ');
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $books = [];
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
   <style>
      .btn-borrow {
            background-color: #f44336;
        }
        .btn-borrow:hover {
            background-color: #da190b;
        }
        .btn-return {
            background-color: #ff9800;
        }
        .btn-return:hover {
            background-color: #e68900;
        }
     a{
        text-decoration: none
     }
     
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        td{
            border: none
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
      
   </style>

</head>
<body>
    
<main class = "content">
    <h2 class ="button-like">Your Borrowed Book</h2>
    <table class ="data-table">
        <thead>
            <tr>
                <th>Book Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Copies</th>
                <th>Action</th>
            </tr>
       </thead>
       <tbody>
       <?php foreach ($books as $book): ?>
<tr>
    <td><?php echo htmlspecialchars($book['title']); ?></td>
    <td><?php echo htmlspecialchars($book['author']); ?></td>
    <td><?php echo htmlspecialchars($book['isbn']); ?></td>
    <td><?php echo htmlspecialchars($book['copies']); ?></td>
    <td>
        <div class="actions">
          <?php if ($book['borrow_id']): ?>
    <a href="returnlogic.php?borrow_id=<?php echo $book['borrow_id']; ?>"  class="btn btn-return" >Return</a>
    <?php elseif ($book['copies'] > 0): ?>
    <a href="borrowlogic.php?book_id=<?php echo $book['ID']; ?>" class="btn btn-borrow">Borrow</a>
    <?php else: ?>
    <span style="color: gray;">Unavailable</span>
    <?php endif; ?>

        </div>
    </td>
</tr>
<?php endforeach; ?>

     </tbody>
</table>
</main>
                
 
</body>
</html>