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

   $('#ativarAtendente').on('click', function () {
      $.ajax({
            type: "POST",
            url: '/admin/modelos/ativar',
            contentType: "application/json",
            success: function (response) {
               toastr.success('Atendente ativado com sucesso!');
               verificarRascunho();
               gerenciarBotoes(false); // Desativa botões após ativação
            },
            error: function () {
               toastr.error('Erro ao ativar o atendente.');
            }
      });
   });

   // Chamando a função ao carregar a página
   verificarRascunho();
   carregarDominios();

   // Botão "Salvar Rascunho"
   $('#salvaRascunho').on('click', function () {
      let formData = formAtendente.serializeArray();
      let dadosExtras = coletarDadosExtras();
      dadosExtras.estado = "Rascunho";

      let data = converterParaJSON(formData, dadosExtras);

      $.ajax({
          type: "POST",
          url: '/admin/modelos/rascunho',
          data: JSON.stringify(data),
          contentType: "application/json",
          success: function () {
              toastr.success('Rascunho salvo com sucesso!');
              verificarRascunho();
              gerenciarBotoes(true);
          },
          error: function (e) {
              let mensagemAmigavel = interpretarErro(e);
              toastr.error(mensagemAmigavel);
          }
      });
  });



   // $('#salvarDominios').on('click', function () {
   //    let allowedDomains = Array.from($('#domainList li')).map(li => li.innerText);

   //    $.ajax({
   //          type: "POST",
   //          url: '/admin/modelos/dominios',
   //          data: JSON.stringify({ allowed_domains: allowedDomains }),
   //          contentType: "application/json",
   //          success: function () {
   //             toastr.success('Domínios salvos com sucesso!');
   //          },
   //          error: function () {
   //             toastr.error('Erro ao salvar os domínios.');
   //          }
   //    });
   // });
  

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
  
  


});
