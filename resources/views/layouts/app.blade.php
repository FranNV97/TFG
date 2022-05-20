<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ProGob</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.0/dist/sweetalert2.all.min.js"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/gh/jamesssooi/Croppr.js@2.3.0/dist/croppr.min.js"></script>
    <link href="https://cdn.jsdelivr.net/gh/jamesssooi/Croppr.js@2.3.0/dist/croppr.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jointjs/2.1.0/joint.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.10.1/lodash.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.3.3/backbone.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jointjs/2.1.0/joint.js"></script>
</head>
<style>
    .titulo {
        color: #636b6f;
        font-size: 20px;
        font-weight: 600;
        letter-spacing: .1rem;
    }

    .margenArriba {
        margin-top: 2em;
    }

    .pocoMargenArriba {
        margin-top: 1em;
    }

    .pocoMargenBajo {
        margin-bottom: 1em;
    }

    .muyPocoMargenBajo {
        margin-bottom: 0.5em;
    }

    .pocoMargenDerecha {
        margin-right: 1em;
    }

    .derecha {
        text-align: right;
        padding: 10px;
    }

    .centradoTexto {
        text-align: center;
    }

    .sinBorde {
        outline: none;
    }

    .altoBoton {
        height: 3em;
    }

    .unaLinea {
        display: block;
    }

    .modal {
        overflow-y:auto;
    }

    .panelDiv {
        border: 1px solid black;
        padding: 1em;
    }

    .pocoMargen {
        margin: 1em;
    }

    .textoBarraSuperior {
        font-size: 17px;
    }

    .textoLogo {
        font-size: 24px;
    }

    .textoCabeceraTable {
        font-size: 16px;
    }

    .textoTable {
        font-size: 15px;
    }

    body {
        background-image: url("/images/fondo4.jpg");
        background-size: cover;
    }

    .apartado {
        background-color: white;
    }

    .vue-ads-border-b {
        background-color: black;
        color: white;
    }

    .vue-ads-bg-gray-100 {
        background-color: gray;
        color: black;
    }

    .vue-ads-bg-white {
        background-color: white;
        color: black;
    }

</style>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand textoLogo" href="{{ url('/') }}">
                    ProGob
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link textoBarraSuperior" href="{{ route('login') }}">{{ __('Iniciar sesión') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link textoBarraSuperior" href="{{ route('register') }}">{{ __('Registrarse') }}</a>
                                </li>
                            @endif
                        @else

                            <li class="nav-item active pocoMargenDerecha">
                                <a class="nav-link textoBarraSuperior" href="{{ action('ProcessController@getProcessesView') }}">
                                    <b>Procesos</b>
                                </a>
                            </li>

                            <li class="nav-item active pocoMargenDerecha">
                                <a class="nav-link textoBarraSuperior" href="{{ action('IssueController@getIncidences') }}">
                                    <b>Incidencias</b>
                                </a>
                            </li>

                            <li class="nav-item active pocoMargenDerecha">
                                <a class="nav-link textoBarraSuperior" href="{{ action('IssueController@getIssues') }}">
                                    <b>Problemas</b>
                                </a>
                            </li>

                            <li class="nav-item active dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle textoBarraSuperior" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>

                                    @if(isset(Auth::user()->name))

                                        {{ Auth::user()->name }}

                                    @else

                                        {{ Auth::user()->email }}

                                    @endif

                                    <span class="badge badge-light">{{ $badge ?? '' }}</span>
                                    <span class="caret"></span>

                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item textoTable" href="{{ action('NotificationController@getNotificationsView') }}">
                                        Notificaciones <span class="badge badge-dark">{{ $badge ?? '' }}</span>
                                    </a>

                                    <a class="dropdown-item textoTable" href="{{ action('HomeController@myProfile') }}">
                                        Editar perfil
                                    </a>

                                    <a class="dropdown-item textoTable" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar sesión') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
