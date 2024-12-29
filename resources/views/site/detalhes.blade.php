@extends('layouts.site-interna', [
    'elementActive' => 'detalhes'
])

@section('content')

    <style>
      hr{ margin: 40px 0 40px 0}

      #descricao-curta,
        #descricao-completa {
            transition: all 0.3s ease-in-out;
        }

      .d-flex.flex-wrap .me-4 {
          margin-bottom: 10px;
      }
      .d-flex.align-items-center {
          font-size: 16px;
      }
        .img-thumbnail {
            max-height: 120px; /* Tamanho da imagem */
            object-fit: cover; /* Ajusta a imagem sem distorcer */
            border-radius: 12px; /* Define o arredondamento das bordas */
            border: none; /* Remove bordas extras */
            padding: 2px; /* Reduz o espaçamento interno */
        }

        .row.g-1 {
            gap: 5px; /* Espaço mínimo entre as imagens */
        }

        .carousel-inner img {
            max-height: 450px; /* Ajusta a altura da imagem principal */
            object-fit: cover; /* Garante que a imagem se adapte sem distorção */
        }



        .col-4.d-flex.flex-column > div {
            flex: 1; /* Garante que as imagens menores tenham a mesma altura */
            text-align: center; /* Centraliza as imagens menores */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .mb-2 img {
            border: 2px solid #ddd; /* Adiciona borda ao redor das imagens menores */
            transition: transform 0.3s ease, border-color 0.3s ease; /* Efeito suave ao passar o mouse */
        }

        .mb-2 img:hover {
            transform: scale(1.05); /* Aumenta levemente a imagem ao passar o mouse */
            border-color: #007bff; /* Muda a cor da borda ao passar o mouse */
        }

        .col-6 {
            padding: 5px; /* Adiciona espaço entre as colunas */
        }


        @media (max-width: 768px) {
            .carousel-inner img {
                max-height: 250px; /* Reduz a altura da imagem principal em telas menores */
            }

            .img-thumbnail {
                max-height: 70px; /* Reduz o tamanho das imagens menores */
            }

            .col-4.d-flex.flex-column {
                flex-direction: row; /* Alinha as imagens menores em uma linha */
                flex-wrap: wrap; /* Permite quebra de linha */
            }

            .col-4.d-flex.flex-column > div {
                width: 48%; /* Ajusta o tamanho das imagens menores */
                margin-bottom: 5px;
            }
            .carousel-inner img {
                border-radius: 8px; /* Também arredonda as bordas da imagem principal, caso necessário */
            }
            h3{ font-size: 16px !important}
        }

    </style>

    <main id="main" style="margin-top: 70px">

      <section class="sala-detalhes pb-0">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <h2>{{ $sala->nome }}</h2>
            </div>
        


            <div class="col-lg-12 mb-5">
                @if($sala->imagens->isNotEmpty())
                    <div class="row">
                        <!-- Imagem principal do carrossel -->
                        <div class="col-lg-8 col-md-12">
                            <div id="carouselSalaDetalhes" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($sala->imagens as $index => $imagem)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $imagem->path) }}" class="d-block w-100 rounded" alt="{{ $sala->nome }}">
                                        </div>
                                    @endforeach
                                </div>
                                <a class="carousel-control-prev" href="#carouselSalaDetalhes" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Anterior</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselSalaDetalhes" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Próximo</span>
                                </a>
                            </div>
                        </div>

                        <!-- Imagens menores na lateral -->
                        <div class="col-lg-4">
                            <div class="row"> <!-- Reduz o espaçamento entre imagens -->
                                @foreach($sala->imagens->take(6) as $imagem)
                                    <div class="col-6 mb-2">
                                        <img src="{{ asset('storage/' . $imagem->path) }}" class="img-fluid img-thumbnail" alt="Imagem da sala" style="cursor: pointer;" onclick="trocarImagemPrincipal('{{ asset('storage/' . $imagem->path) }}')">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                @endif
            </div>

          </div>
        </div>
        <div style="background: #fff">
            <div class="container">
              <div class="row">

                <!-- Nome e Descrição da Sala -->
                <div class="col-lg-8">

                  <h3 class="mt-4">Sobre {{ $sala->nome }}</h3>
                    <div class="quill-content">
                        <!-- Conteúdo completo inicialmente escondido -->
                        <div id="descricao-completa" style="display: none;">
                            {!! $sala->descricao !!}
                        </div>

                        <!-- Botão para alternar -->
                        <button id="toggle-descricao" class="btn btn-primary btn-sm mt-2">Ver descrição completa</button>
                    </div>


                    <hr>
                    <h3 class="mb-2">Conveniências</h3>
                    <div class="d-flex flex-wrap mt-1 mb-5">
                        @forelse($sala->conveniencias as $conveniencia)
                            <div class="d-flex align-items-center me-4 mr-4  mt-1 ">
                                <i class="{{ $conveniencia->icone }} me-2 pr-1" style="font-size: 24px;"></i>
                                <span>{{ $conveniencia->nome }}</span>
                            </div>
                        @empty
                            <p>Sem conveniências cadastradas para esta sala.</p>
                        @endforelse
                    </div>



                </div>

                <div class="col-lg-4">
       
                  <div class="card" style="margin-top: -40px">
                    <div class="row">
                      <div class="col-12 p-3">

                          
                          <p ><span style="font-size:30px">R$ {{ number_format($sala->valor, 2, ',', '.') }}</span> por hora</p>

                          <p> <strong>Endereço:</strong> {{ $sala->endereco->rua }}, {{ $sala->endereco->numero }}, {{ $sala->endereco->bairro }} - {{ $sala->endereco->cidade }}, {{ $sala->endereco->estado }}</p>

                            <iframe
                                width="100%"
                                height="300"
                                style="border:0"
                                loading="lazy"
                                allowfullscreen
                                src="https://www.google.com/maps?q={{ urlencode($sala->endereco->rua . ', ' . $sala->endereco->numero . ', ' . $sala->endereco->bairro . ', ' . $sala->endereco->cidade . ', ' . $sala->endereco->estado) }}&output=embed">
                            </iframe>

                          <div class="row mt-3">
                            <div class="col-lg-12 text-center">
                              <p id="selected-date">Selecione uma data no calendário.</p>
                              @if(auth()->check())
                                <div id="calendar"></div>
                              @else
                                <p>Para ver a disponibilidade e fazer reservas, faça login.</p>
                                <a href="{{ route('login.google') }}" class="btn btn-primary">Login com Google</a>
                                <a href="{{ route('completar.cadastro.form') }}" class="btn btn-secondary">Cadastro Manual</a>
                              @endif
                            </div>
                          </div>
                      </div>
                    </div>
                  
                  </div>

                  <div class="row">
                      <div class="col-12 text-center mb-2">
                          <p class="text-success mb-2">
                              <i class="fas fa-lock"></i> Este é um ambiente seguro!
                          </p>
                          <p>
                              Trabalhamos constantemente para proteger sua segurança e privacidade. 
                              <a href="{{ route('privacidade') }}" class="text-primary">Saiba mais</a>
                          </p>
                      </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </section>

      <!-- Modal para seleção de horários -->
      <div class="modal fade" id="modalHorarios" tabindex="-1" role="dialog" aria-labelledby="modalHorariosLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalHorariosLabel">Selecione os horários</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p><strong>Sala:</strong> <span id="modalSalaNome">{{ $sala->nome }}</span></p>
              <p> <span class="mr-2"> <strong>Data:</strong> <span id="modalDataReserva"></span> </span> 
                    <strong>Valor por hora:</strong> R$ <span id="valorHora">{{ number_format($sala->valor, 2, ',', '.') }}</span>
              </p>
        
              
              <div id="horarios-disponiveis">
                <!-- Horários serão carregados aqui via AJAX -->
              </div>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <p class="mb-0"><strong>Total:</strong> R$ <span id="valorTotal">0,00</span></p>
                <div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="confirmarReserva()">Reservar</button>
                </div>
            </div>

          </div>
        </div>
      </div>
    </main>

