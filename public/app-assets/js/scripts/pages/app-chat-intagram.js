$(function () {
   'use strict';

    var formAtendente = $('#formAtendente');

    // Verificar se já existe um rascunho ao carregar a página
    function verificarRascunho() {
        $.ajax({
            type: "GET",
            url: '/admin/modelos/rascunho',
            success: function (response) {
                // console.log(response);
                
                atualizarCard(response);
                gerenciarBotoes(response.nome !== 'não criado');
    
                // Preenche o formulário se houver rascunho
                if (response.nome !== 'não criado') {
                    preencherFormulario(response.nome, response.descricao);
                }
            },
            error: function () {
                toastr.error('Erro ao verificar o rascunho.');
            }
        });
    } 

    // Atualizar o card com as informações do modelo
    function atualizarCard(response) {
        $('#nomeModelo').text(response.nome || 'não criado');
        $('#estadoModelo').text(response.estado || '--');
        $('#dataRascunho').text(formatarData(response.updated_at) || '--');
        $('#dataAtivo').text(formatarData(response.activated_at) || '--');
        // Atualiza a imagem do avatar
        if (response.imagem) {
            $('#imagemAvatar').attr('src', '/storage/' + response.imagem);
        }
    }

    // Função para formatar data no formato brasileiro
    function formatarData(dataISO) {
        if (!dataISO) return null;

        const data = new Date(dataISO);
        const dia = String(data.getDate()).padStart(2, '0');
        const mes = String(data.getMonth() + 1).padStart(2, '0'); // Mês começa em 0
        const ano = data.getFullYear();
        const horas = String(data.getHours()).padStart(2, '0');
        const minutos = String(data.getMinutes()).padStart(2, '0');

        return `${dia}/${mes}/${ano} às ${horas}:${minutos}`;
    }

    // Gerenciar estado dos botões com base na existência de um modelo
    function gerenciarBotoes(modeloCriado) {
        if (modeloCriado) {
            $('#ativarAtendente').prop('disabled', false);
        } else {
            $('#ativarAtendente').prop('disabled', true);
        }
    }

    function preencherFormulario(nome, descricao) {
        $('#nome').val(nome || ''); // Preenche o campo "Nome"
        $('#descricao').val(descricao || ''); // Preenche o campo "Descrição"
    }

    function verificarEstadoAtendente() {
        $.ajax({
            type: "GET",
            url: '/admin/modelos/rascunho',
            success: function (response) {
                // Verificar se o atendente está ativado
                if (response.estado === 'Ativo' || response.activated_at) {
                    $('#ativarAtendente').text('Atualizar Atendente'); // Atualiza o botão
                    atualizarCodigo(response.token); // Exibe o card de código com o token
                } else {
                    $('#ativarAtendente').text('Ativar Atendente'); // Reseta o texto do botão
                    $('.cardcodigo').hide(); // Oculta o card de código
                }
            },
            error: function () {
                toastr.error('Erro ao verificar o estado do atendente.');
            }
        });
    }

    // Atualiza o código no card e exibe
    function atualizarCodigo(token) {
        const baseUrl = 'http://127.0.0.1:8000/js/chat-widget.js'; // Base URL do script
    
        const codigo = `
        <script>
        // Atendente inteligente fornecido por Hiia
        (function () {
            const script = document.createElement('script');
            script.src = \`${baseUrl}?token=${token}\`; 
            script.async = true;
            document.head.appendChild(script);
        })();
        </script>`;
        
        $('#codigoProjeto').text(codigo);
        Prism.highlightElement(document.getElementById('codigoProjeto')); // Reaplica o syntax highlighting
        $('.cardcodigo').show();
    }  



    $('#btnVincularInstagram').on('click', function (e) {
        e.preventDefault();
      
        const modelo_id = $('#modelo_id').val();
        const ig_business_id = $('#ig_business_id').val();
        const token = $('#token').val();
        const nome_conta = $('#nome_conta').val();
      
        $.ajax({
          url: '/admin/instagram/conectar',
          method: 'POST',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            modelo_id,
            ig_business_id,
            token,
            nome_conta
          },
          success: function (response) {
            $('#instagram-vincular-alerta').html(`
              <div class="alert alert-success">Conta vinculada com sucesso!</div>
            `);
            // Limpar os campos se quiser
            $('#ig_business_id, #token, #nome_conta').val('');
          },
          error: function (xhr) {
            let mensagem = 'Erro ao tentar vincular.';
      
            if (xhr.responseJSON && xhr.responseJSON.message) {
              mensagem = xhr.responseJSON.message;
            }
      
            $('#instagram-vincular-alerta').html(`
              <div class="alert alert-danger">${mensagem}</div>
            `);
          }
        });
      });
      

    // Botão para copiar código
    $('#copiarCodigo').on('click', function () {
        const codigo = $('#codigoProjeto').text();
        navigator.clipboard.writeText(codigo).then(function () {
            toastr.success('Código copiado para a área de transferência!');
        }).catch(function () {
            toastr.error('Erro ao copiar o código.');
        });
    });

   $('#ativarAtendente').on('click', function () {
      $.ajax({
            type: "POST",
            url: '/admin/modelos/ativar',
            contentType: "application/json",
            success: function (response) {
               toastr.success('Atendente ativado com sucesso!');
               verificarRascunho();
               verificarEstadoAtendente(); // Passa o token para exibir no card
            },
            error: function () {
               toastr.error('Erro ao ativar o atendente.');
            }
      });
   });

   // Chamando a função ao carregar a página
   verificarRascunho();
   carregarDominios();
   verificarEstadoAtendente();

   // Botão "Salvar Rascunho"
    $('#salvaRascunho').on('click', function () {
        let formData = formAtendente.serializeArray();
        let dadosExtras = coletarDadosExtras();
        dadosExtras.estado = "Rascunho";
    
        let data = converterParaJSON(formData, dadosExtras);
    
        // Verificar se o modelo já existe
        if ($('#nomeModelo').text() !== 'não criado') {
            // Atualizar modelo existente
            $.ajax({
                type: "PUT", // ou POST, dependendo do backend
                url: '/admin/modelos/rascunho', // Atualize conforme necessário
                data: JSON.stringify(data),
                contentType: "application/json",
                success: function (response) {
                    toastr.success('Rascunho atualizado com sucesso!');
                    verificarRascunho();
                    verificarEstadoAtendente();
                },
                error: function (e) {
                    let mensagemAmigavel = interpretarErro(e);
                    toastr.error(mensagemAmigavel);
                }
            });
        } else {
            // Criar novo modelo
            $.ajax({
                type: "POST",
                url: '/admin/modelos/rascunho',
                data: JSON.stringify(data),
                contentType: "application/json",
                success: function (response) {
                    toastr.success('Rascunho salvo com sucesso!');
                    verificarRascunho();
                    atualizarCodigo(response.token);
                },
                error: function (e) {
                    let mensagemAmigavel = interpretarErro(e);
                    toastr.error(mensagemAmigavel);
                }
            });
        }
    });  

   // Função para coletar os dados fixos de "dados"
    function coletarDadosExtras() {
        return {
            estado: "Rascunho", // ou "finalizado"
            modelo_utilizado: "gpt-3-turbo",
            prompt_inicial: "Criado pela Hiia"
        };
    }

    // Função para converter os dados do formulário em JSON
    function converterParaJSON(formArray, dadosExtras) {
        let dataObj = {};

        formArray.forEach(function (item) {
            dataObj[item.name] = item.value;
        });

        dataObj.dados = dadosExtras;

        return dataObj;
    }

    function interpretarErro(erro) {
        if (erro.status === 422) {
            return 'Erro de validação: Verifique os dados enviados.';
        }
        return 'Ocorreu um erro ao processar a solicitação.';
    }

    function carregarDominios() {
        $.ajax({
                type: "GET",
                url: '/admin/modelos/dominios',
                success: function (response) {
                console.log('Domínios permitidos:', response.allowed_domains);
                const domainList = $('#domainList');
                domainList.empty(); // Limpa a lista atual

                // Verifica se allowed_domains existe e é um array
                if (Array.isArray(response.allowed_domains)) {
                    response.allowed_domains.forEach(function (domain) {
                            let listItem = $('<li>').addClass('list-group-item d-flex justify-content-between align-items-center');
                            listItem.text(domain);

                            let removeButton = $('<button>').addClass('btn btn-sm btn-danger').text('x');
                            removeButton.on('click', function () {
                            removerDominio(domain, listItem); // Passe o valor correto do domínio
                            });

                            listItem.append(removeButton);
                            domainList.append(listItem);
                    });
                } else {
                    console.warn('Nenhum domínio encontrado ou formato inválido.');
                }
                },
                error: function () {
                toastr.error('Erro ao carregar os domínios.');
                }
        });
    }



    document.getElementById('addDomain').addEventListener('click', function () {
        let domainInput = document.getElementById('dominio');
        let domainValue = domainInput.value.trim();

        // Remove "http://", "https://" e a barra final "/"
        domainValue = domainValue.replace(/^(https?:\/\/)/, '').replace(/\/$/, '');

        console.log(domainValue);
        

        if (domainValue) {
            // Enviar domínio ao backend
            $.ajax({
                type: "POST",
                url: '/admin/modelos/dominios/adicionar',
                data: JSON.stringify({ domain: domainValue }),
                contentType: "application/json",
                success: function () {
                    // Criar item na interface somente após confirmação do backend
                    let listItem = document.createElement('li');
                    listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                    listItem.innerText = domainValue;

                    let removeButton = document.createElement('button');
                    removeButton.className = 'btn btn-sm btn-danger';
                    removeButton.innerText = 'x';

                    // Remoção ao clicar no botão
                    removeButton.addEventListener('click', function () {
                        console.log('remover');
                        
                        removerDominio(domainValue, listItem);
                    });

                    listItem.appendChild(removeButton);
                    document.getElementById('domainList').appendChild(listItem);

                    // Limpar o campo de entrada
                    domainInput.value = '';
                    toastr.success('Domínio adicionado com sucesso!');
                },
                error: function () {
                    toastr.error('Erro ao adicionar o domínio.');
                }
            });
        }
    });

    function removerDominio(domainValue, listItem) {
        console.log('entrou aqui',listItem, domainValue);
        
        // Normaliza o domínio (remove https:// e barras finais)
        domainValue = domainValue.replace(/^(https?:\/\/)/, '').replace(/\/$/, '');

        $.ajax({
                type: "POST",
                url: '/admin/modelos/dominios/remover',
                data: JSON.stringify({ domain: domainValue }),
                contentType: "application/json",
                success: function () {
                listItem.remove(); // Remover o item da interface
                toastr.success('Domínio removido com sucesso!');
                },
                error: function () {
                toastr.error('Erro ao remover o domínio.');
                }
        });
    } 

    let cropper;

    // // Clique no avatar para abrir o seletor de arquivo
    // $('#imagemAvatar').on('click', function () {
    //     $('#imagemAtendente').click();
    // });
    
    // Evento de mudança no input de arquivo
    $('#imagemAtendente').on('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                $('#previewImagem').attr('src', event.target.result);
                $('#modalCorteImagem').modal('show');
    
                if (cropper) {
                    cropper.destroy();
                }
    
                cropper = new Cropper(document.getElementById('previewImagem'), {
                    aspectRatio: 1, // Quadrado perfeito
                    viewMode: .5,
                    autoCropArea: .5,
                });
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Evento de clique no botão "Confirmar"
    $('#confirmarCorte').on('click', function () {
        if (!cropper) {
            alert('Selecione e ajuste a imagem antes de confirmar.');
            return;
        }
    
        const canvas = cropper.getCroppedCanvas({
            width: 200,
            height: 200,
        });
    
        canvas.toBlob(function (blob) {
            const formData = new FormData();
            formData.append('imagem', blob, 'imagem_cortada.jpg');
            formData.append('_token', $('meta[name="csrf-token"]').attr('content')); // Token CSRF
    
            fetch('/admin/modelos/upload-imagem', {
                method: 'POST',
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert('Imagem enviada com sucesso!');
                        $('#imagemAvatar').attr('src', data.caminho); // Atualiza o avatar
                        $('#imagemFinal').val(data.caminho);
                        $('#modalCorteImagem').modal('hide');
                    } else {
                        alert('Erro ao enviar a imagem.');
                    }
                })
                .catch((error) => {
                    console.error('Erro no upload:', error);
                    alert('Erro no upload da imagem.');
                });
        }, 'image/jpeg');
    });
    
    

});
