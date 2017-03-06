@extends('layouts.app')

@section('content')
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

    <div id="calendar" class="row"></div>
    <button data-toggle="modal" data-target="#reservation-modal" class="btn-make-reservation" value="2017-03-07 09:00:00">ajd 9:00</button>
    <button data-toggle="modal" data-target="#reservation-modal" class="btn-make-reservation" value="2017-03-07 10:00:00">ajd 10:00</button>
    <button data-toggle="modal" data-target="#reservation-modal" class="btn-make-reservation" value="2017-03-07 11:00:00">ajd 11:00</button>


    <!-- Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="reservation-modal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form method="post" role="form" method="POST" action="{{ url('/booking')}}">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Réservation</h4>
              </div>
              <div class="modal-body">
                  <div id="modal-resume" class="notice notice-info">
                      <p>Réservation du court .. le 'date' </p>
                  </div>
                  Choisissez votre adversaire
                  {{ csrf_field() }}
                  {{ method_field('POST') }}

                  <input type="hidden" id="reservation-date"name="dateTimeStart">
                  <input type="hidden" id="fkCourt" name="fkCourt" value=1>
                  <select name="fkWithWho">
                    @foreach($membersList as $member)
                      <option value="{{$member->id}}">{{$member->firstname}} {{$member->lastname}}</option>
                    @endforeach
                  </select>

                  <div id="modal-panel"></div>
                  <div id="modal-content"></div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                  <button type="submit" id="booking" class="btn btn-success">
                      <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                      Réserver
                  </button>
              </div>
          </form>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


<script>
  $(".btn-make-reservation").click(function(){
    var myDate = new Date($(this).val());
    $("#modal-resume").html('Réservation du court N° '+$("#fkCourt").val()+' le ' +myDate.getDate()+ "-" + (myDate.getMonth() + 1) + "-" + myDate.getFullYear()+' à '+myDate.getHours() + ":" + myDate.getMinutes() + ":" + myDate.getSeconds());
    $("#reservation-date").val($(this).val());
  });
</script>


{{--    {!! Html::script('/ajax/calendar.js') !!}
    {!! Html::script('/ajax/booking.js') !!}--}}
@endsection
