<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ImmoYes</title>
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
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Seitenleiste -->
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
                    <a href="#" class="sidebar-link active flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
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
                    <a href="http://auftrag.immoyes.com/index.php?page=hilfe" class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-indigo-800 bg-white rounded-md hover:bg-indigo-100 transition duration-150 ease-in-out">
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
                    <h2 class="text-2xl font-semibold text-gray-800">Meine Projekte</h2>
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

            <!-- Dashboard-Inhalt -->
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Alle Projekte Sektion -->
                <div class="mt-8">
                    <!-- Projects Grid -->
                    <div id="projectsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Project cards will be dynamically inserted here -->
                    </div>
                    <!-- No Projects Instructions -->
                    <div id="noProjectsInstructions" class="hidden">
                        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-5xl mx-auto">
                            <h2 class="text-4xl font-bold text-center text-indigo-700 mb-8">Willkommen bei ImmoYes!</h2>
                            <p class="text-xl text-center text-gray-600 mb-12">Folgen Sie diesen einfachen Schritten, um Ihr erstes Projekt zu starten:</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="instructionSteps">
                                <!-- Instruction steps will be dynamically inserted here -->
                            </div>
                            <div class="mt-12 text-center">
                                <a href="http://auftrag.immoyes.com/index.php?page=services" 
                                   class="inline-block px-8 py-4 bg-indigo-600 text-white text-xl font-semibold rounded-lg hover:bg-indigo-700 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Jetzt erstes Projekt starten
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Paginierungs-Container -->
                    <div id="pagination" class="mt-8 flex justify-center"></div>
                </div>
            </div>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchBatchData(1);
        fetchUserProfileAndTransactions();
        setupChatNotifications();

        // Benutzermenü-Toggle
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');
        userMenuButton.addEventListener('click', () => {
            userMenu.classList.toggle('hidden');
        });
    });

    function fetchBatchData(page = 1) {
        const token = localStorage.getItem('token');
        const batchData = {
            projects: { 
                endpoint: 'projects',
                page: page,
                per_page: 12
            }
        };

        fetch('https://api.immoyes.com/batch', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(batchData)
        })
        .then(response => response.json())
        .then(data => {
            displayProjectsOrInstructions(data.projects.items);
            updatePagination(data.projects);
        })
        .catch(error => console.error('Fehler:', error));
    }

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
            checkProfileCompletionWithTransactions(profileData, transactionsData);
        })
        .catch(error => console.error('Fehler:', error));
    }

    function checkProfileCompletionWithTransactions(profileData, transactionsData) {
        const requiredFields = ['vorname', 'nachname', 'adresse', 'zip', 'city'];
        const isProfileIncomplete = requiredFields.some(field => !profileData[field] || profileData[field].trim() === '');
        const hasTransactions = transactionsData.length > 0;
        
        if (isProfileIncomplete && hasTransactions) {
            showProfileCompletionBanner();
        }
    }

    function showProfileCompletionBanner() {
        // Check if the banner already exists
        if (document.getElementById('profileCompletionBanner')) {
            return; // Banner already exists, no need to create another one
        }

        const banner = document.createElement('div');
        banner.id = 'profileCompletionBanner';
        banner.className = 'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4';
        banner.role = 'alert';
        banner.innerHTML = `
            <p class="font-bold">Profilinformation unvollständig</p>
            <p>Bitte vervollständigen Sie Ihr Profil, damit wir Ihre Rechnungen korrekt ausstellen koennen.. 
               <a href="http://auftrag.immoyes.com/index.php?page=profil" class="font-bold underline">Zum Profil</a>
            </p>
        `;
        
        // Insert the banner at the top of the main content area
        const mainContent = document.querySelector('main');
        mainContent.insertBefore(banner, mainContent.firstChild);
    }

    function displayProjectsOrInstructions(projects) {
        const projectsGrid = document.getElementById('projectsGrid');
        const noProjectsInstructions = document.getElementById('noProjectsInstructions');
        const paginationContainer = document.getElementById('pagination');
        
        if (projects.length === 0) {
            projectsGrid.classList.add('hidden');
            noProjectsInstructions.classList.remove('hidden');
            paginationContainer.classList.add('hidden');
            populateInstructions();
        } else {
            projectsGrid.classList.remove('hidden');
            noProjectsInstructions.classList.add('hidden');
            paginationContainer.classList.remove('hidden');
            populateProjects(projects);
        }
    }

    function populateProjects(projects) {
        const projectsGrid = document.getElementById('projectsGrid');
        projectsGrid.innerHTML = '';
        projects.forEach(project => {
            const card = createProjectCard(project);
            projectsGrid.appendChild(card);
        });
    }

    function populateInstructions() {
        const instructionSteps = document.getElementById('instructionSteps');
        const steps = [
            { icon: 'home', title: "Wählen Sie 'Neuer Auftrag'", description: "Klicken Sie auf 'Neuer Auftrag' in der Seitenleiste, um ein neues Projekt zu beginnen." },
            { icon: 'images', title: "Laden Sie Ihre Bilder hoch", description: "Fügen Sie Fotos Ihrer Immobilie hinzu, die Sie umgestalten möchten." },
            { icon: 'couch', title: "Wählen Sie Ihren Möbelstil", description: "Geben Sie an, welchen Stil Sie für Ihre Einrichtung bevorzugen." },
            { icon: 'plus-circle', title: "Bestätigen und erstellen", description: "Überprüfen Sie Ihre Auswahl und erstellen Sie Ihr Projekt." },
            { icon: 'arrow-right', title: "Verfolgen Sie den Fortschritt", description: "Beobachten Sie, wie Ihr Projekt zum Leben erwacht und sehen Sie die Ergebnisse!" }
        ];

        instructionSteps.innerHTML = steps.map((step, index) => `
            <div class="bg-indigo-50 rounded-lg p-6 flex flex-col items-center text-center">
                <div class="bg-indigo-100 rounded-full p-4 mb-4">
                    <i class="fas fa-${step.icon} text-3xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">${index + 1}. ${step.title}</h3>
                <p class="text-gray-600">${step.description}</p>
            </div>
        `).join('');
    }

    function createProjectCard(project) {
        const card = document.createElement('div');
        card.className = 'bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1';
        
        const formattedDate = formatDate(project.created_at);
        const countdown = project.status !== 'completed' ? calculateCountdown(calculateDeadline(new Date(project.created_at))) : null;
        const imageCount = project.image_count || 0;
        const formattedCost = (typeof project.cost === 'number') ? `€${project.cost.toFixed(2)}` : 'N/A';

        let statusDisplay, statusText, timeRemaining;
        switch(project.status) {
            case 'completed':
                statusDisplay = 'bg-green-100 text-green-800';
                statusText = 'Abgeschlossen';
                timeRemaining = 'Projekt fertig';
                break;
            case 'in_progress':
                statusDisplay = 'bg-yellow-100 text-yellow-800';
                statusText = 'In Bearbeitung';
                timeRemaining = countdown ? `${countdown.timeLeft} verbleibend` : 'N/A';
                break;
            case 'pending':
                statusDisplay = 'bg-blue-100 text-blue-800';
                statusText = 'Ausstehend';
                timeRemaining = 'Warten auf Start';
                break;
            case 'failed':
                statusDisplay = 'bg-red-100 text-red-800';
                statusText = 'Fehlgeschlagen';
                timeRemaining = 'N/A';
                break;
            default:
                statusDisplay = 'bg-gray-100 text-gray-800';
                statusText = project.status;
                timeRemaining = 'In Bearbeitung';
        }

        card.innerHTML = `
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 truncate">${project.name || 'Unbenanntes Projekt'}</h3>
                    <span class="px-2 py-1 text-xs font-medium rounded-full ${statusDisplay}">
                        ${statusText}
                    </span>
                </div>
                <div class="space-y-2">
                    <p class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-paint-brush mr-2 text-indigo-500"></i>
                        ${project.furniture_style || '3D Grundriss'}
                    </p>
                    <p class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-images mr-2 text-indigo-500"></i>
                        ${imageCount} ${imageCount === 1 ? 'Bild' : 'Bilder'}
                    </p>
                    <p class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-coins mr-2 text-indigo-500"></i>
                        ${formattedCost}
                    </p>
                </div>
                ${project.status === 'completed' ? `
                    <p class="mt-4 text-sm text-gray-600">
                        <i class="fas fa-calendar mr-2 text-indigo-500"></i>
                        Fertig am ${formattedDate}
                    </p>
                ` : `
                    <div class="mt-4">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-medium text-gray-500">Fortschritt</span>
                            <span class="text-xs font-medium ${timeRemaining === 'Projekt fertig' ? 'text-green-600' : 'text-indigo-600'}">${timeRemaining}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: ${countdown ? countdown.progressPercentage : 0}%"></div>
                        </div>
                    </div>
                `}
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <button onclick="viewProjectDetails(${project.id})" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Details anzeigen
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        `;
        return card;
    }

    function updatePagination(projectsData) {
        const paginationContainer = document.getElementById('pagination');
        paginationContainer.innerHTML = '';
        for (let i = 1; i <= projectsData.pages; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.onclick = () => fetchBatchData(i);
            pageButton.classList.add('mx-1', 'px-3', 'py-1', 'border', 'rounded', 'focus:outline-none', 'focus:ring-2', 'focus:ring-indigo-500');
            if (i === projectsData.current_page) {
                pageButton.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
            } else {
                pageButton.classList.add('bg-white', 'text-indigo-600', 'border-indigo-300', 'hover:bg-indigo-50');
            }
            paginationContainer.appendChild(pageButton);
        }
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        return `${day}.${month}.${year}`;
    }

    function calculateDeadline(startDate) {
        let deadline = new Date(startDate);
        let daysToAdd = 2;

        while (daysToAdd > 0) {
            deadline.setDate(deadline.getDate() + 1);
            if (deadline.getDay() !== 0 && deadline.getDay() !== 6) {
                daysToAdd--;
            }
        }

        return deadline;
    }

    function calculateCountdown(deadlineDate) {
        const now = new Date();
        const timeDiff = deadlineDate - now;
        const hoursLeft = Math.max(0, Math.floor(timeDiff / (1000 * 60 * 60)));
        const minutesLeft = Math.max(0, Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60)));

        const totalHours = 48;
        const progressPercentage = Math.min(100, ((totalHours - hoursLeft) / totalHours) * 100);

        return {
            timeLeft: `${hoursLeft}h ${minutesLeft}m`,
            progressPercentage: progressPercentage.toFixed(2)
        };
    }

    function viewProjectDetails(projectId) {
        window.location.href = `index.php?page=project-details&id=${projectId}`;
    }

    function logout() {
        const token = localStorage.getItem('token');
        
        fetch('https://api.immoyes.com/logout', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            credentials: 'include' // This ensures cookies are sent with the request
        })
        .then(response => {
            if (response.ok) {
                // Clear local storage
                localStorage.clear();
                
                // Clear cookies
                document.cookie.split(";").forEach(function(c) { 
                    document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
                });
                
                // Redirect to login page
                window.location.href = 'http://auftrag.immoyes.com/login.php';
            } else {
                throw new Error('Logout failed');
            }
        })
        .catch(error => {
            console.error('Logout error:', error);
            // Even if the API call fails, we should still clear local data
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

        // Set up Server-Sent Events for real-time updates
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

        // Update unread count initially and every 60 seconds
        updateUnreadCount();
        setInterval(updateUnreadCount, 60000);

        // Mark messages as read when chat page is opened
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
    </script>
</body>
</html>