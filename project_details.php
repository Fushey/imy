<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projektdetails - ImmoYes</title>
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
                </nav>
            </div>
            <div class="mt-auto p-6">
                <div class="bg-white bg-opacity-10 rounded-lg p-4">
                    <h3 class="text-sm font-semibold mb-2">Benötigen Sie Hilfe?</h3>
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
                    <h2 class="text-2xl font-semibold text-gray-800">Projektdetails</h2>
                    <div class="flex items-center space-x-4">
                        <span id="userCredits" class="text-sm font-medium text-gray-500"></span>
                        <a href="index.php?page=logout" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Abmelden
                        </a>
                    </div>
                </div>
            </header>

            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Neue Aufgabenliste-Sektion -->
                <div id="project-task-list" class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Projektaufgaben</h2>
                        <ul id="task-list" class="space-y-3">
                            <!-- Aufgabenelemente werden hier dynamisch eingefügt -->
                        </ul>
                    </div>
                </div>

                <div id="project-details" class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                    <div class="px-4 py-5 sm:p-6">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">Projektdetails</h1>
                        <div id="project-info" class="space-y-4"></div>
                    </div>
                </div>

                <div id="project-images" class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Projektbilder und finale Bilder</h2>
                        <div id="image-container" class="grid grid-cols-1 md:grid-cols-2 gap-6"></div>
                    </div>
                </div>

                <div id="revision-history" class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Revisionsverlauf</h2>
                        <div id="revision-container" class="space-y-4"></div>
                    </div>
                </div>

                <div id="revision-request" class="bg-white shadow-lg rounded-lg overflow-hidden mb-8 hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Revision anfordern</h2>
                        <form id="revision-form" class="space-y-6">
                            <div id="revision-inputs"></div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                <i class="fas fa-paper-plane mr-2"></i> Revisionsanfrage einreichen
                            </button>
                        </form>
                    </div>
                </div>

                <div id="project-status" class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Projektstatus ändern</h2>
                        <div class="flex items-center space-x-4">
                            <select id="statusSelect" class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="In Bearbeitung">In Bearbeitung</option>
                                <option value="abgeschlossen">abgeschlossen</option>
                                <option value="Revision Requested">Revision angefordert</option>
                            </select>
                            <button onclick="changeProjectStatus()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
                                Status aktualisieren
                            </button>
                        </div>
                    </div>
                </div>

                <div id="project-briefing" class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Projektbriefing</h2>
                        <button id="generateBriefing" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg mb-4">
                            Projektbriefing generieren
                        </button>
                        <textarea id="briefingText" rows="6" class="w-full p-2 border rounded-md mb-4" readonly></textarea>
                        <button id="copyBriefing" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg">
                            In Zwischenablage kopieren
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="notification" class="fixed bottom-4 right-4 max-w-md w-full bg-white border-t-4 rounded-b shadow-md transform transition-all duration-300 ease-in-out translate-y-full"></div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('token');
        const urlParams = new URLSearchParams(window.location.search);
        const projectId = urlParams.get('id');

        fetch(`https://api.immoyes.com/admin/project/${projectId}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Fehler beim Laden der Projektdetails');
            }
            return response.json();
        })
        .then(project => {
            console.log('Projektdaten:', project);

            // Aufgabenliste aktualisieren
            updateTaskList(project);

            const detailsContainer = document.getElementById('project-info');
            detailsContainer.innerHTML = `
                <h2 class="text-2xl font-semibold text-gray-900">${project.name}</h2>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">${project.description}</p>
                <div class="mt-4 border-t border-gray-200 pt-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(project.status)}">
                                    ${project.status}
                                </span>
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Kosten</dt>
                            <dd class="mt-1 text-sm text-gray-900">€${project.cost.toFixed(2)}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Möbelstil</dt>
                            <dd class="mt-1 text-sm text-gray-900">${project.furniture_style || 'Nicht angegeben'}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Erstellt am</dt>
                            <dd class="mt-1 text-sm text-gray-900">${formatDate(project.created_at)}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Benutzer-E-Mail</dt>
                            <dd class="mt-1 text-sm text-gray-900">${project.email}</dd>
                        </div></dl>
                </div>
            `;

            const imageContainer = document.getElementById('image-container');
            imageContainer.innerHTML = '';

            if (!project.images || project.images.length === 0) {
                imageContainer.innerHTML = '<p class="text-gray-500 italic col-span-2">Keine Bilder für dieses Projekt verfügbar.</p>';
            } else {
                project.images.forEach((image, index) => {
                    const imageCard = document.createElement('div');
                    imageCard.className = 'bg-white rounded-lg shadow-md overflow-hidden transition transform hover:scale-105 hover:shadow-xl';
                    const imagePath = image.file_path.split('\\').pop().split('/').pop();
                    
                    let finalImageHtml = '';
                    if (project.final_images && project.final_images[index]) {
                        finalImageHtml = `
                            <img src="http://auftrag.immoyes.com/${project.final_images[index]}" alt="Finales Projektbild" class="w-full h-48 object-cover mb-2">
                            <form class="final-image-upload-form" data-index="${index}">
                                <input type="file" name="file" accept="image/*" class="hidden" id="file-input-${index}">
                                <label for="file-input-${index}" class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg inline-block mb-2">
                                    Neues Bild wählen
                                </label>
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg">
                                    Hochladen
                                </button>
                            </form>
                        `;
                    } else {
                        finalImageHtml = `
                            <div class="h-48 bg-gray-200 flex items-center justify-center mb-2">
                                <p class="text-gray-500">Noch kein finales Bild</p>
                            </div>
                            <form class="final-image-upload-form" data-index="${index}">
                                <input type="file" name="file" accept="image/*" class="hidden" id="file-input-${index}">
                                <label for="file-input-${index}" class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg inline-block mb-2">
                                    Bild wählen
                                </label>
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg">
                                    Hochladen
                                </button>
                            </form>
                        `;
                    }

                    imageCard.innerHTML = `
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <img src="http://auftrag.immoyes.com/upload/${project.email}/${projectId}/${imagePath}" alt="Projektbild" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="font-semibold text-lg mb-2">${image.room_type || `Bild ${index + 1}`}</h3>
                                    <p class="text-sm text-gray-600">${image.notes || 'Keine Notizen'}</p>
                                </div>
                            </div>
                            <div>
                                ${finalImageHtml}
                            </div>
                        </div>
                    `;
                    imageContainer.appendChild(imageCard);
                });

                // Event-Listener für Formulare zum Hochladen finaler Bilder hinzufügen
                document.querySelectorAll('.final-image-upload-form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const index = this.getAttribute('data-index');
                        const formData = new FormData(this);
                        uploadFinalImage(projectId, index, formData);
                    });
                });
            }

            const revisionContainer = document.getElementById('revision-container');
revisionContainer.innerHTML = '';

if (!project.revisions || project.revisions.length === 0) {
    console.log('Keine Revisionen gefunden');
    revisionContainer.innerHTML = '<p class="text-gray-500 italic">Kein Revisionsverlauf für dieses Projekt verfügbar.</p>';
} else {
    console.log('Revisionen gefunden:', project.revisions);
    
    project.revisions.forEach((revision, index) => {
        console.log(`Verarbeite Revision ${index}:`, revision);
        const revisionCard = document.createElement('div');
        revisionCard.className = 'bg-gray-50 rounded-lg p-4 shadow mb-4';
        
        const date = new Date(revision.created_at).toLocaleString();
        const associatedImage = project.images.find(img => img.id === revision.image_id);
        console.log('Zugehöriges Bild:', associatedImage);

        revisionCard.innerHTML = `
            <div class="flex items-start space-x-4">
                ${associatedImage ? `
                    <img src="http://auftrag.immoyes.com/upload/${project.email}/${projectId}/${associatedImage.file_path.split('\\').pop().split('/').pop()}" alt="Zugehöriges Bild" class="w-24 h-24 object-cover rounded-md">
                ` : '<div class="w-24 h-24 bg-gray-200 flex items-center justify-center text-gray-400">Kein Bild</div>'}
                <div class="flex-grow">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Revision ${index + 1}</h3>
                        <span class="text-sm text-gray-500">${date}</span>
                    </div>
                    <p class="mt-2 text-gray-600">${revision.revision_text}</p>
                    ${associatedImage ? `
                        <p class="mt-1 text-sm text-gray-500">Zugehörig zu: ${associatedImage.room_type || 'Unbenannter Raum'}</p>
                    ` : `<p class="mt-1 text-sm text-italic text-gray-500">Kein zugehöriges Bild (Bild-ID: ${revision.image_id})</p>`}
                </div>
            </div>
        `;
        revisionContainer.appendChild(revisionCard);
    });
}


            if (project.status.toLowerCase() === 'fertig') {
                const revisionRequest = document.getElementById('revision-request');
                revisionRequest.classList.remove('hidden');

                const revisionInputs = document.getElementById('revision-inputs');
                revisionInputs.innerHTML = '';
                project.images.forEach((image, index) => {
                    const inputField = document.createElement('div');
                    inputField.className = 'space-y-1 mb-4';
                    inputField.innerHTML = `
                        <div class="flex items-start space-x-4 mb-2">
                            <img src="http://auftrag.immoyes.com/upload/${image.file_path.split('\\').pop().split('/').pop()}" alt="Projektbild" class="w-24 h-24 object-cover rounded-md">
                            <div>
                                <h4 class="font-semibold">${image.room_type || `Bild ${index + 1}`}</h4>
                                <p class="text-sm text-gray-600">${image.notes || 'Keine Notizen'}</p>
                            </div>
                        </div>
                        <label class="block text-sm font-medium text-gray-700" for="revision-${image.id}">
                            Revision für ${image.room_type || `Bild ${index + 1}`}:
                        </label>
                        <textarea id="revision-${image.id}" name="revision-${image.id}" rows="3" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Geben Sie Revisionsdetails ein"></textarea>
                    `;
                    revisionInputs.appendChild(inputField);
                });

                document.getElementById('revision-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const revisionData = {
                        revisions: project.images.map(image => ({
                            image_id: image.id,
                            revision_text: document.getElementById(`revision-${image.id}`).value.trim()
                        })).filter(revision => revision.revision_text !== '')
                    };

                    fetch(`https://api.immoyes.com/project/${projectId}/revision`, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(revisionData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        showNotification('Revisionsanfrage erfolgreich eingereicht!', 'success');
                        setTimeout(() => location.reload(), 2000);
                    })
                    .catch(error => {
                        console.error('Fehler:', error);
                        showNotification(`Fehler beim Einreichen der Revisionsanfrage. ${error.message || 'Bitte versuchen Sie es erneut.'}`, 'error');
                    });
                });
            } else {
                const revisionRequest = document.getElementById('revision-request');
                revisionRequest.classList.add('hidden');
            }

            document.getElementById('statusSelect').value = project.status;
        })
        .catch(error => {
            console.error('Fehler:', error);
            showNotification('Fehler beim Laden der Projektdetails. Bitte versuchen Sie es später erneut.', 'error');
        });

        fetch('https://api.immoyes.com/profile', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Fehler beim Abrufen der Benutzerdaten');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('userCredits').innerHTML = `
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3" />
                    </svg>
                    €${data.credits.toFixed(2)}
                </span>
            `;
        })
        .catch(error => {
            console.error('Fehler beim Abrufen der Benutzerdaten:', error);
            document.getElementById('userCredits').textContent = 'Guthaben: Fehler';
        });
    });

    function updateTaskList(project) {
    const taskList = document.getElementById('task-list');
    taskList.innerHTML = ''; // Bestehende Aufgaben löschen

    const tasks = [
        {
            id: 1,
            text: 'Finale Bilder hochladen',
            completed: project.final_images && project.final_images.every(img => img !== null)
        },
        {
            id: 2,
            text: 'Revisionen abschließen',
            completed: project.status !== 'Änderung gewünscht' && (!project.revisions || project.revisions.length === 0 || project.status !== 'In Revision')
        },
        {
            id: 3,
            text: 'Projekt als abgeschlossen markieren',
            completed: project.status === 'Completed' || project.status === 'abgeschlossen'
        }
    ];

    tasks.forEach(task => {
        const listItem = document.createElement('li');
        listItem.className = 'flex items-center space-x-3';
        listItem.innerHTML = `
            ${task.completed ? 
                '<svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' : 
                '<svg class="h-6 w-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
            }
            <span class="text-lg ${task.completed ? 'text-gray-500' : 'text-red-500 font-bold'}">
                ${task.text}
            </span>
        `;
        taskList.appendChild(listItem);
    });
}

    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        return new Date(dateString).toLocaleDateString(undefined, options);
    }

    function getStatusColor(status) {
        switch(status.toLowerCase()) {
            case 'pending': return 'bg-yellow-100 text-yellow-800';
            case 'in progress': return 'bg-blue-100 text-blue-800';
            case 'completed': return 'bg-green-100 text-green-800';
            case 'fertig': return 'bg-green-100 text-green-800';
            case 'in revision': return 'bg-purple-100 text-purple-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    function showNotification(message, type) {
        const notification = document.getElementById('notification');
        notification.className = `fixed bottom-4 right-4 max-w-md w-full bg-white border-t-4 rounded-b shadow-md transform transition-all duration-300 ease-in-out ${type === 'success' ? 'border-green-500' : 'border-red-500'}`;
        notification.innerHTML = `
            <div class="p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        ${type === 'success' 
                            ? '<svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
                            : '<svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>'}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm leading-5 font-medium ${type === 'success' ? 'text-green-800' : 'text-red-800'}">
                            ${message}
                        </p>
                    </div>
                </div>
            </div>
        `;
        notification.style.transform = 'translateY(0)';
        setTimeout(() => {
            notification.style.transform = 'translateY(100%)';
        }, 3000);
    }

    function uploadFinalImage(projectId, imageIndex, formData) {
        const token = localStorage.getItem('token');

        fetch(`https://api.immoyes.com/admin/project/${projectId}/upload-final/${imageIndex}`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Fehler beim Hochladen des finalen Bildes');
            }
            return response.json();
        })
        .then(data => {
            showNotification('Finales Bild erfolgreich hochgeladen!', 'success');
            setTimeout(() => location.reload(), 2000);
        })
        .catch(error => {
            console.error('Fehler:', error);
            showNotification('Fehler beim Hochladen des finalen Bildes. Bitte versuchen Sie es erneut.', 'error');
        });
    }

    function changeProjectStatus() {
        const token = localStorage.getItem('token');
        const newStatus = document.getElementById('statusSelect').value;
        const urlParams = new URLSearchParams(window.location.search);
        const projectId = urlParams.get('id');

        fetch(`https://api.immoyes.com/admin/project/${projectId}/status`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Fehler beim Ändern des Projektstatus');
            }
            return response.json();
        })
        .then(data => {
            showNotification('Projektstatus erfolgreich aktualisiert!', 'success');
            setTimeout(() => location.reload(), 2000);
        })
        .catch(error => {
            console.error('Fehler:', error);
            showNotification('Fehler beim Ändern des Projektstatus. Bitte versuchen Sie es erneut.', 'error');
        });
    }

    function safeGetTextContent(element, selector) {
        const el = element.querySelector(selector);
        return el ? el.textContent.trim() : 'N/A';
    }

    function extractProjectDetails() {
        const projectInfo = document.getElementById('project-info');
        if (!projectInfo) {
            console.error('Projektinfo-Container nicht gefunden');
            return null;
        }

        const details = {
            name: safeGetTextContent(projectInfo, 'h2'),
            description: safeGetTextContent(projectInfo, 'p'),
            status: safeGetTextContent(projectInfo, '.rounded-full'),
            cost: safeGetTextContent(projectInfo, 'dd:nth-of-type(2)'),
            furnitureStyle: safeGetTextContent(projectInfo, 'dd:nth-of-type(3)'),
            createdAt: safeGetTextContent(projectInfo, 'dd:nth-of-type(4)'),
            userEmail: safeGetTextContent(projectInfo, 'dd:nth-of-type(5)')
        };

        const imageContainer = document.getElementById('image-container');
        details.images = [];
        if (imageContainer) {
            details.images = Array.from(imageContainer.querySelectorAll('.bg-white')).map(card => ({
                roomType: safeGetTextContent(card, 'h3'),
                notes: safeGetTextContent(card, 'p')
            }));
        }

        return details;
    }

    async function generateProjectBriefing() {
        const urlParams = new URLSearchParams(window.location.search);
        const projectId = urlParams.get('id');

        if (!projectId) {
            console.error('Projekt-ID nicht in URL gefunden');
            return 'Fehler beim Generieren des Briefings: Projekt-ID nicht gefunden';
        }

        try {
            const token = localStorage.getItem('token');
            const response = await fetch(`https://api.immoyes.com/generate-briefing/${projectId}`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Fehler beim Generieren des Briefings');
            }

            const data = await response.json();
            return data.briefing;
        } catch (error) {
            console.error('Fehler beim Generieren des Projektbriefings:', error);
            return 'Fehler beim Generieren des Projektbriefings. Bitte versuchen Sie es später erneut.';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const generateBriefingButton = document.getElementById('generateBriefing');
        if (generateBriefingButton) {
            generateBriefingButton.addEventListener('click', async () => {
                const briefingTextarea = document.getElementById('briefingText');
                if (briefingTextarea) {
                    briefingTextarea.value = 'Briefing wird generiert...';
                    const briefing = await generateProjectBriefing();
                    briefingTextarea.value = briefing;
                } else {
                    console.error('Briefing-Textarea nicht gefunden');
                }
            });
        } else {
            console.error('Briefing-Generierungsbutton nicht gefunden');
        }

        const copyBriefingButton = document.getElementById('copyBriefing');
        if (copyBriefingButton) {
            copyBriefingButton.addEventListener('click', () => {
                const briefingTextarea = document.getElementById('briefingText');
                if (briefingTextarea) {
                    briefingTextarea.select();
                    document.execCommand('copy');
                    showNotification('Briefing in die Zwischenablage kopiert!', 'success');
                } else {
                    console.error('Briefing-Textarea nicht gefunden');
                }
            });
        } else {
            console.error('Briefing-Kopierbutton nicht gefunden');
        }
    });
    </script>
</body>
</html>