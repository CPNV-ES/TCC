<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tennis Club Chavornay - TCC</title>

        {!! Html::style('/css/bootstrap-theme.min.css')!!}
        {!! Html::style('/css/bootstrap.min.css')!!}
        {!! Html::style('/css/layouts.css')!!}

        {!! Html::script('/js/jquery-2.2.0.js') !!}
        {!! Html::script('/js/bootstrap.min.js') !!}
        {!! Html::script('/js/layouts.js') !!}
    </head>
    <body id="app-layout">
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#spark-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="spark-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a href="{{ url('/home') }}">Home</a>
                        </li>
                        <li>
                            <a href="{{ url('/#') }}">Réservation</a>
                        </li>
                    </ul>

                    <div class="nav navbar-nav" align="center" style="width:65%">
                        {{ HTML::image("css/images/logo.gif", "TC Chavornay", array('width'=> '15%')) }}
                    </div>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                        <li>
                            <a href="{{ url('/register') }}">S'enregistrer</a>
                        </li>
                        <li>
                            <a href="{{ url('/login') }}">Connexion</a>
                        </li>
                        @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} <span class="caret"></span> </a>

                            <ul class="dropdown-menu" role="menu">
                                @if(Auth::user()->administrator == 1)
                                <li>
                                    <a href="{{ url('/admin') }}"><i class="fa fa-btn fa-sign-out"></i>Administration</a>
                                </li>
                                @endif
                                <li>
                                    <a href="{{ url('/profile') }}"><i class="fa fa-btn fa-sign-out"></i>Profile</a>
                                </li>
                                <li>
                                    <a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div class="background">

        </div>
        @yield('content')
        {{-- <script src="{{ elixir('js/app.js') }}"></script>
        --}}
    </body>
</html>
