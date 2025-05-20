<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | UAUT LMS</title>
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
        .course-card:hover { transform: translateY(-5px); box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .loading { opacity: 0.7; pointer-events: none; }
        .animate-fade-in { animation: fadeIn 0.5s ease-in; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        
        /* Sidebar transition */
        .sidebar {
            transition: all 0.3s ease;
        }
        .sidebar-collapsed {
            transform: translateX(-100%);
        }
        .sidebar-toggle {
            transition: all 0.3s ease;
        }
        .sidebar-toggle-collapsed {
            left: 0;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <!-- Sidebar Toggle Button -->
    <button id="sidebarToggle" class="fixed z-20 left-4 top-4 bg-white p-2 rounded-md shadow-md sidebar-toggle">
        <i class="fas fa-bars text-gray-700"></i>
    </button>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar fixed z-10 w-64 h-full bg-white shadow-md">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ url('img/uaut-logo.jpg') }}" alt="UAUT LMS" class="h-8 w-8 mr-3">
                    <h1 class="text-xl font-bold text-gray-800">UAUT LMS</h1>
                </div>
                <button id="closeSidebar" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <!-- User Profile Section -->
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center">
                <div class="bg-primary-100 p-3 rounded-full">
                    <i class="fas fa-user text-primary-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Welcome back</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $user->full_name }}</p>
                </div>
            </div>
        </div>
        
        <!-- Navigation Links -->
        <div class="p-4">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('user.home') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-home mr-3 text-primary-600"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/courses" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-book mr-3 text-primary-600"></i>
                        <span>All Courses</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-tasks mr-3 text-primary-600"></i>
                        <span>My Progress</span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-sign-out-alt mr-3 text-primary-600"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div id="mainContent" class="ml-0 transition-all duration-300">
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <nav class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-gray-800">Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <span class="mr-2 text-gray-700 hidden sm:inline">{{ $user->full_name }}</span>
                            <div class="bg-primary-100 p-2 rounded-full">
                                <i class="fas fa-user text-primary-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <main class="container mx-auto px-6 py-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded animate-fade-in">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif
            @if (session('info'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded animate-fade-in">
                    <i class="fas fa-info-circle mr-2"></i>{{ session('info') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">Welcome, {{ $user->full_name }}</h1>
                <p class="text-gray-600 mb-6">Explore your enrolled courses or discover new ones below.</p>
            </div>

            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Your Enrolled Courses</h2>
                @if ($enrolledCourses->isEmpty())
                    <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                        <i class="fas fa-book-open text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600">You are not enrolled in any courses yet. <a href="/courses" class="text-primary hover:underline">Browse courses</a>.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($enrolledCourses as $course)
                            <div class="course-card bg-white rounded-lg shadow-md p-4">
                                @if ($course->thumbnail)
                                    <img src="{{ url('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-40 object-cover rounded-lg mb-4">
                                @else
                                    <div class="w-full h-40 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-500 text-2xl"></i>
                                    </div>
                                @endif
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-medium text-gray-800">{{ $course->title }}</h3>
                                    <span class="text-xs bg-primary-100 text-primary-800 px-2 py-1 rounded-full">{{ $course->department }}</span>
                                </div>
                                <p class="text-gray-600 mb-2">{{ Str::limit($course->description, 100) }}</p>
                                <p class="text-gray-600 mb-4">Progress: {{ $course->pivot->progress }}%</p>
                                <a href="{{ route('courses.learn', $course->id) }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700">
                                    <i class="fas fa-play mr-2"></i> Continue Learning
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Available Courses</h2>
                    <form method="GET" action="{{ route('user.home') }}" class="flex items-center space-x-4">
                        <select name="department" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">All Departments</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept }}" {{ $department == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                    </form>
                </div>
                
                @if ($availableCourses->isEmpty())
                    <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                        <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600">No courses available.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($availableCourses as $course)
                            <div class="course-card bg-white rounded-lg shadow-md p-4">
                                @if ($course->thumbnail)
                                    <img src="{{ url('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-40 object-cover rounded-lg mb-4">
                                @else
                                    <div class="w-full h-40 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-500 text-2xl"></i>
                                    </div>
                                @endif
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-medium text-gray-800">{{ $course->title }}</h3>
                                    <span class="text-xs bg-primary-100 text-primary-800 px-2 py-1 rounded-full">{{ $course->department }}</span>
                                </div>
                                <p class="text-gray-600 mb-4">{{ Str::limit($course->description, 100) }}</p>
                                <a href="{{ route('courses.show', $course->id) }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700">
                                    <i class="fas fa-eye mr-2"></i> Preview Course
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $availableCourses->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const closeSidebar = document.getElementById('closeSidebar');
            const mainContent = document.getElementById('mainContent');
            
            // Toggle sidebar
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-collapsed');
                mainContent.classList.toggle('ml-64');
                mainContent.classList.toggle('ml-0');
                sidebarToggle.classList.toggle('sidebar-toggle-collapsed');
            });
            
            // Close sidebar
            closeSidebar.addEventListener('click', function() {
                sidebar.classList.add('sidebar-collapsed');
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-0');
                sidebarToggle.classList.add('sidebar-toggle-collapsed');
            });
            
            // Initialize sidebar state
            function checkScreenSize() {
                if (window.innerWidth < 768) {
                    sidebar.classList.add('sidebar-collapsed');
                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-0');
                    sidebarToggle.classList.add('sidebar-toggle-collapsed');
                } else {
                    sidebar.classList.remove('sidebar-collapsed');
                    mainContent.classList.add('ml-64');
                    mainContent.classList.remove('ml-0');
                    sidebarToggle.classList.remove('sidebar-toggle-collapsed');
                }
            }
            
            // Check on load
            checkScreenSize();
            
            // Check on resize
            window.addEventListener('resize', checkScreenSize);
        });
    </script>
</body>
</html>