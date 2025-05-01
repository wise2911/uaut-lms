@extends('layouts.app')

@section('title', 'Log In')

@section('content')
<section class="py-16 bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-6">
        <div class="max-w-md mx-auto bg-white px-8 py-12 rounded-lg shadow-lg animate-slide-in delay-100">
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('img/uaut-logo.jpg') }}" alt="UAUT Logo" class="h-16 w-16 rounded-full">
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Log In</h1>
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-4 mb-6 rounded-md">
                    {{ session('success') }}
                </div>
            @endif
            <form method="POST" action="/user/login" class="space-y-6 px-6"> <!-- Added px-6 for internal padding -->
                @csrf
                <!-- Email -->
                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" name="email" id="email" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" value="{{ old('email') }}" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Password -->
                <div class="relative">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                    <input type="password" name="password" id="password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                    <button type="button" id="toggle-password" class="absolute right-3 top-12 text-gray-600 hover:text-blue-600 focus:outline-none">
                        <i class="fas fa-eye"></i>
                    </button>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-md font-semibold hover:bg-blue-700 transition duration-300">Log In</button>
            </form>
            <p class="text-center text-gray-600 mt-6">Don’t have an account? <a href="/user/signup" class="text-blue-600 hover:underline">Sign Up</a></p>
        </div>
    </div>
</section>

<script>
    // Toggle Password Visibility
    document.getElementById('toggle-password').addEventListener('click', function () {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
@endsection