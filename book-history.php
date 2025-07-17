<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book History</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Styles -->
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
    <h2 class="button-like"><i class="bi bi-clock-history"></i> Your Book History</h2>

    <div class="notification-card">
      <p><i class="bi bi-exclamation-circle text-warning"></i> You have <strong>1</strong> book to return:</p>
      <ul style="list-style: none; padding-left: 0;">
        <li style="margin-top: 10px;">
          <strong>📚 Gifted Hands</strong> – Due: <span style="color: #e67e22;">2025-07-15</span>
          <br><br>
          <a href="#" class="btn btn-return">
            <i class="bi bi-arrow-return-left"></i> Return Now
          </a>
        </li>
      </ul>
    </div>
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

  <!-- Sidebar Toggle Script -->
  <script>
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar');
      sidebar.classList.toggle('show');
    }
  </script>
</body>
</html>
