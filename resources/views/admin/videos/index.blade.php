<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Videos | UAUT LMS</title>
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
</head>
<body class="bg-gray-100 font-sans antialiased flex">
    <aside id="sidebar" class="bg-secondary text-white w-64 min-h-screen p-4 fixed top-0 left-0 transition-transform duration-300 transform lg:translate-x-0 -translate-x-full z-20">
        <div class="flex items-center mb-8">
            <img src="{{ url('img/uaut-logo.jpg') }}" alt="UAUT LMS" class="h-8 w-8 mr-3">
            <h1 class="text-xl font-bold">UAUT LMS Admin</h1>
        </div>
        <nav>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('admin.videos.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition-colors {{ Route::is('admin.videos.index') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-video mr-3"></i>
                        <span>Manage Videos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.videos.create') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition-colors {{ Route::is('admin.videos.create') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-plus mr-3"></i>
                        <span>Add Course</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition-colors {{ Route::is('admin.users') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-users mr-3"></i>
                        <span>Manage Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.ratings') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition-colors {{ Route::is('admin.ratings') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-star mr-3"></i>
                        <span>View Ratings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.payments') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition-colors {{ Route::is('admin.payments') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-money-bill mr-3"></i>
                        <span>View Payments</span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center p-2 w-full text-left rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="flex-1 p-6 lg:ml-64 transition-all duration-300">
        <button id="sidebar-toggle" class="lg:hidden fixed top-4 left-4 z-30 bg-primary text-white p-2 rounded-full">
            <i class="fas fa-bars"></i>
        </button>

        <header class="bg-white shadow-sm rounded-lg p-4 mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Manage Videos</h1>
            <a href="{{ route('admin.videos.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Video
            </a>
        </header>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-6">
            @if($courses->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-video text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-700">No courses uploaded yet</h3>
                    <p class="text-gray-500 mt-2">Click "Add Video" to upload your first course.</p>
                </div>
            @else
                <div class="mb-4 flex flex-col sm:flex-row justify-between items-center">
                    <input type="text" id="search" placeholder="Search courses..." class="w-full sm:w-64 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    <div class="mt-4 sm:mt-0 flex space-x-2">
                        <button id="sort-title" class="px-3 py-1 bg-gray-200 rounded-lg hover:bg-gray-300">Sort by Title</button>
                        <button id="sort-department" class="px-3 py-1 bg-gray-200 rounded-lg hover:bg-gray-300">Sort by Department</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left text-gray-600">Course Title</th>
                                <th class="px-4 py-2 text-left text-gray-600">Department</th>
                                <th class="px-4 py-2 text-left text-gray-600">Videos</th>
                                <th class="px-4 py-2 text-left text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="course-table">
                            @foreach($courses as $course)
                                <tr class="border-t">
                                    <td class="px-4 py-3">{{ $course->title }}</td>
                                    <td class="px-4 py-3">{{ $course->department }}</td>
                                    <td class="px-4 py-3">{{ optional($course->video)->count() ?? 0 }}</td>
                                    <td class="px-4 py-3 flex space-x-2">
                                        @if($course->video && $course->video->isNotEmpty())
                                            @foreach($course->video as $video)
                                                <a href="{{ route('admin.videos.edit', $video) }}" class="text-blue-600 hover:text-blue-800" title="Edit {{ $video->title }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $video->title }} and its associated course?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete {{ $video->title }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </main>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebar-toggle');
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        const searchInput = document.getElementById('search');
        const sortTitle = document.getElementById('sort-title');
        const sortDepartment = document.getElementById('sort-department');
        const tableBody = document.getElementById('course-table');
        let courses = @json($courses);

        function renderTable(data) {
            tableBody.innerHTML = '';
            data.forEach(course => {
                let actions = '';
                if (course.video && course.video.length > 0) {
                    actions = course.video.map(video => `
                        <a href="/admin/videos/${video.id}/edit" class="text-blue-600 hover:text-blue-800" title="Edit ${video.title}">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="/admin/videos/${video.id}" method="POST" onsubmit="return confirm('Are you sure you want to delete ${video.title} and its associated course?');">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-red-600 hover:text-red-800" title="Delete ${video.title}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    `).join(' ');
                }
                tableBody.innerHTML += `
                    <tr class="border-t">
                        <td class="px-4 py-3">${course.title}</td>
                        <td class="px-4 py-3">${course.department}</td>
                        <td class="px-4 py-3">${course.video ? course.video.length : 0}</td>
                        <td class="px-4 py-3 flex space-x-2">${actions}</td>
                    </tr>
                `;
            });
        }

        searchInput.addEventListener('input', () => {
            const term = searchInput.value.toLowerCase();
            const filtered = courses.filter(course =>
                course.title.toLowerCase().includes(term) ||
                course.department.toLowerCase().includes(term)
            );
            renderTable(filtered);
        });

        sortTitle.addEventListener('click', () => {
            courses.sort((a, b) => a.title.localeCompare(b.title));
            renderTable(courses);
        });

        sortDepartment.addEventListener('click', () => {
            courses.sort((a, b) => a.department.localeCompare(b.department));
            renderTable(courses);
        });
    </script>
</body>
</html>