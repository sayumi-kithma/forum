<?php
// Database connection settings — edit these to match your local setup
define('DB_HOST', 'localhost');
define('DB_NAME', 'forum_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // set your MySQL password if you have one

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

session_start();

// Helper: is a user currently logged in?
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Helper: escape output safely
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
