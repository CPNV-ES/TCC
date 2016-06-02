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

    {!! Html::style('/css/admin.css')!!}

    {!! Html::style('/css/sb-admin-2.css')!!}
    {!! Html::style('/css/timeline.css')!!}

    {!! Html::style('/css/jqx.base.css')!!}
    {!! Html::style('/css/jqx.bootstrap.css')!!}

    {!! Html::style('/fonts/font-awesome/css/font-awesome.min.css')!!}

    {!! Html::script('/js/jquery-2.2.0.js') !!}
    {!! Html::script('/js/bootstrap.min.js') !!}
    {!! Html::script('/js/layouts.js') !!}
    {!! Html::script('/js/sb-admin-2.js') !!}

    {!! Html::script('/js/metisMenu/dist/metisMenu.min.js') !!}
    {!! Html::script('/js/raphael/raphael-min.js') !!}
    {!! Html::script('/js/morrisjs/morris.min.js') !!}
    {!! Html::script('/js/morris-data.js') !!}

    {!! Html::script('/js/jqwidget/jqx-all.js') !!}


</head>
<body>
<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('') }}">TC Chavornay</a>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">
            <!-- /.dropdown -->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu" role="menu">
                    @if(Auth::user()->administrator == 1)
                        <li>
                            <a href="{{ url('/admin') }}"><i class="fa fa-gear fa-fw"></i> Administration</a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ url('/profile') }}"><i class="fa fa-user fa-fw"></i> Profile</a>
                    </li>
                    <li>
                        <a href="{{ url('/mybooking') }}"><i class="fa fa-btn fa-calendar"></i> Mes réservations</a>
                    </li>
                    <hr />
                    <li>
                        <a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> Logout</a>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->

        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="{{ url('/admin') }}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                    </li>
                    <li>
                    <li>
                        <a href="#"><i class="fa fa-cog fa-fw"></i> Configuration<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{{ url('/admin/config/courts') }}">Courts</a>
                            </li>
                            <li>
                                <a href="{{ url('/admin/config/seasons') }}">Saisons</a>
                            </li>
                            <li>
                                <a href="{{ url('/admin/config/subscriptions') }}">Cotisations & Status</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="{{ url('/admin/members') }}"><i class="fa fa-users fa-fw"></i> Membres</a>
                    </li>
                    <li>
                        <a href="forms.html"><i class="fa fa-bar-chart-o fa-fw"></i> Statistiques</a>
                    </li>
                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">@yield('title')</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        @yield('content')
    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

{!! Html::script('/js/jqwidget/globalize.js') !!}
{!! Html::script('/js/jqwidget/localization.js') !!}
{!! Html::script('/Ajax/members.js') !!}
{!! Html::script('/Ajax/courts.js') !!}
{!! Html::script('/Ajax/subscriptions.js') !!}
{!! Html::script('/Ajax/seasons.js') !!}

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

</body>
</html>