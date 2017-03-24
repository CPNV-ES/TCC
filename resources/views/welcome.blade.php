@extends('layouts.app')

@section('content')

  {!! Html::script('/js/visualcalendar.js') !!}


    <div class="background"></div>
    <div class="row calendar">
        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
            <div class="box">
                <div class="box-icon">
                    <span class="fa fa-4x fa-html5">{{$courts[0]->name}}</span>
                </div>
                @foreach($courts as $key=>$court)
                  @if ($court->id == 1)
                    <div id="tab-{{$court->id}}"  class="tab-pane fade in active">
                    </div>
                    <script>
                        var vc{{$court->id}} = new VisualCalendar();
                        vc{{$court->id}}.config={!! \App\Court::getVcConfigHomeJSON($court->id, 'div#tab-'.$court->id) !!};
                        vc{{$court->id}}.build();
                        vc{{$court->id}}.generate();
                        vc{{$court->id}}.ev.onSelect = function(elem, datetime){
                            var myDate = parseDate(datetime);
                            $("#fkCourt").val({{$court->id}});
                            $("#modal-resume").html('Réservation du court N° '+$("#fkCourt").val()+' le ' +myDate.getUTCDate().toStringN(2)+ "-" + (myDate.getMonth() + 1).toStringN(2) + "-" + myDate.getFullYear()+' à '+myDate.getHours().toStringN(2) + ":" + myDate.getMinutes().toStringN(2) );
                            $("#reservation-date").val(datetime+':00');
                            $('#reservation-modal').modal('show');
                        }
                        vc{{$court->id}}.ev.onPlanifClick=function(elem, planif){
                            console.log(elem, planif);
                        }
                    </script>
                  @endif
                @endforeach
            </div>
        </div>

        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
            <div class="box">
                <div id="weather_picture"></div>
                <div class="info">
                    <h1 id="weather_tmp"></h1>
                    <div id="weather_condition"></div>
                    <div id="weather_wind"></div>
                    <div id="weather_hour"></div>
                </div>
            </div>

            <div class="box" style="margin-top:40px;">
                <div class="info">
                    <a href="{{ url('https://www.facebook.com/tcchavornay/info?tab=overview') }}" target="_blank">{{ Html::image("css/images/fb.jpg", "Actualité Facebook", array('width'=> '50px')) }}</a>
                    <h5>Suivez-nous !</h5>
                </div>
            </div>

            {{--<div class="box" style="margin-top:40px;">--}}
                {{--<div class="info">--}}
                    {{--<a href="{{ url('/booking') }}"><button class="btn btn-primary btn-lg">Je réserve !</button></a>--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="box" style="margin-top:40px;">
                <div class="info">
                    <a href="{{ url('https://www.google.ch/maps/place/Tennis+Club+de+Chavornay/@46.7100609,6.5010883,12z/data=!4m8!1m2!2m1!1stennis+club+%C3%A0+proximit%C3%A9+de+Chavornay!3m4!1s0x0000000000000000:0x2007be33c2832496!8m2!3d46.7047773!4d6.5773129') }}" target="_blank">{{ Html::image("css/images/google_maps.ico", "Actualité Facebook", array('width'=> '50px')) }}</a>
                    <h5>Nous trouver !</h5>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
            <div class="box">
                <div class="box-icon">
                    <span class="fa fa-4x fa-css3">{{$courts[1]->name}}</span>
                </div>
                @foreach($courts as $key=>$court)
                  @if ($court->id == 2)
                    <div id="tab-{{$court->id}}" class="tab-pane fade in active">
                    </div>
                    <script>
                        var vc{{$court->id}} = new VisualCalendar();
                        vc{{$court->id}}.config={!! \App\Court::getVcConfigHomeJSON($court->id, 'div#tab-'.$court->id) !!};
                        vc{{$court->id}}.build();
                        vc{{$court->id}}.generate();
                        vc{{$court->id}}.ev.onSelect = function(elem, datetime){
                            var myDate = parseDate(datetime);
                            $("#fkCourt").val({{$court->id}});
                            $("#modal-resume").html('Réservation du court N° '+$("#fkCourt").val()+' le ' +myDate.getUTCDate().toStringN(2)+ "-" + (myDate.getMonth() + 1).toStringN(2) + "-" + myDate.getFullYear()+' à '+myDate.getHours().toStringN(2) + ":" + myDate.getMinutes().toStringN(2) );
                            $("#reservation-date").val(datetime+':00');
                            $('#reservation-modal').modal('show');
                        }
                        vc{{$court->id}}.ev.onPlanifClick = function(elem, planif){
                            console.log(elem, planif);
                        }
                    </script>
                  @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Reservation -->
    <div class="modal fade" tabindex="-1" role="dialog" id="reservation-modal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Réservation</h4>
              </div>
              <div class="modal-body">
                  <div id="modal-resume" class="notice notice-info">
                          <p>Réservation du court .. le 'date' </p>
                  </div>
                  @if (Auth::check())
                    <ul class="nav nav-tabs" role="tablist">
                      <li role="presentation" class="active"><a href="#member-invite" aria-controls="member-invite" role="tab" data-toggle="tab">Réservation avec un membre</a></li>
                      <li role="presentation"><a href="#nonmember-invite" aria-controls="nonmember-invite" role="tab" data-toggle="tab">Réservation avec un invité</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="member-invite">
                            <form method="post" role="form" method="POST" action="{{ url('/booking')}}" name="reservation-form" >
                                {{ csrf_field() }}
                                {{ method_field('POST') }}
                                <input type="hidden" class="reservation-date" name="dateTimeStart">
                                <input type="hidden" class="fkCourt" id="fkCourt" name="fkCourt" value=1>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Choissiez votre adversaire:</label>
                                     <select name="fkWithWho" class="form-control">
                                        @foreach($membersList as $member)
                                            <option value="{{$member->id}}">{{$member->firstname}} {{$member->lastname}} {{$member->reservations_count}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="booking" class="btn btn-success btn-block push-to-bottom" name="btn-reserver">
                                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                        Réserver
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="nonmember-invite">
                            <form method="post" role="form" method="POST" action="{{ url('/booking')}}" name="reservation-member-invite-form" >
                                {{ csrf_field() }}
                                {{ method_field('POST') }}
                                <input type="hidden" class="reservation-date" name="dateTimeStart">
                                <input type="hidden" class="fkCourt" id="fkCourt" name="fkCourt" value=1>

                                <div class="form-group @if($errors->has('invitFirstname')) {{'has-error'}} @endif" >
                                    <label for="recipient-name" class="control-label">Prénom de votre invité*:</label>
                                    <input class="form-control" type="text" value="{{old('invitFirstname')}}" name="invitFirstname" data-verif="required|text|min_l:2|max_l:45" />
                                    @if ($errors->has('invitFirstname'))
                                        <span class="help-block">
                                              <strong>{{ $errors->first('invitFirstname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group @if($errors->has('invitLastname')) {{'has-error'}} @endif">
                                    <label for="recipient-name" class="control-label">Nom de votre invité*:</label>
                                    <input class="form-control" type="text" name="invitLastname" value="{{old('invitLastname')}}" data-verif="required|text|min_l:2|max_l:45"/>
                                    @if ($errors->has('invitLastname'))
                                        <span class="help-block">
                                              <strong>{{ $errors->first('invitLastname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                * obligatoire
                                <div class="form-group push-to-bottom ">
                                    <button type="button" id="btn-reserver-member-invite" class="btn btn-success btn-block" name="btn-reserver">
                                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                        Réserver
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                  @endif

                  <div id="modal-panel"></div>
                  <div id="modal-content"></div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
              </div>
          </form>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <style media="screen">
        .vc-cnt {
            box-sizing: border-box;
            width: 100%;
            overflow: auto;
            font-family: Arial,sans-serif;
            position: relative;
        }
        table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
            box-sizing: border-box;
        }
        td {
            border: 1px solid black;
            box-sizing: border-box;
        }
        td.vc-table-head {
            background-color: #999;
            color: #eee;
        }
        tr>td:nth-child(1) {
            color: #5a5a5a;
            font-size: 10px;
            vertical-align: top;
        }
        tr>td.vc-table-head:nth-child(1) {
            color: #eee;
            font-size: 14px;
            vertical-align: middle;
        }
        tr>td {
            border-bottom-color: #ccc;
            background-color: #fff;
            height: 23px;
        }
        tr:last-child>td {
            border-bottom-color: black;
        }
        tr>td:first-child {
            min-width: 70px;
            max-width: 70px;
            width: 70px;
        }
        .vc-selected{
            background-color: #9cbcf7;
        }
        .vc-clickable{
            cursor: pointer;
            transition-duration: 250ms;
        }
        .vc-clickable:not(.vc-selected):hover{
            background-color: #c5d9ff;
        }
        .vc-nomoreselect .vc-clickable{
            cursor: default;
        }
        .aucune{background-color: #afa;}
        .simple2{background-color: #ffa;}
        .vc-passed{filter: brightness(0.9);}
        .vc-own-planif{filter: hue-rotate(-60deg);}
    </style>

    {{--<div class="container">--}}
        {{--<div class="row" >--}}
            {{--<div class="col-md-12" style="margin-top:20px;">--}}
                {{--<p>--}}
                    {{--<h1>Bienvenue sur tcchavornay.ch</h1>--}}

                    {{--Ce site est actuellement en construction.--}}
                    {{--N'hésitez pas à venir le visiter prochainement !<br/><br/>--}}

                    {{--Mais retenez déjà quelques dates clés ci-dessous et la date de notre évènement majeur, Le Double Mixte du mois de Juin 2016 !<br/><br/>--}}


                {{--<ul class="timeline">--}}
                    {{--<li>--}}
                        {{--<div class="timeline-panel">--}}
                            {{--<div class="timeline-heading">--}}
                                {{--<h4 class="timeline-title">Ouverture de la saison</h4>--}}
                                {{--<p><small class="text-muted"><i class="glyphicon glyphicon-calendar"></i> Le 14 avril</small></p>--}}
                            {{--</div>--}}
                            {{--<div class="timeline-body">--}}
                                {{--<p>Dés 18h, Apéro d'ouverture de la saison avec fondue (sur inscription)</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<div class="timeline-panel">--}}
                            {{--<div class="timeline-heading">--}}
                                {{--<h4 class="timeline-title">Cours 2016</h4>--}}
                                {{--<p><small class="text-muted"><i class="glyphicon glyphicon-calendar"></i> Le 18 avril</small></p>--}}
                            {{--</div>--}}
                            {{--<div class="timeline-body">--}}
                                {{--<p>Début de tous les cours 2016 (juniors à adultes) et démarrage du concours de la pyramide</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<div class="timeline-panel">--}}
                            {{--<div class="timeline-heading">--}}
                                {{--<h4 class="timeline-title">Tournois simple dames</h4>--}}
                                {{--<p><small class="text-muted"><i class="glyphicon glyphicon-calendar"></i> Du 30 avril au 1er mai</small></p>--}}
                            {{--</div>--}}
                            {{--<div class="timeline-body">--}}
                                {{--<p>Tournoi Simple Dames R5-R9</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<div class="timeline-panel">--}}
                            {{--<div class="timeline-heading">--}}
                                {{--<h4 class="timeline-title">Tournoi double mixte</h4>--}}
                                {{--<p><small class="text-muted"><i class="glyphicon glyphicon-calendar"></i> Du 16 au 19 juin</small></p>--}}
                            {{--</div>--}}
                            {{--<div class="timeline-body">--}}
                                {{--<p>Tournoi Double Mixte "Les Pirates"</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<div class="timeline-panel">--}}
                            {{--<div class="timeline-heading">--}}
                                {{--<h4 class="timeline-title">Stage juniors</h4>--}}
                                {{--<p><small class="text-muted"><i class="glyphicon glyphicon-calendar"></i> Du 4 au 8 juillet</small></p>--}}
                            {{--</div>--}}
                            {{--<div class="timeline-body">--}}
                                {{--<p>Stage d'été Juniors</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</li>--}}
                {{--</ul>--}}
                {{--</p>--}}
           {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{-- {!! Html::script('/ajax/disponibility.js') !!} --}}
@endsection
