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
        .course-card { transition: all 0.3s ease; }
        .course-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.2); }
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
        .accordion-header { transition: background-color 0.3s ease; }
        .accordion-header:hover { background-color: #f3f4f6; }
        .progress-bar { transition: width 0.5s ease; }
        .segment-active { background-color: #e0e7ff; }
        .now-playing::after {
            content: 'Now Playing';
            @apply ml-2 bg-accent text-white text-xs px-2 py-1 rounded-full;
        }
        .video-controls { transition: opacity 0.3s ease; }
        .video-controls:hover { opacity: 1; }
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
    <div id="mainContent" class="ml-0 lg:ml-64 transition-all duration-300 flex flex-col min-h-screen">
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <nav class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('courses.show', $course->id) }}" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $course->title }}</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700 hidden sm:inline">{{ Auth::user()->name }}</span>
                        <div class="bg-primary-100 p-2 rounded-full">
                            <i class="fas fa-user text-primary-600"></i>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <main class="container mx-auto px-6 py-8 flex-grow">
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

            <!-- Define $firstVideo and $firstSegment -->
            @php
                $allVideosWithSegments = $course->videos->filter(function ($video) {
                    return $video->segments->isNotEmpty();
                })->sortBy('id');
                $firstVideo = $allVideosWithSegments->first();
                $firstSegment = $firstVideo && $firstVideo->segments->isNotEmpty() ? $firstVideo->segments->sortBy('order')->first() : null;
            @endphp

            <!-- Progress and Course Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $course->title }}</h1>
                <div class="flex items-center mb-4">
                    @php
                        $userProgress = Auth::user()->courses()->where('course_id', $course->id)->first()->pivot->progress ?? 0;
                    @endphp
                    <p class="text-gray-600 mr-4">Progress: <span id="progress-text">{{ $userProgress }}%</span></p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div id="progress-bar" class="bg-primary h-2.5 rounded-full progress-bar" style="width: {{ $userProgress }}%"></div>
                    </div>
                </div>
                <p class="text-gray-500">Instructor: {{ $course->instructor_name }} | Department: {{ $course->department }}</p>
            </div>

            <!-- Course Content -->
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Video Player and Segments -->
                <div class="lg:w-3/4">
                    @if (!$firstVideo || !$firstSegment)
                        <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
                            <i class="fas fa-video text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-600">No videos with segments available. Please check the course content.</p>
                        </div>
                    @else
                        <div class="course-card bg-white rounded-2xl shadow-lg p-6 mb-6">
                            <div class="video-container rounded-lg overflow-hidden shadow-md mb-4">
                                <video id="main-video" controls autoplay class="w-full" data-video-id="{{ $firstVideo->id }}" data-segment-id="{{ $firstSegment->id }}">
                                    <source src="{{ url($firstSegment->url) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2" id="video-title">{{ $firstSegment->title }}</h3>
                            <div class="flex items-center space-x-4">
                                <button class="mark-watched inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700" data-video-id="{{ $firstVideo->id }}" data-course-id="{{ $course->id }}">
                                    <i class="fas fa-check mr-2"></i> Mark as Watched
                                </button>
                                <button id="next-video" class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-green-600">
                                    <i class="fas fa-forward mr-2"></i> Next Video
                                </button>
                            </div>
                        </div>

                        <!-- Segments Accordion -->
                        @foreach ($allVideosWithSegments as $video)
                            @if ($video->segments->isNotEmpty())
                                <div class="course-card bg-white rounded-2xl shadow-lg mb-4">
                                    <div class="accordion-header p-4 flex justify-between items-center cursor-pointer" data-video-id="{{ $video->id }}">
                                        <h3 class="text-lg font-medium text-gray-800">{{ $video->title }}</h3>
                                        <i class="fas fa-chevron-down text-gray-600"></i>
                                    </div>
                                    <div class="accordion-content {{ $video->id == $firstVideo->id ? '' : 'hidden' }} p-4">
                                        <h4 class="text-md font-semibold text-gray-800 mb-2">Video Segments</h4>
                                        <ul class="space-y-2">
                                            @foreach ($video->segments->sortBy('order') as $segment)
                                                <li>
                                                    <button class="segment-link text-primary hover:underline w-full text-left flex items-center {{ $segment->id == $firstSegment->id ? 'segment-active now-playing' : '' }}" data-url="{{ url($segment->url) }}" data-title="{{ $segment->title }}" data-video-id="{{ $video->id }}" data-segment-id="{{ $segment->id }}">
                                                        <i class="fas fa-play-circle mr-2"></i>
                                                        {{ $segment->title }} (Segment {{ $segment->order }})
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <button class="mark-watched inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700 mt-4" data-video-id="{{ $video->id }}" data-course-id="{{ $course->id }}">
                                            <i class="fas fa-check mr-2"></i> Mark as Watched
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>

                <!-- Course Navigation Sidebar -->
                <div class="lg:w-1/4 bg-white rounded-2xl shadow-lg p-6 sticky top-24 max-h-[calc(100vh-8rem)] overflow-y-auto">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Course Content</h3>
                    <ul class="space-y-2">
                        @foreach ($allVideosWithSegments as $index => $video)
                            @if ($video->segments->isNotEmpty())
                                @php
                                    $hasActiveSegment = $video->id == $firstVideo->id;
                                @endphp
                                <li>
                                    <button class="video-nav w-full text-left flex items-center p-2 hover:bg-primary-50 rounded-lg {{ $hasActiveSegment ? 'bg-primary-100 text-primary-800' : 'text-gray-700' }}" data-video-id="{{ $video->id }}">
                                        <i class="fas fa-video mr-2"></i>
                                        <span class="line-clamp-1">{{ $video->title }}</span>
                                    </button>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </main>

        <footer class="bg-white shadow-sm mt-auto">
            <div class="container mx-auto px-6 py-4 text-center text-gray-600">
                © {{ date('Y') }} UAUT LMS. All rights reserved.
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const closeSidebar = document.getElementById('closeSidebar');
            const mainContent = document.getElementById('mainContent');
            const videoPlayer = document.getElementById('main-video');
            const videoTitle = document.getElementById('video-title');
            const markWatchedButtons = document.querySelectorAll('.mark-watched');
            const accordionHeaders = document.querySelectorAll('.accordion-header');
            const segmentLinks = document.querySelectorAll('.segment-link');
            const videoNavs = document.querySelectorAll('.video-nav');
            const nextVideoButton = document.getElementById('next-video');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');

            // Flatten all segments for sequential playback
            const allSegments = [
                @foreach ($allVideosWithSegments as $video)
                    @foreach ($video->segments->sortBy('order') as $segment)
                        {
                            videoId: '{{ $video->id }}',
                            segmentId: '{{ $segment->id }}',
                            url: '{{ url($segment->url) }}',
                            title: '{{ $segment->title }}',
                            segmentOrder: {{ $segment->order }},
                            videoOrder: {{ $video->id }}
                        },
                    @endforeach
                @endforeach
            ].sort((a, b) => a.videoOrder - b.videoOrder || a.segmentOrder - b.segmentOrder);

            // Sidebar toggle
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
                    mainContent.classList.add('ml-64');
                    mainContent.classList.remove('ml-0');
                    sidebarToggle.classList.remove('sidebar-toggle-collapsed');
                }
            }

            checkScreenSize();
            window.addEventListener('resize', checkScreenSize);

            // Accordion functionality
            accordionHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const content = this.nextElementSibling;
                    content.classList.toggle('hidden');
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-chevron-down');
                    icon.classList.toggle('fa-chevron-up');
                });
            });

            // Segment video switching
            segmentLinks.forEach(link => {
                link.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    const title = this.getAttribute('data-title');
                    const videoId = this.getAttribute('data-video-id');
                    const segmentId = this.getAttribute('data-segment-id');
                    videoPlayer.src = videoPlayer.querySelector('source').src = url;
                    videoPlayer.setAttribute('data-video-id', videoId);
                    videoPlayer.setAttribute('data-segment-id', segmentId);
                    videoTitle.textContent = title;
                    videoPlayer.play();

                    // Update active states
                    segmentLinks.forEach(l => l.classList.remove('segment-active', 'now-playing'));
                    this.classList.add('segment-active', 'now-playing');
                    videoNavs.forEach(nav => nav.classList.remove('bg-primary-100', 'text-primary-800'));
                    document.querySelector(`.video-nav[data-video-id="${videoId}"]`)?.classList.add('bg-primary-100', 'text-primary-800');

                    // Open accordion if closed
                    const accordionContent = this.closest('.accordion-content');
                    if (accordionContent.classList.contains('hidden')) {
                        this.closest('.course-card').querySelector('.accordion-header').click();
                    }
                });
            });

            // Video navigation
            videoNavs.forEach(nav => {
                nav.addEventListener('click', function() {
                    const videoId = this.getAttribute('data-video-id');
                    const firstSegment = allSegments.find(segment => segment.videoId === videoId);
                    if (firstSegment) {
                        videoPlayer.src = videoPlayer.querySelector('source').src = firstSegment.url;
                        videoPlayer.setAttribute('data-video-id', firstSegment.videoId);
                        videoPlayer.setAttribute('data-segment-id', firstSegment.segmentId);
                        videoTitle.textContent = firstSegment.title;
                        videoPlayer.play();

                        // Update active states
                        segmentLinks.forEach(l => l.classList.remove('segment-active', 'now-playing'));
                        const segmentLink = document.querySelector(`.segment-link[data-segment-id="${firstSegment.segmentId}"]`);
                        segmentLink?.classList.add('segment-active', 'now-playing');
                        videoNavs.forEach(n => n.classList.remove('bg-primary-100', 'text-primary-800'));
                        this.classList.add('bg-primary-100', 'text-primary-800');

                        // Open accordion
                        const accordionHeader = document.querySelector(`.accordion-header[data-video-id="${videoId}"]`);
                        if (accordionHeader.nextElementSibling.classList.contains('hidden')) {
                            accordionHeader.click();
                        }
                    }
                });
            });

            // Auto-play next video
            videoPlayer.addEventListener('ended', function() {
                const currentVideoId = videoPlayer.getAttribute('data-video-id');
                const currentSegmentId = videoPlayer.getAttribute('data-segment-id');
                const currentIndex = allSegments.findIndex(segment => 
                    segment.videoId === currentVideoId && segment.segmentId === currentSegmentId
                );
                const nextSegment = allSegments[currentIndex + 1];

                if (nextSegment) {
                    videoPlayer.src = videoPlayer.querySelector('source').src = nextSegment.url;
                    videoPlayer.setAttribute('data-video-id', nextSegment.videoId);
                    videoPlayer.setAttribute('data-segment-id', nextSegment.segmentId);
                    videoTitle.textContent = nextSegment.title;
                    videoPlayer.play();

                    // Update active states
                    segmentLinks.forEach(l => l.classList.remove('segment-active', 'now-playing'));
                    const segmentLink = document.querySelector(`.segment-link[data-segment-id="${nextSegment.segmentId}"]`);
                    segmentLink?.classList.add('segment-active', 'now-playing');
                    videoNavs.forEach(n => n.classList.remove('bg-primary-100', 'text-primary-800'));
                    document.querySelector(`.video-nav[data-video-id="${nextSegment.videoId}"]`)?.classList.add('bg-primary-100', 'text-primary-800');

                    // Open accordion
                    const accordionHeader = document.querySelector(`.accordion-header[data-video-id="${nextSegment.videoId}"]`);
                    if (accordionHeader.nextElementSibling.classList.contains('hidden')) {
                        accordionHeader.click();
                    }

                    // Mark current video as watched
                    markVideoWatched(currentVideoId);
                } else {
                    alert('You have completed all videos in this course!');
                }
            });

            // Next video button
            nextVideoButton.addEventListener('click', function() {
                const currentVideoId = videoPlayer.getAttribute('data-video-id');
                const currentSegmentId = videoPlayer.getAttribute('data-segment-id');
                const currentIndex = allSegments.findIndex(segment => 
                    segment.videoId === currentVideoId && segment.segmentId === currentSegmentId
                );
                const nextSegment = allSegments[currentIndex + 1];

                if (nextSegment) {
                    videoPlayer.src = videoPlayer.querySelector('source').src = nextSegment.url;
                    videoPlayer.setAttribute('data-video-id', nextSegment.videoId);
                    videoPlayer.setAttribute('data-segment-id', nextSegment.segmentId);
                    videoTitle.textContent = nextSegment.title;
                    videoPlayer.play();

                    // Update active states
                    segmentLinks.forEach(l => l.classList.remove('segment-active', 'now-playing'));
                    const segmentLink = document.querySelector(`.segment-link[data-segment-id="${nextSegment.segmentId}"]`);
                    segmentLink?.classList.add('segment-active', 'now-playing');
                    videoNavs.forEach(n => n.classList.remove('bg-primary-100', 'text-primary-800'));
                    document.querySelector(`.video-nav[data-video-id="${nextSegment.videoId}"]`)?.classList.add('bg-primary-100', 'text-primary-800');

                    // Open accordion
                    const accordionHeader = document.querySelector(`.accordion-header[data-video-id="${nextSegment.videoId}"]`);
                    if (accordionHeader.nextElementSibling.classList.contains('hidden')) {
                        accordionHeader.click();
                    }

                    // Mark current video as watched
                    markVideoWatched(currentVideoId);
                } else {
                    alert('No more videos to play!');
                }
            });

            // Mark as watched
            function markVideoWatched(videoId) {
                const courseId = markWatchedButtons[0].getAttribute('data-course-id');
                const token = document.querySelector('meta[name="csrf-token"]').content;

                fetch(`/courses/${courseId}/mark-watched`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({ video_id: videoId }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        progressBar.style.width = `${data.progress}%`;
                        progressText.textContent = `${data.progress}%`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }

            markWatchedButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const videoId = this.getAttribute('data-video-id');
                    markVideoWatched(videoId);
                    alert('Video marked as watched!');
                });
            });

            // Auto-mark watched after 80% playback
            videoPlayer.addEventListener('timeupdate', function() {
                if (videoPlayer.duration && videoPlayer.currentTime / videoPlayer.duration > 0.8) {
                    const videoId = videoPlayer.getAttribute('data-video-id');
                    markVideoWatched(videoId);
                }
            });
        });
    </script>
</body>
</html>