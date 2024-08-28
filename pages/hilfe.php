<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support & Feedback - ImmoYes</title>
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
        .collapsible-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Seitenleiste -->
        <aside class="w-64 bg-gradient-to-b from-indigo-800 to-indigo-900 text-white">
            <div class="p-6">
                <div class="flex items-center justify-center mb-8">
                    <svg class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <h1 class="text-2xl font-bold ml-3">ImmoYes</h1>
                </div>
                <nav>
                    <a href="http://auftrag.immoyes.com/index.php" class="sidebar-link flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-home sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Meine Projekte</span>
                    </a>
                    <a href="http://auftrag.immoyes.com/index.php?page=services" class="sidebar-link flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-concierge-bell sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Neuer Auftrag</span>
                    </a>
                    <a href="http://auftrag.immoyes.com/index.php?page=aufladen" class="sidebar-link flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-wallet sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Guthaben Kaufen</span>
                    </a>
                    <a href="http://auftrag.immoyes.com/index.php?page=history" class="sidebar-link flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-history sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Rechnungen</span>
                    </a>
                    <a href="http://auftrag.immoyes.com/index.php?page=chat" class="sidebar-link flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2 relative">
                        <i class="fas fa-comments sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Chat</span>
                        <span id="chatNotificationBadge" class="notification-badge hidden absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">0</span>
                    </a>
                </nav>
            </div>
            <div class="mt-auto p-6">
                <div class="bg-white bg-opacity-10 rounded-lg p-4">
                    <h3 class="text-sm font-semibold mb-2">Hilfe benötigt?</h3>
                    <p class="text-xs mb-3">Kontaktieren Sie unser Support-Team für Unterstützung.</p>
                    <a href="#" class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-indigo-800 bg-white rounded-md hover:bg-indigo-100 transition duration-150 ease-in-out">
                        Support kontaktieren
                    </a>
                </div>
            </div>
        </aside>

        <!-- Hauptinhalt -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Obere Navigation -->
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">Support & Feedback</h2>
                    <div class="flex items-center">
                        <span id="userCredits" class="mr-4 py-2 px-4 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            <i class="fas fa-coins mr-2"></i>
                            Guthaben: €0.00
                        </span>
                        <div class="relative">
                            <button id="userMenuButton" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-800">
                                <svg class="h-8 w-8 rounded-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span id="username" class="ml-2"></span>
                                <i class="fas fa-chevron-down ml-2"></i>
                            </button>
                            <!-- Dropdown-Menü -->
                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
                                <a href="http://auftrag.immoyes.com/index.php?page=profil" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                                <a href="http://auftrag.immoyes.com" onclick="logout()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Abmelden</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Support & Feedback Inhalt -->
            <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        
                        <div class="flex items-center bg-gray-100 rounded-lg p-4 mb-6">
                            <img src="/static/bilder/maria.png" alt="Maria" class="w-12 h-12 rounded-full mr-4">
                            <div>
                                <p class="font-semibold" id="supportAvailability"></p>
                                <p class="text-sm text-gray-600">Sie können innerhalb der nächsten 24 Geschäftsstunden mit einer Antwort rechnen, in der Regel sind wir aber schneller.</p>
                                <p class="text-sm text-gray-600">Bitte haben Sie etwas Geduld - wir sind hier, um Ihnen zu helfen 😊</p>
                                <p class="text-sm text-gray-600 mt-2">
                                    Möchten Sie schneller eine Antwort? Versuchen Sie unseren <a href="http://auftrag.immoyes.com/index.php?page=chat" class="text-indigo-600 hover:text-indigo-800 font-medium">Live-Chat</a>.
                                </p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="border-b pb-2">
                                <button class="flex justify-between items-center w-full text-left font-semibold" onclick="toggleCollapsible(this)">
                                    Kontaktieren Sie uns
                                    <i class="fas fa-plus"></i>
                                </button>
                                <div class="collapsible-content">
                                    <form id="contactForm" class="mt-4 space-y-4">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                            <input type="text" id="name" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </div>
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700">E-Mail</label>
                                            <input type="email" id="email" name="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </div>
                                        <div>
                                            <label for="message" class="block text-sm font-medium text-gray-700">Nachricht</label>
                                            <textarea id="message" name="message" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                        </div>
                                        <div>
                                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Nachricht senden
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            
                            <div class="border-b pb-2">
    <button class="flex justify-between items-center w-full text-left font-semibold expanded-section" onclick="toggleCollapsible(this)">
        Wie man das ImmoYes Auftragsportal benutzt
        <i class="fas fa-minus"></i>
    </button>
    <div class="collapsible-content" style="max-height: none;">
                                    <div class="space-y-4 mt-4">
                                        <div>
                                            <h3 class="font-semibold text-lg mb-2">Wie man Guthaben auflädt</h3>
                                            <ol class="list-decimal list-inside space-y-2">
                                                <li>Besuchen Sie die <a href="http://auftrag.immoyes.com/index.php?page=aufladen" class="text-indigo-600 hover:text-indigo-800">Aufladeseite</a></li>
                                                <li>Wählen Sie PayPal/Kreditkarte oder Banküberweisung</li>
                                                <li>Wählen Sie die Anzahl des Guthabens und schließen Sie den Kauf ab</li>
                                            </ol>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-lg mb-2">Warum muss ich Guthaben aufladen?</h3>
                                            <p>Verfügen Sie über Guthaben, können wir sofort mit der Bearbeitung Ihres Auftrags loslegen. Dies ermöglicht uns, Ihren Auftrag priorisiert zu behandeln und schneller zu bearbeiten. Zudem vereinfacht es den Zahlungsprozess und Sie müssen sich keine Gedanken über einzelne Rechnungen machen.</p>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-lg mb-2">Wie lege ich einen neuen Auftrag an?</h3>
                                            <ol class="list-decimal list-inside space-y-2">
                                                <li>Gehen Sie zur <a href="http://auftrag.immoyes.com/index.php?page=services" class="text-indigo-600 hover:text-indigo-800">Serviceseite</a></li>
                                                <li>Wählen Sie den gewünschten Service</li>
                                                <li>Folgen Sie dem Assistenten, der Sie durch den Prozess führt</li>
                                            </ol>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-lg mb-2">Wann wird mein Auftrag fertig?</h3>
                                            <ul class="list-disc list-inside space-y-2">
                                                <li>Bei der Auftragserstellung sehen Sie den voraussichtlichen Fertigstellungstermin</li>
                                                <li>Für bestehende Aufträge finden Sie die verbleibende Dauer in Ihrem <a href="http://auftrag.immoyes.com/index.php?page=dashboard" class="text-indigo-600 hover:text-indigo-800">Dashboard</a></li>
                                            </ul>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-lg mb-2">Wie fordere ich Änderungen an?</h3>
                                            <p>Änderungen können Sie ganz einfach über unser Portal anfordern, indem Sie die Projektdetails aufrufen:</p>
                                            <img src="/static/bilder/rev.png" alt="Änderungen anfordern" class="mt-2 rounded-lg shadow-md">
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-lg mb-2">Wie erhalte ich eine Rechnung?</h3>
                                            <p>Rechnungen stehen sofort für Sie bereit. Sie finden sie unter <a href="http://auftrag.immoyes.com/index.php?page=history" class="text-indigo-600 hover:text-indigo-800">Rechnungen</a>.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchUserProfileAndTransactions();
        setupChatNotifications();
        updateSupportAvailabilityMessage();

        // Benutzermenü-Toggle
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');
        userMenuButton.addEventListener('click', () => {
            userMenu.classList.toggle('hidden');
        });
    });

    function fetchUserProfileAndTransactions() {
        const token = localStorage.getItem('token');
        Promise.all([
            fetch('https://api.immoyes.com/extended-profile', {
                method: 'GET',
                headers: { 'Authorization': `Bearer ${token}` }
            }),
            fetch('https://api.immoyes.com/api/transactions', {
                method: 'GET',
                headers: { 'Authorization': `Bearer ${token}` }
            })
        ])
        .then(([profileResponse, transactionsResponse]) => 
            Promise.all([profileResponse.json(), transactionsResponse.json()])
        )
        .then(([profileData, transactionsData]) => {
            document.getElementById('username').textContent = profileData.username;
            document.getElementById('userCredits').innerHTML = `<i class="fas fa-coins mr-2"></i>Guthaben: €${profileData.credits.toFixed(2)}`;
        })
        .catch(error => console.error('Fehler:', error));
    }


 // New code to expand the section on page load
 const expandedSection = document.querySelector('.expanded-section');
    if (expandedSection) {
        const content = expandedSection.nextElementSibling;
        content.style.maxHeight = content.scrollHeight + "px";
    }


