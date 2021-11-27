<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Iconos fontawesome --> 
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ url('css/app.css') }}">

    <!-- jQuery --> 
    <script src="http://code.jquery.com/jquery-2.1.1.min.js"></script> 

    <!-- Bootstrap editable -->
    <link rel="stylesheet" type="text/css" href="{{ url('css/bootstrap-editable.css') }}">
    <script src="{{ asset('js/bootstrap-editable.js') }}" defer></script>

    <!--jConfirm --> 
    <link rel="stylesheet" type="text/css" href="{{ url('css/jConfirm.min.css') }}">
    <script src="{{ asset('js/jConfirm.min.js') }}" defer></script>
</head>
<body>
    <!-- menu superior --> 
    <div class="row">
        <div class="col-12">
            <nav class="navbar navbar-expand-lg navbar-light bg-light marginSideNavbar">
                <a class="navbar-brand" href="{{ route('home') }}">Gastos mensuales</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item {{ setActive('home') }}"><a class="nav-link" href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>
                        <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-plus"></i> Secciones</a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item {{ setActive('salary') }}" href="{{ route('salary') }}"><i class="fas fa-euro-sign"></i> Salarios</a>
                            <a class="dropdown-item {{ setActive('category') }}" href="{{ route('category') }}"><i class="fab fa-cuttlefish"></i> Categorías</a>
                            <a class="dropdown-item {{ setActive('category') }}" href="{{ route('category') }}"><i class="fas fa-comment-dollar"></i> Transferencias/Ingresos</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item {{ setActive('category') }}" href="{{ route('category') }}"><i class="fas fa-coins"></i> Gastos mensuales</a>
                          </div>
                        </li>
                        <li class="nav-item {{ setActive('category') }}"><a class="nav-link" href="{{ route('category') }}"><i class="fas fa-book"></i></i> Historial de gastos</a></li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                      <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">{{auth()->user()->username}}</a>
                          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item {{ setActive('config') }}" href="{{ route('config') }}"><i class="fas fa-cog"></i> Configuración</a>
                            <div class="dropdown-divider"></div>
                            <form class="form-inline my-2 my-lg-0 marginLeft" action="/logout" method="POST">
                              @csrf
                              <button type="submit" class="btn btn-default"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</button>
                            </form>
                          </div>
                        </li>
                      <li>
                    </ul>
                </div>
            </nav>                
        </div>
    </div>
    <!-- menu superior -->

    <!-- Body para escribir todo el contenido-->
    <div class="row">
        <div class="col-12">@yield('body')</div>
    </div>
    <!-- Body para escribir todo el contenido-->
</body>
</html>
