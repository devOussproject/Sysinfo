<?php
include 'config.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM login_logs ORDER BY login_time DESC");
$logs = $stmt->fetchAll();
?>
