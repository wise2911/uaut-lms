<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ratings | UAUT LMS</title>
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
                        <span>Add Video</span>
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
            <h1 class="text-2xl font-bold text-gray-800">Manage Ratings</h1>
        </header>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-6">
            @if($courseRatings->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-star text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-700">No ratings submitted yet</h3>
                    <p class="text-gray-500 mt-2">Ratings from users will appear here.</p>
                </div>
            @else
                <div class="mb-4 flex flex-col sm:flex-row justify-between items-center">
                    <input type="text" id="search" placeholder="Search ratings..." class="w-full sm:w-64 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left text-gray-600">User</th>
                                <th class="px-4 py-2 text-left text-gray-600">Course</th>
                                <th class="px-4 py-2 text-left text-gray-600">Average Rating</th>
                                <th class="px-4 py-2 text-left text-gray-600">Feedback</th>
                                <th class="px-4 py-2 text-left text-gray-600">Submitted</th>
                            </tr>
                        </thead>
                        <tbody id="ratings-table">
                            @foreach($courseRatings as $rating)
                                <tr class="border-t">
                                    <td class="px-4 py-3">{{ $rating->user->full_name }}</td>
                                    <td class="px-4 py-3">{{ $rating->course->title }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex">
                                            @php
                                                $averageRating = count($rating->responses) > 0 ? round(array_sum($rating->responses) / count($rating->responses)) : 0;
                                            @endphp
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $averageRating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">{{ $rating->feedback ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">{{ $rating->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-8">
                    {{ $courseRatings->links('vendor.pagination.tailwind') }}
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
        const tableBody = document.getElementById('ratings-table');
        let ratings = @json($courseRatings->map(function($rating) {
            return [
                'id' => $rating->id,
                'user' => ['full_name' => $rating->user->full_name],
                'course' => ['title' => $rating->course->title],
                'average_rating' => count($rating->responses) > 0 ? array_sum($rating->responses) / count($rating->responses) : 0,
                'feedback' => $rating->feedback,
                'created_at' => $rating->created_at->toDateTimeString(),
            ];
        }));

        function renderTable(data) {
            tableBody.innerHTML = '';
            data.forEach(rating => {
                const stars = Array(5).fill(0).map((_, i) => `
                    <i class="fas fa-star ${i < Math.round(rating.average_rating) ? 'text-yellow-400' : 'text-gray-300'}"></i>
                `).join('');
                tableBody.innerHTML += `
                    <tr class="border-t">
                        <td class="px-4 py-3">${rating.user.full_name}</td>
                        <td class="px-4 py-3">${rating.course.title}</td>
                        <td class="px-4 py-3"><div class="flex">${stars}</div></td>
                        <td class="px-4 py-3">${rating.feedback || 'N/A'}</td>
                        <td class="px-4 py-3">${new Date(rating.created_at).toLocaleString('en-US', { month: 'short', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                    </tr>
                `;
            });
        }

        searchInput.addEventListener('input', () => {
            const term = searchInput.value.toLowerCase();
            const filtered = ratings.filter(rating =>
                rating.user.full_name.toLowerCase().includes(term) ||
                rating.course.title.toLowerCase().includes(term) ||
                (rating.feedback && rating.feedback.toLowerCase().includes(term))
            );
            renderTable(filtered);
        });
    </script>
</body>
</html>