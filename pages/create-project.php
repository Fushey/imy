<!-- pages/create-project.php -->
<?php
$pageTitle = 'Create New Project';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectName = $_POST['project-name'];
    $projectDescription = $_POST['project-description'];
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO projects (name, description, user_id) VALUES (?, ?, ?)");
    if ($stmt->execute([$projectName, $projectDescription, $userId])) {
        $_SESSION['message'] = "Project created successfully!";
        header('Location: index.php?page=projects');
        exit();
    } else {
        $error = "Failed to create project. Please try again.";
    }
}

$content = 'templates/create-project.html';
include 'layout.php';
?>