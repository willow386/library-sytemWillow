<?php
session_start();
if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
}else{
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
</head>
<body>
 <nav id="sidebar">
    <ul>
        <li class="active">
            <a href="home.php">
             <span>Home</span>
            </a>
        </li>
        <li class="active">
            <a href="home.php">
            <span>Available Books</span>
            </a>
        </li>
        <li class="active">
            <a href="home.php">
            <span>Borrowed Books</span>
            </a>
        </li>
        <li class="active">
            <a href="home.php">
            <span>Recently Added</span>
            </a>
        </li>
    </ul>
 </nav>
 <main></main>
</body>
</html>