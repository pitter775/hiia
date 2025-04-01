<!DOCTYPE html>
<html lang="en" id="htmlscroll" style="overflow: none">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    {{-- <title>Chat - Hiia</title> --}}
    <link rel="stylesheet" href="/css/chatdefault.css">
</head>
<style>

</style>

<body style="overflow: none; font-family: 'Archivo', sans-serif; margin: 0; padding: 0; background-color: #f9f9f9;">
    
<div id="hiia-chat-container">
    <div id="hiia-chat-messages"></div>
    <form id="hiia-chat-form" class="hiia-chat-input">
        <input type="hidden" id="hiia-chat-id" value="{{ $modelo->id }}">
        <input type="text" id="hiia-message-input" placeholder="Digite sua mensagem">
    </form>
</div>

<script>
    const htmlElement = document.getElementById('hiia-chat-messages');

    function scrollToBottom() {
        htmlElement.scrollTo({ top: htmlElement.scrollHeight, behavior: 'smooth' });
    }
    const chatId = document.getElementById('hiia-chat-id').value;
    const messagesContainer = document.getElementById('hiia-chat-messages');
    const chatForm = document.getElementById('hiia-chat-form');

    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const messageInput = document.getElementById('hiia-message-input');
        const message = messageInput.value;

        // Add user message
        const userMessageDiv = document.createElement('div');
        userMessageDiv.className = 'hiia-message user';
        userMessageDiv.innerHTML = `<div class="hiia-message-text">${message}</div>`;
        messagesContainer.appendChild(userMessageDiv);
        scrollToBottom();

        // Send to backend
        const response = await fetch('/api/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ chat_id: chatId, message }),
        });

        if (!response.ok) {
            console.error('Erro na solicitação:', response.status);
            return;
        }

        const data = await response.json();

        // Add bot response
        const botMessageDiv = document.createElement('div');
        botMessageDiv.className = 'hiia-message bot';
        botMessageDiv.innerHTML = `<div class="hiia-message-text">${data.response}</div>`;
        messagesContainer.appendChild(botMessageDiv);

        // Clear input and scroll to bottom
        messageInput.value = '';
        scrollToBottom();
    });
</script>
</body>
</html>
