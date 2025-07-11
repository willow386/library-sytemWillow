<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];

if ($user['role'] !== 'admin') {
    header('Location: userdashboard.php');
    exit();
}

require_once 'dbconnect.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background-color: #f4f4f4;
    }

    .topbar {
      background-color:#ff9800;
      color: white;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .topbar .logo {
      font-size: 1.5rem;
      font-weight: bold;
    }

    .hamburger {
      display: none;
      flex-direction: column;
      cursor: pointer;
    }

    .hamburger div {
      width: 25px;
      height: 3px;
      background-color: white;
      margin: 4px 0;
    }

    .dashboard {
     display: flex;
    }
    .dashboard h2{
        text-align: center;
    }
    .dashboard p {
        text-align: center;
        color: #555;
        font-size: 1.1rem;
        font-weight:bold;
    }
    
    .sidebar {
      width: 250px;
      background-color:#da190b;
      color: white;
      padding: 20px;
      height: 100vh;
      transition: transform 0.3s ease-in-out;
    }

    .sidebar h1 {
      font-size: 1.4rem;
      margin-bottom: 2rem;
      text-align: center;
    }

    .sidebar img {
      width: 100px;
      display: block;
      margin: 0 auto 1rem;
    }

    .sidebar a {
      display: block;
      color: white;
      text-decoration: none;
      margin: 15px 0;
      font-size: 1rem;
      padding: 10px;
      border-radius: 5px;
      transition: background 0.3s;
    }

    .sidebar a:hover {
      background-color: #143D60;
    }

    .content {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;  /* Vertical center */
  align-items: center;      /* Horizontal center */
  text-align: center;
  padding: 2rem;
  background: url('images/admin-bg.jpg') no-repeat center center;
  background-size: cover;
  color: white; /* for readability */
  min-height: 100vh;
}

.button-like {
  display: inline-block;
  background-color:#ff9800; 
  color: #ecf0f1;
  padding: 15px 30px;
  border-radius: 8px;
  font-size: 1.2rem;
  font-weight: bold;
  text-decoration: none;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  transition: background-color 0.3s, transform 0.2s;
  cursor: pointer;
  margin-bottom: 1.5rem;
  text-align: center;
}

.button-like:hover {
  background-color: #34495e;
  transform: translateY(-2px);
}


    .content p {
      font-size: 1.1rem;
      color: #143D60;
      max-width: 600px;
      line-height: 1.6;
    }

    @media (max-width: 768px) {
      .hamburger {
        display: flex;
      }

      .sidebar {
        position: fixed;
        left: -250px;
        top: 0;
        height: 100%;
        z-index: 1000;
        transform: translateX(0);
      }

      .sidebar.active {
        left: 0;
      }

      .dashboard {
        flex-direction: column;
      }

      .content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>
    
  <!-- Top Navigation -->
  <div class="topbar">
    <div class="logo">📚 Admin Panel</div>
    <div class="hamburger" onclick="toggleSidebar()">
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>

  <!-- Main Dashboard Layout -->
  <div class="dashboard">
    <nav class="sidebar">
      <div class="logo">
        <img src="images/G-EYprpJ_400x400-removebg-preview.png" alt="Logo" />
      </div>
      <h1>Admin Panel</h1>
      <a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
      <a href="dashboard.php"><i class="fas fa-cogs"></i> Control Panel</a>
    </nav>

    <main class="content">
      <h2 class="button-like">Hi Admin, Welcome to Your Dashboard</h2>
      <p>Here you can manage books, edit/delete content, manage users, and monitor system activities.</p>
      <p>Use the sidebar to navigate through the different sections of your dashboard.</p>
    </main>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar');
      sidebar.classList.toggle('active');
    }
  </script>

</body>
</html>
