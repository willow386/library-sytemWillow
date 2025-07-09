<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Dashboard</title>

  <!-- Link your CSS stylesheet -->
  <link rel="stylesheet" href="style.css" />

  <!-- Link Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body>
  <div class="hamburger" onclick="toggleSidebar()">
  
    <div></div>
    <div></div>
    <div></div>
  </div>

  <div class="dashboard">
    <nav class="sidebar">
      <div class="logo">
        <img src="images/G-EYprpJ_400x400-removebg-preview.png" alt="Logo" />
      </div>

      <h1>User Dashboard</h1>
      <ul>
        <li><a href="/index.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
        <li><a href="borrowedbook.php"><i class="fas fa-book"></i> Borrowed Book</a></li>
        <li><a href="search-catalogue.php"><i class="fas fa-search"></i> Search Catalogue</a></li>
        <li><a href="returnbooks.php"><i class="fas fa-undo"></i> Return Books</a></li>
        <li><a href="readhistory.php"><i class="fas fa-history"></i> Read History</a></li>
        <li><a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
        <li><a href="contactadmin.php"><i class="fas fa-envelope"></i> Contact Admin</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul>
    </nav>

    <main class="content">
      <h2 class =  "button-like">Welcome to Your Dashboard</h2>
      <p>Here you can manage your borrowed books, search the catalogue, return books, view your reading history, and more.</p>
      <p>Use the sidebar to navigate through the different sections of your dashboard.</p>
    </main>
  </div>
  <!-- Link your JavaScript correctly -->
 <script>
 function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('active');
  }


</script>
</body>
</html>
