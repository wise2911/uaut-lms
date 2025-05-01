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
</head>
<body class="bg-gray-100 font-sans antialiased">
    <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ url('img/uaut-logo.jpg') }}" alt="UAUT LMS" class="h-8 w-8 mr-3">
                <h1 class="text-xl font-bold text-gray-800">{{ $course->title }}</h1>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-600 hover:text-red-700 flex items-center">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
            </form>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-48 object-cover rounded-lg mb-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $course->title }}</h2>
            <p class="text-gray-600 mb-4">{{ $course->description }}</p>
            <p class="text-gray-600 mb-4">Instructor: {{ $course->instructor_name }}</p>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Learning Outcomes</h3>
            <ul class="list-disc pl-5 mb-4">
                @foreach(json_decode($course->learning_outcomes, true) as $outcome)
                    <li class="text-gray-600">{{ $outcome }}</li>
                @endforeach
            </ul>

            <h3 class="text-xl font-bold text-gray-800 mb-4">Course Content</h3>
            @if(empty($course->topics))
                <p class="text-gray-500">No topics available for this course.</p>
            @else
                <div class="space-y-6">
                    @foreach(json_decode($course->topics, true) as $index => $topic)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-lg font-medium text-gray-800">{{ $topic['title'] }} (Duration: {{ $topic['duration'] }})</h4>
                            <h5 class="text-md font-medium text-gray-700 mt-2">Lessons</h5>
                            <ul class="list-disc pl-5">
                                @foreach($topic['lessons'] as $lesson)
                                    <li class="text-gray-600">{{ $lesson }}</li>
                                @endforeach
                            </ul>
                            <h5 class="text-md font-medium text-gray-700 mt-2">Videos</h5>
                            @php
                                $topicVideos = $course->videos->where('topic_index', $index)->sortBy('order');
                            @endphp
                            @if($topicVideos->isEmpty())
                                <p class="text-gray-500">No videos available for this topic.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($topicVideos as $video)
                                        <div class="bg-white p-4 rounded-lg border">
                                            <h6 class="text-md font-medium text-gray-800">{{ $video->title }} (Order: {{ $video->order }})</h6>
                                            <video controls class="w-full mt-2 rounded-lg" src="{{ $video->cloudinary_url }}"></video>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
</body>
</html>