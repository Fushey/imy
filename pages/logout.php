<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-8">
                <h2 class="text-2xl font-bold text-center text-gray-700 mb-8">You have been logged out</h2>
                <p class="text-center mb-4">Thank you for using ImmoYes.</p>
                <div class="text-center">
                    <a href="index.php?page=login" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Log In Again
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Clear the token from localStorage
        localStorage.removeItem('token');
    </script>
</body>
</html>