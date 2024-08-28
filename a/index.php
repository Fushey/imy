<?php
session_start();

$flask_api_url = 'http://62.146.181.66:8080';

function callAPI($endpoint, $data = [], $method = 'POST', $headers = []) {
    global $flask_api_url;
    $url = $flask_api_url . $endpoint;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method === 'GET' && !empty($data)) {
        $url .= '?' . http_build_query($data);
        curl_setopt($ch, CURLOPT_URL, $url);
    }

    $headers[] = 'Content-Type: application/json';
    if (isset($_SESSION['token'])) {
        $headers[] = 'Authorization: Bearer ' . $_SESSION['token'];
    }
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
$messageType = '';

$creditPackages = [
    ['credits' => 10, 'price' => 1],
    ['credits' => 50, 'price' => 5],
    ['credits' => 100, 'price' => 10],
    ['credits' => 200, 'price' => 20],
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $result = callAPI('/register', [
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ]);
        if (isset($result['message'])) {
            $message = "Registration successful! Please check your email to confirm your account. You'll receive 5 free credits after confirmation.";
            $messageType = 'success';
        } else {
            $message = isset($result['error']) ? $result['error'] : "Error registering user. Please try again or contact support if the problem persists.";
            $messageType = 'error';
        }
    } elseif (isset($_POST['login'])) {
        $result = callAPI('/login', [
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ]);
        if (isset($result['token'])) {
            $_SESSION['token'] = $result['token'];
            $_SESSION['user_id'] = $result['userId'];
            
            if (isset($_GET['confirm_email'])) {
                // If confirm_email parameter is present, it means the automatic login failed earlier
                $message = "Your email was already confirmed. You have been logged in successfully.";
            } else {
                $message = "Logged in successfully. Welcome back!";
            }
            $messageType = 'success';
            
            // Fetch user data immediately after login
            $userData = callAPI('/user-data', []);
            
            // Remove the confirm_email parameter from the URL
            $redirect_url = strtok($_SERVER["REQUEST_URI"], '?');
            header("Location: " . $redirect_url);
            exit();
        } else {
            if (isset($result['error']) && $result['error'] == "Please confirm your email before logging in") {
                $message = "Your email is not confirmed yet. Please check your inbox and confirm your email before logging in.";
            } else {
                $message = "Invalid credentials. Please check your email and password and try again.";
            }
            $messageType = 'error';
        }
    
    } elseif (isset($_POST['logout'])) {
        // Unset all of the session variables
        $_SESSION = array();
    
        // If it's desired to kill the session, also delete the session cookie.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    
        // Destroy the session
        session_destroy();
    
        $message = "You have been successfully logged out. Thank you for using our service!";
        $messageType = 'success';
    
        // Redirect to the login page
        header("Location: auftrag.immoyes.com/index.php?1");
        exit();
    
    } elseif (isset($_POST['confirm_payment'])) {
        if (!isset($_SESSION['token'])) {
            $message = "Please log in to buy credits. If you were logged in, your session may have expired. Please log in and try again.";
            $messageType = 'error';
        } else {
            $orderID = $_POST['orderID'];
            $selectedCredits = intval($_POST['credits']);
            $selectedPrice = isset($_POST['price']) ? floatval($_POST['price']) : 0;
            $expectedPrice = $selectedCredits * 0.1;
            
            if (abs($selectedPrice - $expectedPrice) <= 0.01) {
                $result = callAPI('/add-credits', [
                    'credits' => $selectedCredits,
                    'orderID' => $orderID,
                    'payment_amount' => $selectedPrice
                ]);
                if (isset($result['message'])) {
                    $message = "Payment successful! {$selectedCredits} credits have been added to your account.";
                    $messageType = 'success';
                    $userData = callAPI('/user-data', []);
                } else {
                    $message = "Error adding credits: " . ($result['error'] ?? 'Unknown error. Please contact support if this persists.');
                    $messageType = 'error';
                }
            } else {
                $message = "Invalid credit package selected. Please try again or contact support if this issue persists.";
                $messageType = 'error';
            }
        }
    } elseif (isset($_POST['reset_password'])) {
        $result = callAPI('/reset-password', [
            'email' => $_POST['email']
        ]);
        if (isset($result['message'])) {
            $message = "Password reset instructions have been sent to your email. Please check your inbox and follow the instructions to reset your password.";
            $messageType = 'success';
        } else {
            $message = "Error resetting password. Please ensure you've entered a valid email address and try again.";
            $messageType = 'error';
        }
    }
}

