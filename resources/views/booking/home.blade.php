@extends('layouts.app')

@section('content')

    {!! Html::script('/js/visualcalendar.js') !!}
    <div class="container-fluid">
    <h1>Réservation</h1>
    @if(Session::has('successMessage'))
        <div class="alert alert-success">
            {{Session::get('successMessage')}}
        </div>
    @elseif(Session::has('errorMessage'))
        <div class="alert alert-danger">
            {{Session::get('errorMessage')}}
        </div>
    @endif
    <div style="width:510px;color:#000;margin:auto;">
        <iframe height="85" frameborder="0" width="510" scrolling="no" src="http://www.prevision-meteo.ch/services/html/chavornay-vd/horizontal" allowtransparency="true"></iframe>
        <a style="text-decoration:none;font-size:0.75em;" title="Prévisions à 4 jours pour Chavornay (VD)" href="http://www.prevision-meteo.ch/meteo/localite/chavornay-vd">Prévisions à 4 jours pour Chavornay (VD)</a>
    </div><br><br>

    @include('booking/own_reservs')

    <ul class="nav nav-tabs">
        @foreach($courts as $key=>$court)
            <li class="@if( Session::has('currentCourt')) @if(Session::get('currentCourt')== $court->id){{'active'}}@endif @elseif($key == 0) active @endif" ><a data-toggle="tab" href="#tab-{{$court->id}}">{{$court->name}}</a></li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach($courts as $key=>$court)
            <div id="tab-{{$court->id}}"  class="tab-pane fade @if( Session::has('currentCourt')) @if(Session::get('currentCourt')== $court->id){{'in active'}}@endif @elseif($key == 0) in active @endif">
            </div>
            <script>
                var vc{{$court->id}} = new VisualCalendar();
                vc{{$court->id}}.config={!! \App\Reservation::getVcConfigJSON($court->id, 'div#tab-'.$court->id) !!};
                vc{{$court->id}}.build();
                vc{{$court->id}}.generate();
                vc{{$court->id}}.ev.onSelect=function(elem, datetime){
                    var myDate = parseDate(datetime);
                    $(".fkCourt").val({{$court->id}});
                    $("#modal-resume").html('Réservation du court N° '+$("#fkCourt").val()+' le ' +myDate.getUTCDate().toStringN(2)+ "-" + (myDate.getMonth() + 1).toStringN(2) + "-" + myDate.getFullYear()+' à '+myDate.getHours().toStringN(2) + ":" + myDate.getMinutes().toStringN(2) );
                    $(".reservation-date").val(datetime+':00');
                    $('#reservation-modal').modal('show');
                }
                vc{{$court->id}}.ev.onPlanifClick=function(elem, planif){
                    console.log(elem, planif);
                    var myDate = parseDate(planif.datetime);
                    $("#id-del-reserv").val(planif.datetime);
                    $("#modal-del-resume").html('Supprimer la réservation du ' +myDate.getUTCDate().toStringN(2)+ "-" + (myDate.getMonth() + 1).toStringN(2) + "-" + myDate.getFullYear()+' à '+myDate.getHours().toStringN(2) + ":" + myDate.getMinutes().toStringN(2) );
                    $('#del-resevation-modal').modal('show');
                }
            </script>
        @endforeach

        @include('booking.reserve_modal')

        @if (Auth::check())
        <!-- Delete Modal Reservation -->
        <div class="modal fade" tabindex="-1" role="dialog" id="del-resevation-modal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel">Supprimer réservation</h4>
                  </div>
                  <div class="modal-body">
                      <div id="modal-del-resume" class="notice notice-info">
                            <p>Supression de la réservation du court .. le 'date' </p>
                      </div>
                      <form id="form-modal-delete" name="form-modal-delete" role="form" method="POST" action="{{ url('/booking/')}}"  >
                          {{ csrf_field() }}
                          {{ method_field('DELETE') }}
                          <input type="hidden" name="id-del-reserv" id="id-del-reserv" value=''>
                          <input type="hidden" name="baseurl-del-reserv" id="baseurl-del-reserv" value="{{ url('/booking/')}}">
                          <div class="form-group push-to-bottom ">
                              <button type="button" id="btn-del-res-modal" class="btn btn-danger btn-block" name="btn-delete">
                                  <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                  Supprimer
                              </button>
                              <script type="text/javascript">
                                  $("#btn-del-res-modal").click(function(){
                                      var form = document.forms['form-modal-delete'];
                                      form.action=$("#baseurl-del-reserv").val()+$("#id-del-reserv").val();
                                      form.submit();
                                  });
                              </script>
                          </div>
                        </form>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                  </div>
            </div>
          </div>
        </div>
        @endif


</div>
    <style media="screen">
        .tab-content .vc-cnt {
            box-sizing: border-box;
            width: 100%;
            overflow: auto;
            font-family: Arial,sans-serif;
            position: relative;
        }
        .tab-content table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
            box-sizing: border-box;
        }
        .tab-content td {
            border: 1px solid black;
            box-sizing: border-box;
        }
        .tab-content td.vc-table-head {
            background-color: #999;
            color: #eee;
        }
        .tab-content tr>td:nth-child(1) {
            color: #5a5a5a;
            font-size: 10px;
            vertical-align: top;
        }
        .tab-content tr>td.vc-table-head:nth-child(1) {
            color: #eee;
            font-size: 14px;
            vertical-align: middle;
        }
        .tab-content tr>td {
            border-bottom-color: #ccc;
            background-color: #fff;
            height: 23px;
        }
        .tab-content tr:last-child>td {
            border-bottom-color: black;
        }
        .tab-content tr>td:first-child {
            min-width: 70px;
            max-width: 70px;
            width: 70px;
        }
        .tab-content .vc-selected{
            background-color: #9cbcf7;
        }
        .tab-content .vc-clickable{
            cursor: pointer;
            transition-duration: 250ms;
        }
        .tab-content .vc-clickable:not(.vc-selected):hover{
            background-color: #c5d9ff;
        }
        .tab-content .vc-nomoreselect .vc-clickable{
            cursor: default;
        }
        .tab-content .aucune{background-color: #afa;}
        .tab-content .simple2{background-color: #ffa;}
        .tab-content .vc-passed{filter: brightness(0.9);}
        .tab-content .vc-own-planif{filter: hue-rotate(-60deg);}
    </style>

{{--    {!! Html::script('/ajax/calendar.js') !!}
    {!! Html::script('/ajax/booking.js') !!}--}}
@endsection
