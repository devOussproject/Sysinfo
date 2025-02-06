<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$host = "localhost";
$dbname = "auth_system";
$username = "root"; // user MySQL
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
