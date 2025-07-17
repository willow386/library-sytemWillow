<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Dashboard</title>

  <!-- General CSS -->
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />


  <!-- Font Awesome & Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

  <!-- Sidebar -->
  <?php include 'sidebar.php'; ?> 

  <!-- Main Content -->
  <main class="main-content">
    <div class="centered-box">
      <h2 class="button-like">
        <i class="bi bi-speedometer2"></i> Welcome to Your Dashboard
      </h2>
      <p>Here you can manage your borrowed books, search the catalogue, return books, view your reading history, and more.</p>
      <p>Use the sidebar to navigate through the different sections of your dashboard.</p>

      <!-- Optional: Summary Stats (future use) -->
      <div class="cards-container">
        <div class="card">
          <i class="bi bi-journal-bookmark"></i>
          <h3>120</h3>
          <p>Total Books</p>
        </div>
        <div class="card">
          <i class="bi bi-bookmark-check"></i>
          <h3>5</h3>
          <p>Currently Borrowed</p>
        </div>
        <div class="card">
          <i class="bi bi-arrow-return-left"></i>
          <h3>18</h3>
          <p>Returned Books</p>
        </div>
      </div>
    </div>
  </main>

  <!-- Toggle Sidebar Script -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('show');
    }
  </script>
</body>
</html>
