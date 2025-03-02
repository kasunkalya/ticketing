<!DOCTYPE html>
<html>
<head>
    <title>Support Ticket Confirmation</title>
</head>
<body>
    <p>Dear {{ $ticket->customer_name }},</p>
    <p>Your support ticket has been created successfully.</p>
    <p><strong>Reference No:</strong> {{ $ticket->reference_no }}</p>
    <p><strong>Issue Description:</strong> {{ $ticket->problem_description }}</p>
    <p>We will get back to you shortly.</p>
</body>
</html>
