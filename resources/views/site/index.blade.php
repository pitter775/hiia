<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Hiia</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicons -->
  <link href="{{ asset('assets') }}/img/favicon.png" rel="icon">
  <link href="{{ asset('assets') }}/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets') }}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('assets') }}/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="{{ asset('assets') }}/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="{{ asset('assets') }}/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="{{ asset('assets') }}/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="{{ asset('assets') }}/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/extensions/toastr.min.css">
  <link href="{{ asset('assets') }}/css/style.css" rel="stylesheet">

  <script src="https://aframe.io/releases/1.2.0/aframe.min.js"></script>
  <style>
  

  </style>




         <script>
         // Atendente inteligente fornecido por Hiia
         (function () {
             const script = document.createElement('script');
             script.src = `https://www.hiia.com.br/js/chat-widget.js?token=9158a689-e644-4f2f-a22c-3c87be035ad4`; 
             script.async = true;
             document.head.appendChild(script);
         })();
         </script>

</head>
{{-- <style>

  @keyframes moveLeft {
    from {
      transform: translateX(100%);
    }
    to {
      transform: translateX(0);
    }
  }

  #meu-modelo {
    animation: moveLeft 3s ease-out;
  }

</style> --}}

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top header-transparent">
    <div class="container d-flex align-items-center hero-content">

      <div class="logo mr-auto" style="color: #fff">
       <img src="{{ asset('assets') }}/img/favicon.png" alt="" class="mr-2" style="float:left">  hiia
      </div>

      <nav class="nav-menu d-none d-lg-block">
        <ul>
          {{-- <li class="active"><a href="#hero">Home</a></li> --}}
          
          <li><a href="#sobre">Sobre Nós</a></li>
             <li>
              @if(auth()->check())
                  @if(auth()->user()->tipo_usuario === 'admin')
                      <a href="{{ route('admin.dashboard') }}">Gestão</a>
                  @else
                      <a href="{{ route('cliente.reservas') }}">Minhas Reservas</a>
                  @endif
              @else
                  <a href="{{ route('login') }}">Entre</a>
              @endif
          </li>

        </ul>
      </nav><!-- .nav-menu -->

    </div>
  </header><!-- End Header -->


  <main id="main">


