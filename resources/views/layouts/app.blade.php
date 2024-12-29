<!DOCTYPE html>
<html class="loading" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="Public">
    <meta name="theme-color" content="#66619c">
    <meta name="author" content="pitter775@gmail.com">


    <!-- Favicons -->
    <link href="{{ asset('assets') }}/img/favicon.png" rel="icon">
    <link href="{{ asset('assets') }}/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>HiiA - Gestão</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/extensions/nouislider.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/extensions/toastr.min.css">
    @stack('css_vendor')
    
    <!-- END: Vendor CSS-->



    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/core/menu/menu-types/vertical-menu.css">

    @stack('css_page')


    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../../../assets/vendor/aos/aos.css">


    

     <!-- Scripts -->
     <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
</head>
<body class="vertical-layout vertical-menu-modern  navbar-sticky footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="">
         

    @include('layouts.nav_topo')   
    @include('layouts.nav_lateral')   
    @include('layouts.pagina')   

    <!-- END: Content-->
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>


    <!-- BEGIN: Vendor JS-->     
    <script src="../../../app-assets/vendors/js/vendors.min.js"></script>



    <!-- BEGIN: Page Vendor JS-->
    <script src="../../../app-assets/vendors/js/extensions/wNumb.min.js"></script>
    <script src="../../../app-assets/vendors/js/extensions/nouislider.min.js"></script>
    <script src="../../../app-assets/vendors/js/extensions/toastr.min.js"></script>
    <!-- Adicione esse script no cabeçalho ou rodapé, antes de aplicar a máscara -->
 


    @stack('js_vendor')

    <!-- BEGIN: Theme JS-->
    <script src="../../../app-assets/js/core/app-menu.js"></script>
    <script src="../../../app-assets/js/core/app.js"></script>
    


    <!-- BEGIN: Page JS-->

    @stack('js_page')
 
  

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
       

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let prot = location.href;
        position = prot.search("127");
        console.log(position);
        if(position !== 7){
            if (location.protocol !== 'https:') {
                location.replace(`https:${location.href.substring(location.protocol.length)}`);
            }
        }   

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    

    <script>
        const datatablesLangUrl = "{{ asset('assets/js/datatables-pt-br.json') }}";
    </script>
</body>
<!-- END: Body-->
</html>
