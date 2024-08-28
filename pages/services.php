<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - ImmoYes</title>
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
        .image-compare {
            position: relative;
            width: 100%;
            height: 300px;
            overflow: hidden;
        }
        .image-compare img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
        }
        .image-compare .before-image {
            z-index: 1;
        }
        .image-compare .after-image {
            z-index: 2;
            clip-path: polygon(50% 0, 100% 0, 100% 100%, 50% 100%);
        }
        .slider {
            position: absolute;
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 100%;
            background: transparent;
            outline: none;
            margin: 0;
            transition: all 0.2s;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 3;
        }
        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 3px;
            height: 300px;
            background: white;
            cursor: ew-resize;
            box-shadow: 0 0 5px rgba(0,0,0,0.5);
        }
        .slider::-moz-range-thumb {
            width: 3px;
            height: 300px;
            background: white;
            cursor: ew-resize;
            box-shadow: 0 0 5px rgba(0,0,0,0.5);
        }
        .slider-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: white;
            box-shadow: 0 0 5px rgba(0,0,0,0.5);
            z-index: 4;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            color: #333;
            pointer-events: none;
        }
        .slider-hint {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            z-index: 5;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .image-compare:hover .slider-hint {
            opacity: 1;
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
                    <a href="http://auftrag.immoyes.com/index.php?page=services" class="sidebar-link active flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
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
                    <h2 class="text-2xl font-semibold text-gray-800">Neuer Auftrag</h2>
                    <div class="flex items-center space-x-4">
                        <span id="userCredits" class="text-sm font-medium text-gray-500"></span>
                        <a href="index.php?page=logout" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Log Out
                        </a>
                    </div>
                </div>
            </header>

            <div class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8">
                <div id="services" class="space-y-6">
                    <!-- Service items will be dynamically inserted here -->
                </div>
            </div>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('token');

        // Fetch user profile data for credits display
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
        .catch(error => console.error('Error fetching user data:', error));

        // Fetch services data from Flask backend
        fetch('https://api.immoyes.com/api/jobs', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(services => {
            const servicesContainer = document.getElementById('services');
            services.forEach(service => {
                const serviceItem = createServiceItem(service);
                servicesContainer.appendChild(serviceItem);
            });
            initializeSliders();
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('services').innerHTML = `<p class="text-red-500">Error loading services: ${error.message}</p>`;
        });
    });

    function createServiceItem(service) {
        const item = document.createElement('div');
        item.className = 'bg-white shadow-md rounded-lg overflow-hidden flex flex-col md:flex-row';
        
        const serviceUrl = service.title.toLowerCase().replace(/ /g, '-');
        
        const { estimatedDate, etaClass } = calculateEstimatedFinishDate(service.title);
        
        item.innerHTML = `
            <div class="w-full md:w-1/2 image-compare">
                <img src="${service.beforeImage}" alt="Before ${service.title}" class="before-image">
                <img src="${service.afterImage}" alt="After ${service.title}" class="after-image">
                <input type="range" min="0" max="100" value="50" class="slider" aria-label="Image comparison slider">
                <div class="slider-button">
                    <i class="fas fa-arrows-alt-h"></i>
                </div>
                <div class="slider-hint">Ziehen Sie den Schieberegler, um zu vergleichen</div>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-between">
                <div>
                    <h2 class="font-bold text-2xl mb-3">${service.title}</h2>
                    <p class="text-gray-700 text-lg mb-3">Preis: ${service.price.toFixed(2)}€</p>
                    <div class="mb-4 bg-gray-100 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Geschätzte Fertigstellung:</p>
                        <div class="flex items-center">
                            <i class="far fa-calendar-alt ${etaClass} mr-2 text-xl"></i>
                            <span class="${etaClass} font-medium text-lg">${estimatedDate}</span>
                        </div>
                    </div>
                </div>
                <a href="index.php?page=${serviceUrl}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg inline-block transition duration-150 ease-in-out text-center text-lg">
                    Jetzt Bestellen
                </a>
            </div>
        `;
        return item;
    }

    function initializeSliders() {
        const sliders = document.querySelectorAll('.slider');
        sliders.forEach(slider => {
            const parentElement = slider.parentElement;
            const afterImage = parentElement.querySelector('.after-image');
            const sliderButton = parentElement.querySelector('.slider-button');

            function updateSlider() {
                const sliderValue = slider.value;
                afterImage.style.clipPath = `polygon(${sliderValue}% 0, 100% 0, 100% 100%, ${sliderValue}% 100%)`;
                sliderButton.style.left = `${sliderValue}%`;
            }

            slider.addEventListener('input', updateSlider);

            // Initial animation
            let direction = 1;
            let currentValue = 50;

            function animate() {
                currentValue += direction;
                if (currentValue >= 100 || currentValue <= 0) {
                    direction *= -1;
                }
                slider.value = currentValue;
                updateSlider();

                if (currentValue !== 50) {
                    requestAnimationFrame(animate);
                }
            }

            setTimeout(() => {
                animate();
            }, 1000);  // Start the animation after 1 second
        });
    }

    function calculateEstimatedFinishDate(serviceTitle) {
        const today = new Date();
        let daysToAdd;

        switch (serviceTitle.toLowerCase()) {
            case 'virtuelles homestaging':
            case '3d grundriss':
                daysToAdd = 3; // 48 hours
                break;
            case 'virtuelle renovierung':
                daysToAdd = 4; // 72 hours
                break;
            default:
            daysToAdd = 3; // Default to 48 hours if service is not recognized
        }

        let estimatedDate = new Date(today);
        let addedDays = 0;

        while (addedDays < daysToAdd) {
            estimatedDate.setDate(estimatedDate.getDate() + 1);
            if (estimatedDate.getDay() !== 0 && estimatedDate.getDay() !== 6) {
                addedDays++;
            }
        }

        const formattedDate = estimatedDate.toLocaleDateString('de-DE', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

        // Determine color class based on delivery speed
        let etaClass;
        if (daysToAdd <= 5) {
            etaClass = 'text-green-600';
        } else if (daysToAdd <= 5) {
            etaClass = 'text-yellow-600';
        } else {
            etaClass = 'text-red-600';
        }

        return { estimatedDate: formattedDate, etaClass };
    }
    </script>
</body>
</html>