<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ImmoYes</title>
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
                    <h1 class="text-2xl font-bold ml-3">ImmoYes Admin</h1>
                </div>
                <a href="http://auftrag.immoyes.com/admin.php" class="sidebar-link active flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-project-diagram sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Auftraege</span>
                    </a>
                    <a href="http://auftrag.immoyes.com/pages/operatorchat.php" class="sidebar-link flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-project-diagram sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Chat</span>
                    </a>
                    
                    <a href="http://auftrag.immoyes.com/pages/analytics.php" class="sidebar-link flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-chart-line sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Analytics</span>
                    </a>
                    
            </div>
            
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">Project Management</h2>
                    <div class="flex items-center space-x-4">
                        <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Logout
                        </a>
                    </div>
                </div>
            </header>

            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="mb-6">
                    <input type="text" id="searchInput" placeholder="Search by project name, email or username..." class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-indigo-600 mb-4">Active Projects</h2>
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="activeProjectsTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Active project rows will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-yellow-600 mb-4">Änderung gewünscht</h2>
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="changeRequestedProjectsTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Change requested project rows will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-green-600 mb-4">Completed Projects</h2>
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="completedProjectsTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Completed project rows will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('token');
        let allProjects = [];

        fetch('https://api.immoyes.com/admin/projects', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(projects => {
            allProjects = projects;
            renderProjects(projects);
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error loading projects. Please try again later.', 'error');
        });

        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const filteredProjects = allProjects.filter(project => 
                project.name.toLowerCase().includes(searchTerm) ||
                (project.email && project.email.toLowerCase().includes(searchTerm))
            );
            renderProjects(filteredProjects);
        });
    });

    function renderProjects(projects) {
        const activeProjects = projects.filter(project => 
            project.status.toLowerCase() === 'in bearbeitung'
        );
        const changeRequestedProjects = projects.filter(project => 
            project.status.toLowerCase() === 'änderung gewünscht'
        );
        const completedProjects = projects.filter(project => 
            project.status.toLowerCase() === 'abgeschlossen'
        );

        renderProjectTable(activeProjects, 'activeProjectsTableBody');
        renderProjectTable(changeRequestedProjects, 'changeRequestedProjectsTableBody');
        renderProjectTable(completedProjects, 'completedProjectsTableBody');
    }

    function renderProjectTable(projects, tableId) {
        const tableBody = document.getElementById(tableId);
        tableBody.innerHTML = projects.map(project => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${project.name}</div>
                    <div class="text-sm text-gray-500">${project.description || 'No description'}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(project.status)}">
                        ${project.status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${project.username}</div>
                    <div class="text-sm text-gray-500">${project.email || 'N/A'}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${formatDate(project.created_at)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="project_details.php?id=${project.id}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                </td>
            </tr>
        `).join('');
    }

    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        return new Date(dateString).toLocaleDateString(undefined, options);
    }

    function getStatusColor(status) {
        switch(status.toLowerCase()) {
            case 'in bearbeitung': return 'bg-blue-100 text-blue-800';
            case 'completed': return 'bg-green-100 text-green-800';
            case 'änderung gewünscht': return 'bg-yellow-100 text-yellow-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    function showNotification(message, type) {
        console.log(`${type}: ${message}`);
        // Implement a more sophisticated notification system here if needed
    }
    </script>
</body>
</html>