<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | UAUT LMS</title>
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
    <!-- Sidebar -->
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
                    <a href="{{ route('admin.users') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition-colors {{ Route::is('admin.users') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-users mr-3"></i>
                        <span>Manage Users</span>
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

    <!-- Main Content -->
    <main class="flex-1 p-6 lg:ml-64 transition-all duration-300">
        <!-- Mobile Sidebar Toggle -->
        <button id="sidebar-toggle" class="lg:hidden fixed top-4 left-4 z-30 bg-primary text-white p-2 rounded-full">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Header -->
        <header class="bg-white shadow-sm rounded-lg p-4 mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Video Management Dashboard</h1>
            <a href="{{ route('admin.videos.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Video
            </a>
        </header>

        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-accent border-l-4 border-green-500 text-white p-4 mb-6 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Video Table -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            @if($videos->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-video text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-700">No videos uploaded yet</h3>
                    <p class="text-gray-500 mt-2">Click "Add Video" to upload your first video segment.</p>
                </div>
            @else
                <div class="mb-4 flex flex-col sm:flex-row justify-between items-center">
                    <input type="text" id="search" placeholder="Search videos..." class="w-full sm:w-64 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    <div class="mt-4 sm:mt-0 flex space-x-2">
                        <button id="sort-title" class="px-3 py-1 bg-gray-200 rounded-lg hover:bg-gray-300">Sort by Title</button>
                        <button id="sort-course" class="px-3 py-1 bg-gray-200 rounded-lg hover:bg-gray-300">Sort by Course</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table id="videos-table" class="w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                                <th class="px-6 py-3 text-left font-medium">Course</th>
                                <th class="px-6 py-3 text-left font-medium">Video Title</th>
                                <th class="px-6 py-3 text-left font-medium">Topic</th>
                                <th class="px-6 py-3 text-left font-medium">Order</th>
                                <th class="px-6 py-3 text-left font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($videos as $video)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-700">{{ $video->course->title }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $video->title }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $video->course->topics[$video->topic_index]['title'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $video->order }}</td>
                                    <td class="px-6 py-4 flex space-x-2">
                                        <a href="{{ $video->cloudinary_url }}" target="_blank" class="text-primary hover:text-primary-dark" title="View Video">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Delete Video" onclick="return confirm('Are you sure you want to delete this video?');">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebar-toggle');
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        // Search and Sort
        const searchInput = document.getElementById('search');
        const table = document.getElementById('videos-table');
        const rows = table.querySelectorAll('tbody tr');
        const sortTitle = document.getElementById('sort-title');
        const sortCourse = document.getElementById('sort-course');

        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase();
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });

        sortTitle.addEventListener('click', () => {
            const sortedRows = Array.from(rows).sort((a, b) => {
                const aText = a.cells[1].textContent.toLowerCase();
                const bText = b.cells[1].textContent.toLowerCase();
                return aText.localeCompare(bText);
            });
            table.querySelector('tbody').innerHTML = '';
            sortedRows.forEach(row => table.querySelector('tbody').appendChild(row));
        });

        sortCourse.addEventListener('click', () => {
            const sortedRows = Array.from(rows).sort((a, b) => {
                const aText = a.cells[0].textContent.toLowerCase();
                const bText = b.cells[0].textContent.toLowerCase();
                return aText.localeCompare(bText);
            });
            table.querySelector('tbody').innerHTML = '';
            sortedRows.forEach(row => table.querySelector('tbody').appendChild(row));
        });
    </script>
</body>
</html>