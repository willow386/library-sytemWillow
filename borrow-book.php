<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Borrowed Books</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      margin: 0;
    }
    .dashboard {
      display: flex;
    }
    .container {
      flex: 1;
      padding: 20px;
    }
    h1 {
      color: #333;
    }
  </style>
</head>
<body>
  <div class="topbar" style="background:#ff9800; padding:1rem 2rem; color:white; display:flex; justify-content:space-between;">
    <div class="logo">📚 Admin Panel</div>
  </div>

  <div class="dashboard">
    <?php include 'admin-sidebar.php'; ?>

    <div class="container">
      <h1>Borrowed Books</h1>
      <p>This page will display a list of all books currently borrowed by users.</p>
      <!-- You can add a table of borrowed books here -->
    </div>
  </div>
</body>
</html>
