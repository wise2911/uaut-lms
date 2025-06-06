<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Learn {{ $course->title }} | UAUT LMS</title>
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
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            background: #000;
        }
        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .progress-container { position: relative; }
        .progress-tooltip {
            display: none;
            position: absolute;
            background: #1f2937;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
            z-index: 10;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .progress-container:hover .progress-tooltip { display: block; }
        .progress-bar {
            transition: width 0.5s ease-in-out, background-color 0.3s ease;
            background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            height: 12px;
            border-radius: 9999px;
            position: relative;
            overflow: hidden;
        }
        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                -45deg,
                rgba(255, 255, 255, 0.2) 25%,
                transparent 25%,
                transparent 50%,
                rgba(255, 255, 255, 0.2) 50%,
                rgba(255, 255, 255, 0.2) 75%,
                transparent 75%,
                transparent
            );
            background-size: 20px 20px;
            animation: progress-stripes 1s linear infinite;
            opacity: 0.5;
        }
        .progress-bar-container {
            background: #e5e7eb;
            border-radius: 9999px;
            overflow: hidden;
            height: 12px;
            transition: background-color 0.3s ease;
        }
        .progress-bar-container:hover {
            background: #d1d5db;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        @keyframes progress-stripes {
            0% { background-position: 0 0; }
            100% { background-position: 20px 0; }
        }
        .progress-complete .progress-bar {
            animation: pulse 1.5s ease-in-out infinite;
            background: linear-gradient(90deg, #059669 0%, #10b981 100%);
        }
        .progress-complete .progress-bar::after {
            animation: none;
            opacity: 0;
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
                    <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->full_name }}</p>
                </div>
            </div>
        </div>
        <div class="p-4">
            <ul class="space-y-2">
                <li><a href="{{ route('user.home') }}" class="flex items-center p-3 text-gray-700 hover:bg-primary-50 rounded-lg"><i class="fas fa-home mr-3 text-primary-600"></i>Dashboard</a></li>
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
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('courses.show', $course->id) }}" class="text-gray-600 hover:text-gray-800"><i class="fas fa-arrow-left"></i></a>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $course->title }}</h1>
                    </div>
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
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded animate-fade-in"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</div>
            @endif

            @php
                $totalSegments = $course->video->flatMap->segments->count();
            @endphp

            @if (request()->has('completed'))
                <!-- Course Completion Section -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">Congratulations!</h1>
                    <p class="text-gray-600 mb-6">You have successfully completed <strong>{{ $course->title }}</strong>. Download your certificate and share your feedback to help us improve.</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('courses.certificate', $course->id) }}" class="inline-flex items-center px-6 py-3 bg-accent text-white rounded-lg hover:bg-green-600 text-lg">
                            <i class="fas fa-certificate mr-2"></i> Download Certificate
                        </a>
                        @if (!\App\Models\CourseRating::where('user_id', Auth::id())->where('course_id', $course->id)->exists())
                            <a href="{{ route('courses.rate', $course->id) }}" class="inline-flex items-center px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 text-lg">
                                <i class="fas fa-star mr-2"></i> Rate Course
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <!-- Course Header -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $course->title }}</h1>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center mb-4 space-y-4 sm:space-y-0 sm:space-x-4">
                        <div class="progress-container w-full sm:w-3/4">
                            @php
                                $completedCount = Auth::user()->completedSegments($course)->count();
                            @endphp
                            <p class="text-gray-600 mb-2">Progress: <span id="progress-text">{{ $userProgress }}%</span> (<span id="segment-count">{{ $completedCount }}/{{ $totalSegments }}</span> segments completed)</p>
                            <div class="progress-bar-container" role="progressbar" aria-valuenow="{{ $userProgress }}" aria-valuemin="0" aria-valuemax="100" data-total-segments="{{ $totalSegments }}">
                                <div id="progress-bar" class="progress-bar {{ $userProgress >= 100 ? 'progress-complete' : '' }}" style="width: {{ $userProgress }}%" data-tooltip="{{ $completedCount }} of {{ $totalSegments }} segments completed"></div>
                            </div>
                            <span class="progress-tooltip">{{ $completedCount }} of {{ $totalSegments }} segments completed</span>
                        </div>
                        <div id="course-actions" class="flex space-x-4">
                            <button id="progress-details" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700"><i class="fas fa-info-circle mr-2"></i>View Progress Details</button>
                        </div>
                    </div>
                    <p class="text-gray-500">Instructor: {{ $course->instructor_name }} | Department: {{ $course->department ?? 'N/A' }}</p>
                </div>

                <!-- Video Content -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Course Content</h2>
                    <div class="flex flex-col lg:flex-row gap-6">
                        <!-- Video Player -->
                        <div class="lg:w-3/4">
                            <div class="course-card bg-white rounded-2xl shadow-lg p-6">
                                @php
                                    $currentSegment = $course->video->flatMap->segments->sortBy('order')->first();
                                    if (request()->has('segment_id')) {
                                        $currentSegment = \App\Models\Segment::find(request('segment_id'));
                                    }
                                    $isCompleted = $currentSegment ? Auth::user()->segmentProgress()->where('segment_id', $currentSegment->id)->where('completed', true)->exists() : false;
                                @endphp
                                @if ($currentSegment && $currentSegment->url)
                                    <div class="video-container rounded-lg overflow-hidden shadow-md mb-4">
                                        <video id="course-video" controls data-segment-id="{{ $currentSegment->id }}" data-video-id="{{ $currentSegment->video_id }}">
                                            <source src="{{ url($currentSegment->url) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $currentSegment->title }} (Segment {{ $currentSegment->order }})</h3>
                                    <button id="mark-watched" style="display: none;" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700 {{ $isCompleted ? 'opacity-50 cursor-not-allowed' : '' }}" data-video-id="{{ $currentSegment->video_id }}" data-segment-id="{{ $currentSegment->id }}" {{ $isCompleted ? 'disabled' : '' }}>
                                        <i class="fas fa-check-circle mr-2"></i>{{ $isCompleted ? 'Completed' : 'Mark as Watched' }}
                                    </button>
                                @else
                                    <div class="bg-gray-200 rounded-lg p-6 text-center">
                                        <i class="fas fa-video text-gray-400 text-4xl mb-4"></i>
                                        <p class="text-gray-600">No segments available for this course.</p>
                                    </div>
                                @endif
                            </div>
                            <!-- Navigation Buttons -->
                            @if ($currentSegment)
                                <div class="flex justify-between mt-4">
                                    @php
                                        $prevSegment = $course->video->flatMap->segments->sortBy('order')->where('order', '<', $currentSegment->order)->last();
                                        $nextSegment = $course->video->flatMap->segments->sortBy('order')->where('order', '>', $currentSegment->order)->first();
                                    @endphp
                                    <a href="{{ $prevSegment ? route('courses.learn', [$course->id, 'segment_id' => $prevSegment->id]) : '#' }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-lg {{ $prevSegment ? 'hover:bg-gray-400' : 'opacity-50 cursor-not-allowed' }}" {{ !$prevSegment ? 'disabled' : '' }}>
                                        <i class="fas fa-arrow-left mr-2"></i>Previous
                                    </a>
                                    <a id="next-button" href="{{ $nextSegment ? route('courses.learn', [$course->id, 'segment_id' => $nextSegment->id]) : '#' }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-lg {{ $nextSegment ? 'hover:bg-gray-400' : 'opacity-50 cursor-not-allowed' }}" data-is-last="{{ $nextSegment ? 'false' : 'true' }}" {{ !$nextSegment ? 'disabled' : '' }}>
                                        Next<i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <!-- Segment List -->
                        <div class="lg:w-1/4">
                            <div class="bg-white rounded-2xl shadow-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Course Segments</h3>
                                @if ($course->video->isEmpty())
                                    <p class="text-gray-600">No videos available.</p>
                                @else
                                    <ul class="space-y-2">
                                        @foreach ($course->video as $video)
                                            @foreach ($video->segments->sortBy('order') as $segment)
                                                @php
                                                    $isSegmentCompleted = Auth::user()->segmentProgress()->where('segment_id', $segment->id)->where('completed', true)->exists();
                                                @endphp
                                                <li>
                                                    <a href="{{ route('courses.learn', [$course->id, 'segment_id' => $segment->id]) }}" class="flex items-center p-2 text-gray-700 hover:bg-primary-50 rounded-lg {{ $currentSegment && $currentSegment->id == $segment->id ? 'bg-primary-50' : '' }}">
                                                        <i class="fas fa-play-circle mr-2 {{ $isSegmentCompleted ? 'text-accent' : 'text-gray-500' }}"></i>
                                                        <span class="text-sm">{{ $segment->title }} (Segment {{ $segment->order }})</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </main>

        <footer class="bg-white shadow-sm mt-auto">
            <div class="container mx-auto px-6 py-4 text-center text-gray-600">© {{ date('Y') }} UAUT LMS. All rights reserved.</div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Sidebar Toggle
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

            // Progress Bar and Mark as Watched
            const markWatchedButton = document.querySelector('#mark-watched');
            const video = document.querySelector('#course-video');
            const progressBar = document.querySelector('#progress-bar');
            const progressText = document.querySelector('#progress-text');
            const segmentCount = document.querySelector('#segment-count');
            const actionsDiv = document.querySelector('#course-actions');
            const nextButton = document.querySelector('#next-button');
            const isLastSegment = nextButton && nextButton.dataset.isLast === 'true';
            const totalSegments = parseInt(document.querySelector('.progress-bar-container').dataset.totalSegments);

            function markSegmentAsWatched(videoId, segmentId) {
                fetch(`/courses/{{ $course->id }}/mark-watched`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ video_id: videoId, segment_id: segmentId }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        markWatchedButton.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Completed';
                        markWatchedButton.classList.add('opacity-50', 'cursor-not-allowed');
                        markWatchedButton.disabled = true;

                        // Update Progress Bar Dynamically
                        progressBar.style.width = `${data.progress}%`;
                        progressBar.setAttribute('aria-valuenow', data.progress);
                        progressText.textContent = `${data.progress}%`;
                        segmentCount.textContent = `${data.completedCount}/${totalSegments}`;
                        progressBar.dataset.tooltip = `${data.completedCount} of ${totalSegments} segments completed`;
                        if (data.progress >= 100) {
                            progressBar.classList.add('progress-complete');
                        }
                    } else {
                        alert(data.message || 'Failed to mark segment as watched.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }

            if (markWatchedButton && !markWatchedButton.disabled) {
                markWatchedButton.addEventListener('click', () => {
                    const videoId = markWatchedButton.dataset.videoId;
                    const segmentId = markWatchedButton.dataset.segmentId;
                    markSegmentAsWatched(videoId, segmentId);
                });
            }

            if (video) {
                // Auto-mark at 80% for non-last segments
                video.addEventListener('timeupdate', () => {
                    if (!isLastSegment && video.currentTime / video.duration >= 0.8 && !markWatchedButton.disabled) {
                        markSegmentAsWatched(video.dataset.videoId, video.dataset.segmentId);
                    }
                });

                // Handle last segment completion
                if (isLastSegment) {
                    video.addEventListener('ended', () => {
                        if (!markWatchedButton.disabled) {
                            markSegmentAsWatched(video.dataset.videoId, video.dataset.segmentId);
                        }
                        // Show Certificate and Rate Buttons
                        if (!document.querySelector('#certificate-button')) {
                            actionsDiv.insertAdjacentHTML('beforeend', `
                                <a id="certificate-button" href="{{ route('courses.certificate', $course->id) }}" class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-green-600">
                                    <i class="fas fa-certificate mr-2"></i>Download Certificate
                                </a>
                            `);
                        }
                        @if (!\App\Models\CourseRating::where('user_id', Auth::id())->where('course_id', $course->id)->exists())
                            if (!document.querySelector('#rate-button')) {
                                actionsDiv.insertAdjacentHTML('beforeend', `
                                    <a id="rate-button" href="{{ route('courses.rate', $course->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                        <i class="fas fa-star mr-2"></i>Rate Course
                                    </a>
                                `);
                            }
                        @endif
                    });
                }
            }
        });
    </script>
</body>
</html>