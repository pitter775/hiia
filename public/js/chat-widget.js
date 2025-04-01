(function () {



    
    // Função para extrair parâmetros do src do script atual
    function getScriptQueryParam(param) {
        const currentScript = document.currentScript || Array.from(document.getElementsByTagName('script')).pop();
        const src = currentScript.src;
        const params = new URLSearchParams(src.split('?')[1]);
        return params.get(param);
    }

    // Função para buscar dados do modelo
    function fetchModelData(token) {

        console.log('fetchModelData');
        
        
        return fetch(`/api/chat-model/${token}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro na requisição: ${response.status}`);
                }
              
                
                return response.json();
            })
            .catch(error => {
                console.error('Erro ao buscar dados do modelo:', error);
                return { nome: 'Hiia Chat Inteligente', logo: '' }; // Valores padrão em caso de erro
            });
    }

    // Extrair o token do próprio script
    const token = getScriptQueryParam('token');
    if (!token) {
        console.error('Token não fornecido na URL do script.');
        return;
    }

    // Incluir o CSS externo
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = '/css/chatdefault.css'; // Caminho relativo do CSS default
    document.head.appendChild(link);


    // Incluir a fonte externa
    const fontLink = document.createElement('link');
    fontLink.rel = 'stylesheet';
    fontLink.href = 'https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100..900;1,100..900&display=swap';
    document.head.appendChild(fontLink);

    // Criar elementos do widget
    const widgetContainer = document.createElement('div');
    widgetContainer.id = 'hiia-chat-widget';

    const chatHeader = document.createElement('div');
    chatHeader.className = 'chat-header';

    const headerLogo = document.createElement('img');
    headerLogo.style.cssText = ' ';
    headerLogo.className = 'hiia-avatar';
    const headerTitle = document.createElement('span');
    headerTitle.className = 'hiia-avatar-nome';

    const closeButton = document.createElement('button');
    closeButton.textContent = '✖';
    closeButton.onclick = function () {
        widgetContainer.style.display = 'none';
    };

    chatHeader.appendChild(headerLogo);
    chatHeader.appendChild(headerTitle);
    chatHeader.appendChild(closeButton);

    const chatContent = document.createElement('iframe');
    chatContent.src = `/chat?token=${token}`;

    chatContent.onload = function () {
        console.log('teste');
        
        try {
            const iframeDoc = chatContent.contentDocument || chatContent.contentWindow.document;
            if (iframeDoc.body && iframeDoc.body.innerText.trim().startsWith('{')) {
                console.warn('O endpoint /chat está retornando JSON ao invés de HTML. Verifique o backend.');

                // Adicionar fallback amigável
                const fallbackMessage = document.createElement('div');
                fallbackMessage.className = 'fallback-message';
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
    chatInput.className = 'chat-input';
    chatInput.placeholder = 'Digite sua mensagem aqui...';

    widgetContainer.appendChild(chatInput);
    document.body.appendChild(widgetContainer);

    const chatIcon = document.createElement('div');
    chatIcon.id = 'hiia-chat-icon';
    chatIcon.onclick = function () {
        widgetContainer.style.display = 'block';
    };


    // Abrir chat e esconder ícone
chatIcon.onclick = function () {
    widgetContainer.style.display = 'block';
    chatIcon.style.display = 'none';
};

// Fechar chat e mostrar ícone
closeButton.onclick = function () {
    widgetContainer.style.display = 'none';
    chatIcon.style.display = 'block';
};

let isDragging = false;
let offsetX, offsetY;



chatHeader.onmousedown = function (e) {
    isDragging = true;
    offsetX = e.clientX - widgetContainer.offsetLeft;
    offsetY = e.clientY - widgetContainer.offsetTop;
    document.onmousemove = onMouseMove;
    document.onmouseup = stopDragging;
};

function onMouseMove(e) {
    if (!isDragging) return;
    widgetContainer.style.left = `${e.clientX - offsetX}px`;
    widgetContainer.style.top = `${e.clientY - offsetY}px`;
    widgetContainer.style.position = 'fixed';
}

function stopDragging() {
    isDragging = false;
    document.onmousemove = null;
    document.onmouseup = null;
}


    document.body.appendChild(chatIcon);

    // Buscar dados do modelo e atualizar o cabeçalho
    fetchModelData(token).then(data => {
        headerTitle.textContent = data.nome;

        console.log(data.nome);
        console.log(data.logo);

        if (data.logo) {
            headerLogo.src = `${data.logo}`;
            
        } else {
            headerLogo.style.display = 'none';
        }
    });
})();
