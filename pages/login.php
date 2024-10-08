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

                <h2 id="page-title" class="text-2xl font-bold text-center text-gray-700 mb-8">Einloggen - ImmoYes</h2>
                
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

                <form id="password-reset-form" class="hidden">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="reset-email">
                            E-Mail
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="reset-email" type="email" name="email" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Passwort zurücksetzen
                        </button>
                        <a id="back-to-login" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="#">
                            Zurück zum Login
                        </a>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <a id="forgot-password" class="font-bold text-sm text-blue-500 hover:text-blue-800" href="#">
                        Haben Sie Ihr Passwort vergessen?
                    </a>
                </div>
            </div>
        </div>
    </div>

<script>
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
        console.log('Success:', data);
        localStorage.setItem('token', data.token);
        
        return fetch('set_session.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                user_id: data.user_id,
                email: data.email
            })
        });
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            statusMessage.textContent = "Anmeldung erfolgreich. Weiterleitung...";
            statusMessage.classList.remove('bg-blue-100', 'text-blue-700');
            statusMessage.classList.add('bg-green-100', 'text-green-700');
            setTimeout(() => {
                window.location.href = 'index.php?page=dashboard';
            }, 1500);
        } else {
            throw new Error('Failed to set session');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        statusMessage.textContent = error.message || "Login failed. Please try again.";
        statusMessage.classList.remove('bg-blue-100', 'text-blue-700');
        statusMessage.classList.add('bg-red-100', 'text-red-700');
    });
});

document.getElementById('password-reset-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const email = document.getElementById('reset-email').value;
    const statusMessage = document.getElementById('status-message');

    statusMessage.textContent = "Passwort-Reset wird angefordert...";
    statusMessage.classList.remove('hidden', 'bg-red-100', 'text-red-700', 'bg-green-100', 'text-green-700');
    statusMessage.classList.add('bg-blue-100', 'text-blue-700');

    fetch('https://api.immoyes.com/request-password-reset', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        statusMessage.textContent = "Wenn ein Konto mit dieser E-Mail-Adresse existiert, wurden Anweisungen zum Zurücksetzen des Passworts gesendet.";
        statusMessage.classList.remove('bg-blue-100', 'text-blue-700');
        statusMessage.classList.add('bg-green-100', 'text-green-700');
    })
    .catch((error) => {
        console.error('Error:', error);
        statusMessage.textContent = "Es ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.";
        statusMessage.classList.remove('bg-blue-100', 'text-blue-700');
        statusMessage.classList.add('bg-red-100', 'text-red-700');
    });
});

document.getElementById('forgot-password').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('login-form').classList.add('hidden');
    document.getElementById('password-reset-form').classList.remove('hidden');
    document.getElementById('page-title').textContent = "Passwort zurücksetzen - ImmoYes";
});

document.getElementById('back-to-login').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('password-reset-form').classList.add('hidden');
    document.getElementById('login-form').classList.remove('hidden');
    document.getElementById('page-title').textContent = "Einloggen - ImmoYes";
});
</script>
</body>
</html>