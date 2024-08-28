<!-- layout.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImmoYes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 p-4 text-white">
        <div class="container mx-auto flex justify-between">
            <a href="index.php" class="font-bold">ImmoYes</a>
            <ul class="flex space-x-4">
                <li><a href="index.php?page=login">Login</a></li>
                <li><a href="index.php?page=register">Register</a></li>
                <li><a href="index.php?page=dashboard">Dashboard</a></li>
                <li><a href="index.php?page=projects">Projects</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container mx-auto mt-8">
        <?php include $content; ?>
    </div>
</body>
</html>
