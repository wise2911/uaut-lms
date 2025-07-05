<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course | UAUT LMS</title>
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

        <header class="bg-white shadow-sm rounded-lg p-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Add New Course</h1>
        </header>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form id="course-form" action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Course Details</h2>
                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 font-medium mb-2">Course Title</label>
                            <input type="text" name="new_course[title]" id="title" value="{{ old('new_course.title') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('new_course.title') border-red-500 @enderror" required>
                            @error('new_course.title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
                            <textarea name="new_course[description]" id="description" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('new_course.description') border-red-500 @enderror" rows="4" required>{{ old('new_course.description') }}</textarea>
                            @error('new_course.description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="department" class="block text-gray-700 font-medium mb-2">Department</label>
                            <select name="new_course[department]" id="department" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('new_course.department') border-red-500 @enderror" required>
                                <option value="">Select Department</option>
                                <option value="COBA" {{ old('new_course.department') == 'COBA' ? 'selected' : '' }}>COBA</option>
                                <option value="COEIT" {{ old('new_course.department') == 'COEIT' ? 'selected' : '' }}>COEIT</option>
                            </select>
                            @error('new_course.department')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="instructor_name" class="block text-gray-700 font-medium mb-2">Instructor Name</label>
                            <input type="text" name="new_course[instructor_name]" id="instructor_name" value="{{ old('new_course.instructor_name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('new_course.instructor_name') border-red-500 @enderror" required>
                            @error('new_course.instructor_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-gray-700 font-medium mb-2">Price ($)</label>
                            <input type="number" name="new_course[price]" id="price" step="0.01" value="{{ old('new_course.price') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('new_course.price') border-red-500 @enderror" required>
                            @error('new_course.price')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="thumbnail" class="block text-gray-700 font-medium mb-2">Thumbnail</label>
                            <input type="file" name="new_course[thumbnail]" id="thumbnail" accept="image/jpeg,image/png" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-indigo-700 @error('new_course.thumbnail') border-red-500 @enderror">
                            @error('new_course.thumbnail')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Video Content</h2>
                        <div class="mb-4">
                            <label for="preview_url" class="block text-gray-700 font-medium mb-2">Preview Video URL</label>
                            <input type="text" name="new_course[video][preview][url]" id="preview_url" value="{{ old('new_course.video.preview.url') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('new_course.video.preview.url') border-red-500 @enderror" placeholder="e.g., videos/html/preview.mp4" required>
                            <p class="text-gray-500 text-sm mt-1">Enter path relative to public/, e.g., videos/html/preview.mp4</p>
                            @error('new_course.video.preview.url')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div id="segments">
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Video Segments</h3>
                            <div class="segment mb-4 p-4 border border-gray-200 rounded-lg">
                                <div class="mb-2">
                                    <label for="segment_title_0" class="block text-gray-700 font-medium">Title</label>
                                    <input type="text" name="new_course[video][segments][0][title]" id="segment_title_0" value="{{ old('new_course.video.segments.0.title') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('new_course.video.segments.0.title') border-red-500 @enderror" required>
                                    @error('new_course.video.segments.0.title')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-2">
                                    <label for="segment_url_0" class="block text-gray-700 font-medium">Video URL</label>
                                    <input type="text" name="new_course[video][segments][0][url]" id="segment_url_0" value="{{ old('new_course.video.segments.0.url') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('new_course.video.segments.0.url') border-red-500 @enderror" placeholder="e.g., videos/html/lesson1.mp4" required>
                                    <p class="text-gray-500 text-sm mt-1">Enter path relative to public/, e.g., videos/html/lesson1.mp4</p>
                                    @error('new_course.video.segments.0.url')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-2">
                                    <label for="segment_order_0" class="block text-gray-700 font-medium">Order</label>
                                    <input type="number" name="new_course[video][segments][0][order]" id="segment_order_0" value="{{ old('new_course.video.segments.0.order') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('new_course.video.segments.0.order') border-red-500 @enderror" min="1" required>
                                    @error('new_course.video.segments.0.order')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-segment" class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-green-600 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Add Segment
                        </button>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.videos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                    <button type="submit" id="submit-btn" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> Create Course
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        let segmentCount = 1;
        const addSegmentBtn = document.getElementById('add-segment');
        const segmentsDiv = document.getElementById('segments');
        const form = document.getElementById('course-form');
        const submitBtn = document.getElementById('submit-btn');

        addSegmentBtn.addEventListener('click', () => {
            const segmentHtml = `
                <div class="segment mb-4 p-4 border border-gray-200 rounded-lg relative">
                    <button type="button" class="remove-segment absolute top-2 right-2 text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                    <div class="mb-2">
                        <label for="segment_title_${segmentCount}" class="block text-gray-700 font-medium">Title</label>
                        <input type="text" name="new_course[video][segments][${segmentCount}][title]" id="segment_title_${segmentCount}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-2">
                        <label for="segment_url_${segmentCount}" class="block text-gray-700 font-medium">Video URL</label>
                        <input type="text" name="new_course[video][segments][${segmentCount}][url]" id="segment_url_${segmentCount}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="e.g., videos/html/lesson${segmentCount + 1}.mp4" required>
                        <p class="text-gray-500 text-sm mt-1">Enter path relative to public/, e.g., videos/html/lesson${segmentCount + 1}.mp4</p>
                    </div>
                    <div class="mb-2">
                        <label for="segment_order_${segmentCount}" class="block text-gray-700 font-medium">Order</label>
                        <input type="number" name="new_course[video][segments][${segmentCount}][order]" id="segment_order_${segmentCount}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary" min="1" required>
                    </div>
                </div>
            `;
            segmentsDiv.insertAdjacentHTML('beforeend', segmentHtml);
            segmentCount++;
        });

        segmentsDiv.addEventListener('click', (e) => {
            if (e.target.closest('.remove-segment')) {
                e.target.closest('.segment').remove();
            }
        });

        // Sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        form.addEventListener('submit', () => {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creating...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>