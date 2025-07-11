<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | UAUT LMS</title>
    <link rel="shortcut icon" href="{{ url('img/uaut-logo.jpg') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#1f2937',
                        accent: '#10b981',
                    }
                }
            }
        }
    </script>
    <style>
        .course-card { transition: transform 0.3s, box-shadow 0.3s; }
        .course-card:hover { transform: scale(1.05); box-shadow: 0 8px 24px rgba(0,0,0,0.2); }
        .animate-fade-in { animation: fadeIn 0.5s ease-in; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .sidebar { transition: transform 0.3s ease; }
        .sidebar-collapsed { transform: translateX(-100%); }
        .sidebar-toggle { transition: left 0.3s ease; }
        .sidebar-toggle-collapsed { left: 0; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Sidebar Toggle Button -->
    <button id="sidebarToggle" class="fixed z-20 left-4 top-4 bg-white p-2 rounded-md shadow-md sidebar-toggle">
        <i class="fas fa-bars text-gray-700"></i>
    </button>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar fixed z-10 w-64 h-full bg-white shadow-xl">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ url('img/uaut-logo.jpg') }}" alt="UAUT LMS" class="h-10 w-10 mr-3 rounded-full">
                    <h1 class="text-2xl font-bold text-gray-800">UAUT LMS</h1>
                </div>
                <button id="closeSidebar" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center">
                <div class="bg-primary-100 p-3 rounded-full">
                    <i class="fas fa-user text-primary-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Welcome back</p>
                    <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->full_name }}</p>
                </div>
            </div>
        </div>
        <div class="p-4">
            <ul class="space-y-2">
                <li><a href="{{ route('user.home') }}" class="flex items-center p-3 text-gray-700 bg-primary-50 rounded-lg"><i class="fas fa-home mr-3 text-primary-600"></i>Dashboard</a></li>
                <li><a href="/courses" class="flex items-center p-3 text-gray-700 hover:bg-primary-50 rounded-lg"><i class="fas fa-book mr-3 text-primary-600"></i>All Courses</a></li>
                <li><a href="#" class="flex items-center p-3 text-gray-700 hover:bg-primary-50 rounded-lg"><i class="fas fa-tasks mr-3 text-primary-600"></i>My Progress</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center p-3 text-gray-700 hover:bg-primary-50 rounded-lg"><i class="fas fa-sign-out-alt mr-3 text-primary-600"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div id="mainContent" class="ml-0 lg:ml-64 transition-all duration-300 flex flex-col min-h-screen">
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <nav class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700 hidden sm:inline">{{ Auth::user()->full_name }}</span>
                        <div class="bg-primary-100 p-2 rounded-full"><i class="fas fa-user text-primary-600"></i></div>
                    </div>
                </div>
            </nav>
        </header>

        <main class="container mx-auto px-6 py-8 flex-grow">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded animate-fade-in"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>
            @endif
            @if (session('info'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded animate-fade-in"><i class="fas fa-info-circle mr-2"></i>{{ session('info') }}</div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">Welcome, {{ Auth::user()->full_name }}!</h1>
                <p class="text-gray-600 mb-6">Explore your enrolled courses and continue your learning journey.</p>
            </div>

            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Your Courses</h2>
                @if ($enrolledCourses->isEmpty())
                    <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
                        <i class="fas fa-book text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600">You haven't enrolled in any courses yet.</p>
                        <a href="/courses" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700 mt-4">
                            <i class="fas fa-book-open mr-2"></i> Explore Courses
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($enrolledCourses as $course)
                            <div class="course-card bg-white rounded-2xl shadow-md overflow-hidden">
                                <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : asset('img/placeholder.jpg') }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                                <div class="p-5">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">{{ $course->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-3">{{ $course->short_description }}</p>
                                    <p class="text-gray-500 text-xs mb-3">Progress: {{ $course->pivot->progress }}%</p>
                                    <a href="{{ route('courses.learn', $course->id) }}" class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-green-600 text-sm">
                                        <i class="fas fa-play-circle mr-2"></i> Continue
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $enrolledCourses->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </main>

        <footer class="bg-white shadow-sm mt-auto">
            <div class="container mx-auto px-6 py-4 text-center text-gray-600">© {{ date('Y') }} UAUT LMS. All rights reserved.</div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('#sidebar');
            const sidebarToggle = document.querySelector('#sidebarToggle');
            const closeSidebar = document.querySelector('#closeSidebar');
            const mainContent = document.querySelector('#mainContent');

            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('sidebar-collapsed');
                mainContent.classList.toggle('ml-64');
                mainContent.classList.toggle('ml-0');
                sidebarToggle.classList.toggle('sidebar-toggle-collapsed');
            });

            closeSidebar.addEventListener('click', () => {
                sidebar.classList.add('sidebar-collapsed');
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-0');
                sidebarToggle.classList.add('sidebar-toggle-collapsed');
            });

            function checkScreenSize() {
                if (window.innerWidth < 768) {
                    sidebar.classList.add('sidebar-collapsed');
                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-0');
                    sidebarToggle.classList.add('sidebar-toggle-collapsed');
                }
            }

            checkScreenSize();
            window.addEventListener('resize', checkScreenSize);
        });
    </script>
</body>
</html>