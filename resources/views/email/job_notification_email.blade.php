<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application Notification</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;">
    <div class="container" style="max-width: 600px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h1 style="color: #333;">Hello {{ $mailData['employer']->name }},</h1>
        <p style="color: #666;">A job application has been submitted for the following position:</p>
        <hr>
        <h2 style="color: #333;">{{ $mailData['job']->title }}</h2>
        <p style="color: #666;"><strong>Employee Details:</strong></p>
        <ul style="padding: 0;">
            <li><strong>Name:</strong> {{ $mailData['user']->name }}</li>
            <li><strong>Email:</strong> {{ $mailData['user']->email }}</li>
            <li><strong>Mobile No:</strong> {{ $mailData['user']->mobile }}</li>
        </ul>
        <p style="color: #666;">You can download the resume that attached with email</p>
        {{-- <a href="{{ asset('Resumes/' . urlencode($mailData['user']->resume)) }}" download="{{ $mailData['user']->name }}_resume.pdf" style="color: #007bff; text-decoration: none;">Download Resume</a> --}}

        <p style="color: #666;">Thank you.</p>
    </div>
</body>
</html>
