<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImmoYes Chat</title>
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
        #chatMessages {
            height: calc(100vh - 240px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }
        #chatMessages::-webkit-scrollbar {
            width: 6px;
        }
        #chatMessages::-webkit-scrollbar-track {
            background: transparent;
        }
        #chatMessages::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 20px;
            border: transparent;
        }
        .message {
            max-width: 70%;
            word-wrap: break-word;
            margin-bottom: 12px;
            line-height: 24px;
            position: relative;
            padding: 10px 20px;
            border-radius: 25px;
        }
        .message:before {
            content: "";
            position: absolute;
            bottom: -2px;
            height: 20px;
            width: 20px;
        }
        .user-message {
            color: #fff;
            background: #4F46E5;
            align-self: flex-end;
        }
        .user-message:before {
            right: -7px;
            border-bottom-left-radius: 16px 14px;
            transform: translate(0, -2px);
            background: #4F46E5;
        }
        .user-message:after {
            content: "";
            position: absolute;
            bottom: -2px;
            right: -56px;
            width: 26px;
            height: 20px;
            background: white;
            border-bottom-left-radius: 10px;
            transform: translate(-30px, -2px);
        }
        .assistant-message {
            background: #E5E7EB;
            color: #4B5563;
            align-self: flex-start;
        }
        .assistant-message:before {
            left: -7px;
            border-bottom-right-radius: 16px 14px;
            transform: translate(0, -2px);
            background: #E5E7EB;
        }
        .assistant-message:after {
            content: "";
            position: absolute;
            bottom: -2px;
            left: -56px;
            width: 26px;
            height: 20px;
            background: white;
            border-bottom-right-radius: 10px;
            transform: translate(30px, -2px);
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

        <!-- Main content -->
        <main class="flex-1 flex flex-col overflow-hidden bg-gray-100">
            <!-- Top bar -->
            <header class="bg-white shadow-sm z-10">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">ImmoYes Chat</h2>
                    <div class="flex items-center space-x-4">
                        <span id="userCredits" class="text-sm font-medium text-gray-500"></span>
                        <a href="index.php?page=logout" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Abmelden
                        </a>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-hidden py-4">
                <div class="h-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col">
                    <div class="flex-1 bg-white shadow-lg rounded-lg flex flex-col overflow-hidden">
                        <div class="p-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">Immo Yes - Support Chat</h3>
                        </div>
                        <div id="chatMessages" class="flex-1 p-4 space-y-4 overflow-y-auto flex flex-col"></div>
                        <div class="p-4 bg-gray-50 border-t border-gray-200">
                            <div class="flex items-center space-x-3">
                                <input type="text" id="messageInput" class="flex-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Type your message...">
                                <button id="sendButton" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Send
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    const token = localStorage.getItem('token');
    if (!token) {
        alert('No token found. Please log in.');
        window.location.href = 'index.php?page=login';
        return;
    }

    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const chatNotificationBadge = document.getElementById('chatNotificationBadge');

    function addMessage(message, isUser) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', isUser ? 'user-message' : 'assistant-message');
        messageElement.textContent = message;
        chatMessages.appendChild(messageElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function sendMessage() {
        const message = messageInput.value.trim();
        if (message) {
            addMessage(message, true);
            fetch('https://api.immoyes.com/api/chat', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Message sent successfully', data);
                // Don't add the message here, as it will be received via SSE
            })
            .catch(error => console.error('Error sending message:', error));
            messageInput.value = '';
        }
    }

    sendButton.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    function fetchChatHistory() {
        fetch('https://api.immoyes.com/api/chat/history', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(data => {
            chatMessages.innerHTML = '';
            data.forEach(msg => {
                addMessage(msg.message, msg.is_user_message);
            });
            chatMessages.scrollTop = chatMessages.scrollHeight;
        })
        .catch(error => console.error('Error fetching chat history:', error));
    }

    fetchChatHistory();

    // Set up Server-Sent Events for real-time updates
    let eventSource;

    function setupSSE() {
        eventSource = new EventSource(`https://api.immoyes.com/api/chat/stream?token=${token}`);
        
        eventSource.onmessage = function(event) {
            const data = JSON.parse(event.data);
            console.log('Received message via SSE:', data);
            if (!data.is_user_message) {
                addMessage(data.message, data.is_user_message);
                updateUnreadCount();
            }
        };

        eventSource.onerror = function(error) {
            console.error('EventSource failed:', error);
            eventSource.close();
            setTimeout(setupSSE, 5000);  // Attempt to reconnect after 5 seconds
        };
    }

    setupSSE();

    // Fetch user profile data
    fetch('https://api.immoyes.com/profile', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('userCredits').innerHTML = `
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3" />
                </svg>
               Guthaben ${data.credits.toFixed(2)}€
            </span>
        `;
    })
    .catch(error => console.error('Fehler:', error));

    function updateUnreadCount() {
        fetch('https://api.immoyes.com/api/chat/unread', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.unreadCount > 0) {
                chatNotificationBadge.textContent = data.unreadCount;
                chatNotificationBadge.classList.remove('hidden');
            } else {
                chatNotificationBadge.classList.add('hidden');
            }
        })
        .catch(error => console.error('Error fetching unread count:', error));
    }

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
});
    </script>
</body>
</html>