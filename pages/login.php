<?php

$pageTitle = 'Login - ImmoYes';

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: index.php?page=dashboard');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-8">
                <!-- Logo -->
                <div class="flex justify-center mb-8">
                    <img src="static/logo.png" alt="ImmoYes Logo" class="h-16">
                </div>

                <h2 class="text-2xl font-bold text-center text-gray-700 mb-8">Einloggen - ImmoYes</h2>
                
                <div id="status-message" class="px-4 py-3 rounded relative mb-4 hidden" role="alert">
                    <span class="block sm:inline"></span>
                </div>

                <form id="login-form">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            E-Mail
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" name="email" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                            Passwort
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" name="password" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Einloggen
                        </button>
                        <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="index.php?page=signup">
                        Sie haben noch kein Konto?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
// Updated frontend JavaScript
document.getElementById('login-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const statusMessage = document.getElementById('status-message');

    const data = {
        email: email,
        password: password
    };

    statusMessage.textContent = "Sie werden eingeloggt";
    statusMessage.classList.remove('hidden', 'bg-red-100', 'text-red-700', 'bg-green-100', 'text-green-700');
    statusMessage.classList.add('bg-blue-100', 'text-blue-700');

    fetch('https://api.immoyes.com/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
        credentials: 'include'
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        console.log('Login Success:', data);
        // Store the token in localStorage
        localStorage.setItem('token', data.token);
        // Store other user data if needed
        localStorage.setItem('user_id', data.user_id);
        localStorage.setItem('email', data.email);
        
        // Show success message
        statusMessage.textContent = "Anmeldung erfolgreich. Weiterleitung...";
        statusMessage.classList.remove('bg-blue-100', 'text-blue-700');
        statusMessage.classList.add('bg-green-100', 'text-green-700');
        
        // Redirect to dashboard after a short delay
        setTimeout(() => {
            window.location.href = 'index.php?page=dashboard';
        }, 1500);
    })
    .catch((error) => {
        console.error('Error:', error);
        statusMessage.textContent = error.message || "Login fehlgeschlagen. Bitte versuchen Sie es erneut.";
        statusMessage.classList.remove('bg-blue-100', 'text-blue-700');
        statusMessage.classList.add('bg-red-100', 'text-red-700');
    });
});

// Function to get the token (to be used for authenticated requests)
function getToken() {
    return localStorage.getItem('token');
}

// Example of how to use the token in subsequent requests
function makeAuthenticatedRequest(url, method, body) {
    return fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${getToken()}`
        },
        body: JSON.stringify(body)
    })
    .then(response => response.json());
}

// Logout function
function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('user_id');
    localStorage.removeItem('email');
    // Redirect to login page or home page
    window.location.href = 'index.php?page=login';
}
</script>
</body>
</html>
