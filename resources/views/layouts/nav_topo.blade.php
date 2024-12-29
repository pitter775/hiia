    <!-- BEGIN: Header-->

    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light " style="margin-top:0px; ">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>
                <ul class="nav navbar-nav ">
                    <li class="nav-item d-none d-lg-block "><h4>Área de Acesso Privado</h4></li>                   
                </ul>

            </div>
            <ul class="nav navbar-nav align-items-center ml-auto">
                <li class="nav-item dropdown dropdown-user">
                    <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none">
                            <span class="user-name font-weight-bolder">{{Auth::user()->name}}</span>
                            <span class="user-status">{{Auth::user()->tipo_usuario}}</span>
                        </div>
                        <span class="avatar">
                            @if(Auth::user()->use_foto == null)
                            <img src=" {{asset('app-assets/images/avatars/avatar.png')}}" alt="avatar" height="40" width="40">

                            @endif
                            @if(Auth::user()->use_foto !== null)
                            <img src="{{asset('arquivos').'/'.Auth::user()->use_foto}}" alt="avatar" height="40" width="40">
                            @endif
                            <span class="avatar-status-online"></span>

                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                        <a class="dropdown-item" href="{{ route('logout') }}"><i class="mr-50" data-feather="power"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- END: Header-->