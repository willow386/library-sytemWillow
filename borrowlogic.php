<?php
session_start();
require_once 'dbconnect.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['book_id'])) {
    header('Location: user-books.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$book_id = $_GET['book_id'];

try {
    // 1. Check if the book exists and has available copies
    $stmt = $pdo->prepare("SELECT * FROM books WHERE ID = :ID AND copies > 0");
    $stmt->execute(['ID' => $book_id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        $_SESSION['errors'] = ['message' => 'Book not available or out of stock'];
        header('Location: borrowedbook.php');
        exit();
    }

    // 2. Check if the user already borrowed this book and hasn't returned it
    $stmt = $pdo->prepare("SELECT * FROM borrowed_books WHERE user_id = :user_id AND book_id = :book_id AND returned_at IS NULL");
    $stmt->execute(['user_id' => $user_id, 'book_id' => $book_id]);
    if ($stmt->fetch()) {
        $_SESSION['errors'] = ['message' => 'You already borrowed this book'];
        header('Location: borrowedbook.php');
        exit();
    }

    // 3. Insert into borrowed_books
    $stmt = $pdo->prepare("INSERT INTO borrowed_books (user_id, book_id, borrowed_at) VALUES (:user_id, :book_id, NOW())");
    $stmt->execute(['user_id' => $user_id, 'book_id' => $book_id]);

    // 4. Reduce book copies
    $stmt = $pdo->prepare("UPDATE books SET copies = copies - 1 WHERE ID = :ID");
    $stmt->execute(['ID' => $book_id]);

    $_SESSION['success'] = "Book borrowed successfully!";
    header('Location: borrowedbook.php');
    exit();

} catch (PDOException $e) {
    $_SESSION['errors'] = ['message' => 'Error processing borrow'];
    header('Location: borrowedbook.php');
    exit();
}
