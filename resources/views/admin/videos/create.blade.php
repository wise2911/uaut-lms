<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course/Video | UAUT LMS</title>
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
                    <a href="{{ route('admin.videos.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition-colors {{ Route::is('admin.videos.*') ? 'bg-gray-700' : '' }}">
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
            <h1 class="text-2xl font-bold text-gray-800">Create New Course/Video</h1>
            <a href="{{ route('admin.videos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back to Videos
            </a>
        </header>

        <!-- Alerts -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 max-w-3xl mx-auto">
            <form id="videoUploadForm" action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- New Course -->
                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-gray-700 font-medium mb-2">Course Title</label>
                        <input type="text" name="new_course[title]" id="title" value="{{ old('new_course.title') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
                        <textarea name="new_course[description]" id="description" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>{{ old('new_course.description') }}</textarea>
                    </div>
                    <div>
                        <label for="department" class="block text-gray-700 font-medium mb-2">Department</label>
                        <select name="new_course[department]" id="department" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="COBA" {{ old('new_course.department') == 'COBA' ? 'selected' : '' }}>COBA</option>
                            <option value="COEIT" {{ old('new_course.department') == 'COEIT' ? 'selected' : '' }}>COEIT</option>
                        </select>
                    </div>
                    <div>
                        <label for="instructor_name" class="block text-gray-700 font-medium mb-2">Instructor Name</label>
                        <input type="text" name="new_course[instructor_name]" id="instructor_name" value="{{ old('new_course.instructor_name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Learning Outcomes</label>
                        <div id="learning-outcomes-container" class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <input type="text" name="new_course[learning_outcomes][]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary" value="{{ old('new_course.learning_outcomes.0') }}" placeholder="Enter learning outcome">
                                <button type="button" class="remove-outcome text-red-600 hover:text-red-800" disabled><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                        <button type="button" id="add-outcome" class="mt-2 text-primary hover:underline flex items-center">
                            <i class="fas fa-plus mr-2"></i> Add Learning Outcome
                        </button>
                    </div>
                    <div>
                        <label for="thumbnail" class="block text-gray-700 font-medium mb-2">Course Thumbnail</label>
                        <input type="file" name="new_course[thumbnail]" id="thumbnail" accept="image/jpeg,image/png" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        <p class="text-sm text-gray-500 mt-1">Max size: 5MB (JPEG, PNG)</p>
                    </div>
                    <!-- Dynamic Topics -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Topics</label>
                        <div id="topics-container" class="space-y-4">
                            <div class="topic bg-gray-50 p-4 rounded-lg">
                                <div class="mb-2">
                                    <label class="block text-gray-700 text-sm">Topic Title</label>
                                    <input type="text" name="new_course[topics][0][title]" class="w-full border border-gray-300 rounded-lg px-4 py-2" value="{{ old('new_course.topics.0.title') }}" required>
                                </div>
                                <div class="mb-2">
                                    <label class="block text-gray-700 text-sm">Duration</label>
                                    <input type="text" name="new_course[topics][0][duration]" class="w-full border border-gray-300 rounded-lg px-4 py-2" value="{{ old('new_course.topics.0.duration') }}" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm">Lessons</label>
                                    <div class="lessons-container space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <input type="text" name="new_course[topics][0][lessons][]" class="w-full border border-gray-300 rounded-lg px-4 py-2" value="{{ old('new_course.topics.0.lessons.0') }}" placeholder="Enter lesson" required>
                                            <button type="button" class="remove-lesson text-red-600 hover:text-red-800" disabled><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <button type="button" class="add-lesson mt-2 text-primary hover:underline flex items-center">
                                        <i class="fas fa-plus mr-2"></i> Add Lesson
                                    </button>
                                </div>
                                <!-- Video Segments -->
                                <div class="mt-4">
                                    <label class="block text-gray-700 text-sm">Video Segments</label>
                                    <div class="videos-container space-y-2">
                                        <div class="video-segment bg-white p-4 rounded-lg border">
                                            <div class="mb-2">
                                                <label class="block text-gray-700 text-sm">Video Title</label>
                                                <input type="text" name="new_course[topics][0][videos][0][title]" class="w-full border border-gray-300 rounded-lg px-4 py-2" value="{{ old('new_course.topics.0.videos.0.title') }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="block text-gray-700 text-sm">Video File</label>
                                                <input type="file" name="new_course[topics][0][videos][0][file]" accept="video/mp4,video/mov,video/avi" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                                                <p class="text-sm text-gray-500 mt-1">Max size: 100MB (MP4, MOV, AVI)</p>
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 text-sm">Order</label>
                                                <input type="number" name="new_course[topics][0][videos][0][order]" min="1" value="{{ old('new_course.topics.0.videos.0.order', 1) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="add-video mt-2 text-primary hover:underline flex items-center">
                                        <i class="fas fa-plus mr-2"></i> Add Video Segment
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-topic" class="mt-2 text-primary hover:underline flex items-center">
                            <i class="fas fa-plus mr-2"></i> Add Topic
                        </button>
                    </div>
                </div>

                <button type="submit" id="uploadButton" class="w-full bg-primary text-white px-4 py-3 rounded-lg hover:bg-primary-dark transition-colors flex items-center justify-center disabled:bg-gray-400" disabled>
                    <i class="fas fa-upload mr-2"></i> Create Course & Upload Videos
                </button>
            </form>
        </div>
    </main>

    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebar-toggle');
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        // Dynamic Learning Outcomes
        let outcomeIndex = 1;
        document.getElementById('add-outcome').addEventListener('click', () => {
            const container = document.getElementById('learning-outcomes-container');
            const outcomeDiv = document.createElement('div');
            outcomeDiv.className = 'flex items-center space-x-2';
            outcomeDiv.innerHTML = `
                <input type="text" name="new_course[learning_outcomes][]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Enter learning outcome">
                <button type="button" class="remove-outcome text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
            `;
            container.appendChild(outcomeDiv);
            outcomeIndex++;
            updateRemoveButtons(container, 'remove-outcome');
            updateButtonState();
        });

        // Dynamic Topics
        let topicIndex = 1;
        document.getElementById('add-topic').addEventListener('click', () => {
            const container = document.getElementById('topics-container');
            const topicDiv = document.createElement('div');
            topicDiv.className = 'topic bg-gray-50 p-4 rounded-lg';
            topicDiv.innerHTML = `
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm">Topic Title</label>
                    <input type="text" name="new_course[topics][${topicIndex}][title]" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm">Duration</label>
                    <input type="text" name="new_course[topics][${topicIndex}][duration]" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm">Lessons</label>
                    <div class="lessons-container space-y-2">
                        <div class="flex items-center space-x-2">
                            <input type="text" name="new_course[topics][${topicIndex}][lessons][]" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Enter lesson" required>
                            <button type="button" class="remove-lesson text-red-600 hover:text-red-800" disabled><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <button type="button" class="add-lesson mt-2 text-primary hover:underline flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add Lesson
                    </button>
                </div>
                <div class="mt-4">
                    <label class="block text-gray-700 text-sm">Video Segments</label>
                    <div class="videos-container space-y-2">
                        <div class="video-segment bg-white p-4 rounded-lg border">
                            <div class="mb-2">
                                <label class="block text-gray-700 text-sm">Video Title</label>
                                <input type="text" name="new_course[topics][${topicIndex}][videos][0][title]" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                            </div>
                            <div class="mb-2">
                                <label class="block text-gray-700 text-sm">Video File</label>
                                <input type="file" name="new_course[topics][${topicIndex}][videos][0][file]" accept="video/mp4,video/mov,video/avi" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                                <p class="text-sm text-gray-500 mt-1">Max size: 100MB (MP4, MOV, AVI)</p>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm">Order</label>
                                <input type="number" name="new_course[topics][${topicIndex}][videos][0][order]" min="1" value="1" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="add-video mt-2 text-primary hover:underline flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add Video Segment
                    </button>
                </div>
                <button type="button" class="remove-topic text-red-600 hover:underline mt-2">Remove Topic</button>
            `;
            container.appendChild(topicDiv);
            topicIndex++;
            addTopicListeners(topicDiv);
            updateRemoveButtons(container, 'remove-topic');
            updateButtonState();
        });

        function addTopicListeners(topicDiv) {
            // Add Lesson
            topicDiv.querySelector('.add-lesson').addEventListener('click', () => {
                const lessonsContainer = topicDiv.querySelector('.lessons-container');
                const lessonDiv = document.createElement('div');
                lessonDiv.className = 'flex items-center space-x-2';
                const topicIdx = topicDiv.querySelector('input[name*="topics"]').name.match(/\[(\d+)\]/)[1];
                lessonDiv.innerHTML = `
                    <input type="text" name="new_course[topics][${topicIdx}][lessons][]" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Enter lesson" required>
                    <button type="button" class="remove-lesson text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                `;
                lessonsContainer.appendChild(lessonDiv);
                updateRemoveButtons(lessonsContainer, 'remove-lesson');
                updateButtonState();
            });

            // Add Video
            let videoIndex = 1;
            topicDiv.querySelector('.add-video').addEventListener('click', () => {
                const videosContainer = topicDiv.querySelector('.videos-container');
                const videoDiv = document.createElement('div');
                videoDiv.className = 'video-segment bg-white p-4 rounded-lg border';
                const topicIdx = topicDiv.querySelector('input[name*="topics"]').name.match(/\[(\d+)\]/)[1];
                videoDiv.innerHTML = `
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm">Video Title</label>
                        <input type="text" name="new_course[topics][${topicIdx}][videos][${videoIndex}][title]" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm">Video File</label>
                        <input type="file" name="new_course[topics][${topicIdx}][videos][${videoIndex}][file]" accept="video/mp4,video/mov,video/avi" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                        <p class="text-sm text-gray-500 mt-1">Max size: 100MB (MP4, MOV, AVI)</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm">Order</label>
                        <input type="number" name="new_course[topics][${topicIdx}][videos][${videoIndex}][order]" min="1" value="${videoIndex + 1}" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                    </div>
                    <button type="button" class="remove-video text-red-600 hover:underline mt-2">Remove Video</button>
                `;
                videosContainer.appendChild(videoDiv);
                videoIndex++;
                updateRemoveButtons(videosContainer, 'remove-video');
                updateButtonState();
            });
        }

        function updateRemoveButtons(container, className) {
            const buttons = container.querySelectorAll(`.${className}`);
            buttons.forEach((button, index) => {
                button.disabled = index === 0 && buttons.length === 1;
                button.addEventListener('click', () => {
                    button.closest('.flex, .topic, .video-segment').remove();
                    updateButtonState();
                });
            });
        }

        // Form Validation and Button State
        function updateButtonState() {
            const requiredFields = document.querySelectorAll('input[required], textarea[required], select[required]');
            const uploadButton = document.getElementById('uploadButton');
            uploadButton.disabled = !Array.from(requiredFields).every(field => field.value);
        }

        // Form Submission with Loading State
        document.getElementById('videoUploadForm').addEventListener('submit', function(e) {
            const uploadButton = document.getElementById('uploadButton');
            uploadButton.disabled = true;
            uploadButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Uploading...';
        });

        // Initialize
        document.querySelectorAll('.topic').forEach(addTopicListeners);
        updateRemoveButtons(document.getElementById('learning-outcomes-container'), 'remove-outcome');
        updateRemoveButtons(document.getElementById('topics-container'), 'remove-topic');
        updateRemoveButtons(document.querySelector('.lessons-container'), 'remove-lesson');
        updateRemoveButtons(document.querySelector('.videos-container'), 'remove-video');
        document.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('input', updateButtonState);
        });
        updateButtonState();
    </script>
</body>
</html>