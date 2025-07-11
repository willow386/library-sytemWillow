<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Books</title>
    <link rel="stylesheet" href="style.css" />

</head>
<body>
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
<?php include 'sidebar.php'; ?>
    <main class="content">
  <h2 class="button-like">Return Borrowed Books</h2>
  <div class="return-list">
    <p>You have 1 book to return:</p>
    <ul>
      <li>
        <strong>Gifted Hands</strong> – Due: 2025-07-15
        <button class="btn">Return Now</button>
      </li>
    </ul>
  </div>
</main>
<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('show');
    }  
  </script>  
</body>
</html>