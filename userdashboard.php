<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Dashboard</title>

  <!-- Link your CSS -->
  <link rel="stylesheet" href="style.css" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<body>

  <!-- Load sidebar -->
  <?php include 'sidebar.php'; ?> 

  <!-- Main content beside sidebar -->
  <main class="main-content">
    <div class= "centered-box">
    <h2 class="button-like">Welcome to Your Dashboard</h2>
    <p>Here you can manage your borrowed books, search the catalogue, return books, view your reading history, and more.</p>
    <p>Use the sidebar to navigate through the different sections of your dashboard.</p>
  </main>

  <!-- Toggle sidebar script -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('show');
    }
  </script>
</body>
</html>
