<?php
session_start();

$flask_api_url = 'https://api.immoyes.com';  // Update this with your Flask app's URL

function callAPI($endpoint, $data, $headers = []) {
    global $flask_api_url;
    $ch = curl_init($flask_api_url . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $result = json_decode($response, true);
    if ($httpCode >= 400) {
        error_log("API Error ($httpCode): " . print_r($result, true));
    }
    return $result;
}

$message = '';
$token = isset($_GET['token']) ? $_GET['token'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_password'])) {
    $result = callAPI('/complete-reset', [
        'token' => $token,
        'new_password' => $_POST['new_password']
    ]);
    if (isset($result['message'])) {
        $message = $result['message'];
    } else {
        $message = "Error resetting password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Songfinder Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-6 flex flex-col justify-center sm:py-12">
    <div class="relative py-3 sm:max-w-xl sm:mx-auto">
        <div class="absolute inset-0 gradient-bg shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl"></div>
        <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20">
            <div class="max-w-md mx-auto">
                <h1 class="text-2xl font-semibold mb-6 text-center text-gray-900">Reset Password</h1>
                
                <?php if ($message): ?>
                    <div class="mb-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                        <p><?php echo htmlspecialchars($message); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if ($token): ?>
                    <form method="POST" class="space-y-4">
                        <div>
                            <label for="new-password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" id="new-password" name="new_password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Reset Password</button>
                    </form>
                <?php else: ?>
                    <p class="text-red-600">Invalid or missing reset token. Please request a new password reset.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>