<!-- pages/admin.php -->
<?php
$pageTitle = 'Admin Dashboard';

// Ensure user is admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: index.php?page=home');
    exit();
}

// Fetch all users
$stmt = $pdo->query("SELECT id, username, email FROM users");
$users = $stmt->fetchAll();

// Fetch all projects
$stmt = $pdo->query("SELECT p.id, p.name, u.username as user FROM projects p JOIN users u ON p.user_id = u.id");
$projects = $stmt->fetchAll();

ob_start();
include 'templates/admin.html';
$content = ob_get_clean();

include 'layout.php';
?>