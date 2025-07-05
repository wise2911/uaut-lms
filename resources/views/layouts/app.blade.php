<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAUT LMS | @yield('title', 'Home')</title>
    <link rel="shortcut icon" href="{{ ('img/uaut-logo.jpg') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gray-50 antialiased text-gray-800">
    <!-- Navigation Bar -->
    <nav class="bg-blue-900 p-4 shadow-md sticky top-0 z-50 transition-all duration-300">
        <div class="container mx-auto flex justify-between items-center max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Logo -->
            <a href="/" class="flex items-center space-x-3">
                <img src="{{ asset('img/uaut-logo.jpg') }}" alt="UAUT Logo" class="h-10 w-10 rounded-full object-cover border-2 border-white">
                <span class="text-white text-xl font-bold tracking-tight hidden sm:block">UAUT Learning Management System</span>
                <span class="text-white text-xl font-bold tracking-tight sm:hidden">UAUT LMS</span>
            </a>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="/" class="text-white text-base font-medium hover:text-blue-200 transition duration-200 px-3 py-2 rounded-md">Home</a>
                <a href="#courses" class="text-white text-base font-medium hover:text-blue-200 transition duration-200 px-3 py-2 rounded-md">Courses</a>
                <a href="#about" class="text-white text-base font-medium hover:text-blue-200 transition duration-200 px-3 py-2 rounded-md">About</a>
                <div class="h-6 w-px bg-blue-700 mx-2"></div>
                <a href="/user/login" class="text-blue-100 text-base font-medium hover:text-white transition duration-200 px-3 py-2">Log In</a>
                <a href="/user/signup" class="bg-white text-blue-900 px-4 py-2 rounded-md font-medium hover:bg-blue-50 transition duration-300 shadow-sm">Sign Up</a>
            </div>
            
            <!-- Mobile Menu Toggle -->
            <div class="md:hidden">
                <button id="navbar-toggle" class="text-white focus:outline-none p-2 rounded-md hover:bg-blue-800 transition">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-blue-900 px-4 pb-4">
            <div class="flex flex-col space-y-2">
                <a href="/" class="text-white py-3 px-4 rounded-md hover:bg-blue-800 transition">Home</a>
                <a href="#courses" class="text-white py-3 px-4 rounded-md hover:bg-blue-800 transition">Courses</a>
                <a href="#about" class="text-white py-3 px-4 rounded-md hover:bg-blue-800 transition">About</a>
                <div class="border-t border-blue-700 my-2"></div>
                <a href="/user/login" class="text-white py-3 px-4 rounded-md hover:bg-blue-800 transition">Log In</a>
                <a href="/user/signup" class="bg-blue-700 text-white py-3 px-4 rounded-md font-medium hover:bg-blue-600 transition text-center">Sign Up</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-[calc(100vh-200px)]">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 py-12 text-white">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="{{ asset('img/uaut-logo.jpg') }}" alt="UAUT Logo" class="h-12 w-12 rounded-full object-cover border-2 border-white">
                        <span class="text-xl font-bold">UAUT LMS</span>
                    </div>
                    <p class="text-gray-400 mb-4">Empowering education through technology. Our learning management system provides a seamless experience for students and educators.</p>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/uaut1" target="_blank" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="https://www.instagram.com/uautuniversity/" target="_blank" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="#courses" class="text-gray-400 hover:text-white transition">Courses</a></li>
                        <li><a href="#about" class="text-gray-400 hover:text-white transition">About Us</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-envelope mr-3"></i>
                            <span>admin@uaut.ac.tz</span>
                        </li>
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-phone mr-3"></i>
                            <span>+255 684 505 012</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 text-sm mb-4 md:mb-0">© {{ date('Y') }} UAUT Learning Management System. All rights reserved.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-500 hover:text-white text-sm transition">Privacy Policy</a>
                    <a href="#" class="text-gray-500 hover:text-white text-sm transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        document.getElementById('navbar-toggle').addEventListener('click', () => {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
            
            // Toggle icon between bars and times
            const icon = document.querySelector('#navbar-toggle i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    </script>
</body>
</html>