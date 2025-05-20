@extends('layouts.app')

@section('title', 'Home')
@section('content')
<!-- Hero Section -->
<section class="relative bg-cover bg-center h-[90vh] flex items-center" style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('img/landing-page.jpg') }}');">
    <div class="container mx-auto px-6 max-w-7xl">
        <div class="text-white max-w-2xl">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight animate-fade-in">
                Transform Your Future with <span class="text-blue-400">UAUT LMS</span>
            </h1>
            <p class="text-lg md:text-xl mb-8 text-gray-300 leading-relaxed">
                Access world-class education through our innovative learning platform. 
                Designed to empower students and professionals with cutting-edge skills.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="/user/signup" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    Get Started
                </a>
                <a href="#courses" class="border-2 border-blue-400 text-blue-400 hover:bg-blue-400 hover:text-white px-8 py-4 rounded-lg font-semibold text-lg transition duration-300 flex items-center justify-center gap-2">
                    <i class="fas fa-play"></i> Explore Courses
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6 max-w-7xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <div class="bg-blue-50 p-8 rounded-xl border border-blue-100">
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-graduation-cap text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Expert Instructors</h3>
                <p class="text-gray-600">Learn from industry professionals with years of teaching and practical experience.</p>
            </div>
            <div class="bg-blue-50 p-8 rounded-xl border border-blue-100">
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-laptop-code text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Hands-on Learning</h3>
                <p class="text-gray-600">Practical projects and real-world scenarios to enhance your skills.</p>
            </div>
            <div class="bg-blue-50 p-8 rounded-xl border border-blue-100">
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-certificate text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Certification</h3>
                <p class="text-gray-600">Earn recognized certificates to boost your career prospects.</p>
            </div>
        </div>
    </div>
</section>

<!-- Courses Section -->
<section id="courses" class="py-16 bg-gray-50">
    <div class="container mx-auto px-6 max-w-7xl">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Our Courses</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Browse our programs designed for academic excellence</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Course 1 -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                <div class="relative" style="padding-block-end: 56.25%;">
                    <iframe class="absolute top-0 left-0 w-full h-full" src="https://www.youtube.com/embed/W6NZfCO5SIk" title="Web Development Overview" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Web Development</h3>
                    <p class="text-gray-600 mb-4">Master modern web technologies including HTML5, CSS3, JavaScript, and frameworks.</p>
                    <div class="flex items-center">
                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                        <span class="text-gray-700 font-medium">4.8</span>
                    </div>
                </div>
            </div>
            
            <!-- Course 2 -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                <div class="relative" style="padding-block-end: 56.25%;">
                    <iframe class="absolute top-0 left-0 w-full h-full" src="https://www.youtube.com/embed/VwN91x5i25g?list=PLBlnK6fEyqRgMCUAG0XRw78UA8qnv6jEx" title="Computer Networking Overview" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Computer Networking</h3>
                    <p class="text-gray-600 mb-4">Comprehensive training in network design, security protocols, and administration.</p>
                    <div class="flex items-center">
                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                        <span class="text-gray-700 font-medium">4.7</span>
                    </div>
                </div>
            </div>
            
            <!-- Course 3 -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                <div class="relative" style="padding-block-end: 56.25%;">
                    <iframe class="absolute top-0 left-0 w-full h-full" src="https://www.youtube.com/embed/I9ceqw5Ny-4?list=PLSzsOkUDsvdtl3Pw48-R8lcK2oYkk40cm" title="Mobile Application Overview" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Mobile Application Development</h3>
                    <p class="text-gray-600 mb-4">Build cross-platform apps using React Native and Flutter frameworks.</p>
                    <div class="flex items-center">
                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                        <span class="text-gray-700 font-medium">4.9</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-12">
            <a href="/user/login" class="inline-block border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-8 py-3 rounded-lg font-semibold transition duration-300">
                View All Courses <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- About Us Section -->
<section id="about" class="py-16 bg-white">
    <div class="container mx-auto px-6 max-w-7xl">
        <div class="flex flex-col md:flex-row items-center gap-12">
            <div class="md:w-1/2">
                <img src="{{ url('img/uaut-learn.jpg') }}" alt="UAUT Campus" class="rounded-xl shadow-lg w-full">
            </div>
            <div class="md:w-1/2">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">About UAUT Learning Platform</h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    The United African University of Tanzania (UAUT) Learning Management System represents our commitment to 
                    accessible, high-quality education. Our platform combines academic rigor with innovative technology 
                    to create transformative learning experiences.
                </p>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <i class="fas fa-check-circle text-blue-600"></i>
                        </div>
                        <p class="ml-3 text-gray-600">Accredited programs recognized by industry leaders</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <i class="fas fa-check-circle text-blue-600"></i>
                        </div>
                        <p class="ml-3 text-gray-600">Interactive learning with peer collaboration</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <i class="fas fa-check-circle text-blue-600"></i>
                        </div>
                        <p class="ml-3 text-gray-600">Career support and professional networking</p>
                    </div>
                </div>
                <a href="#" class="inline-block mt-8 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
                    Learn More About Us
                </a>
            </div>
        </div>
    </div>
</section>
@endsection