<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAUT LMS</title>
    <link rel="shortcut icon" href="{{ url('img/uaut-logo.jpg') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Mobile Header -->
    <header class="md:hidden bg-white shadow-sm sticky top-0 z-10">
        <div class="flex justify-between items-center p-4">
            <button id="mobileMenuButton" class="text-gray-600">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-bold text-gray-800">UAUT LMS</h1>
            <div class="w-8"></div> <!-- Spacer for balance -->
        </div>
    </header>

    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="hidden md:block w-full md:w-64 bg-white shadow-md transform transition-all duration-300 ease-in-out fixed md:relative inset-0 z-20">
            <div class="p-4 md:p-6 h-full overflow-y-auto">
                <div class="flex justify-between items-center mb-6 md:hidden">
                    <h2 class="text-lg font-semibold">Menu</h2>
                    <button id="closeSidebar" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- User Profile Card -->
                <div class="flex flex-col items-center mb-8">
                    <div class="relative w-24 h-24 md:w-32 md:h-32 mb-4">
                        <img src="{{ Auth::user()->profile_pic ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->full_name).'&background=random' }}" 
                             class="w-full h-full rounded-full object-cover border-4 border-blue-100 shadow">
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-800 text-center">{{ Auth::user()->full_name }}</h3>
                    <p class="text-sm text-gray-600 text-center">{{ Auth::user()->email }}</p>
                </div>

                <!-- Navigation -->
                <nav class="space-y-1">
                    <a href="{{ route('user.home') }}" id="dashboardLink" class="flex items-center p-3 rounded-lg bg-blue-50 text-blue-600">
                        <i class="fas fa-home mr-3 text-blue-500 w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="#" id="myCoursesLink" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-book-open mr-3 text-blue-500 w-5 text-center"></i>
                        <span>My Courses</span>
                        <span class="ml-auto bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                            {{ $enrolledCourses->count() }}
                        </span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center p-3 rounded-lg hover:bg-red-50 text-red-600 hover:text-red-700 transition-colors">
                            <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-6 mt-16 md:mt-0">
            <!-- Dashboard View (Default View) -->
            <div id="dashboardView">
                <!-- Welcome Message -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6 text-center">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">Welcome to UAUT LMS</h1>
                    <p class="text-xl text-gray-600 mb-6">Hello, {{ Auth::user()->full_name }}!</p>
                    
                    <div class="max-w-2xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-100">
                            <i class="fas fa-user text-3xl text-blue-500 mb-3"></i>
                            <h3 class="font-semibold text-lg text-blue-800 mb-2">Your Profile</h3>
                            <p class="text-gray-700"><span class="font-medium">Name:</span> {{ Auth::user()->full_name }}</p>
                            <p class="text-gray-700"><span class="font-medium">Email:</span> {{ Auth::user()->email }}</p>
                            <p class="text-gray-700"><span class="font-medium">Member since:</span> {{ Auth::user()->created_at->format('F Y') }}</p>
                        </div>
                        
                        <div class="bg-green-50 p-6 rounded-lg border border-green-100">
                            <i class="fas fa-book text-3xl text-green-500 mb-3"></i>
                            <h3 class="font-semibold text-lg text-green-800 mb-2">Learning Summary</h3>
                            @if($enrolledCourses->count() > 0)
                                <p class="text-gray-700">You're enrolled in {{ $enrolledCourses->count() }} courses</p>
                                <div class="mt-3">
                                    <a href="#" id="viewCoursesBtn" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                        View your courses <i class="fas fa-chevron-right ml-1"></i>
                                    </a>
                                </div>
                            @else
                                <p class="text-gray-700">You haven't enrolled in any courses yet</p>
                                <div class="mt-3">
                                    <a href="#" id="browseCoursesBtn" class="inline-flex items-center text-green-600 hover:text-green-800">
                                        Browse available courses <i class="fas fa-chevron-right ml-1"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Courses View (Hidden by default) -->
            <div id="myCoursesView" class="hidden">
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">My Courses</h2>
                    
                    @if($enrolledCourses->count() > 0)
                        <div class="space-y-4">
                            @foreach($enrolledCourses as $course)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex flex-col md:flex-row md:items-center gap-4">
                                    <div class="flex-shrink-0 w-full md:w-64">
                                        <img src="{{ $course->thumbnail_url ?? 'https://via.placeholder.com/640x360' }}" 
                                             alt="{{ $course->title }}" 
                                             class="w-full h-36 object-cover rounded-lg">
                                    </div>
                                    <div class="flex-grow">
                                        <h3 class="text-lg font-semibold">{{ $course->title }}</h3>
                                        <div class="flex items-center mt-1 mb-3">
                                            <span class="text-xs px-2 py-1 rounded-full {{ $course->department == 'COEIT' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $course->department }}
                                            </span>
                                            <span class="text-xs text-gray-500 ml-3">
                                                Enrolled on {{ $course->pivot->created_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $course->pivot->progress }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Progress: {{ $course->pivot->progress }}%</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('courses.show', $course->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800">
                                            Continue <i class="fas fa-chevron-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-book-open text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-700">No courses enrolled yet</h3>
                            <p class="text-gray-500 mt-2">You haven't enrolled in any courses. Browse our catalog below to get started.</p>
                        </div>
                    @endif
                </div>

                <!-- Course Catalog -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Course Catalog</h2>
                            <p class="text-gray-600">Browse and enroll in available courses</p>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-3">
                            <form method="GET" action="{{ route('user.home') }}" class="flex flex-1">
                                <select name="department" class="flex-1 bg-white border border-gray-300 rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm md:text-base">
                                    <option value="">All Departments</option>
                                    <option value="COEIT" {{ $department == 'COEIT' ? 'selected' : '' }}>COEIT</option>
                                    <option value="COBA" {{ $department == 'COBA' ? 'selected' : '' }}>COBA</option>
                                </select>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                                    <i class="fas fa-filter mr-2"></i>
                                    <span class="hidden sm:inline">Filter</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Course Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($availableCourses as $course)
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300 border border-gray-100">
                            <!-- Course Thumbnail -->
                            <img src="{{ $course->thumbnail_url ?? 'https://via.placeholder.com/640x360' }}" 
                                 alt="{{ $course->title }}" 
                                 class="w-full h-36 object-cover">
                            
                            <!-- Course Details -->
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-semibold text-gray-800 line-clamp-2">{{ $course->title }}</h3>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $course->department == 'COEIT' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }} whitespace-nowrap ml-2">
                                        {{ $course->department }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $course->description }}</p>
                                
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <i class="fas fa-chalkboard-teacher mr-1"></i>
                                    <span>{{ $course->instructor_name }}</span>
                                </div>
                            </div>
                            
                            <!-- Course Footer -->
                            <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                                @if ($enrolledCourses->contains($course->id))
                                    <a href="{{ route('courses.show', $course->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                        <i class="fas fa-eye mr-2"></i>
                                        View Course
                                    </a>
                                @else
                                    <form action="{{ route('enroll', $course->id) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                                            <i class="fas fa-plus mr-2"></i>
                                            Enroll Now
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $availableCourses->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden"></div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuButton').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('hidden');
            document.getElementById('overlay').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });

        document.getElementById('closeSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('hidden');
            document.getElementById('overlay').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });

        document.getElementById('overlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('hidden');
            document.getElementById('overlay').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });

        // View toggle functionality
        const dashboardView = document.getElementById('dashboardView');
        const myCoursesView = document.getElementById('myCoursesView');
        
        // Dashboard link
        document.getElementById('dashboardLink').addEventListener('click', function(e) {
            e.preventDefault();
            dashboardView.classList.remove('hidden');
            myCoursesView.classList.add('hidden');
            this.classList.add('bg-blue-50', 'text-blue-600');
            document.getElementById('myCoursesLink').classList.remove('bg-blue-50', 'text-blue-600');
        });
        
        // My Courses link
        document.getElementById('myCoursesLink').addEventListener('click', function(e) {
            e.preventDefault();
            dashboardView.classList.add('hidden');
            myCoursesView.classList.remove('hidden');
            this.classList.add('bg-blue-50', 'text-blue-600');
            document.getElementById('dashboardLink').classList.remove('bg-blue-50', 'text-blue-600');
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Quick links from dashboard
        document.getElementById('viewCoursesBtn')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('myCoursesLink').click();
        });
        
        document.getElementById('browseCoursesBtn')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('myCoursesLink').click();
            setTimeout(() => {
                const catalogSection = document.querySelector('#myCoursesView > div:last-child');
                catalogSection.scrollIntoView({ behavior: 'smooth' });
            }, 100);
        });

        // Display success/error messages
        @if(session('success'))
            setTimeout(() => {
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center';
                toast.innerHTML = `
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                `;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }, 300);
        @endif

        @if(session('info'))
            setTimeout(() => {
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center';
                toast.innerHTML = `
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>{{ session('info') }}</span>
                `;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }, 300);
        @endif
    </script>
</body>
</html>