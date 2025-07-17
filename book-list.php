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
  <title>Book List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- CSS -->
  <link rel="stylesheet" href="style.css" />

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

  <!-- Sidebar -->
  <?php include 'sidebar.php'; ?>

  <!-- Content -->
  <main class="content">
    <h2 class="button-like"><i class="bi bi-book"></i> Your Book List</h2>

    <table class="data-table">
      <thead>
        <tr>
          <th><i class="bi bi-journal"></i> Title</th>
          <th><i class="bi bi-person"></i> Author</th>
          <th><i class="bi bi-upc-scan"></i> ISBN</th>
          <th><i class="bi bi-stack"></i> Copies</th>
          <th><i class="bi bi-gear"></i> Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($books as $book): ?>
          <tr>
            <td><?= htmlspecialchars($book['title']) ?></td>
            <td><?= htmlspecialchars($book['author']) ?></td>
            <td><?= htmlspecialchars($book['isbn']) ?></td>
            <td><?= htmlspecialchars($book['copies']) ?></td>
            <td>
              <div class="actions">
                <?php if ($book['borrow_id']): ?>
                  <a href="returnlogic.php?borrow_id=<?= $book['borrow_id'] ?>" class="btn btn-return">
                    <i class="bi bi-arrow-return-left"></i> Return
                  </a>
                <?php elseif ($book['copies'] > 0): ?>
                  <a href="borrowlogic.php?book_id=<?= $book['ID'] ?>" class="btn btn-borrow">
                    <i class="bi bi-bookmark-plus"></i> Borrow
                  </a>
                <?php else: ?>
                  <span style="color: gray;">Unavailable</span>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <main class="content">
  <!-- Your page content -->

  <!-- Place this at the very end -->
  <div class="navigation-buttons">
    <a href="userdashboard.php" class="btn btn-nav">
      <i class="bi bi-house-door-fill"></i> Dashboard
    </a>
  </div>
</main>

  </main>

  <script>
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar');
      sidebar.classList.toggle('show');
    }
  </script>
</body>
</html>
