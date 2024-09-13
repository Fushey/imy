<?php
session_start();

// Basic routing
$page = $_GET['page'] ?? 'home';

// Check if user is logged in
$loggedIn = isset($_SESSION['user_id']);

// Pages that require authentication
$authRequired = ['dashboard', 'projects', 'create-project'];

// Redirect to login if trying to access auth-required page while not logged in
if (in_array($page, $authRequired) && !$loggedIn) {
    header('Location: index.php?page=login');
    exit();
}

// Redirect to dashboard if logged in user tries to access login or register page
if (($page == 'login' || $page == 'register') && $loggedIn) {
    header('Location: index.php?page=dashboard');
    exit();
}

// Handle logout
if ($page == 'logout') {
    session_destroy();
    header('Location: index.php?page=home');
    exit();
}

// Include the appropriate page
switch ($page) {
    case 'login':
        include 'pages/login.php';
        break;
    case 'register':
        include 'pages/register.php';
        break;
    case 'dashboard':
        include 'pages/dashboard.php';
        break;
        case 'profil':
            include 'pages/profil.php';
            break;
    case 'projects':
        include 'pages/projects.php';
        break;
    case 'virtuelles-homestaging':
            include 'pages/virtuelleshomestaging.php';
            break;
    case 'virtuelle-renovierung':
                include 'pages/virtualrenovation.php';
                break;
    case 'chat':
                    include 'pages/chat.php';
                    break;
    case 'operatorchat':
                    include 'pages/operatorchat.php';
                    break;
    case 'hilfe':
                    include 'pages/hilfe.php';
                    break;
    case '3d-grundriss':
                    include 'pages/3dgrundriss.php';
                    break;            
    case 'services':
            include 'pages/services.php';
            break;
    case 'create-project':
        include 'pages/create-project.php';
        break;
    case 'project-details':
            include 'pages/project-details.php';
            break;
    case 'analytics':
                include 'pages/analytics.php';
                break;        
    case 'aufladen':
                include 'pages/aufladen.php';
                break;

                case 'history':
                    include 'pages/history.php';
                    break;            
    case 'admin':
        // Check if user is admin before allowing access
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
            include 'pages/admin.php';
        } else {
            header('Location: index.php?page=home');
            exit();
        }
        break;
    default:
        include 'home.php';
        break;
}
?>