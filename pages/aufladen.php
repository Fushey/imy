<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guthaben Kaufen - ImmoYes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        .sidebar-icon {
            transition: all 0.3s;
        }
        .sidebar-link:hover .sidebar-icon {
            transform: translateX(5px);
        }
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #fff;
        }
        .sidebar-link.active .sidebar-icon {
            color: #FFF;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-indigo-800 to-indigo-900 text-white">
            <div class="p-6">
                <div class="flex items-center justify-center mb-8">
                    <svg class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <a href="http://auftrag.immoyes.com/index.php?page=dashboard">
  <h1 class="text-2xl font-bold ml-3">ImmoYes</h1>
</a>
                </div>
                <nav>
                    <a href="http://auftrag.immoyes.com/index.php?page=dashboard" class="sidebar-link flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-home sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Meine Projekte</span>
                    </a>
                    <a href="http://auftrag.immoyes.com/index.php?page=services" class="sidebar-link flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-concierge-bell sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Neuer Auftrag</span>
                    </a>
                    <a href="http://auftrag.immoyes.com/index.php?page=aufladen" class="sidebar-link active flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-wallet sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Guthaben Kaufen</span>
                    </a>
                    <a href="http://auftrag.immoyes.com/index.php?page=history" class="sidebar-link flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-history sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Rechnungen</span>
                    </a>
                </nav>
            </div>
            <div class="mt-auto p-6">
                <div class="bg-white bg-opacity-10 rounded-lg p-4">
                    <h3 class="text-sm font-semibold mb-2">Hilfe benötigt?</h3>
                    <p class="text-xs mb-3">Kontaktieren Sie unser Support-Team für Unterstützung.</p>
                    <a href="http://auftrag.immoyes.com/index.php?page=hilfe" class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-indigo-800 bg-white rounded-md hover:bg-indigo-100 transition duration-150 ease-in-out">
                        Support kontaktieren
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">Guthaben Kaufen</h2>
                    <div class="flex items-center space-x-4">
                        <span id="userCredits" class="text-sm font-medium text-gray-500"></span>
                        <a href="index.php?page=logout" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Abmelden
                        </a>
                    </div>
                </div>
            </header>

            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div id="creditInfo" class="text-lg text-gray-600 mb-6"></div>
                
                <!-- Payment Method Selection Screen -->
                <div id="payment-method-selection" class="bg-white shadow-lg rounded-lg p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Zahlungsmethode auswählen</h2>
                    <div class="flex flex-col space-y-4">
                        <button id="paypal-option" class="payment-option bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out">
                            <i class="fab fa-paypal mr-2"></i> PayPal / Kreditkarte
                            <span class="block text-sm mt-2">Sofortige Gutschrift</span>
                        </button>
                        <button id="bank-transfer-option" class="payment-option bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out">
                            <i class="fas fa-university mr-2"></i> Banküberweisung
                            <span class="block text-sm mt-2">Bearbeitung innerhalb von 1-2 Werktagen</span>
                        </button>
                    </div>
                </div>

                <!-- PayPal Payment Screen (initially hidden) -->
                <div id="paypal-payment-screen" class="bg-white shadow-lg rounded-lg p-6 mb-8 hidden">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Guthaben mit PayPal kaufen</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                        <button class="credit-package bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105" data-credits="50" data-price="50">
                            <span class="block text-2xl mb-1">50 Credits</span>
                            <span class="block text-lg">50 €</span>
                        </button>
                        <button class="credit-package bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105" data-credits="100" data-price="100">
                            <span class="block text-2xl mb-1">100 Credits</span>
                            <span class="block text-lg">100 €</span>
                        </button>
                        <button class="credit-package bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105" data-credits="200" data-price="200">
                            <span class="block text-2xl mb-1">200 Credits</span>
                            <span class="block text-lg">200 €</span>
                        </button>
                        <button class="credit-package bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105" data-credits="500" data-price="500">
                            <span class="block text-2xl mb-1">500 Credits</span>
                            <span class="block text-lg">500 €</span>
                        </button>
                        <button class="credit-package bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105" data-credits="1000" data-price="1000">
                            <span class="block text-2xl mb-1">1000 Credits</span>
                            <span class="block text-lg">1000 €</span>
                        </button>
                        <button class="credit-package bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105" data-credits="2000" data-price="2000">
                            <span class="block text-2xl mb-1">2000 Credits</span>
                            <span class="block text-lg">2000 €</span>
                        </button>
                    </div>
                    
                    <div class="mb-8">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="customCredits">
                            Benutzerdefinierter Betrag
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">
                                    €
                                </span>
                            </div>
                            <input type="number" name="customCredits" id="customCredits" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Benutzerdefinierten Guthabenbetrag eingeben">
                            <div class="absolute inset-y-0 right-0 flex items-center">
                                <label for="currency" class="sr-only">Währung</label>
                                <select id="currency" name="currency" class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                    <option>EUR</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div id="selected-package" class="text-xl font-bold text-gray-900 mb-8"></div>
                    
                    <!-- PayPal button container -->
                    <div id="paypal-button-container" class="w-full max-w-md mx-auto"></div>
                </div>

                <!-- Bank Transfer Screen (initially hidden) -->
                <div id="bank-transfer-screen" class="bg-white shadow-lg rounded-lg p-6 mb-8 hidden">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Guthaben per Banküberweisung kaufen</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                        <button class="credit-package bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105" data-credits="50" data-price="50">
                            <span class="block text-2xl mb-1">50 Credits</span>
                            <span class="block text-lg">50 €</span>
                        </button>
                        <button class="credit-package bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105" data-credits="100" data-price="100">
                            <span class="block text-2xl mb-1">100 Credits</span>
                            <span class="block text-lg">100 €</span>
                        </button>
                        <button class="credit-package bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105" data-credits="200" data-price="200">
                            <span class="block text-2xl mb-1">200 Credits</span>
                            <span class="block text-lg">200 €</span>
                        </button>
                        <button class="credit-package bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105" data-credits="500" data-price="500">
                            <span class="block text-2xl mb-1">500 Credits</span>
                            <span class="block text-lg">500 €</span>
                        </button>
                        <button class="credit-package bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105" data-credits="1000" data-price="1000">
                            <span class="block text-2xl mb-1">1000 Credits</span>
                            <span class="block text-lg">1000 €</span>
                        </button>
                        <button class="credit-package bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105" data-credits="2000" data-price="2000">
                            <span class="block text-2xl mb-1">2000 Credits</span>
                            <span class="block text-lg">2000 €</span>
                        </button>
                    </div>
                    
                    <div class="mb-8">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="customCreditsBankTransfer">
                            Benutzerdefinierter Betrag
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">
                                    €
                                </span>
                            </div>
                            <input type="number" name="customCreditsBankTransfer" id="customCreditsBankTransfer" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Benutzerdefinierten Guthabenbetrag eingeben">
                            <div class="absolute inset-y-0 right-0 flex items-center">
                                <label for="currencyBankTransfer" class="sr-only">Währung</label>
                                <select id="currencyBankTransfer" name="currencyBankTransfer" class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                    <option>EUR</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div id="selected-package-bank" class="text-xl font-bold text-gray-900 mb-8"></div>
                    
                    <!-- Bank transfer information -->
                    <div id="bank-transfer-info" class="mt-8 p-4 bg-gray-100 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Banküberweisung Informationen</h3>
                        <p class="mb-2"><strong>Empfänger:</strong> ImmoYes GmbH</p>
                        <p class="mb-2"><strong>IBAN:</strong> DE89 3704 0044 0532 0130 00</p>
                        <p class="mb-2"><strong>BIC:</strong> COBADEFFXXX</p>
                        <p class="mb-2"><strong>Bank:</strong> Commerzbank</p>
                        <p class="mb-2"><strong>Verwendungszweck:</strong> <span id="bank-transfer-reference"></span></p>
                        <p class="mt-4 text-sm text-gray-600">Bitte überweisen Sie den exakten Betrag von <span id="bank-transfer-amount"></span> €. Ihre Credits werden innerhalb von 1-2 Werktagen nach Zahlungseingang gutgeschrieben.</p>
                    </div>

                    <!-- Success message (initially hidden) -->
                    <div id="success-message" class="hidden mt-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        <p class="font-semibold">Banküberweisung erfolgreich registriert!</p>
                        <p>Ihre Credits werden nach Zahlungseingang gutgeschrieben.</p>
                        <p class="mt-2">Sie werden in <span id="redirect-countdown">5</span> Sekunden zum Dashboard weitergeleitet.</p>
                    </div>
                    
                    <button id="confirm-bank-transfer" class="mt-6 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Banküberweisung bestätigen
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script src="https://www.paypal.com/sdk/js?client-id=AQjRqzAsOwt46ocslyW6pHRa1W5Rp1NKuqEWrxVhSweEtOrNAqr6DktdukBE_u4OygKYnaiIlccQsId0&currency=EUR"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = 'login.php';
            return;
        }

        let selectedCredits = 0;
        let selectedPrice = 0;
        let initialReference = '';

        function checkTokenValidity() {
            fetch('https://api.immoyes.com/profile', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Token invalid');
                }
                return response.json();
            })
            .then(data => {
                console.log('Token is valid');
                document.getElementById('userCredits').innerHTML = `
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
        <circle cx="4" cy="4" r="3" />
    </svg>
    Guthaben ${data.credits.toFixed(2)} €
</span>`;

            })
            .catch(error => {
                console.error('Token validation error:', error);
                // Redirect to login page if token is invalid
                window.location.href = 'login.php';
            });
        }

        checkTokenValidity();

        function updateSelectedPackage(element) {
            if (selectedCredits > 0) {
                element.textContent = `Ausgewählt: ${selectedCredits} Credits - ${selectedPrice} €`;
                element.classList.add('animate-pulse', 'bg-green-100', 'p-4', 'rounded-lg');
                setTimeout(() => element.classList.remove('animate-pulse'), 500);
            } else {
                element.textContent = '';
                element.classList.remove('bg-green-100', 'p-4', 'rounded-lg');
            }
        }

        function setupCreditPackages(containerSelector, selectedPackageSelector) {
            const container = document.querySelector(containerSelector);
            const selectedPackageElement = document.querySelector(selectedPackageSelector);

            container.querySelectorAll('.credit-package').forEach(button => {
                button.addEventListener('click', () => {
                    container.querySelectorAll('.credit-package').forEach(btn => {
                        btn.classList.remove('ring-4', 'ring-indigo-500', 'bg-indigo-700');
                        btn.classList.add('bg-indigo-600');
                    });
                    button.classList.remove('bg-indigo-600');
                    button.classList.add('ring-4', 'ring-indigo-500', 'bg-indigo-700');
                    selectedCredits = parseInt(button.dataset.credits);
                    selectedPrice = parseFloat(button.dataset.price);
                    container.querySelector('input[type="number"]').value = '';
                    updateSelectedPackage(selectedPackageElement);
                });
            });

            container.querySelector('input[type="number"]').addEventListener('input', (e) => {
                const customCredits = parseInt(e.target.value);
                if (customCredits > 0) {
                    container.querySelectorAll('.credit-package').forEach(btn => {
                        btn.classList.remove('ring-4', 'ring-indigo-500', 'bg-indigo-700');
                        btn.classList.add('bg-indigo-600');
                    });
                    selectedCredits = customCredits;
                    selectedPrice = customCredits; // Annahme: 1 Credit = 1 €
                    updateSelectedPackage(selectedPackageElement);
                } else {
                    selectedCredits = 0;
                    selectedPrice = 0;
                    updateSelectedPackage(selectedPackageElement);
                }
            });
        }

        // Set up credit packages for both PayPal and Bank Transfer screens
        setupCreditPackages('#paypal-payment-screen', '#selected-package');
        setupCreditPackages('#bank-transfer-screen', '#selected-package-bank');

        // Payment method selection
        document.getElementById('paypal-option').addEventListener('click', () => {
            document.getElementById('payment-method-selection').classList.add('hidden');
            document.getElementById('paypal-payment-screen').classList.remove('hidden');
        });

        document.getElementById('bank-transfer-option').addEventListener('click', () => {
            document.getElementById('payment-method-selection').classList.add('hidden');
            document.getElementById('bank-transfer-screen').classList.remove('hidden');
        });

        function generateReference() {
            return `IMY${Math.floor(Math.random() * 1000000).toString().padStart(6, '0')}`;
        }

        function updateBankTransferInfo() {
            const amountElement = document.getElementById('bank-transfer-amount');
            amountElement.textContent = selectedPrice.toFixed(2);
        }

        function setInitialReference() {
            const referenceElement = document.getElementById('bank-transfer-reference');
            initialReference = generateReference();
            referenceElement.textContent = initialReference;
        }

        // Set initial reference when the page loads
        setInitialReference();

        document.getElementById('confirm-bank-transfer').addEventListener('click', () => {
            if (selectedCredits <= 0) {
                alert('Bitte wählen Sie ein Guthabenpaket aus oder geben Sie einen benutzerdefinierten Betrag ein.');
                return;
            }
            updateBankTransferInfo();
            processPurchase(initialReference, 'bank_transfer');
        });

        paypal.Buttons({
            style: {
                layout: 'vertical',
                color:  'blue',
                shape:  'rect',
                label:  'paypal'
            },

            createOrder: function(data, actions) {
                if (selectedCredits <= 0) {
                    alert('Bitte wählen Sie ein Guthabenpaket aus oder geben Sie einen benutzerdefinierten Betrag ein.');
                    return;
                }
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: selectedPrice.toFixed(2)
                        },
                        description: `${selectedCredits} Credits für ImmoYes`
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    processPurchase(details.id, 'paypal');
                });
            }
        }).render('#paypal-button-container');

        function processPurchase(transactionId, method) {
            console.log('Token:', token); // Log the token
            console.log('Request body:', JSON.stringify({
                credits: selectedCredits,
                transactionId: transactionId,
                amount: selectedPrice,
                method: method
            }));

            fetch('https://api.immoyes.com/api/purchase-credits', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({
                    credits: selectedCredits,
                    transactionId: transactionId,
                    amount: selectedPrice,
                    method: method
                }),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    if (method === 'paypal') {
                        alert(`Transaktion erfolgreich abgeschlossen. Ihr neues Guthaben beträgt ${data.new_balance} Credits.`);
                        window.location.href = 'index.php?page=dashboard';
                    } else if (method === 'bank_transfer') {
                        showSuccessMessage();
                    }
                } else {
                    alert('Es gab einen Fehler beim Verarbeiten Ihrer Anfrage. Bitte kontaktieren Sie den Support.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Es gab einen Fehler bei der Verarbeitung Ihrer Anfrage. Bitte versuchen Sie es erneut oder kontaktieren Sie den Support.');
            });
        }

        function showSuccessMessage() {
            const successMessage = document.getElementById('success-message');
            const bankTransferInfo = document.getElementById('bank-transfer-info');
            const confirmButton = document.getElementById('confirm-bank-transfer');
            const countdownElement = document.getElementById('redirect-countdown');

            // Hide the bank transfer info and confirm button
            bankTransferInfo.classList.add('hidden');
            confirmButton.classList.add('hidden');

            // Show the success message
            successMessage.classList.remove('hidden');

            
        }
    });
    </script>
</body>
</html>