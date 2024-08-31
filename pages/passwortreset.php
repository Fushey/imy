<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort zurücksetzen - ImmoYes</title>
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

                <h2 class="text-2xl font-bold text-center text-gray-700 mb-8">Neues Passwort setzen</h2>
                
                <div id="status-message" class="px-4 py-3 rounded relative mb-4 hidden" role="alert">
                    <span class="block sm:inline"></span>
                </div>

                <form id="reset-password-form">
                    <input type="hidden" id="reset-token" name="token">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="new-password">
                            Neues Passwort
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="new-password" type="password" name="new-password" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm-password">
                            Passwort bestätigen
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="confirm-password" type="password" name="confirm-password" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Passwort ändern
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the token from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    if (token) {
        document.getElementById('reset-token').value = token;
    } else {
        showMessage("Ungültiger oder fehlender Token. Bitte fordern Sie einen neuen Passwort-Reset-Link an.", "error");
    }
});

document.getElementById('reset-password-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const token = document.getElementById('reset-token').value;
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    if (newPassword !== confirmPassword) {
        showMessage("Die Passwörter stimmen nicht überein.", "error");
        return;
    }

    const data = {
        token: token,
        new_password: newPassword
    };

    showMessage("Passwort wird zurückgesetzt...", "info");

    fetch('https://api.immoyes.com/reset-password', {
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
        showMessage("Ihr Passwort wurde erfolgreich zurückgesetzt. Sie werden zur Login-Seite weitergeleitet.", "success");
        setTimeout(() => {
            window.location.href = 'https://auftrag.immoyes.com/index.php?page=login';
        }, 3000);
    })
    .catch((error) => {
        console.error('Error:', error);
        showMessage(error.message || "Fehler beim Zurücksetzen des Passworts. Bitte versuchen Sie es erneut.", "error");
    });
});

function showMessage(message, type) {
    const statusMessage = document.getElementById('status-message');
    statusMessage.textContent = message;
    statusMessage.classList.remove('hidden', 'bg-blue-100', 'text-blue-700', 'bg-red-100', 'text-red-700', 'bg-green-100', 'text-green-700');
    
    switch(type) {
        case 'error':
            statusMessage.classList.add('bg-red-100', 'text-red-700');
            break;
        case 'success':
            statusMessage.classList.add('bg-green-100', 'text-green-700');
            break;
        default:
            statusMessage.classList.add('bg-blue-100', 'text-blue-700');
    }
}
</script>
</body>
</html>