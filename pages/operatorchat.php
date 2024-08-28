<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImmoYes Operator Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        #chatMessages {
            height: calc(100vh - 200px);
            overflow-y: auto;
        }
        #userList {
            height: calc(100vh - 200px);
            overflow-y: auto;
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
                    <h1 class="text-2xl font-bold ml-3">ImmoYes Admin</h1>
                </div>
                <nav>
                <a href="http://auftrag.immoyes.com/admin.php" class="sidebar-link flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-project-diagram sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Auftraege</span>
                    </a>
                    <a href="http://auftrag.immoyes.com/pages/operatorchat.php" class="sidebar-link active flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-project-diagram sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Chat</span>
                    </a>
                    
                    <a href="http://auftrag.immoyes.com/pages/analytics.php" class="sidebar-link flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-chart-line sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Analytics</span>
                    </a>
                    
                </nav>
            </div>
            
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">Operator Chat</h2>
                    <div class="flex items-center space-x-4">
                        <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Logout
                        </a>
                    </div>
                </div>
            </header>

            <!-- Chat Interface -->
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="flex">
                    <!-- User List -->
                    <div class="w-1/4 bg-white rounded-lg shadow-md mr-4 p-4">
                        <h2 class="text-xl font-semibold mb-4">Active Users</h2>
                        <ul id="userList" class="space-y-2">
                            <!-- Active users will be dynamically added here -->
                        </ul>
                    </div>
                    <!-- Chat Area -->
                    <div class="w-3/4 bg-white rounded-lg shadow-md overflow-hidden">
                        <div id="chatMessages" class="p-4"></div>
                        <div class="p-4 border-t">
                            <div class="flex items-center">
                                <input type="text" id="messageInput" class="flex-1 p-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Type your message...">
                                <button id="sendButton" class="bg-indigo-600 text-white px-4 py-2 rounded-r-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Send</button>
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
            // Redirect to login page or handle this case as needed
            return;
        }

        const userList = document.getElementById('userList');
        const chatMessages = document.getElementById('chatMessages');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');

        let currentUserId = null;

        function addMessage(message, isUserMessage) {
            const messageElement = document.createElement('div');
            messageElement.classList.add('mb-2', 'p-2', 'rounded-lg', 'max-w-xs');
            
            if (isUserMessage) {
                messageElement.classList.add('bg-gray-100', 'mr-auto');
            } else {
                messageElement.classList.add('bg-indigo-100', 'text-right', 'ml-auto');
            }
            
            messageElement.textContent = message;
            chatMessages.appendChild(messageElement);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function sendMessage() {
            const message = messageInput.value.trim();
            if (message && currentUserId) {
                fetch('https://api.immoyes.com/api/operator/reply', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ user_id: currentUserId, message: message })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Message sent successfully', data);
                    // The message will be added via SSE
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

        function fetchActiveChats() {
            fetch('https://api.immoyes.com/api/operator/chats', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => response.json())
            .then(data => {
                userList.innerHTML = '';
                data.forEach(chat => {
                    const userElement = document.createElement('li');
                    userElement.classList.add('p-2', 'hover:bg-gray-100', 'cursor-pointer');
                    userElement.textContent = `User ${chat.user_id}`;
                    userElement.onclick = () => loadUserChat(chat.user_id);
                    userList.appendChild(userElement);
                });
            })
            .catch(error => console.error('Error fetching active chats:', error));
        }

        function loadUserChat(userId) {
            currentUserId = userId;
            fetch(`https://api.immoyes.com/api/operator/chat/${userId}`, {
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
            })
            .catch(error => console.error('Error loading user chat:', error));
        }

        fetchActiveChats();

        // Set up Server-Sent Events for real-time updates
        const eventSource = new EventSource(`https://api.immoyes.com/api/operator/stream?token=${token}`);
        eventSource.onmessage = function(event) {
            const data = JSON.parse(event.data);
            if (data.user_id === currentUserId) {
                addMessage(data.message, data.is_user_message);
            }
            // Refresh the active chats list
            fetchActiveChats();
        };
        eventSource.onerror = function(error) {
            console.error('EventSource failed:', error);
            setTimeout(() => {
                eventSource.close();
                // You might want to call a function here to reinitialize the EventSource
                console.log('Attempting to reconnect...');
            }, 5000);
        };
    });
    </script>
</body>
</html>