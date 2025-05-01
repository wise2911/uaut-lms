<!DOCTYPE html>
     <html lang="en">
     <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>Admin Login | UAUT LMS</title>
         <link rel="shortcut icon" href="{{ url('img/uaut-logo.jpg') }}" type="image/x-icon">
         <script src="https://cdn.tailwindcss.com"></script>
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
         <script>
             tailwind.config = {
                 theme: {
                     extend: {
                         colors: {
                             primary: '#4f46e5',
                             dark: '#1f2937',
                             light: '#6b7280'
                         }
                     }
                 }
             }
         </script>
     </head>
     <body class="bg-gray-100 font-sans antialiased flex items-center justify-center min-h-screen">
         <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
             <div class="flex justify-center mb-6">
                 <img src="{{ url('img/uaut-logo.jpg') }}" alt="UAUT LMS" class="h-16 w-16">
             </div>
             <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Admin Login</h2>

             @if (session('success'))
                 <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg flex items-center">
                     <i class="fas fa-check-circle mr-2"></i>
                     {{ session('success') }}
                 </div>
             @endif

             @if ($errors->any())
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                     <ul class="list-disc pl-5">
                         @foreach ($errors->all() as $error)
                             <li>{{ $error }}</li>
                         @endforeach
                     </ul>
                 </div>
             @endif

             <form action="{{ route('admin.login.post') }}" method="POST">
                 @csrf
                 <div class="mb-6">
                     <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                     <div class="relative">
                         <i class="fas fa-envelope absolute left-3 top-3 text-gray-400"></i>
                         <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full pl-10 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                     </div>
                 </div>

                 <div class="mb-6">
                     <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                     <div class="relative">
                         <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                         <input type="password" name="password" id="password" class="w-full pl-10 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary" required>
                     </div>
                 </div>

                 <button type="submit" class="w-full bg-primary text-white px-4 py-3 rounded-lg hover:bg-primary-dark transition-colors flex items-center justify-center">
                     <i class="fas fa-sign-in-alt mr-2"></i>
                     Log In
                 </button>
             </form>

             <p class="text-center text-gray-600 mt-6">
                 Not an admin? <a href="{{ route('login') }}" class="text-primary hover:underline">Log in as user</a>
             </p>
         </div>
     </body>
     </html>