<style>


    .about::before  { 
          width: 100% !important;
    height: 500px;

    padding: 0;
    margin: 0;

     }

  #about {
      width: 100% !important;
      height: 500px;
      background: rgba(2, 9, 19, 1) url('/assets/img/bg-inicio.png') no-repeat center bottom !important;
      background-size: cover; /* Faz a imagem cobrir todo o espaço */
      padding: 0;
      margin: 0;
    }
  .banneranimadopai {
    width: 100%;
    height: 500px;
    position: relative; /* Define o contêiner como referência para posicionamento absoluto */
    overflow: hidden; /* Garante que nada saia dos limites do contêiner */
  }

  .banneranimado {
    width: 100%;
    height: 500px;
    background:url('/assets/img/bg-foguete.png') no-repeat center bottom;
    background-size: auto;
    position: absolute; /* Permite posicionamento dentro do pai */
    bottom: 0; /* Alinha ao limite inferior do pai */
    z-index: 1; /* Coloca a imagem atrás da cena */
opacity: 0; transition: opacity 1s ease-in-out;
  }

  a-scene {
    position: absolute;
    background: transparent;
    top: 0;
    left: 0;
    width: 100%;
    height: 500px;
    z-index: 2; /* Garante que a cena fique por cima */
  }
  
  </style>

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container banneranimadopai" >
      
      <div class="banneranimado">
      </div>

        <a-scene embedded vr-mode-ui="enabled: false" renderer="alpha: true">
                
          <!-- Luz ambiente -->
          <a-light type="ambient" color="#ffffff" intensity="0.5"></a-light>
          
          <!-- Luz direcional -->
          <a-light type="directional" color="#ffffff" intensity="1" position="5 5 5"></a-light>

          <!-- Modelo 3D com animações e transparência -->
          <a-entity 
            id="rocket" 
            gltf-model="{{ asset('assets/models/low_poly_rocket.glb') }}"
            position="20 0 0" 
            rotation="90 180 -90" 
            scale="0 0 0" 
            
            animation__move1="property: position; to: 0 0 0; dur: 2800; easing: easeInOutSine; startEvents: start-move"
            animation__scale1="property: scale; to: 1.6 1.6 1.6; dur: 2800; easing: easeInOutSine; startEvents: start-move"
            animation__rotate="property: rotation; to: 0 180 0; dur: 1000; easing: easeInOutSine; startEvents: start-rotate">
            {{-- animation__move2="property: position; to: 0 15 0; dur: 1200; easing: easeInOutSine; startEvents: start-move2"
            animation__scale2="property: scale; to: 0 0 0; dur: 900; easing: easeInOutSine; startEvents: start-move2"> --}}
          </a-entity>


          <!-- Câmera -->
          <a-camera position="0 1.6 10" rotation="0 -90 0"></a-camera>
        </a-scene>

      
      </div>
    </section>

    <!-- End About Section -->


    <!-- ======= Testimonials Section ======= -->
    <section id="sobre" class="testimonials section-bg">
      <div class="container">

        <div class=" mb-5">

          <h3>Sobre Nós</h3>   
          <p class="mb-5">Somos uma empresa dedicada a oferecer espaços modernos, práticos e confortáveis para profissionais e empresas que buscam ambientes ideais para atender seus clientes ou realizar reuniões e eventos. Nosso objetivo é criar uma experiência única, onde cada detalhe foi pensado para proporcionar funcionalidade e bem-estar. <br>

            Com salas totalmente equipadas, flexibilidade de horários e localização estratégica, atendemos às necessidades de psicólogos, terapeutas, coaches, empresários e diversos outros profissionais. Seja para um atendimento individual, um workshop ou uma reunião corporativa, aqui você encontra o ambiente perfeito para alcançar seus objetivos.<br>

            Nossa missão é simplificar sua rotina e oferecer o suporte que você precisa para focar no que realmente importa: seus clientes e negócios.

            Venha conhecer nossas salas e transforme seu dia a dia profissional com um espaço feito para você!</p>

            <hr>

          <h3 class="mt-5">O que dizem do nossos espaço</h3>     
          <p>Nossos espaços foram cuidadosamente planejados para atender às necessidades de profissionais das mais diversas áreas, como psicólogos, terapeutas, coaches, e muitos outros. Aqui, cada detalhe foi pensado para garantir conforto, praticidade e um ambiente profissional. Veja o que alguns de nossos parceiros têm a dizer sobre suas experiências:</p>
        </div>

        <div class="owl-carousel testimonials-carousel">

          <div class="testimonial-item" data-aos="fade-up">
              <p>
                  <i class="bx bxs-quote-alt-left quote-icon-left"style="color: #ff5722;"></i>
                  As salas são perfeitas para atender meus pacientes. O ambiente é acolhedor e profissional, exatamente o que eu precisava para oferecer um serviço de qualidade.
                  <i class="bx bxs-quote-alt-right quote-icon-right"style="color: #ff5722;"></i>
              </p>
              <h3>Dr. Ricardo Almeida</h3>
              <h4>Psicólogo</h4>
          </div>


          <div class="testimonial-item" data-aos="fade-up">
              <p>
                  <i class="bx bxs-quote-alt-left quote-icon-left"style="color: #ff5722;"></i>
                  Encontrei um espaço incrível para realizar minhas terapias. A organização e a estrutura são impecáveis, e meus clientes também elogiam.
                  <i class="bx bxs-quote-alt-right quote-icon-right"style="color: #ff5722;"></i>
              </p>
              <h3>Maria Beatriz Rocha</h3>
              <h4>Terapeuta</h4>
          </div>


          <div class="testimonial-item" data-aos="fade-up">
              <p>
                  <i class="bx bxs-quote-alt-left quote-icon-left"style="color: #ff5722;"></i>
                  Atender aqui é um diferencial. A flexibilidade de horários e a infraestrutura moderna me ajudam a causar uma ótima impressão nos meus clientes.
                  <i class="bx bxs-quote-alt-right quote-icon-right"style="color: #ff5722;"></i>
              </p>
              <h3>Paulo Mendes</h3>
              <h4>Terapeuta</h4>
          </div>


          <div class="testimonial-item" data-aos="fade-up">
              <p>
                  <i class="bx bxs-quote-alt-left quote-icon-left"style="color: #ff5722;"></i>
                  A localização estratégica e o ambiente organizado me ajudam a fidelizar meus clientes. Recomendo para qualquer profissional da área da saúde.
                  <i class="bx bxs-quote-alt-right quote-icon-right"style="color: #ff5722;"></i>
              </p>
              <h3>Luiza Martins</h3>
              <h4>Nutricionista</h4>
          </div>


          <div class="testimonial-item" data-aos="fade-up">
              <p>
                  <i class="bx bxs-quote-alt-left quote-icon-left"style="color: #ff5722;"></i>
                  As salas atendem perfeitamente às minhas necessidades. Conforto e privacidade para os atendimentos são pontos fortes do espaço.
                  <i class="bx bxs-quote-alt-right quote-icon-right"style="color: #ff5722;"></i>
              </p>
              <h3>Juliana Torres</h3>
              <h4>Psicopedagoga</h4>
          </div>


        </div>

      </div>
    </section><!-- End Testimonials Section -->
    <!-- ======= Contact Section ======= -->


    
    <!-- End Contact Section -->

  </main>
  <!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-4 col-md-6">
            <div class="footer-info" data-aos="fade-up" data-aos-delay="50">
              <img src="{{ asset('assets') }}/img/logoescuro.png" alt="" class="img-fluid mb-4" style=" width: 70%;"> 
              <div class="social-links mt-3">
                <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="250">
              <h4>Nossos Serviços</h4>
              <ul>
                  <li><i class="bx bx-chevron-right"></i> Atendimento Personalizado</li>
                  <li><i class="bx bx-chevron-right"></i> Aluguel de Salas</li>
                  <li><i class="bx bx-chevron-right"></i> Espaços para Eventos</li>
                  <li><i class="bx bx-chevron-right"></i> Salas de Reunião</li>                  
                  <li><i class="bx bx-chevron-right"></i> Ambientes Equipados</li>
              </ul>
          </div>


          <div class="col-lg-4 col-md-6 footer-newsletter" data-aos="fade-up" data-aos-delay="350">
            <h4>Inscreva-se na nossa Newsletter</h4>
            <p>Fique por dentro das nossas ofertas, disponibilidade e Atualizações das salas.</p>
            <form action="" method="post">
              <input type="email" name="email"><input type="submit" value="Inscrever-se">
            </form>
          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>Equilibra Mente</span></strong>. Todos os direitos reservados.
      </div>

    </div>
  </footer>
  
  <!-- End Footer -->

  {{-- <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a> --}}

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets') }}/vendor/jquery/jquery.min.js"></script>
  <script src="{{ asset('assets') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('assets') }}/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="{{ asset('assets') }}/vendor/php-email-form/validate.js"></script>
  <script src="{{ asset('assets') }}/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="{{ asset('assets') }}/vendor/counterup/counterup.min.js"></script>
  <script src="{{ asset('assets') }}/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="{{ asset('assets') }}/vendor/venobox/venobox.min.js"></script>
  <script src="{{ asset('assets') }}/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="{{ asset('assets') }}/vendor/aos/aos.js"></script>
  <script src="../../../app-assets/vendors/js/extensions/toastr.min.js"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('assets') }}/js/main.js"></script>

  <script src="https://cdn.jsdelivr.net/gh/donmccurdy/aframe-extras@6.1.1/dist/aframe-extras.min.js"></script> <!-- aframe-extras -->
  
  

  <script>
    const rocket = document.getElementById('rocket');
        // Impede interações com o modelo
      rocket.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        console.log('Interação bloqueada');
      });

        // Inicia o movimento e a escala simultaneamente
        rocket.emit('start-move');

        // Dispara a rotação quando 75% do movimento for concluído
      setTimeout(() => {
        rocket.emit('start-rotate'); // Inicia a rotação para ficar em pé
        
        // Inicia rotação contínua após a rotação inicial
        setTimeout(() => {
          rocket.setAttribute('animation__spin', {
            property: 'rotation',
            to: '0 -180 0',
            loop: true,
            dur: 15000, // Duração de uma rotação completa
            easing: 'easeInOutQuad'
          });
        }, 1000); // Ajuste conforme necessário

        setTimeout(() => {
          const banner = document.querySelector('.banneranimado');
          banner.style.opacity = .8; // Torna o banner visível suavemente
        }, 900);

      }, 1900);

          

  </script>

</body>

</html>