<!-- pages/projects.php -->
<?php
$pageTitle = 'My Projects';

// Fetch user's projects from the database
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM projects WHERE user_id = ?");
$stmt->execute([$userId]);
$projects = $stmt->fetchAll();

ob_start();
include 'templates/projects.html';
$content = ob_get_clean();

include 'layout.php';
?>