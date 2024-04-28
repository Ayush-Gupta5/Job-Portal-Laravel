<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Job Application Received</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0;">
  <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center" style="padding: 20px 0;">
        <h1 style="margin: 0;">Job Application Received</h1>
      </td>
    </tr>
    <tr>
      <td style="padding: 20px 0;">
        <p>Dear {{ $EmployeeMailData['user']->name }},</p>
        <p>We are writing to inform you that we have received your job application for the position of <b>{{ $EmployeeMailData['job']->title }}</b>.</p>
        <p>Our hiring team will review your application carefully. If your qualifications match our requirements, we will contact you to schedule an interview.</p>
        <p>Thank you for your interest in joining our team.</p>
        <p>Sincerely,</p>
        <p><b>{{ $EmployeeMailData['job']->company_name }}</b></p>
      </td>
    </tr>
  </table>
</body>
</html>