@endsection



@push('js_page')

  <script>
      @if(auth()->check())
        let horariosSelecionados = []; // Array para armazenar os horários selecionados
        const valorPorHora = {{ $sala->valor }}; // Valor por hora da sala

        document.addEventListener('DOMContentLoaded', function () {
          var calendarEl = document.getElementById('calendar');
          var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            locale: 'pt-br',
            validRange: {
              start: new Date().toISOString().split('T')[0] // Bloqueia datas passadas
            },
            select: function(info) {
              var selectedDate = info.startStr;
              mostrarModalHorarios(selectedDate);
            }
          });
          calendar.render();
        });

        function mostrarModalHorarios(data_reserva) {
          horariosSelecionados = []; // Reseta a seleção de horários quando abre a modal
          $('#modalHorarios').modal('show');
          document.getElementById('modalDataReserva').innerText = data_reserva; // Mostra a data selecionada

          fetch(`/horarios-disponiveis/{{ $sala->id }}/${data_reserva}`)
            .then(response => response.json())
            .then(data => {
              let horariosDisponiveisContainer = document.getElementById('horarios-disponiveis');
              horariosDisponiveisContainer.innerHTML = '';
              data.horarios.forEach(horario => {
                horariosDisponiveisContainer.innerHTML += `
                  <button class="btn btn-secondary horario-btn" onclick="selecionarHorario('${data_reserva}', '${horario.inicio}', '${horario.fim}', this)">
                    ${horario.inicio} - ${horario.fim}
                  </button>
                `;
              });
              atualizarValorTotal(); // Reseta o valor total ao abrir a modal
            })
            .catch(error => console.error('Erro:', error));
        }

        function selecionarHorario(data_reserva, hora_inicio, hora_fim, button) {
          const horario = { data_reserva, hora_inicio, hora_fim };
          
          // Verifica se o horário já está selecionado
          const index = horariosSelecionados.findIndex(h => h.hora_inicio === hora_inicio && h.hora_fim === hora_fim);
          
          if (index === -1) {
            // Se não estiver, adiciona à lista e marca o botão
            horariosSelecionados.push(horario);
            button.classList.add('btn-success');
            button.classList.remove('btn-secondary');
          } else {
            // Se já estiver, remove da lista e desmarca o botão
            horariosSelecionados.splice(index, 1);
            button.classList.add('btn-secondary');
            button.classList.remove('btn-success');
          }

          atualizarValorTotal(); // Atualiza o valor total sempre que um horário é selecionado ou desmarcado
        }

        function atualizarValorTotal() {
          const total = horariosSelecionados.length * valorPorHora;
          document.getElementById('valorTotal').innerText = total.toFixed(2).replace('.', ',');
        }

        function confirmarReserva() {
            if (horariosSelecionados.length === 0) {
                toastr.warning('Por favor, selecione pelo menos um horário.');
                return;
            }

            fetch('/reserva/revisao', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    sala_id: {{ $sala->id }},
                    horarios: horariosSelecionados
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta do servidor');
                }
                return response.json(); // Certifique-se de que a resposta é JSON
            })
            .then(data => {
                if (data.redirect) {
                    window.location.href = data.redirect; // Redireciona para a página de revisão
                } else {
                    toastr.error('Erro ao continuar.');
                }
            })
            .catch(error => {
                console.error('Erro no fetch:', error);
                toastr.error('Erro inesperado ao continuar. Tente novamente mais tarde.');
            });


        }
      @endif

      function trocarImagemPrincipal(novaImagem) {
          const carrosselAtivo = document.querySelector('#carouselSalaDetalhes .carousel-item.active img');
          if (carrosselAtivo) {
              carrosselAtivo.src = novaImagem;
          }
      }
      window.trocarImagemPrincipal = trocarImagemPrincipal;

      //alternar entre a descrição curta e completa
      document.addEventListener('DOMContentLoaded', function () {
              const descricaoCompleta = document.getElementById('descricao-completa');
              const toggleButton = document.getElementById('toggle-descricao');

              // Define o limite de caracteres para exibição inicial
              const limiteCaracteres = 890; // Ajuste conforme necessário

              // Clona o conteúdo completo e cria a versão curta
              const descricaoOriginal = descricaoCompleta.innerHTML;
              const descricaoCurta = descricaoOriginal.substring(0, limiteCaracteres) + '...';

              // Exibe a descrição curta inicialmente
              let descricaoVisivel = false;
              descricaoCompleta.innerHTML = descricaoCurta;
              descricaoCompleta.style.display = 'block';

              // Alterna entre curta e completa
              toggleButton.addEventListener('click', function () {
                  if (descricaoVisivel) {
                      descricaoCompleta.innerHTML = descricaoCurta;
                      toggleButton.textContent = 'Ver descrição completa';
                  } else {
                      descricaoCompleta.innerHTML = descricaoOriginal;
                      toggleButton.textContent = 'Ver descrição reduzida';
                  }
                  descricaoVisivel = !descricaoVisivel;
              });
          });
  </script>
@endpush