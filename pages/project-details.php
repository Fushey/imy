<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projekt Details - ImmoYes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
        .transition-height {
            transition: max-height 0.3s ease-out;
            overflow: hidden;
        }
        .image-comparison {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .image-pair {
    display: flex;
    gap: 1rem;
    align-items: stretch;
    flex-wrap: wrap;
    justify-content: center;
}
        .image-card {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .image-container {
            flex: 1;
            position: relative;
            overflow: hidden;
        }
        .image-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
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
                    <h1 class="text-2xl font-bold ml-3">ImmoYes</h1>
                </div>
                <nav>
                    <a href="http://localhost/index.php?page=dashboard" class="sidebar-link active flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-home sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Meine Projekte</span>
                    </a>
                    <a href="http://localhost/index.php?page=services" class="sidebar-link flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-concierge-bell sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Neuer Auftrag</span>
                    </a>
                    <a href="http://localhost/index.php?page=aufladen" class="sidebar-link flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-wallet sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Guthaben Kaufen</span>
                    </a>
                    <a href="http://localhost/index.php?page=history" class="sidebar-link flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-white hover:bg-opacity-10 mb-2">
                        <i class="fas fa-history sidebar-icon mr-3 text-lg"></i>
                        <span class="font-medium">Rechnungen</span>
                    </a>
                </nav>
            </div>
            <div class="mt-auto p-6">
                <div class="bg-white bg-opacity-10 rounded-lg p-4">
                    <h3 class="text-sm font-semibold mb-2">Hilfe benötigt?</h3>
                    <p class="text-xs mb-3">Kontaktieren Sie unser Support-Team für Unterstützung.</p>
                    <a href="http://localhost/index.php?page=hilfe" class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-indigo-800 bg-white rounded-md hover:bg-indigo-100 transition duration-150 ease-in-out">
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
                    <h2 class="text-2xl font-semibold text-gray-800">Projekt Details</h2>
                    <div class="flex items-center space-x-4">
                        <span id="userCredits" class="text-sm font-medium text-gray-500"></span>
                        <a href="index.php?page=logout" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Ausloggen
                        </a>
                    </div>
                </div>
            </header>

            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div id="project-details" class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                    <div class="px-4 py-5 sm:p-6">
                        <div id="project-info" class="space-y-4"></div>
                    </div>
                </div>

                <div id="image-comparison" class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Projekt Bilder Vorher/Nachher</h2>
                        <div id="image-comparison-container" class="image-comparison"></div>
                    </div>
                </div>

                <div id="revision-history" class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Änderungs Historie</h2>
                        <div id="revision-container" class="space-y-4"></div>
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
        let userEmail = '';

        fetchUserProfile();

        function fetchUserProfile() {
            fetch('https://api.immoyes.com/profile', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch user data');
                }
                return response.json();
            })
            .then(data => {
                userEmail = data.email;
                document.getElementById('userCredits').innerHTML = `
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        €${data.credits.toFixed(2)}
                    </span>
                `;
                fetchProjectDetails();
            })
            .catch(error => {
                console.error('Error fetching user data:', error);
                document.getElementById('userCredits').textContent = 'Credits: Error';
                fetchProjectDetails();
            });
        }

        function downloadFinalImages(projectId, token) {
            fetch(`https://api.immoyes.com/project/${projectId}/download-final-images`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 500) {
                        throw new Error('Server error occurred. Please try again later.');
                    }
                    return response.json().then(err => { throw new Error(err.message) });
                }
                return response.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = `project_${projectId}_final_images.zip`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                showNotification('Final images downloaded successfully!', 'success');
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification(`Error downloading final images: ${error.message}`, 'error');
            });
        }

        function fetchProjectDetails() {
            fetch(`https://api.immoyes.com/project/${projectId}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch project details');
                }
                return response.json();
            })
            .then(project => {
                console.log('Full project data:', project);

                updateProjectInfo(project);
                updateImageComparison(project);
                updateRevisionHistory(project);
            })
            .catch(error => {
                console.error('Error fetching project details:', error);
                showNotification('Error loading project details. Please try again later.', 'error');
            });
        }

        function updateProjectInfo(project) {
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
                            <dt class="text-sm font-medium text-gray-500">Preis</dt>
                            <dd class="mt-1 text-sm text-gray-900">€${project.cost.toFixed(2)}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Einrichtung</dt>
                            <dd class="mt-1 text-sm text-gray-900">${project.furniture_style || 'Not specified'}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Erstellt am</dt>
                            <dd class="mt-1 text-sm text-gray-900">${formatDate(project.created_at)}</dd>
                        </div>
                    </dl>
                </div>
                ${project.status.toLowerCase() === 'abgeschlossen' ? `
                    <div class="mt-6">
                        <button id="download-final-images" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            <i class="fas fa-download mr-2"></i> Alle Bilder herunterladen
                        </button>
                    </div>
                ` : ''}
            `;

            // Add event listener to the download button if it exists
            const downloadButton = document.getElementById('download-final-images');
            if (downloadButton) {
                downloadButton.addEventListener('click', function() {
                    downloadFinalImages(projectId, token);
                });
            }
        }

        function updateImageComparison(project) {
            const imageComparisonContainer = document.getElementById('image-comparison-container');
            imageComparisonContainer.innerHTML = '';

            if (!project.images || project.images.length === 0) {
                imageComparisonContainer.innerHTML = '<p class="text-gray-500 italic">No images available for this project.</p>';
            } else {
                project.images.forEach((image, index) => {
                    const imagePair = document.createElement('div');
                    imagePair.className = 'image-pair mb-6 flex flex-col md:flex-row gap-4';
                    
                    const originalImageCard = createImageCard(image, index, 'Original');
                    imagePair.appendChild(originalImageCard);

                    const finalImage = project.final_images && project.final_images[index];
                    if (finalImage) {
                        const finalImageCard = createImageCard({ ...image, file_path: finalImage }, index, 'Final', true);
                        imagePair.appendChild(finalImageCard);
                    } else {
                        const placeholderCard = createPlaceholderCard();
                        imagePair.appendChild(placeholderCard);
                    }

                    imageComparisonContainer.appendChild(imagePair);
                });
            }
        }

        function createImageCard(image, index, type, isFinal = false) {
            const card = document.createElement('div');
            card.className = 'image-card bg-white rounded-lg shadow-md overflow-hidden mb-4';
            
            let imagePath = `https://api.immoyes.com/${image.file_path.replace(/\\/g, '/')}`;

            card.innerHTML = `
                <img src="${imagePath}" alt="${type} Image" class="w-full h-80 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2">${type} - ${image.room_type || `Image ${index + 1}`}</h3>
                    ${!isFinal ? `<p class="text-sm text-gray-600">${image.notes || ''}</p>` : ''}
                </div>
            `;

            if (isFinal) {
                const revisionToggle = document.createElement('div');
                revisionToggle.className = 'p-4 bg-gray-50 border-t';
                revisionToggle.innerHTML = `
                    <button onclick="toggleRevisionForm(${image.id})" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                        Änderungen gewünscht?
                    </button>
                `;
                card.appendChild(revisionToggle);

                const revisionForm = createRevisionForm(image.id);
                card.appendChild(revisionForm);
            }

            return card;
        }

        function createRevisionForm(imageId) {
            const form = document.createElement('div');
            form.id = `revisionForm-${imageId}`;
            form.className = 'hidden p-4 bg-gray-100 border-t';
            form.innerHTML = `
                <textarea id="revisionText-${imageId}" class="w-full p-2 border rounded-md text-sm" rows="3" placeholder="Welche Änderungen wünschen Sie?"></textarea>
                <button onclick="submitRevision(${imageId})" class="mt-2 px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out">
                    Absenden
                </button>
            `;
            return form;
        }

        function createPlaceholderCard() {
            const card = document.createElement('div');
            card.className = 'image-card bg-white rounded-lg shadow-md overflow-hidden';
            card.innerHTML = `
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <p class="text-gray-500 italic">Fertiges Bild folgt in Kürze</p>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2">Finale Bilder</h3>
                    <p class="text-sm text-gray-600">Sobald das fertige Bild verfügbar ist, wird es hier angezeigt.</p>
                </div>
            `;
            return card;
        }

        function updateRevisionHistory(project) {
            const revisionContainer = document.getElementById('revision-container');
            revisionContainer.innerHTML = '';

            if (!project.revisions || project.revisions.length === 0) {
                revisionContainer.innerHTML = '<p class="text-gray-500 italic">Für dieses Projekt ist kein Revisionsverlauf verfügbar.</p>';
            } else {
                project.revisions.forEach((revision, index) => {
                    const revisionCard = document.createElement('div');
                    revisionCard.className = 'bg-gray-50 rounded-lg p-4 shadow mb-4';
                    
                    const date = new Date(revision.created_at).toLocaleString();
                    const associatedImage = project.images.find(img => img.id === revision.image_id);

                    revisionCard.innerHTML = `
                        <div class="flex items-start space-x-4">
                            ${associatedImage ? `
                                <img src="https://api.immoyes.com/${associatedImage.file_path.replace(/\\/g, '/')}" alt="Associated Image" class="w-24 h-24 object-cover rounded-md">
                            ` : '<div class="w-24 h-24 bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>'}
                            <div class="flex-grow">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold">Revision ${index + 1}</h3>
                                    <span class="text-sm text-gray-500">${date}</span>
                                </div>
                                <p class="mt-2 text-gray-600">${revision.revision_text}</p>
                                ${associatedImage ? `
                                    <p class="mt-1 text-sm text-gray-500">Bzgl: ${associatedImage.room_type || 'Unnamed Room'}</p>
                                ` : `<p class="mt-1 text-sm text-italic text-gray-500">No associated image (Image ID: ${revision.image_id})</p>`}
                            </div>
                        </div>
                    `;
                    revisionContainer.appendChild(revisionCard);
                });
            }
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
                case 'abgeschlossen': return 'bg-green-100 text-green-800';
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
                                : '<svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>'
                            }
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

        // Add these new functions to handle the revision form toggle and submission
        window.toggleRevisionForm = function(imageId) {
            const form = document.getElementById(`revisionForm-${imageId}`);
            form.classList.toggle('hidden');
        }

        window.submitRevision = function(imageId) {
            const revisionText = document.getElementById(`revisionText-${imageId}`).value.trim();
            if (revisionText) {
                const revisionData = {
                    revisions: [{
                        image_id: imageId,
                        revision_text: revisionText
                    }]
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
                    showNotification('Revision request submitted successfully!', 'success');
                    toggleRevisionForm(imageId);
                    document.getElementById(`revisionText-${imageId}`).value = '';
                    setTimeout(() => location.reload(), 2000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification(`Failed to submit revision request. ${error.message || 'Please try again.'}`, 'error');
                });
            } else {
                showNotification('Please enter a revision request before submitting.', 'error');
            }
        }

        // Initial call to fetch project details
        fetchProjectDetails();
    });
    </script>
</body>
</html>