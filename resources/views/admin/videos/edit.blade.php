<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Video | UAUT LMS</title>
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
            <h1 class="text-2xl font-bold text-gray-800">Edit Video: {{ $video->title }}</h1>
        </header>

        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Video Details</h2>
            <form action="{{ route('admin.videos.update', $video) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $video->title) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('title') border-red-500 @enderror" required>
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="url" class="block text-gray-700 font-medium mb-2">Video URL</label>
                    <input type="text" name="url" id="url" value="{{ old('url', $video->url) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('url') border-red-500 @enderror" placeholder="e.g., videos/html/video.mp4" required>
                    <p class="text-gray-500 text-sm mt-1">Enter path relative to public/, e.g., videos/html/video.mp4</p>
                    @error('url')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="is_preview" class="block text-gray-700 font-medium mb-2">Is Preview Video?</label>
                    <input type="checkbox" name="is_preview" id="is_preview" value="1" {{ old('is_preview', $video->is_preview) ? 'checked' : '' }} class="rounded border-gray-300 text-primary focus:ring-primary">
                    @error('is_preview')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> Update Video
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Add Segment</h2>
            <form action="{{ route('segments.store', $video) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="segment_title" class="block text-gray-700 font-medium mb-2">Segment Title</label>
                    <input type="text" name="title" id="segment_title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('title') border-red-500 @enderror" required>
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="segment_url" class="block text-gray-700 font-medium mb-2">Segment URL</label>
                    <input type="text" name="url" id="segment_url" value="{{ old('url') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('url') border-red-500 @enderror" placeholder="e.g., videos/html/segment1.mp4" required>
                    <p class="text-gray-500 text-sm mt-1">Enter path relative to public/, e.g., videos/html/segment1.mp4</p>
                    @error('url')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="segment_order" class="block text-gray-700 font-medium mb-2">Order</label>
                    <input type="number" name="order" id="segment_order" value="{{ old('order', $video->segments()->max('order') + 1 ?? 1) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary @error('order') border-red-500 @enderror" min="1" required>
                    @error('order')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Add Segment
                    </button>
                </div>
            </form>
        </div>

        @if ($video->segments->count())
            <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Existing Segments</h2>
                <ul class="divide-y divide-gray-200">
                    @foreach ($video->segments as $segment)
                        <li class="py-2 flex justify-between items-center">
                            <span>{{ $segment->title }} (Order: {{ $segment->order }}) - {{ $segment->url }}</span>
                            <form action="{{ route('segments.destroy', [$video, $segment]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </main>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>