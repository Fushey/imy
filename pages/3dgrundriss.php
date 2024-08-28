<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Grundrisse - ImmoYes</title>
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
        .step-inactive { display: none; }
        .file-item { transition: all 0.3s ease; }
        .file-item:hover { transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .upsell-option {
            transition: all 0.3s ease;
        }
        .upsell-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border-left-color: #4F46E5;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        .notification.show {
            opacity: 1;
        }
        .notification.success {
            background-color: #48BB78;
        }
        .notification.error {
            background-color: #F56565;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
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

        <!-- Hauptinhalt -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Obere Navigation -->
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">3D Grundrisse</h2>
                    <div class="flex items-center space-x-4">
                        <span id="userCredits" class="text-sm font-medium text-gray-500"></span>
                        <a href="index.php?page=logout" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Abmelden
                        </a>
                    </div>
                </div>
            </header>

            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold">SCHRITT <span id="currentStep">1</span>/3</h2>
                            <div>
                                <span class="bg-indigo-500 text-white px-2 py-1 rounded">69,00 € pro Grundriss</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                            <div class="bg-indigo-500 h-2.5 rounded-full" style="width: 33.33%"></div>
                        </div>
                    </div>

                    <form id="3dFloorplanForm">
                        <!-- Schritt 1 -->
                        <div id="step1" class="step">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="jobTitle">
                                    AUFTRAGSTITEL *
                                </label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="jobTitle" type="text" required>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">
                                HINWEIS: 3D-Grundrisse werden auf Basis Ihres 2D-Grundrisses oder Ihrer Skizze erstellt. Bitte stellen Sie sicher, dass Ihr Grundriss Maßangaben und Raumbeschriftungen für bestmögliche Ergebnisse enthält.
                            </p>
                        </div>

                        <!-- Schritt 2 -->
                        <div id="step2" class="step step-inactive">
                            <h3 class="text-lg font-semibold mb-4">GRUNDRISSE HOCHLADEN</h3>
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Bitte laden Sie Ihre 2D-Grundrisse oder Skizzen hoch. Akzeptierte Dateiformate sind .jpg, .png, .pdf.</p>
                                <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                    <p class="text-gray-500 mb-2">[ DATEIEN HIER ABLEGEN ]</p>
                                    <input type="file" id="fileInput" multiple accept=".jpg,.jpeg,.png,.pdf" class="hidden">
                                    <button type="button" id="selectFiles" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                        DATEIEN AUSWÄHLEN
                                    </button>
                                </div>
                                <div id="fileList" class="mt-4 space-y-4"></div>
                            </div>
                            <div id="totalPrice" class="text-xl font-bold mt-4"></div>
                        </div>

                        <!-- Schritt 3: Zusatzleistungen -->
                        <div id="step3" class="step step-inactive">
                            <h3 class="text-lg font-semibold mb-4">ZUSÄTZLICHE LEISTUNGEN</h3>
                            <div class="space-y-4">
                                <div class="upsell-option bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold">48-Stunden-Express-Service</h4>
                                        <p class="text-sm text-gray-600">Erhalten Sie Ihren 3D-Grundriss in nur 48 Stunden</p>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-indigo-600 font-semibold mr-2">+25 €</span>
                                        <input type="checkbox" id="expressService" class="form-checkbox h-5 w-5 text-indigo-600">
                                    </div>
                                </div>
                            </div>
                            <div id="totalPriceWithUpsells" class="mt-6 text-xl font-bold"></div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" id="prevStep" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Zurück
                            </button>
                            <button type="button" id="nextStep" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Nächster Schritt
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <div id="loadingSpinner" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50 hidden">
        <div class="spinner"></div>
    </div>

    <div id="notification" class="notification"></div>

    <script>
    let currentStep = 1;
    const totalSteps = 3;
    const fileList = [];
    const pricePerFloorplan = 69;
    const expressServicePrice = 25;

    const floorOptions = [
        "Erdgeschoss",
        "1. Stock",
        "2. Stock",
        "3. Stock",
        "4. Stock",
        "Keller",
        "Dachgeschoss"
    ];

    function validateCurrentStep() {
        switch (currentStep) {
            case 1:
                const jobTitle = document.getElementById('jobTitle').value.trim();
                if (!jobTitle) {
                    showNotification('Bitte geben Sie einen Auftragstitel ein.', 'error');
                    return false;
                }
                return true;
            case 2:
                if (fileList.length === 0) {
                    showNotification('Bitte laden Sie mindestens einen Grundriss hoch.', 'error');
                    return false;
                }
                for (let file of fileList) {
                    if (!file.floor) {
                        showNotification('Bitte wählen Sie für jeden hochgeladenen Grundriss ein Stockwerk aus.', 'error');
                        return false;
                    }
                }
                return true;
            case 3:
                // No mandatory fields in step 3
                return true;
            default:
                return true;
        }
    }

    function updateStep(step) {
    document.querySelectorAll('.step').forEach(s => s.classList.add('step-inactive'));
    document.getElementById(`step${step}`).classList.remove('step-inactive');
    document.getElementById('currentStep').textContent = step;
    document.querySelector('.bg-indigo-500.h-2\\.5').style.width = `${(step / totalSteps) * 100}%`;
    
    document.getElementById('prevStep').style.display = step === 1 ? 'none' : 'block';
    document.getElementById('nextStep').textContent = step === totalSteps ? 'Absenden' : 'Nächster Schritt';
    
    if (step === totalSteps) {
        updateTotalPrice();
    }
}

document.getElementById('nextStep').addEventListener('click', async () => {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                currentStep++;
                await updateStep(currentStep);
            } else {
                document.getElementById('3dFloorplanForm').dispatchEvent(new Event('submit'));
            }
        }
    });

    document.getElementById('prevStep').addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            updateStep(currentStep);
        }
    });

    document.getElementById('selectFiles').addEventListener('click', () => {
        document.getElementById('fileInput').click();
    });

    document.getElementById('fileInput').addEventListener('change', handleFileSelect);

    const dropZone = document.getElementById('dropZone');
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.add('bg-gray-100');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('bg-gray-100');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('bg-gray-100');
        handleFileSelect(e);
    });

    function handleFileSelect(e) {
        const files = e.target.files || e.dataTransfer.files;
        for (let file of files) {
            if (isValidFile(file)) {
                if (!fileList.some(f => f.file.name === file.name)) {
                    fileList.push({
                        file: file,
                        floor: '',
                        notes: ''
                    });
                } else {
                    showNotification(`Die Datei ${file.name} wurde bereits hinzugefügt.`, 'error');
                }
            } else {
                showNotification(`Ungültiger Dateityp: ${file.name}. Bitte laden Sie nur .jpg, .png oder .pdf Dateien hoch.`, 'error');
            }
        }
        updateFileList();
    }

    function isValidFile(file) {
        const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        return validTypes.includes(file.type);
    }

    function updateFileList() {
        const fileListElement = document.getElementById('fileList');
        fileListElement.innerHTML = '';
        fileList.forEach((fileInfo, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item bg-white shadow-md rounded-lg p-6 mb-4 transition-all duration-300 ease-in-out';
            fileItem.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <span class="font-semibold text-lg">${fileInfo.file.name}</span>
                    <button type="button" class="text-red-500 hover:text-red-700 transition-colors duration-300" onclick="removeFile(${index})">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stockwerk</label>
                        <select class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-indigo-500 transition-colors duration-300" onchange="updateFileInfo(${index}, 'floor', this.value)">
                            <option value="">Stockwerk auswählen...</option>
                            ${floorOptions.map(floor => `<option value="${floor}" ${fileInfo.floor === floor ? 'selected' : ''}>${floor}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Anmerkungen</label>
                        <textarea class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-indigo-500 transition-colors duration-300" rows="3" onchange="updateFileInfo(${index}, 'notes', this.value)" placeholder="Fügen Sie hier spezifische Anweisungen hinzu...">${fileInfo.notes}</textarea>
                    </div>
                    <div class="flex justify-center items-center">
                        <div class="w-32 h-32 overflow-hidden rounded-lg">
                            ${fileInfo.file.type === 'application/pdf' 
                                ? '<img src="/path/to/pdf-icon.png" alt="PDF" class="w-full h-full object-cover">'
                                : `<img src="${URL.createObjectURL(fileInfo.file)}" alt="Vorschau" class="w-full h-full object-cover">`
                            }
                        </div>
                    </div>
                </div>
            `;
            fileListElement.appendChild(fileItem);
        });
        updateTotalPrice();
    }

    function updateFileInfo(index, field, value) {
        fileList[index][field] = value;
    }

    function removeFile(index) {
        fileList.splice(index, 1);
        updateFileList();
    }

    function updateTotalPrice() {
        const totalPriceElement = document.getElementById('totalPrice');
        const totalPriceWithUpsellsElement = document.getElementById('totalPriceWithUpsells');
        let basePrice = fileList.length * pricePerFloorplan;
        let totalPrice = basePrice;
        
        const expressService = document.getElementById('expressService');
        
        if (expressService && expressService.checked) {
            totalPrice += expressServicePrice;
        }
        
        totalPriceElement.textContent = `Grundpreis: ${basePrice.toFixed(2)}€`;
        totalPriceWithUpsellsElement.textContent = `Gesamt: ${totalPrice.toFixed(2)}€`;
        
        updateSubmitButton(totalPrice);
    }

    document.getElementById('expressService').addEventListener('change', updateTotalPrice);

    async function updateSubmitButton(totalPrice) {
        try {
            const creditsResponse = await fetch('https://api.immoyes.com/api/user-credits', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });
            const creditsData = await creditsResponse.json();
            const userCredits = creditsData.credits;

            const submitButton = document.getElementById('nextStep');
            if (currentStep === totalSteps) {
                if (userCredits >= totalPrice) {
                    submitButton.textContent = 'Absenden';
                    submitButton.className = 'bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded';
                } else {
                    submitButton.textContent = 'Als Entwurf speichern & Punkte kaufen';
                    submitButton.className = 'bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded';
                }
            }
        } catch (error) {
            console.error('Fehler beim Abrufen der Benutzerguthaben:', error);
        }
    }

    function showSpinner() {
        document.getElementById('loadingSpinner').classList.remove('hidden');
    }

    function hideSpinner() {
        document.getElementById('loadingSpinner').classList.add('hidden');
    }

    function showNotification(message, type) {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.className = 'notification';
        notification.classList.add(type, 'show');
        setTimeout(() => {
            notification.classList.remove('show');
        }, 5000);
    }

    document.getElementById('3dFloorplanForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if (!validateCurrentStep()) {
            return;
        }

        const formData = new FormData();
        
        formData.append('jobTitle', document.getElementById('jobTitle').value);
        let totalPrice = fileList.length * pricePerFloorplan;

        const expressService = document.getElementById('expressService');
        
        formData.append('expressService', expressService.checked);
        
        if (expressService.checked) {
            totalPrice += expressServicePrice;
        }
        
        formData.append('totalPrice', totalPrice.toFixed(2));

        fileList.forEach((fileInfo, index) => {
            formData.append(`files`, fileInfo.file);
            formData.append(`floors[${index}]`, fileInfo.floor);
            formData.append(`notes[${index}]`, fileInfo.notes);
        });

        try {
            const creditsResponse = await fetch('https://api.immoyes.com/api/user-credits', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });
            const creditsData = await creditsResponse.json();
            const userCredits = creditsData.credits;

            if (userCredits >= totalPrice) {
                showSpinner();
                
                // Simulate a 3-second delay
                await new Promise(resolve => setTimeout(resolve, 3000));

                const response = await fetch('https://api.immoyes.com/api/3d-floorplan', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    },
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Fehler beim Einreichen des 3D-Grundriss-Auftrags');
                }

                const result = await response.json();
                hideSpinner();
                showNotification(`3D-Grundriss-Auftrag erfolgreich eingereicht! Verbleibendes Guthaben: ${result.remaining_credits}€`, 'success');
                setTimeout(() => {
                    window.location.href = 'index.php?page=dashboard';
                }, 2000);
            } else {
                const draftResponse = await fetch('https://api.immoyes.com/api/3d-floorplan/draft', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    },
                    body: formData
                });

                if (!draftResponse.ok) {
                    throw new Error('Fehler beim Speichern des 3D-Grundriss-Auftrags als Entwurf');
                }

                const draftResult = await draftResponse.json();
                showNotification('3D-Grundriss-Auftrag als Entwurf gespeichert. Weiterleitung zum Guthaben-Kauf.', 'success');
                setTimeout(() => {
                    window.location.href = 'http://auftrag.immoyes.com/index.php?page=aufladen';
                }, 2000);
            }
        } catch (error) {
            console.error('Fehler:', error);
            hideSpinner();
            showNotification('Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut.', 'error');
        }
    });

    // Initial call to set up the form
    updateStep(currentStep);
    </script>
</body>
</html>