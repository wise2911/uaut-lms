@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Forgot Password</h2>
    <form action="/user/forgot-password" method="POST">
        @csrf
        <label for="email">Enter your email</label>
        <input type="email" name="email" class="w-full border p-2 rounded" required>
        <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded mt-4">Send OTP</button>
    </form>
</div>
@endsection