$userData = null;
if (isset($_SESSION['token'])) {
    $userData = callAPI('/user-data', []);
    if (!is_array($userData) || !isset($userData['email'])) {
        $message = "Error fetching user data. Please try logging in again.";
        $messageType = 'error';
        session_destroy();
        $userData = null;
    }
}

// Handle email confirmation
if (isset($_GET['confirm_email'])) {
    $confirmation_token = $_GET['confirm_email'];
    $result = callAPI('/confirm-email/' . $confirmation_token, [], 'GET');
    
    if (isset($result['message'])) {
        // Email confirmed successfully
        $message = $result['message'];
        $messageType = 'success';

        // Now, let's try to log the user in
        if (isset($result['email'])) {
            $loginResult = callAPI('/login', [
                'email' => $result['email'],
                'password' => $confirmation_token // Use the confirmation token as a temporary password
            ]);

            if (isset($loginResult['token'])) {
                $_SESSION['token'] = $loginResult['token'];
                $_SESSION['user_id'] = $loginResult['userId'];
                $message .= " You have been automatically logged in.";
                
                // Fetch user data immediately after login
                $userData = callAPI('/user-data', []);
                
                // Remove the confirm_email parameter from the URL
                $redirect_url = strtok($_SERVER["REQUEST_URI"], '?');
                header("Location: " . $redirect_url);
                exit();
            } else {
                $message .= " However, automatic login failed. Please log in manually.";
            }
        } else {
            $message .= " However, we couldn't log you in automatically. Please log in manually.";
        }
    } else {
        $message = "Error confirming email: " . ($result['error'] ?? 'Unknown error');
        $messageType = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Songfinder Premium</title>
    <script src="https://www.paypal.com/sdk/js?client-id=AelZ1hdxwcW6zFeEdzac-JwNPlCFc0B9tgkzAR1aau59wJGVY1sMCN4_vO1MZVqIhAIDZDx7P3rKA4x9&currency=USD"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

    * {
        box-sizing: border-box;
    }

    body {
        background: #f6f5f7;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        font-family: 'Montserrat', sans-serif;
        height: 100vh;
        margin: -20px 0 50px;
    }

    h1 {
        font-weight: bold;
        margin: 0;
    }

    h2 {
        text-align: center;
    }

    p {
        font-size: 14px;
        font-weight: 100;
        line-height: 20px;
        letter-spacing: 0.5px;
        margin: 20px 0 30px;
    }

    span {
        font-size: 12px;
    }

    a {
        color: #333;
        font-size: 14px;
        text-decoration: none;
        margin: 15px 0;
    }

    button {
        border-radius: 20px;
        border: 1px solid #FF4B2B;
        background-color: #FF4B2B;
        color: #FFFFFF;
        font-size: 12px;
        font-weight: bold;
        padding: 12px 45px;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: transform 80ms ease-in;
    }

    button:active {
        transform: scale(0.95);
    }

    button:focus {
        outline: none;
    }

    button.ghost {
        background-color: transparent;
        border-color: #FFFFFF;
    }

    form {
        background-color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 50px;
        height: 100%;
        text-align: center;
    }

    input {
        background-color: #eee;
        border: none;
        padding: 12px 15px;
        margin: 8px 0;
        width: 100%;
    }

    .container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 14px 28px rgba(0,0,0,0.25), 
                0 10px 10px rgba(0,0,0,0.22);
        position: relative;
        overflow: hidden;
        width: 768px;
        max-width: 100%;
        min-height: 480px;
    }

    .form-container {
        position: absolute;
        top: 0;
        height: 100%;
        transition: all 0.6s ease-in-out;
    }

    .sign-in-container {
        left: 0;
        width: 50%;
        z-index: 2;
    }

    .container.right-panel-active .sign-in-container {
        transform: translateX(100%);
    }

    .sign-up-container {
        left: 0;
        width: 50%;
        opacity: 0;
        z-index: 1;
    }

    .container.right-panel-active .sign-up-container {
        transform: translateX(100%);
        opacity: 1;
        z-index: 5;
        animation: show 0.6s;
    }

    @keyframes show {
        0%, 49.99% {
            opacity: 0;
            z-index: 1;
        }
        
        50%, 100% {
            opacity: 1;
            z-index: 5;
        }
    }

    .overlay-container {
        position: absolute;
        top: 0;
        left: 50%;
        width: 50%;
        height: 100%;
        overflow: hidden;
        transition: transform 0.6s ease-in-out;
        z-index: 100;
    }

    .container.right-panel-active .overlay-container{
        transform: translateX(-100%);
    }

    .overlay {
        background: #FF416C;
        background: -webkit-linear-gradient(to right, #FF4B2B, #FF416C);
        background: linear-gradient(to right, #FF4B2B, #FF416C);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: 0 0;
        color: #FFFFFF;
        position: relative;
        left: -100%;
        height: 100%;
        width: 200%;
        transform: translateX(0);
        transition: transform 0.6s ease-in-out;
    }

    .container.right-panel-active .overlay {
        transform: translateX(50%);
    }

    .overlay-panel {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 40px;
        text-align: center;
        top: 0;
        height: 100%;
        width: 50%;
        transform: translateX(0);
        transition: transform 0.6s ease-in-out;
    }

    .overlay-left {
        transform: translateX(-20%);
    }

    .container.right-panel-active .overlay-left {
        transform: translateX(0);
    }

    .overlay-right {
        right: 0;
        transform: translateX(0);
    }

    .container.right-panel-active .overlay-right {
        transform: translateX(20%);
    }

    .logged-in {
        background: linear-gradient(135deg, #FF416C 0%, #FF4B2B 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
        text-align: center;
        max-width: 600px;
        margin: 0 auto;
        position: relative;
    }

    .logged-in h1 {
        margin-bottom: 1rem;
        font-size: 1.8rem;
    }

    .credit-balance {
        background: rgba(255, 255, 255, 0.1);
        padding: 1rem;
        border-radius: 5px;
        margin-bottom: 2rem;
    }

    .credit-balance span {
        display: block;
        font-size: 0.9rem;
    }

    .credit-balance strong {
        font-size: 2.5rem;
        display: block;
        margin-top: 0.5rem;
    }

    .credit-packages {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .credit-package {
        background: white;
        color: #FF4B2B;
        border: none;
        padding: 0.8rem;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        font-weight: bold;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .credit-package:hover, .credit-package.selected {
        background: #FF4B2B;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    #paypal-button-container {
        margin-top: 1rem;
    }

    .logout-btn {
        background: white;
        border: none;
        color: #FF4B2B;
        padding: 0.8rem 2rem;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        font-weight: bold;
        margin-top: 1rem;
        width: 100%;
    }

    .logout-btn:hover {
        background: rgba(255, 255, 255, 0.9);
    }

    .status-message {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: bold;
        z-index: 1000;
        display: none;
    }

    .status-message.success {
        background-color: #4CAF50;
        color: white;
    }

    .status-message.error {
        background-color: #f44336;
        color: white;
    }

    .status-message.warning {
        background-color: #ff9800;
        color: white;
    }
    </style>
</head>
<body>
    <div id="status-message" class="status-message"></div>

    <?php if (!isset($_SESSION['token']) || $userData === null): ?>
        <div class="container" id="container">
            <div class="form-container sign-up-container">
                <form method="POST">
                    <h1>Create Account</h1>
                    <span>use your email for registration</span>
                    <input type="email" name="email" placeholder="Email" required />
                    <input type="password" name="password" placeholder="Password" required />
                    <button type="submit" name="register">Sign Up</button>
                </form>
            </div>
            <div class="form-container sign-in-container">
                <form method="POST">
                    <h1>Sign in</h1>
                    <span>Songfinder Premium</span>
                    <input type="email" name="email" placeholder="Email" required />
                    <input type="password" name="password" placeholder="Password" required />
                    <a href="#" id="forgot-password-link">Forgot your password?</a>
                    <button type="submit" name="login">Sign In</button>
                </form>
            </div>
            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <h1>Welcome Back!</h1>
                        <p>To keep connected with us please login with your personal info</p>
                        <button class="ghost" id="signIn">Sign In</button>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <h1>Song Finder Extension!</h1>
                        <p>Discover Songs that you love</p>
                        <button class="ghost" id="signUp">Sign Up</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reset Password Form -->
        <div class="bg-white p-6 rounded-lg shadow-md" id="reset-password-form" style="display:none;">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Reset Password</h2>
            <form method="POST" class="space-y-4" onsubmit="handlePasswordReset(event)">
                <div>
                    <label for="reset-email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="reset-email" name="email" required class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <button type="submit" name="reset_password" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-150 ease-in-out">Reset Password</button>
            </form>
        </div>
    <?php else: ?>
        <div class="container logged-in">
            <div class="content">
                <h1>Welcome, <?php echo htmlspecialchars($userData['email']); ?>!</h1>
                <div class="credit-balance">
                    <span>Your current credit balance:</span>
                    <strong><?php echo isset($userData['credits']) ? $userData['credits'] : 'N/A'; ?></strong>
                </div>
                <h2>Buy Credits</h2>
                <div class="credit-packages">
                    <?php foreach ($creditPackages as $package): ?>
                        <button class="credit-package" data-credits="<?php echo $package['credits']; ?>" data-price="<?php echo $package['price']; ?>">
                            <?php echo $package['credits']; ?> CREDITS<br>$<?php echo number_format($package['price'], 2); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <div id="paypal-button-container"></div>
                <form method="POST" id="logout-form">
                    <button type="submit" name="logout" class="logout-btn">Log Out</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    if (signUpButton && signInButton) {
        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });
    }

    function showStatusMessage(message, type) {
        const statusMessage = document.getElementById('status-message');
        if (statusMessage) {
            statusMessage.textContent = message;
            statusMessage.className = 'status-message ' + type;
            statusMessage.style.display = 'block';
            setTimeout(() => {
                statusMessage.style.display = 'none';
            }, 5000);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const logoutForm = document.getElementById('logout-form');
        if (logoutForm) {
            logoutForm.addEventListener('submit', function(event) {
                event.preventDefault();
                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this)
                }).then(() => {
                    window.location.reload();
                });
            });
        }

        <?php if ($message): ?>
        showStatusMessage('<?php echo addslashes($message); ?>', '<?php echo $messageType; ?>');
        <?php endif; ?>

        document.getElementById('forgot-password-link').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('container').style.display = 'none';
            document.getElementById('reset-password-form').style.display = 'block';
        });
    });

    function handlePasswordReset(event) {
        event.preventDefault();
        const statusMessage = document.getElementById('status-message');
        statusMessage.style.display = 'block';

        // Hide the status message after 3 seconds
        setTimeout(() => {
            statusMessage.style.display = 'none';
        }, 15000);

        // Simulate form submission process
        setTimeout(() => {
            document.querySelector('form[name="reset_password"]').submit();
        }, 15000);
    }

    <?php if (isset($_SESSION['token'])): ?>
    let selectedPackage = null;

    document.querySelectorAll('.credit-package').forEach(button => {
        button.addEventListener('click', function() {
            selectedPackage = <?php echo json_encode($creditPackages); ?>.find(
                package => package.credits == this.dataset.credits && Math.abs(package.price - parseFloat(this.dataset.price)) <= 0.01
            );
            document.querySelectorAll('.credit-package').forEach(btn => btn.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    paypal.Buttons({
        createOrder: function(data, actions) {
            if (!selectedPackage) {
                showStatusMessage('Please select a credit package first.', 'error');
                return;
            }
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: selectedPackage.price.toFixed(2)
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="confirm_payment" value="1">
                    <input type="hidden" name="orderID" value="${data.orderID}">
                    <input type="hidden" name="credits" value="${selectedPackage.credits}">
                    <input type="hidden" name="price" value="${selectedPackage.price.toFixed(2)}">
                `;
                document.body.appendChild(form);
                form.submit();
            });
        }
    }).render('#paypal-button-container');
    <?php endif; ?>
    </script>
</body>
</html>