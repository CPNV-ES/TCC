<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Tennis Club Chavornay - TCC</title>

        {!! Html::style('/css/bootstrap-theme.min.css')!!}
        {!! Html::style('/css/bootstrap.min.css')!!}
        {!! Html::style('https://fonts.googleapis.com/css?family=Open+Sans')!!}
        {!! Html::style('/css/layouts.css')!!}
        {!! Html::style('/css/bootstrap-datepicker3.min.css')!!}

        {!! Html::style('/css/bootstrap-datetimepicker.min.css') !!}

        {!! Html::style('/fonts/font-awesome/css/font-awesome.min.css')!!}

        {!! Html::style('/css/jqx.base.css')!!}
        {!! Html::style('/css/jqx.bootstrap.css')!!}
        {!! Html::style('/css/jqx.custom.css')!!}

        {!! Html::script('/js/jquery-2.2.0.js') !!}
        {!! Html::script('/js/bootstrap.min.js') !!}
        {!! Html::script('/js/layouts.js') !!}

        {!! Html::script('/js/bootstrap-datepicker.min.js') !!}
        {!! Html::script('/js/bootstrap-datepicker.fr-CH.min.js') !!}

        {{--is like datepicker but with hour --}}
        {!! Html::script('/js/bootstrap-datetimepicker.min.js') !!}
        {!! Html::script('/js/bootstrap-datetimepicker.fr.js') !!}

        {!! Html::script('/js/jqwidget/jqx-all.js') !!}
        {!! Html::script('/js/jqwidget/globalize.js') !!}
        {!! Html::script('/js/jqwidget/globalize.culture.fr-FR.js') !!}
        {!! Html::script('/js/ee.js') !!}

        {!! Html::script('/ajax/register.js') !!}
        {!! Html::script('/js/weather.js') !!}
        {!! Html::script('/js/verif.js') !!}
        {!! Html::script('/js/customVerif.js') !!}



        {{-- <script>
            is_logged = "{{ Auth::check() }}";
            @if (Auth::check())
                    member_last_name = "{{ Auth::user()->username }}";
            @endif

            $.ajaxSetup({
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Accept', 'application/json');
                    xhr.setRequestHeader('X-Accept', 'application/json');
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
            });
        </script> --}}


    </head>
    <body id="app-layout">
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" style="z-index:1" data-toggle="collapse" data-target="#spark-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>


                    <div class="navbar-brand"><a href="{{ url('/home') }}">{{ HTML::image("css/images/logo.gif", "TC Chavornay", array('width'=> '100px', 'style' => 'display:inline;')) }}</a></div>

                <div class="collapse navbar-collapse" id="spark-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a href="{{ url('/home') }}">Accueil</a>
                        </li>
                        <li>
                            <a href="{{ url('/booking') }}">Réservations</a>
                        </li>
                        @if(Auth::check() && (Auth::user()->isTrainer || Auth::user()->isAdmin))
                            <li>
                                <a href="{{url('/staff_booking')}}">Réservation staff</a>
                            </li>
                        @endif
                    </ul>



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
                          @if (Auth::user()->invitRight == 0)
                            <li>
                              <i id="status-info-warning" class="fa fa-exclamation-triangle fa-2x"></i>
                              <div id="status-info">
                                <div>
                                  <b><i class="text-danger">Statuts</i></b>
                                  <p>
                                    Vous n'avez pas le droit d'inviter!
                                  </p>
                                </div>
                              </div>
                            </li>
                          @endif
                          <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> {{ App\User::find(Auth::user()->id)->personal_information->firstname }} {{ App\User::find(Auth::user()->id)->personal_information->lastname }} <span class="caret"></span> </a>

                              <ul class="dropdown-menu" role="menu">
                                  @if(Auth::user()->isAdmin == 1)
                                  <li>
                                      <a href="{{ url('/admin') }}"><i class="fa fa-gear"></i> Administration</a>
                                  </li>
                                  @endif
                                  <li>
                                      <a href="{{ url('/profile') }}"><i class="fa fa-user"></i> Profile</a>
                                  </li>
                                  <li>
                                      <a href="{{ url('/mybooking') }}"><i class="fa fa-calendar"></i> Mes réservations</a>
                                  </li>
                                  <hr />
                                  <li>
                                      <a href="{{ url('/logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                                  </li>
                              </ul>
                          </li>
                        @endif
                    </ul>
                  <div>
            </div>
        </nav>

        @yield('content')
        {!! Html::script('/js/jqwidget/globalize.js') !!}
        {!! Html::script('/js/jqwidget/localization.js') !!}


    </body>
    {{-- <br style="clear:both"/> --}}
    <footer>
      <p>© Centre Professionnel du Nord Vaudois / 2016 - {{Date('Y')}}</p>
    </footer>

    <script>
    function footerAlign() {
      if ($(document.body).height() < $(window).height()) {
        $('footer').attr('style', 'position: fixed!important; bottom: 0px;');
      }
      else {
        $('footer').attr('style', '');
      }
    }
    $(document).ready(function(){
      footerAlign();
    });

    $( window ).resize(function() {
      footerAlign();
    });
    // $(document).ready(function() {
    //   $('#status-info-warning').hover(function() {
    //     $('#status-info').css("display", "block");
    //   },
    //   function() {
    //     $('#status-info').css("display", "none");
    //   });
    // });
    </script>

</html>
