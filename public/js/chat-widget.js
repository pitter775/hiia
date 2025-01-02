// chat-widget.js

(function () {
        const widgetContainer = document.createElement('div');
        widgetContainer.id = 'hiia-chat-widget';
        widgetContainer.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            height: 400px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: none;
        `;
    
        const chatHeader = document.createElement('div');
        chatHeader.style.cssText = `
            background-color: #007bff;
            color: white;
            padding: 10px;
            font-size: 16px;
            text-align: center;
            cursor: pointer;
        `;
        chatHeader.textContent = 'Hiia Chat Inteligente';
        chatHeader.onclick = function () {
            widgetContainer.style.display = widgetContainer.style.display === 'none' ? 'block' : 'none';
        };
    
        const chatContent = document.createElement('iframe');
        chatContent.src = `/chat?token=${window.hiiaToken}`;
        chatContent.style.cssText = 'width: 100%; height: calc(100% - 40px); border: none;';
    
        widgetContainer.appendChild(chatHeader);
        widgetContainer.appendChild(chatContent);
        document.body.appendChild(widgetContainer);
    
        const chatIcon = document.createElement('div');
        chatIcon.id = 'hiia-chat-icon';
        chatIcon.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background-color: #007bff;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        `;
        chatIcon.onclick = function () {
            widgetContainer.style.display = 'block';
        };
    
        document.body.appendChild(chatIcon);
    })();
    