<!DOCTYPE html>
<html lang="de" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImmoYes Auftragsportal - Transformieren Sie Ihre Immobilien digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/ScrollTrigger.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        .gradient-text {
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            background-image: linear-gradient(45deg, #4F46E5, #06B6D4);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <header x-data="{ isOpen: false }" class="fixed w-full z-50 bg-white shadow-sm">
        <nav class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <div class="text-2xl font-bold gradient-text">ImmoYes</div>
                <div class="hidden md:flex space-x-6">
                    <a href="#features" class="text-gray-600 hover:text-indigo-600 transition duration-300">Features</a>
                    <a href="#how-it-works" class="text-gray-600 hover:text-indigo-600 transition duration-300">So funktioniert's</a>
                    <a href="#testimonials" class="text-gray-600 hover:text-indigo-600 transition duration-300">Testimonials</a>
                </div>
                <div class="hidden md:flex space-x-4">
                    <a href="#" class="px-4 py-2 text-indigo-600 border border-indigo-600 rounded-full hover:bg-indigo-600 hover:text-white transition duration-300">Anmelden</a>
                    <a href="#" class="px-4 py-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition duration-300">Registrieren</a>
                </div>
                <button @click="isOpen = !isOpen" class="md:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
            <div x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="md:hidden mt-4">
                <a href="#features" class="block py-2 text-gray-600 hover:text-indigo-600">Features</a>
                <a href="#how-it-works" class="block py-2 text-gray-600 hover:text-indigo-600">So funktioniert's</a>
                <a href="#testimonials" class="block py-2 text-gray-600 hover:text-indigo-600">Testimonials</a>
                <a href="#" class="block py-2 text-indigo-600">Anmelden</a>
                <a href="#" class="block py-2 text-indigo-600">Registrieren</a>
            </div>
        </nav>
    </header>

    <main class="pt-16">
        <section class="relative bg-black text-white overflow-hidden py-16">
            <div class="container mx-auto px-6 relative z-10">
                <div class="flex flex-col items-center text-center">
                    <h1 class="text-5xl md:text-6xl font-bold mb-6 tracking-tight">
                        Immobilien-Präsentation<br>neu definiert
                    </h1>
                    <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto text-gray-300">
                        Nutzen Sie unser Auftragsportal, um Ihre Immobilien professionell zu präsentieren und zu vermarkten. Einfach. Effektiv. Elegant.
                    </p>
                    <a href="#" class="px-8 py-4 bg-white text-black rounded-full text-lg font-semibold hover:bg-gray-200 transition duration-300 inline-block">
                        Jetzt entdecken
                    </a>
                </div>
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-indigo-900 to-black opacity-50"></div>
        </section>
    </main>

        <section id="features" class="py-20 bg-white">
            <div class="container mx-auto px-6">
                <h2 class="text-4xl font-bold text-center mb-16 gradient-text">Unsere Dienstleistungen</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                    <div class="feature-card bg-gray-50 rounded-lg p-8 shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                        <div class="text-indigo-600 mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Professionelles Homestaging</h3>
                        <p class="text-gray-600">Verwandeln Sie leere Räume in attraktiv eingerichtete Zimmer.</p>
                    </div>
                    <div class="feature-card bg-gray-50 rounded-lg p-8 shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                        <div class="text-indigo-600 mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Virtuelle Renovierung</h3>
                        <p class="text-gray-600">Visualisieren Sie Renovierungen mit unseren erfahrenen Designern.</p>
                    </div>
                    <div class="feature-card bg-gray-50 rounded-lg p-8 shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                        <div class="text-indigo-600 mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">3D-Grundrisse</h3>
                        <p class="text-gray-600">Erstellen Sie beeindruckende 3D-Grundrisse aus 2D-Plänen.</p>
                    </div>
                    <div class="feature-card bg-gray-50 rounded-lg p-8 shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                        <div class="text-indigo-600 mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Projektverwaltung</h3>
                        <p class="text-gray-600">Verwalten Sie alle Ihre Projekte effizient in einem Dashboard.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="how-it-works" class="py-20 bg-gray-50">
            <div class="container mx-auto px-6">
                <h2 class="text-4xl font-bold text-center mb-16 gradient-text">So einfach geht's</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                    <div class="step-card bg-white rounded-lg p-8 shadow-lg">
                        <div class="text-3xl font-bold text-indigo-600 mb-4">01</div>
                        <h3 class="text-xl font-semibold mb-2">Konto erstellen</h3>
                        <p class="text-gray-600">Registrieren Sie sich und richten Sie Ihr Profil ein.</p>
                    </div>
                    <div class="step-card bg-white rounded-lg p-8 shadow-lg">
                        <div class="text-3xl font-bold text-indigo-600 mb-4">02</div>
                        <h3 class="text-xl font-semibold mb-2">Guthaben kaufen</h3>
                        <p class="text-gray-600">Erwerben Sie Guthaben für unsere Dienste.</p>
                    </div>
                    <div class="step-card bg-white rounded-lg p-8 shadow-lg">
                        <div class="text-3xl font-bold text-indigo-600 mb-4">03</div>
                        <h3 class="text-xl font-semibold mb-2">Projekt einreichen</h3>
                        <p class="text-gray-600">Laden Sie Fotos hoch und geben Sie Anweisungen.</p>
                    </div>
                    <div class="step-card bg-white rounded-lg p-8 shadow-lg">
                        <div class="text-3xl font-bold text-indigo-600 mb-4">04</div>
                        <h3 class="text-xl font-semibold mb-2">Ergebnisse erhalten</h3>
                        <p class="text-gray-600">Erhalten Sie Ihre transformierten Bilder.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="testimonials" class="py-20 bg-white">
            <div class="container mx-auto px-6">
                <h2 class="text-4xl font-bold text-center mb-16 gradient-text">Was unsere Kunden sagen</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div class="testimonial-card bg-gray-50 rounded-lg p-8 shadow-lg">
                        <div class="flex items-center mb-4">
                            <img src="/api/placeholder/100/100" alt="Sarah Johnson" class="w-12 h-12 rounded-full mr-4">
                            <div>
                                <h4 class="font-semibold">Sarah Johnson</h4>
                                <p class="text-gray-600 text-sm">CEO, Dream Home Immobilien</p>
                            </div>
                        </div>
                        <p class="text-gray-600">"ImmoYes hat die Art und Weise, wie wir Immobilien unseren Kunden präsentieren, komplett verändert. Das virtuelle Homestaging und die 3D-Grundrisse haben das Interesse an unseren Angeboten deutlich erhöht."</p>
                    </div>
                    <div class="testimonial-card bg-gray-50 rounded-lg p-8 shadow-lg">
                        <div class="flex items-center mb-4">
                            <img src="/api/placeholder/100/100" alt="Tom Mueller" class="w-12 h-12 rounded-full mr-4">
                            <div>
                                <h4 class="font-semibold">Tom Mueller</h4>
                                <p class="text-gray-600 text-sm">Immobilienmakler</p>
                            </div>
                        </div>
                        <p class="text-gray-600">"Die virtuelle Renovierung hat es mir ermöglicht, meinen Kunden das volle Potenzial einer Immobilie zu zeigen. Ein echter Gamechanger in meinem Geschäft!"</p>
                    </div>
                    <div class="testimonial-card bg-gray-50 rounded-lg p-8 shadow-lg">
                        <div class="flex items-center mb-4">
                            <img src="/api/placeholder/100/100" alt="Lisa Bergmann" class="w-12 h-12 rounded-full mr-4">
                            <div>
                                <h4 class="font-semibold">Lisa Bergmann</h4>
                                <p class="text-gray-600 text-sm">Innenarchitektin</p>
                            </div>
                        </div>
                        <p class="text-gray-600">"Die 3D-Grundrisse von ImmoYes sind beeindruckend detailliert und helfen mir, meine Designideen effektiv zu kommunizieren. Eine großartige Plattform!"</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 bg-gradient-to-r from-indigo-600 to-cyan-500 text-white">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-4xl font-bold mb-8">Bereit, Ihre Immobilie zu transformieren?</h2>
                <p class="text-xl mb-12">Schließen Sie sich Tausenden zufriedener Nutzer an, die ihre Immobilienangebote mit unseren virtuellen Diensten verbessert haben.</p>
                <a href="#" class="px-8 py-4 bg-white text-indigo-600 rounded-full text-lg hover:bg-gray-100 transition duration-300 inline-block">Jetzt kostenlos registrieren</a>
            </div>
        </section>
    </main>

    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div>
                    <h3 class="text-2xl font-bold mb-4 gradient-text">ImmoYes</h3>
                    <p class="text-gray-400">Transformieren Sie Ihre Immobilien digital.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Dienste</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Homestaging</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Virtuelle Renovierung</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">3D-Grundrisse</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Projektverwaltung</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Unternehmen</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Über uns</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Karriere</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Kontakt</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Folgen Sie uns</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" fill-rule="evenodd" clip-rule="evenodd"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12c0-5.523-4.477-10-10-10z" fill-rule="evenodd" clip-rule="evenodd"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" fill-rule="evenodd" clip-rule="evenodd"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-12 border-t border-gray-700 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400">&copy; 2024 ImmoYes GmbH. Alle Rechte vorbehalten.</p>
                <div class="mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white transition duration-300 mr-4">Datenschutz</a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-300">AGB</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // GSAP animations
        gsap.registerPlugin(ScrollTrigger);

        gsap.from(".feature-card", {
            scrollTrigger: {
                trigger: "#features",
                start: "top center"
            },
            opacity: 15,
           
            stagger: 0.1,
            duration: 1,
            ease: "power3.out"
        });

        gsap.from(".step-card", {
            scrollTrigger: {
                trigger: "#how-it-works",
                start: "top center"
            },
            opacity: 0,
            x: -50,
            stagger: 0.2,
            duration: 1,
            ease: "power3.out"
        });

        gsap.from(".testimonial-card", {
            scrollTrigger: {
                trigger: "#testimonials",
                start: "top center"
            },
            opacity: 0,
            scale: 0.8,
            stagger: 0.2,
            duration: 1,
            ease: "back.out(1.7)"
        });
    </script>
</body>
</html>