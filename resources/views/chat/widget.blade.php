<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Hiia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        #chat-box {
            width: 100%;
            height: 100vh;
            border: none;
        }
    </style>
</head>
<body>
    <iframe id="chat-box" src="/api/chat/start?token={{ request('token') }}"></iframe>
</body>
</html>