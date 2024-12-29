<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Espaço Equilibra Mente - {{ $sala->nome ?? '' }}</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicons -->
  <link href="{{ asset('assets') }}/img/favicon.png" rel="icon">
  <link href="{{ asset('assets') }}/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->  
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">

  <!-- Template Main CSS File -->
  
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap-extended.css">
  <link rel="stylesheet" type="text/css" href="../../../app-assets/css/colors.css">
  <link rel="stylesheet" type="text/css" href="../../../app-assets/css/components.css">
  <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/dark-layout.css">
  <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/bordered-layout.css">
  <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/semi-dark-layout.css">
  <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors.min.css">  
  <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/extensions/toastr.min.css">
      <link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/extensions/ext-component-toastr.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/extensions/ext-component-sweet-alerts.css">
  
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  


  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets') }}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('assets') }}/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="{{ asset('assets') }}/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="{{ asset('assets') }}/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="{{ asset('assets') }}/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="{{ asset('assets') }}/vendor/aos/aos.css" rel="stylesheet">

  

  <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/calendars/fullcalendar.min.css') }}">
  <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">


  <link href="{{ asset('assets') }}/css/style.css" rel="stylesheet">


</head>

<body>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top header-transparent">
    <div class="container d-flex align-items-center hero-content">

      <div class="logo mr-auto">
        {{-- <h1 class="text-light"><a href="/"><span>Equilibra Mente</span></a></h1> --}}
          <a href="/"><img src="{{ asset('assets') }}/img/logofinoescuro.png" style="height: 30px"> </a>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
      </div>

      <nav class="nav-menu d-none d-lg-block">
        <ul>
          <li><a href="/#hero">Home</a></li>
          <li><a href="/#about">Salas</a></li>
          <li><a href="/#contact">Contato</a></li>
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


    @yield('content')


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

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

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
  <!-- FullCalendar e Dependências -->
  <script src="{{ asset('app-assets/vendors/js/calendar/fullcalendar.min.js') }}"></script>
  <script src="{{ asset('app-assets/vendors/js/extensions/moment.min.js') }}"></script>
  <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
  <script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/locale/pt-br.min.js"></script>
  
  <script src="../../../app-assets/vendors/js/extensions/toastr.min.js"></script>


  <!-- Template Main JS File -->
    <script src="{{ asset('assets') }}/js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    
    <script>
        // Aplica a máscara após o carregamento completo do DOM
        $(document).ready(function() {
            
                $('#endereco_cep').mask('00000-000');
                $('#telefone').mask('(00) 00000-0000');
            

            // Evento para buscar endereço quando o CEP é preenchido
            $(document).on('blur', '#endereco_cep', function () {
                let cep = $(this).val().replace(/\D/g, '');

                if (cep.length === 8) {
                    $.getJSON(`/api/cep/${cep}`, function (data) {
                        if (!("erro" in data)) {
                            $('#endereco_rua').val(data.logradouro);
                            $('#endereco_bairro').val(data.bairro);
                            $('#endereco_cidade').val(data.localidade);
                            $('#endereco_estado').val(data.uf);
                        } else {
                            toastr.error("CEP não encontrado.");
                        }
                    }).fail(function() {
                        toastr.error("Erro ao buscar o endereço. Tente novamente.");
                    });
                } else {
                    toastr.warning("CEP inválido. Insira um CEP com 8 dígitos.");
                }
            });

        });
    </script>
    <script>
        const datatablesLangUrl = "{{ asset('assets/js/datatables-pt-br.json') }}";
    </script>

    @stack('js_page')

</body>

</html>