<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 50px; }
        .certificate { border: 5px solid #4f46e5; padding: 40px; max-width: 800px; margin: auto; }
        h1 { color: #1f2937; font-size: 36px; }
        h2 { color: #4f46e5; font-size: 24px; }
        p { font-size: 18px; color: #333; }
        .date { font-size: 16px; color: #666; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>Certificate of Completion</h1>
        <p>This certifies that</p>
        <h2>{{ $user->full_name }}</h2>
        <p>has successfully completed the course</p>
        <h2>{{ $course->title }}</h2>
        <p class="date">Awarded on {{ $date }}</p>
        <p>UAUT Learning Management System</p>
    </div>
</body>
</html>