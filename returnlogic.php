<?php
session_start();
require_once 'dbconnect.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['borrow_id'])) {
    header('Location: borrowedbook.php');
    exit();
}

$borrow_id = $_GET['borrow_id'];
$user_id = $_SESSION['user_id'];

try {
    // 1. Get the borrow record
    $stmt = $pdo->prepare("SELECT * FROM borrowed_books WHERE ID = :ID AND user_id = :user_id AND returned_at IS NULL");
    $stmt->execute(['ID' => $borrow_id, 'user_id' => $user_id]);
    $borrow = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$borrow) {
        $_SESSION['errors'] = ['message' => 'Borrow record not found or already returned'];
        header('Location: borrowedbook.php');
        exit();
    }

    // 2. Mark book as returned
    $stmt = $pdo->prepare("UPDATE borrowed_books SET returned_at = NOW() WHERE ID = :ID");
    $stmt->execute(['ID' => $borrow_id]);

    // 3. Increase available copies in books table
    $stmt = $pdo->prepare("UPDATE books SET copies = copies + 1 WHERE ID = :book_id");
    $stmt->execute(['book_id' => $borrow['book_id']]);

    $_SESSION['success'] = "Book returned successfully!";
    header('Location: borrowedbook.php');
    exit();

} catch (PDOException $e) {
    $_SESSION['errors'] = ['message' => 'Error processing return'];
    header('Location: borrowedbook.php');
    exit();
}
