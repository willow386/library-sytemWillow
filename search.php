<?php

require 'dbconnect.php';

$stmt = $pdo->prepare("SELECT * FROM 'books' WHERE 'title' LIKE ? OR  'author' LIKE ?");
$stmt->execute([
    "%". $_POST['search'] . "%",  "%". $_POST['search'] . "%"
]);
$results = $stmt->fetchAll();