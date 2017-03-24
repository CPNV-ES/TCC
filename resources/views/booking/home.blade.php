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
    </div>
    <ul class="nav nav-tabs">
        @foreach($courts as $key=>$court)
            <li class="@if( Session::has('currentCourt')) @if(Session::get('currentCourt')== $court->id){{'active'}}@endif @elseif($key == 0) active @endif" ><a data-toggle="tab" href="#tab-{{$court->name}}">{{$court->name}}</a></li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($courts as $key=>$court)
            <div id="tab-{{$court->name}}"  class="tab-pane fade @if( Session::has('currentCourt')) @if(Session::get('currentCourt')== $court->id){{'in active'}}@endif @elseif($key == 0) in active @endif">
            </div>
            <script>
                var vc{{$court->name}} = new VisualCalendar();
                vc{{$court->name}}.config={!! \App\Reservation::getVcConfigJSON($court->id, 'div#tab-'.$court->name) !!};
                vc{{$court->name}}.build();
                vc{{$court->name}}.generate();
                vc{{$court->name}}.ev.onSelect=function(elem, datetime){
                    var myDate = new Date(datetime.replace(' ','T'));
                    console.log(myDate);
                    $(".fkCourt").val({{$court->id}});
                    $("#modal-resume").html('Réservation du court N° '+$("#fkCourt").val()+' le ' +myDate.getUTCDate().toStringN(2)+ "-" + (myDate.getMonth() + 1).toStringN(2) + "-" + myDate.getFullYear()+' à '+myDate.getHours().toStringN(2) + ":" + myDate.getMinutes().toStringN(2) );
                    $(".reservation-date").val(datetime+':00');
                    console.log(datetime);
                    $('#reservation-modal').modal('show');
                }
            </script>
        @endforeach


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
                              <input type="hidden" class="fkCourt" name="fkCourt" value=1>
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
                              <input type="hidden" class="fkCourt" name="fkCourt" value=1>

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
</div>
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
            background-color: #dae9f1;
        }
        .vc-clickable{
            cursor: pointer;
            transition-duration: 250ms;
        }
        .vc-clickable:not(.vc-selected):hover{
            background-color: #eee;
        }
        .vc-nomoreselect .vc-clickable{
            cursor: default;
        }
        .aucune{background-color: #aaf;}
        .simple2{background-color: #afa;}

        .push-to-bottom {
           margin-top: 20px;
        }
    </style>
        <script>
            hasErros = false;

            @if($errors->has('invitLastname')|| $errors->has('invitFirstname')) {{"hasErrors= true;"}} @else {{"hasErrors=false;"}} @endif
            if(hasErrors)
            {
                chooseDate = new Date("{{old('dateTimeStart')}}");
                $("#modal-resume").html('Réservation du court N° '+$("#fkCourt").val()+' le ' +chooseDate.getUTCDate().toStringN(2)+ "-" + (chooseDate.getMonth() + 1).toStringN(2) + "-" + chooseDate.getFullYear()+' à '+chooseDate.getHours().toStringN(2) + ":" + chooseDate.getMinutes().toStringN(2) );

                /*can use chooseDate because of the format*/
                $(".reservation-date").val("{{old('dateTimeStart')}}");
                $('[href="#nonmember-invite"]').tab('show');
                $("#reservation-modal").modal('show');

            }


            document.querySelector('#btn-reserver-member-invite').addEventListener('click', function(){
                VERIF.verifForm('reservation-member-invite-form',function(isOk){
                if(isOk) document.forms["reservation-member-invite-form"].submit();
            });
            });
        </script>


{{--    {!! Html::script('/ajax/calendar.js') !!}
    {!! Html::script('/ajax/booking.js') !!}--}}
@endsection
