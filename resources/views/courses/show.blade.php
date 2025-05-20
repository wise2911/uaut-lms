<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} | UAUT LMS</title>
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
        .course-card:hover { transform: scale(1.02); box-shadow: 0 8px 24px rgba(0,0,0,0.2); }
        .animate-fade-in { animation: fadeIn 0.5s ease-in; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .sidebar { transition: transform 0.3s ease; }
        .sidebar-collapsed { transform: translateX(-100%); }
        .sidebar-toggle { transition: left 0.3s ease; }
        .sidebar-toggle-collapsed { left: 0; }
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }
        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
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
                    <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                </div>
            </div>
        </div>
        <div class="p-4">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('user.home') }}" class="flex items-center p-3 text-gray-700 hover:bg-primary-50 rounded-lg">
                        <i class="fas fa-home mr-3 text-primary-600"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/courses" class="flex items-center p-3 text-gray-700 hover:bg-primary-50 rounded-lg">
                        <i class="fas fa-book mr-3 text-primary-600"></i>
                        <span>All Courses</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-primary-50 rounded-lg">
                        <i class="fas fa-tasks mr-3 text-primary-600"></i>
                        <span>My Progress</span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center p-3 text-gray-700 hover:bg-primary-50 rounded-lg">
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
                    <h1 class="text-2xl font-bold text-gray-800">{{ $course->title }}</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700 hidden sm:inline">{{ Auth::user()->name }}</span>
                        <div class="bg-primary-100 p-2 rounded-full">
                            <i class="fas fa-user text-primary-600"></i>
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

            <!-- Hero Section -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex flex-col lg:flex-row gap-8">
                    <div class="lg:w-2/3">
                        <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $course->title }}</h1>
                        <p class="text-gray-600 mb-4">{{ $course->short_description }}</p>
                        <p class="text-gray-500 mb-4">By <span class="font-semibold">{{ $course->instructor_name }}</span> | Department: {{ $course->department }}</p>
                        <p class="text-gray-500 mb-6">Modules: {{ $course->modules_count }}</p>
                        @if ($isEnrolled)
                            <a href="{{ route('courses.learn', $course->id) }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-indigo-700 text-lg">
                                <i class="fas fa-play mr-2"></i> Continue Learning
                            </a>
                        @else
                            <form method="POST" action="{{ route('courses.enroll', $course->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-accent text-white rounded-lg hover:bg-green-600 text-lg">
                                    <i class="fas fa-plus mr-2"></i> Enroll Now
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="lg:w-1/3">
                        @if ($course->thumbnail)
                            <img src="{{ url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover rounded-lg shadow-md">
                        @else
                            <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-image text-gray-500 text-3xl"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Course Preview</h2>
                <div class="course-card bg-white rounded-2xl shadow-lg p-6">
                    @php
                        $previewVideo = $course->videos->where('is_preview', true)->first();
                    @endphp
                    @if ($previewVideo)
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Preview Video</h3>
                            <div class="video-container rounded-lg overflow-hidden shadow-md">
                                <video controls class="w-full">
                                    <source src="{{ url($previewVideo->url) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    @endif
                    <p class="text-gray-600 mb-4">{{ $course->description }}</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const closeSidebar = document.getElementById('closeSidebar');
            const mainContent = document.getElementById('mainContent');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-collapsed');
                mainContent.classList.toggle('ml-64');
                mainContent.classList.toggle('ml-0');
                sidebarToggle.classList.toggle('sidebar-toggle-collapsed');
            });

            closeSidebar.addEventListener('click', function() {
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
                } else {
                    sidebar.classList.remove('sidebar-collapsed');
                    mainContent.classList.add('ml-64');
                    mainContent.classList.remove('ml-0');
                    sidebarToggle.classList.remove('sidebar-toggle-collapsed');
                }
            }

            checkScreenSize();
            window.addEventListener('resize', checkScreenSize);
        });
    </script>
</body>
</html>