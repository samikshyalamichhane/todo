<!DOCTYPE html>
<html>
<head>
    <title>Todo Reminder</title>
</head>
<body>
    <h2>Reminder: Todo Due Tomorrow</h2>
    <p>Hi {{ $todo->user->name }},</p>
    <p>This is a reminder that you have a todo due tomorrow:</p>
    <p>Title: {{ $todo->title }}</p>
    <p>Description: {{ $todo->description }}</p>
    <p>Due Date: {{ $todo->due_date }}</p>
    <p>Thank you!</p>
</body>
</html>
