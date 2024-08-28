<?php

$pageTitle = 'Registrieren - ImmoYes';

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: index.php?page=dashboard');
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
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

                <h2 class="text-2xl font-bold text-center text-gray-700 mb-8">Erstellen Sie ein Konto</h2>
                
                <div id="error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
                    <span class="block sm:inline"></span>
                </div>

                <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
                    <span class="block sm:inline"></span>
                </div>

                <form id="signup-form">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                            Benutzername
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" name="username" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            E-Mail
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" name="email" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                            Passwort
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" name="password" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">
                            Passwort bestätigen
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="confirm_password" type="password" name="confirm_password" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Registrieren
                        </button>
                        <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="index.php?page=login">
                            Haben Sie schon ein Konto?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
document.getElementById('signup-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    if (password !== confirmPassword) {
        showError("Passwörter stimmen nicht überein.");
        return;
    }

    const data = {
        username: username,
        email: email,
        password: password
    };

    fetch('https://api.immoyes.com/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        console.log('Success:', data);
        showSuccess("Konto erfolgreich erstellt! Sie können sich jetzt einloggen.");
    })
    .catch((error) => {
        console.error('Error:', error);
        showError(error.message || "Ein Fehler ist bei der Registrierung aufgetreten. Bitte versuchen Sie es später erneut.");
    });
});

function showError(message) {
    const errorDiv = document.getElementById('error-message');
    errorDiv.textContent = message;
    errorDiv.classList.remove('hidden');
    document.getElementById('success-message').classList.add('hidden');
}

function showSuccess(message) {
    const successDiv = document.getElementById('success-message');
    successDiv.textContent = message;
    successDiv.classList.remove('hidden');
    document.getElementById('error-message').classList.add('hidden');
}
</script>
</body>
</html>