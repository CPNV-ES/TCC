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

{{--        {!! Html::style('/css/jqx.base.css')!!}
        {!! Html::style('/css/jqx.bootstrap.css')!!}
        {!! Html::style('/css/jqx.custom.css')!!}--}}

        {!! Html::script('/js/jquery-2.2.0.js') !!}
        {!! Html::script('/js/bootstrap.min.js') !!}
        {!! Html::script('/js/layouts.js') !!}

        {!! Html::script('/js/bootstrap-datepicker.min.js') !!}
        {!! Html::script('/js/bootstrap-datepicker.fr-CH.min.js') !!}

        {{--is like datepicker but with hour --}}
  {{--      {!! Html::script('/js/bootstrap-datetimepicker.min.js') !!}
        {!! Html::script('/js/bootstrap-datetimepicker.fr.js') !!}--}}

        {{--{!! Html::script('/js/jqwidget/jqx-all.js') !!}
        {!! Html::script('/js/jqwidget/globalize.js') !!}
        {!! Html::script('/js/jqwidget/globalize.culture.fr-FR.js') !!}--}}
        {!! Html::script('/js/ee.js') !!}

        {!! Html::script('/Ajax/register.js') !!}
        {!! Html::script('/js/weather.js') !!}
        {!! Html::script('/js/verif.js') !!}
        {!! Html::script('/js/customVerif.js') !!}

        {!! Html::script('/js/functions.js') !!}

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
                          @if (Auth::user()->invitRight == 0 || maxReservations())
                            <div id="status-info-warning">
                              <i id="warning-triangle" class="fa fa-exclamation-triangle fa-2x"></i>
                              <div id="status-info">
                                <div>
                                  <b><i class="text-warning">Statuts</i></b>
                                  @if (Auth::user()->invitRight == 0)
                                    <p>
                                      Vous n'avez pas le droit d'inviter!
                                    </p>
                                  @endif
                                  @if (maxReservations())
                                    <p>
                                      Vous avez atteint le nombre maximum de réservation simultanée!
                                    </p>
                                  @endif
                                </div>
                              </div>
                            </div>
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
        <div class="wrapper">
          @yield('content')
        </div>
        {!! Html::script('/js/jqwidget/globalize.js') !!}
        {!! Html::script('/js/jqwidget/localization.js') !!}

        <footer style="position:absolute;">
          <p>© Centre Professionnel du Nord Vaudois / 2016 - {{Date('Y')}}</p>
          <p id="GIT_RELEASE" style="position:fixed;bottom: 0px;font-size: 10px;right:15px;color:rgba(0,0,0,.2);">UNDEFINED</p>
        </footer>
        <script>
        (function(){
        var attachEvent = document.attachEvent;
        var isIE = navigator.userAgent.match(/Trident/);
        var requestFrame = (function(){
          var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame ||
              function(fn){ return window.setTimeout(fn, 20); };
          return function(fn){ return raf(fn); };
        })();

        var cancelFrame = (function(){
          var cancel = window.cancelAnimationFrame || window.mozCancelAnimationFrame || window.webkitCancelAnimationFrame ||
                 window.clearTimeout;
          return function(id){ return cancel(id); };
        })();

        function resizeListener(e){
          var win = e.target || e.srcElement;
          if (win.__resizeRAF__) cancelFrame(win.__resizeRAF__);
          win.__resizeRAF__ = requestFrame(function(){
            var trigger = win.__resizeTrigger__;
            trigger.__resizeListeners__.forEach(function(fn){
              fn.call(trigger, e);
            });
          });
        }

        function objectLoad(e){
          this.contentDocument.defaultView.__resizeTrigger__ = this.__resizeElement__;
          this.contentDocument.defaultView.addEventListener('resize', resizeListener);
        }

        window.addResizeListener = function(element, fn){
          if (!element.__resizeListeners__) {
            element.__resizeListeners__ = [];
            if (attachEvent) {
              element.__resizeTrigger__ = element;
              element.attachEvent('onresize', resizeListener);
            }
            else {
              if (getComputedStyle(element).position == 'static') element.style.position = 'relative';
              var obj = element.__resizeTrigger__ = document.createElement('object');
              obj.setAttribute('style', 'display: block; position: absolute; top: 0; left: 0; height: 100%; width: 100%; overflow: hidden; pointer-events: none; z-index: -1;');
              obj.__resizeElement__ = element;
              obj.onload = objectLoad;
              obj.type = 'text/html';
              if (isIE) element.appendChild(obj);
              obj.data = 'about:blank';
              if (!isIE) element.appendChild(obj);
            }
          }
          element.__resizeListeners__.push(fn);
        };

        window.removeResizeListener = function(element, fn){
          element.__resizeListeners__.splice(element.__resizeListeners__.indexOf(fn), 1);
          if (!element.__resizeListeners__.length) {
            if (attachEvent) element.detachEvent('onresize', resizeListener);
            else {
              element.__resizeTrigger__.contentDocument.defaultView.removeEventListener('resize', resizeListener);
              element.__resizeTrigger__ = !element.removeChild(element.__resizeTrigger__);
            }
          }
        }
      })();
        function footerAlign() {
          if (($('.navbar.navbar-default').height() + $('.wrapper').height()) < $(window).height()-75) {
            $('footer').css('position', 'fixed');
            $('footer').css('bottom', '0px');
          }
          else {
            $('footer').css('position', 'inherit');
          }
        }
        function statusMobile() {
          if ($(window).width() < 767) {
            $('#status-info-warning').appendTo($('.navbar-header'));
          }
          else {
            $('#status-info-warning').insertBefore($('.nav.navbar-nav.navbar-right .dropdown'));
          }
        }
        $(document).ready(function(){
          footerAlign();
          statusMobile();
        });
        addResizeListener(document.body, function(){
          footerAlign();
        });
        $(window).resize(function() {
          footerAlign();
          statusMobile();
        });
        </script>
    </body>
</html>
