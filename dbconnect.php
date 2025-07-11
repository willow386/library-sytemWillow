<?php
$host = "localhost";
$dbname = "library_db";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}




// class Database {
//     private $host = "localhost";
//     private $dbname = "library_db";
//     private $username = "root";
//     private $password = "";
//     private $pdo;

//     public function __construct() {
//         try {
//             $this->pdo = new PDO(
//                 "mysql:host={$this->host};dbname={$this->dbname}",
//                 $this->username,
//                 $this->password
//             );
//             $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//         } catch (PDOException $e) {
//             die("DB connection failed: " . $e->getMessage());
//         }
//     }

//     public function getConnection() {
//         return $this->pdo;
//     }
// }