function toggleCollapsible(element) {
    const content = element.nextElementSibling;
    const icon = element.querySelector('i');
    if (content.style.maxHeight && content.style.maxHeight !== 'none') {
        content.style.maxHeight = null;
        icon.classList.remove('fa-minus');
        icon.classList.add('fa-plus');
    } else {
        content.style.maxHeight = content.scrollHeight + "px";
        icon.classList.remove('fa-plus');
        icon.classList.add('fa-minus');
    }
}

    function logout() {
        const token = localStorage.getItem('token');
        
        fetch('https://api.immoyes.com/logout', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            credentials: 'include'
        })
        .then(response => {
            if (response.ok) {
                localStorage.clear();
                document.cookie.split(";").forEach(function(c) { 
                    document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
                });
                window.location.href = 'http://auftrag.immoyes.com/login.php';
            } else {
                throw new Error('Logout failed');
            }
        })
        .catch(error => {
            console.error('Logout error:', error);
            localStorage.clear();
            document.cookie.split(";").forEach(function(c) { 
                document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
            });
            window.location.href = 'http://auftrag.immoyes.com/login.php';
        });
    }

    function setupChatNotifications() {
        const token = localStorage.getItem('token');
        if (!token) {
            console.error('No token found. Unable to set up chat notifications.');
            return;
        }

        function updateUnreadCount() {
            fetch('https://api.immoyes.com/api/chat/unread', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => response.json())
            .then(data => {
                const chatNotificationBadge = document.getElementById('chatNotificationBadge');
                if (data.unreadCount > 0) {
                    chatNotificationBadge.textContent = data.unreadCount;
                    chatNotificationBadge.classList.remove('hidden');
                } else {
                    chatNotificationBadge.classList.add('hidden');
                }
            })
            .catch(error => console.error('Error fetching unread count:', error));
        }

        const eventSource = new EventSource(`https://api.immoyes.com/api/chat/stream?token=${token}`);
        
        eventSource.onmessage = function(event) {
            const data = JSON.parse(event.data);
            console.log('Received message via SSE:', data);
            if (!data.is_user_message) {
                updateUnreadCount();
            }
        };

        eventSource.onerror = function(error) {
            console.error('EventSource failed:', error);
            eventSource.close();
            setTimeout(setupChatNotifications, 5000);  // Attempt to reconnect after 5 seconds
        };

        updateUnreadCount();
        setInterval(updateUnreadCount, 60000);

        if (window.location.href.includes('page=chat')) {
            fetch('https://api.immoyes.com/api/chat/mark-read', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(() => updateUnreadCount())
            .catch(error => console.error('Error marking messages as read:', error));
        }
    }

    function getNextAvailableTime() {
        const now = new Date();
        const dayOfWeek = now.getDay();
        const hours = now.getHours();
        const minutes = now.getMinutes();

        // If it's Saturday or Sunday, next available time is Monday at 9:00
        if (dayOfWeek === 0 || dayOfWeek === 6) {
            const next = new Date(now);
            next.setDate(now.getDate() + (8 - dayOfWeek) % 7);
            next.setHours(9, 0, 0, 0);
            return next;
        }

        // If it's before 9:00 or after 17:00 on a weekday
        if (hours < 9 || hours >= 17) {
            const next = new Date(now);
            if (hours >= 17) {
                next.setDate(now.getDate() + 1);
            }
            next.setHours(9, 0, 0, 0);
            return next;
        }

        // If it's between 9:00 and 17:00, support is currently available
        return null;
    }

    function updateSupportAvailabilityMessage() {
        const nextAvailable = getNextAvailableTime();
        const supportAvailabilityElement = document.getElementById('supportAvailability');

        if (nextAvailable === null) {
            supportAvailabilityElement.innerHTML = 'Unser Support-Team ist jetzt für Sie da!';
        } else {
            const options = { weekday: 'long', hour: '2-digit', minute: '2-digit' };
            const formattedTime = nextAvailable.toLocaleString('de-DE', options);
            supportAvailabilityElement.innerHTML = `Maria Hellig ist wieder erreichbar am ${formattedTime} Uhr.`;
        }
    }

    function toggleCollapsible(element) {
        const content = element.nextElementSibling;
        const icon = element.querySelector('i');
        if (content.style.maxHeight) {
            content.style.maxHeight = null;
            icon.classList.remove('fa-minus');
            icon.classList.add('fa-plus');
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
            icon.classList.remove('fa-plus');
            icon.classList.add('fa-minus');
        }
    }

    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const searchParams = new URLSearchParams();

        for (const pair of formData) {
            searchParams.append(pair[0], pair[1]);
        }

        fetch('https://formspree.io/f/jansen.tobias@gmail.com', {
            method: 'POST',
            body: searchParams,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                alert('Vielen Dank für Ihre Nachricht. Wir werden uns bald bei Ihnen melden.');
                this.reset();
            } else {
                alert('Es gab ein Problem beim Senden Ihrer Nachricht. Bitte versuchen Sie es später erneut.');
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('Es gab ein Problem beim Senden Ihrer Nachricht. Bitte versuchen Sie es später erneut.');
        });
    });
    </script>
</body>
</html>