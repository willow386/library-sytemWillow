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
  <title>User History</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- CSS -->
  <link rel="stylesheet" href="style.css" />

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

  
  <?php include 'sidebar.php'; ?>


  <main class="content">
   
    <h2 class="button-like"><i class="bi bi-clock-history"></i> User Borrowing History</h2>

    <table class="data-table">
      <thead>
        <tr>
          <th><i class="bi bi-journal-text"></i> Title</th>
          <th><i class="bi bi-person"></i> Author</th>
          <th><i class="bi bi-calendar-check"></i> Borrowed At</th>
          <th><i class="bi bi-calendar-check-fill"></i> Returned At</th>
          <th><i class="bi bi-activity"></i> Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($history)): ?>
          <tr><td colspan="5" style="text-align:center;">No history found.</td></tr>
        <?php else: ?>
          <?php foreach ($history as $entry): ?>
            <?php
              $returned = !empty($entry['returned_at']);
              $statusClass = $returned ? 'status-returned' : 'status-pending';
              $statusText = $returned ? 'Returned' : 'Not Returned';
            ?>
            <tr>
              <td><?= htmlspecialchars($entry['title']) ?></td>
              <td><?= htmlspecialchars($entry['author']) ?></td>
              <td><?= htmlspecialchars($entry['borrowed_at']) ?></td>
              <td><?= $returned ? htmlspecialchars($entry['returned_at']) : '—' ?></td>
              <td><span class="badge <?= $statusClass ?>"><?= $statusText ?></span></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
    <main class="content">
  


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
