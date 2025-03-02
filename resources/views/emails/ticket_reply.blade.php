<!DOCTYPE html>
<html>
<head>
    <title>Reply for Support Ticket</title>
</head>
<body>
    <p>Dear {{ $ticket->customer_name }},</p>    
    <p><strong>Reference No:</strong> {{ $ticket->reference_no }}</p>
    <p><strong>Issue Description:</strong> {{ $ticket->problem_description }}</p>
    <p><strong>Reply :</strong> {{ $reply->message }}</p>
</body>
</html>
