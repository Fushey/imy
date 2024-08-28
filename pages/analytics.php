<?php
// dashboard.php

function fetchDataFromApi($endpoint, $params = []) {
    $url = "https://api.immoyes.com/api/analytics/" . $endpoint;
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    $response = file_get_contents($url);
    if ($response === FALSE) {
        // Handle error (e.g., API is down)
        return null;
    }
    return json_decode($response, true);
}

// At the top of your PHP file
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    // This is an AJAX request, return JSON data
    header('Content-Type: application/json');
    $dateRange = isset($_GET['dateRange']) ? $_GET['dateRange'] : 'all';
    $params = ['dateRange' => $dateRange];
    $dashboardData = fetchDataFromApi("dashboard", $params);
    echo json_encode($dashboardData);
    exit;
}

// Rest of your PHP code for initial page load
$dateRange = isset($_GET['dateRange']) ? $_GET['dateRange'] : 'all';
$params = ['dateRange' => $dateRange];
$dashboardData = fetchDataFromApi("dashboard", $params);
$userStats = $dashboardData['userStats'] ?? [];
$projectStats = $dashboardData['projectStats'] ?? [];
$financialStats = $dashboardData['financialStats'] ?? [];
$engagementStats = $dashboardData['engagementStats'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImmoYes Analytics Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <nav>
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
                   
                </nav>
            </div>
            
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">Analytics Dashboard</h2>
                    <div class="flex items-center space-x-4">
                        <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Logout
                        </a>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="px-4 py-6 sm:px-0">
                    <div class="mb-6">
                        <select id="dateRangeSelect" onchange="updateDashboard()" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="all" <?php echo $dateRange === 'all' ? 'selected' : ''; ?>>All Time</option>
                            <option value="last7days" <?php echo $dateRange === 'last7days' ? 'selected' : ''; ?>>Last 7 Days</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- User Statistics Card -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <h3 class="text-lg font-medium text-gray-900">User Statistics</h3>
                                <div class="mt-1 text-3xl font-semibold text-gray-900">
                                    Total Users: <span id="totalUsers"><?php echo $userStats['totalUsers'] ?? 'N/A'; ?></span>
                                </div>
                                <div class="mt-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Admin Users:</span>
                                        <span id="adminUsers" class="font-medium text-gray-900"><?php echo $userStats['adminUsers'] ?? 'N/A'; ?></span>
                                    </div>
                                    <div class="flex justify-between mt-2">
                                        <span class="text-gray-500">Operator Users:</span>
                                        <span id="operatorUsers" class="font-medium text-gray-900"><?php echo $userStats['operatorUsers'] ?? 'N/A'; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Project Overview Card -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <h3 class="text-lg font-medium text-gray-900">Project Overview</h3>
                                <div class="mt-1 text-3xl font-semibold text-gray-900">
                                    Total Projects: <span id="totalProjects"><?php echo $projectStats['totalProjects'] ?? 'N/A'; ?></span>
                                </div>
                                <div class="mt-4">
                                    <canvas id="projectStatusChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Overview Card -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <h3 class="text-lg font-medium text-gray-900">Financial Overview</h3>
                                <div class="mt-1 text-3xl font-semibold text-gray-900">
                                    Total Revenue: $<span id="totalRevenue"><?php echo number_format($financialStats['totalRevenue'] ?? 0, 2); ?></span>
                                </div>
                                <div class="mt-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Avg. Project Cost:</span>
                                        <span id="avgProjectCost" class="font-medium text-gray-900">$<?php echo number_format($financialStats['averageProjectCost'] ?? 0, 2); ?></span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <canvas id="revenueChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Project Types Card -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <h3 class="text-lg font-medium text-gray-900">Project Types</h3>
                                <div class="mt-4">
                                    <canvas id="projectTypesChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Engagement Metrics Card -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <h3 class="text-lg font-medium text-gray-900">Engagement Metrics</h3>
                                <div class="mt-1 text-3xl font-semibold text-gray-900">
                                    Total Messages: <span id="totalMessages"><?php echo number_format($engagementStats['totalMessages'] ?? 0); ?></span>
                                </div>
                                <div class="mt-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Avg. Messages per Project:</span>
                                        <span id="avgMessagesPerProject" class="font-medium text-gray-900"><?php echo number_format($engagementStats['averageMessagesPerProject'] ?? 0, 2); ?></span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <canvas id="messagesTrendChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
    // Utility function to safely get nested properties
    function safeGet(obj, path) {
        return path.split('.').reduce((acc, part) => acc && acc[part], obj);
    }

    // Chart.js Global Configuration
    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.color = '#374151';

    // Initialize charts
    let projectStatusChart, revenueChart, projectTypesChart, messagesTrendChart;

    // Function to update the dashboard based on the selected date range
    async function updateDashboard() {
        const dateRange = document.getElementById('dateRangeSelect').value;
        const response = await fetch(`index.php?page=analytics&dateRange=${dateRange}&ajax=1`);
        const data = await response.json();

        // Update user statistics
        document.getElementById('totalUsers').textContent = data.userStats.totalUsers;
        document.getElementById('adminUsers').textContent = data.userStats.adminUsers;
        document.getElementById('operatorUsers').textContent = data.userStats.operatorUsers;

        // Update project statistics
        document.getElementById('totalProjects').textContent = data.projectStats.totalProjects;

        // Update financial statistics
        document.getElementById('totalRevenue').textContent = data.financialStats.totalRevenue.toFixed(2);
        document.getElementById('avgProjectCost').textContent = '$' + data.financialStats.averageProjectCost.toFixed(2);

        // Update engagement statistics
        document.getElementById('totalMessages').textContent = data.engagementStats.totalMessages.toLocaleString();
        document.getElementById('avgMessagesPerProject').textContent = data.engagementStats.averageMessagesPerProject.toFixed(2);

        // Update charts
        updateProjectStatusChart(data.projectStats.projectsByStatus);
        updateRevenueChart(data.financialStats.monthlyRevenue);
        updateProjectTypesChart(data.projectStats.projectsByType);
        updateMessagesTrendChart(data.engagementStats.messagesTrend);
    }

    // Function to update Project Status Chart
    function updateProjectStatusChart(data) {
        projectStatusChart.data.labels = data.map(item => item.name);
        projectStatusChart.data.datasets[0].data = data.map(item => item.value);
        projectStatusChart.update();
    }

    // Function to update Revenue Chart
    function updateRevenueChart(data) {
        revenueChart.data.labels = data.map(item => item.month);
        revenueChart.data.datasets[0].data = data.map(item => item.revenue);
        revenueChart.update();
    }

    // Function to update Project Types Chart
    function updateProjectTypesChart(data) {
        projectTypesChart.data.labels = data.map(item => item.name);
        projectTypesChart.data.datasets[0].data = data.map(item => item.value);
        projectTypesChart.update();
    }

    // Function to update Messages Trend Chart
    function updateMessagesTrendChart(data) {
        messagesTrendChart.data.labels = data.map(item => item.day);
        messagesTrendChart.data.datasets[0].data = data.map(item => item.messages);
        messagesTrendChart.update();
    }

    // Initialize charts when the page loads
    document.addEventListener('DOMContentLoaded', () => {
        // Project Status Chart
        projectStatusChart = new Chart(document.getElementById('projectStatusChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_column($projectStats['projectsByStatus'] ?? [], 'name')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($projectStats['projectsByStatus'] ?? [], 'value')); ?>,
                    backgroundColor: ['#3B82F6', '#10B981', '#EF4444', '#F59E0B'],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: false }
                },
                cutout: '60%',
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        // Revenue Chart
        revenueChart = new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($financialStats['monthlyRevenue'] ?? [], 'month')); ?>,
                datasets: [{
                    label: 'Monthly Revenue',
                    data: <?php echo json_encode(array_column($financialStats['monthlyRevenue'] ?? [], 'revenue')); ?>,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            callback: function(value, index, values) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    x: { grid: { display: false } }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Project Types Chart
        projectTypesChart = new Chart(document.getElementById('projectTypesChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($projectStats['projectsByType'] ?? [], 'name')); ?>,
                datasets: [{
                    label: 'Number of Projects',
                    data: <?php echo json_encode(array_column($projectStats['projectsByType'] ?? [], 'value')); ?>,
                    backgroundColor: '#10B981'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    x: { grid: { display: false } }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                }
            }
        });

        // Messages Trend Chart
        messagesTrendChart = new Chart(document.getElementById('messagesTrendChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($engagementStats['messagesTrend'] ?? [], 'day')); ?>,
                datasets: [{
                    label: 'Messages',
                    data: <?php echo json_encode(array_column($engagementStats['messagesTrend'] ?? [], 'messages')); ?>,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    x: { grid: { display: false } }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeOutCubic'
                }
            }
        });
    });
    </script>
</body>
</html>