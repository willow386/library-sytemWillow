<?php
require_once 'dbconnect.php';

session_start();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addbook'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $copies = $_POST['copies'];
    $created_at = date('Y-m-d H:i:s');

    // Validation
    if (empty($title)) {
        $errors['title'] = 'Title is required';
    }
    if (empty($author)) {
        $errors['author'] = 'Author is required.';  // Fixed: was $error instead of $errors
    }
    if (empty($isbn)) {
        $errors['isbn'] = 'ISBN is required.';
    }
    if (empty($copies) || !is_numeric($copies) || $copies < 1) {
        $errors['copies'] = 'Valid number of copies is required.';
    }

    // Check if book already exists
    $stmt = $pdo->prepare('SELECT * FROM books WHERE title = :title');
    $stmt->execute(['title' => $title]);
    if ($stmt->fetch()) {
        $errors['book_exist'] = 'Book is already registered';
    }

    // If there are errors, redirect back with errors
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: add-book.php');
        exit();
    }

    // Insert the book into database
    $stmt = $pdo->prepare('INSERT INTO books(ID, title, author, isbn, copies, created_at)
                         VALUES (:ID, :title, :author, :isbn, :copies, :created_at)');  // Fixed: added colon before copies

    $stmt->execute([
        'ID'=>$ID,
        'title' => $title,
        'author' => $author,
        'isbn' => $isbn,
        'copies' => $copies,
        'created_at' => $created_at
    ]);

    // Success - redirect with success message
    $_SESSION['success'] = 'Book added successfully!';
    header('Location: add-book.php');
    exit();
}
?>