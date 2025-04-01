(function () {
    // Função para extrair parâmetros do src do script atual
    function getScriptQueryParam(param) {
        const currentScript = document.currentScript || Array.from(document.getElementsByTagName('script')).pop();
        const src = currentScript.src;
        const params = new URLSearchParams(src.split('?')[1]);
        return params.get(param);
    }

    // Extrair o token do próprio script
    const token = getScriptQueryParam('token');
    if (!token) {
        console.error('Token não fornecido na URL do script.');
        return;
    }

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
        z-index: 9999; /* Garantir que o widget fique na frente de outros elementos */
    `;

    const chatHeader = document.createElement('div');
    chatHeader.style.cssText = `
        background-color: #007bff;
        color: white;
        padding: 10px;
        font-size: 16px;
        text-align: center;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    `;

    const headerTitle = document.createElement('span');
    headerTitle.textContent = 'Hiia Chat Inteligente';

    const closeButton = document.createElement('button');
    closeButton.textContent = '✖';
    closeButton.style.cssText = `
        background: none;
        border: none;
        color: white;
        font-size: 16px;
        cursor: pointer;
    `;
    closeButton.onclick = function () {
        widgetContainer.style.display = 'none';
    };

    chatHeader.appendChild(headerTitle);
    chatHeader.appendChild(closeButton);

    const chatContent = document.createElement('iframe');
    chatContent.src = `/chat?token=${token}`;
    chatContent.style.cssText = 'width: 100%; height: calc(100% - 40px); border: none;';

    chatContent.onload = function () {
        try {
            const iframeDoc = chatContent.contentDocument || chatContent.contentWindow.document;
            if (iframeDoc.body && iframeDoc.body.innerText.trim().startsWith('{')) {
                console.warn('O endpoint /chat está retornando JSON ao invés de HTML. Verifique o backend.');

                // Adicionar fallback amigável
                const fallbackMessage = document.createElement('div');
                fallbackMessage.style.cssText = `
                    text-align: center;
                    padding: 20px;
                    font-family: Arial, sans-serif;
                    font-size: 14px;
                    color: #333;
                `;
                fallbackMessage.innerHTML = 'A interface do chat ainda não está configurada. Por favor, entre em contato com o suporte.';

                widgetContainer.appendChild(fallbackMessage);
            }
        } catch (error) {
            console.error('Erro ao verificar o conteúdo do iframe:', error);
        }
    };

    widgetContainer.appendChild(chatHeader);
    widgetContainer.appendChild(chatContent);

    // Adicionar campo de entrada para mensagens
    const chatInput = document.createElement('textarea');
    chatInput.style.cssText = `
        width: 100%;
        height: 50px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-top: 5px;
        padding: 5px;
    `;
    chatInput.placeholder = 'Digite sua mensagem aqui...';

    widgetContainer.appendChild(chatInput);
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
