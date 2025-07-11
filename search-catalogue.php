<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Catalogue</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
</head>
<body>
<?php include 'sidebar.php'; ?>
    <main class="content">
  <h2 class="button-like">Search Catalogue</h2>
  <div class="input-group">
    <input type="text" placeholder="Search by title, author" />
    <button class="btn">search</button>
  </div>
  <div class="book-results">
    <div class="book-card">
      <h3> Think Big</h3>
      <p>Author: Benjamin Carson</p>
      <button class="btn">Borrow</button>
    </div>
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