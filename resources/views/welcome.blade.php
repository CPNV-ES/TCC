@extends('layouts.app')

@section('content')

  {!! Html::script('/js/visualcalendar.js') !!}


    <div class="background"></div>
    <div class="row">
        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
            <div class="box">
                <div class="box-icon">
                    <span>{{$courts[0]->name}}</span>
                </div>
                @foreach($courts as $key=>$court)
                  @if ($court->id == 1)
                    <div id="tab-{{$court->id}}"  class="tab-pane fade in active">
                    </div>
                    <script>
                        var vc{{$court->id}} = new VisualCalendar();
                        vc{{$court->id}}.config={!! \App\Reservation::getVcConfigJSON(1 ,$court->id, 'div#tab-'.$court->id) !!};
                        vc{{$court->id}}.build();
                        vc{{$court->id}}.generate();
                        vc{{$court->id}}.ev.onSelect = function(elem, datetime){
                            var myDate = parseDate(datetime);
                            $(".fkCourt").val({{$court->id}});
                            $("#modal-resume").html('Réservation du court N° '+$("#fkCourt").val()+' le ' +myDate.getUTCDate().toStringN(2)+ "-" + (myDate.getMonth() + 1).toStringN(2) + "-" + myDate.getFullYear()+' à '+myDate.getHours().toStringN(2) + ":" + myDate.getMinutes().toStringN(2) );
                            $(".reservation-date").val(datetime+':00');
                            $('#reservation-modal').modal('show');
                        }
                        vc{{$court->id}}.ev.onPlanifClick=function(elem, planif){
                            var myDate = parseDate(planif.datetime);
                            $("#date-del-reserv").val(planif.datetime);
                            $("#id-del-reserv").val(elem.getAttribute('data-description'));
                            $("#court-del-reserv").val("{{ $court->id }}");
                            $("#modal-del-resume").html('Supprimer la réservation du ' +myDate.getUTCDate().toStringN(2)+ "-" + (myDate.getMonth() + 1).toStringN(2) + "-" + myDate.getFullYear()+' à '+myDate.getHours().toStringN(2) + ":" + myDate.getMinutes().toStringN(2) );
                            $('#del-resevation-modal').modal('show');
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
                    <span>{{$courts[1]->name}}</span>
                </div>
                @foreach($courts as $key=>$court)
                  @if ($court->id == 2)
                    <div id="tab-{{$court->id}}" class="tab-pane fade in active">
                    </div>
                    <script>
                        var vc{{$court->id}} = new VisualCalendar();
                        vc{{$court->id}}.config={!! \App\Reservation::getVcConfigJSON(1 ,$court->id, 'div#tab-'.$court->id) !!};
                        vc{{$court->id}}.build();
                        vc{{$court->id}}.generate();
                        vc{{$court->id}}.ev.onSelect = function(elem, datetime){
                            var myDate = parseDate(datetime);
                            $(".fkCourt").val({{$court->id}});
                            $("#modal-resume").html('Réservation du court N° '+$("#fkCourt").val()+' le ' +myDate.getUTCDate().toStringN(2)+ "-" + (myDate.getMonth() + 1).toStringN(2) + "-" + myDate.getFullYear()+' à '+myDate.getHours().toStringN(2) + ":" + myDate.getMinutes().toStringN(2) );
                            $(".reservation-date").val(datetime+':00');
                            $('#reservation-modal').modal('show');
                        }
                        vc{{$court->id}}.ev.onPlanifClick=function(elem, planif){
                            var myDate = parseDate(planif.datetime);
                            $("#date-del-reserv").val(planif.datetime);
                            $("#id-del-reserv").val(elem.getAttribute('data-description'));
                            $("#court-del-reserv").val("{{ $court->id }}");
                            $("#modal-del-resume").html('Supprimer la réservation du ' +myDate.getUTCDate().toStringN(2)+ "-" + (myDate.getMonth() + 1).toStringN(2) + "-" + myDate.getFullYear()+' à '+myDate.getHours().toStringN(2) + ":" + myDate.getMinutes().toStringN(2) );
                            $('#del-resevation-modal').modal('show');
                        }
                    </script>
                  @endif
                @endforeach
            </div>
        </div>
    </div>

    @include('booking.reserve_modal')
    @include('booking.delete_modal')


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
            font-size: 12px;
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
        .simple2{background-color: #FDE994;}
        .vc-passed{background-color: #e7e7e7;}
        .vc-passed:hover{cursor: not-allowed;}
        .vc-own-planif{background-color: #FDE994;}
        .quotidienne{background-color: #ffb2c1;}
        .hebdomadaire{background-color: #88e6ff;}
    </style>

@endsection
