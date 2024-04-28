<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        p {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hello {{ $mailData['employer']->name }},</h1>
        <p>A job application has been submitted for the following position:</p>
        <hr>
        <h2>{{ $mailData['job']->title }}</h2>
        <p><strong>Employee Details:</strong></p>
        <ul>
            <li><strong>Name:</strong> {{ $mailData['user']->name }}</li>
            <li><strong>Email:</strong> {{ $mailData['user']->email }}</li>
            <li><strong>Mobile No:</strong> {{ $mailData['user']->mobile }}</li>
        </ul>
        <p>You can download the resume by clicking the link below:</p>
        <a href="{{ asset('Resumes/' . urlencode($mailData['user']->resume)) }}" download="{{ Auth::user()->name }}_resume.pdf">Download Resume</a>

        <p>Thank you.</p>
    </div>
</body>
</html>
