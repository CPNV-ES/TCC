@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
        <h1>Réservation Staff</h1>
        @if (Session::has('successMessage'))
            <div class="alert alert-success">
                {{ Session::get('successMessage') }}

            </div>
        @endif
        </div>

        <a class="btn btn-primary" data-toggle="modal" data-target="#reservation-modal">Créer une réservation</a>
        <!-- Modal Reservation -->
        <div class="modal fade" tabindex="-1" role="dialog" id="reservation-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Réservation</h4>
                    </div>
                    <div class="modal-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" @if(session()->has('showSimpleResForm')) class="active" @endif><a href="#simple" aria-controls="home" role="tab" data-toggle="tab">Réservation simple</a></li>
                            <li role="presentation" @if(session()->has('showMultResForm')) class="active" @endif><a href="#multiple" aria-controls="profile" role="tab" data-toggle="tab">Réservation multiple</a></li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane @if(session()->has('showSimpleResForm') || !session()->has('showMultResForm')) active @endif" id="simple">
                                <form method="post" role="form" method="POST" action="{{ url('/staff_booking')}}" name="simple-reservation-form">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <div class="form-group @if($errors->has('title-simple-res')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label">
                                            Libellé*:</label>
                                        <input class="form-control"  type="text" name="title-simple-res" id="title-simple-res" value="{{old('title-simple-res')}}" data-verif="required|text"/>
                                        @if ($errors->has('title-simple-res'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('title-simple-res') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('datetime-start')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label">
                                            Date*:</label>
                                        <input class="form-control datetime-picker"  type="date" name="datetime-start" id="datetime-start" value="{{old('datetime-start')}}" data-verif="required|date_time"/>
                                        @if ($errors->has('datetime-start'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('datetime-start') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('court')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label">
                                            Choix du court*:</label>
                                        <select class="form-control" name="court">
                                            @foreach($courts as $court)
                                                <option value="{{$court->id}}"> {{$court->name}} </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('court'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('court') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group push-to-bottom ">
                                        <button type="button" id="btn-simple-reservation" class="btn btn-success btn-block" name="btn-reserver">
                                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                            Réserver

                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane @if(session()->has('showMultResForm')) active @endif" id="multiple">
                                <form method="post" role="form" method="POST" action="{{ url('/staff_booking')}}" name="multiple-reservation-form">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <div class="form-group @if($errors->has('title-multiple-res')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label">
                                            Libellé*:</label>
                                        <input class="form-control"  type="text" name="title-multiple-res" id="title-multiple-res" value="{{old('title-multiple-res')}}" data-verif="required|text"/>
                                        @if ($errors->has('title-multiple-res'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('title-multiple-res') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('hour-start')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label">
                                            Heure début*:
                                        </label>
                                        @php($start_hour = new DateTime($config->courtOpenTime))
                                        @php($end_hour = new \DateTime($config->courtCloseTime))

                                        <select class="form-control" name="hour-start">
                                            {{--Loop between start and end hour configure in configs table--}}
                                            @for ($i = $start_hour->format('G'); $i < $end_hour->format('G'); $i++)
                                                <option value="{{$i}}" @if(old('hour-start') == $i) selected @endif >{{$start_hour->format('H:i')}}</option>
                                                @php($start_hour->modify('+1 hour'))
                                            @endfor
                                        </select>
                                        @if ($errors->has('hour-start'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('hour-start') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('hour-end')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label">
                                            Heure fin*:
                                          </label>
                                          <select class="form-control" name="hour-end">
                                              @php($start_hour = new DateTime($config->courtOpenTime))
                                              @for ($i = $start_hour->format('G'); $i < $end_hour->format('G'); $i++)
                                                  @php($start_hour->modify('+1 hour'))
                                                  <option value="{{$i+1}}" @if(old('hour-end') == $i) selected @endif >{{$start_hour->format('H:i')}}</option>

                                              @endfor
                                          </select>
                                        @if ($errors->has('hour-end'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('hour-end') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group @if($errors->has('date-start')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label">
                                            Date début*:</label>
                                        <input class="form-control date-picker" type="text" name="date-start" id="date-start" readonly  value="{{old('date-start')}}" data-verif="required"/>
                                        @if ($errors->has('date-start'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('date-start') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('date-end')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label">
                                            Date fin*:</label>
                                        <input class="form-control date-picker" type="text" name="date-end" id="date-end" readonly  value="{{old('date-end')}}" data-verif="required"/>
                                        @if ($errors->has('date-end'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('date-end') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('type-reservation')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label">
                                            Type de réservation (fréquence)*:</label>
                                        <select class="form-control" name="type-reservation">
                                            <option value="2">Quotidienne</option>
                                            <option value="3">Hebodmadaire</option>
                                            <option value="4">Menusel</option>
                                        </select>
                                        @if ($errors->has('type-reservation'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('type-reservation') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('court')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label">
                                            Choix du court*:</label>
                                        <select class="form-control" name="court">
                                            @foreach($courts as $court)
                                                <option value="{{$court->id}}"> {{$court->name}} </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('court'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('court') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group push-to-bottom ">
                                        <button type="button" id="btn-multiple-reservation" class="btn btn-success btn-block" name="btn-reserver">
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

        @if (session()->has('conflictReservations') && count(session('conflictReservations')) > 0)

        <h4>Réservations conflictuelles</h4>
        <table class="table" style="text-align:center;">
          <tr>
            <th>Date et heure</th>
            <th>Court</th>
            <th>Type de réservation</th>


          </tr>
          @foreach(session('conflictReservations') as $reservation)
          <tr>
            <td>{{ date('H:i d-m-Y', strtotime($reservation->dateTimeStart)) }}</td>
            <td>{{ $reservation->court->name }}</td>
            <td>{{ $reservation->type_reservation->type }}</td>
          </tr>
          @endforeach
        </table>

        @endif
        <div class="row spacer">
        <h4>Réservations effectuées</h4>
        <table class="table" style="text-align:center;">
          <tr>
            <th>Libellé</th>
            <th>Date et heure</th>
            <th>Court</th>
            <th>Type de réservation</th>
            <th>Action</th>
          </tr>

          @if(count($ownReservations)> 0)
            @foreach($ownReservations as $reservation)
            <tr>
              <td> {{ $reservation->title }}</td>
              <td> {{ date('H:i d-m-Y', strtotime($reservation->dateTimeStart)) }}</td>
              <td> {{ $reservation->court->name }}</td>
              <td> {{$reservation->type_reservation->type}}</td>
              <td> <button class="btn btn-primary">Modifier</button></td>
            </tr>
            @endforeach
          @else
              <tr><td colspan="5">Aucune réservation</td><tr>
          @endif
        </table>

    </div>
    </div>
    <script>
        var hasError = false;
        @if(session()->has('showMultResForm') || session()->has('showSimpleResForm')) {{"hasError = true;"}} @else {{"hasError = false;"}} @endif
        if(hasError)  $("#reservation-modal").modal('show');

        $('.date-picker').datepicker({
          format: 'dd-mm-yyyy',
          autoclose: true,
          todayHighlight: true,
          language: "fr"
        });
        date = new Date();
        $('.datetime-picker').datetimepicker({
             format: 'dd.mm.yyyy hh:00',
             startDate: date.toISOString().substr(0, 19),
             minView: 'day', //to choose hour and not the minutes too
             autoclose: true

         });

        document.querySelector('#btn-simple-reservation').addEventListener('click', function(){
            VERIF.verifForm('simple-reservation-form',function(isOk){
                if(isOk) document.forms["simple-reservation-form"].submit();
            });
        });
        document.querySelector('#btn-multiple-reservation').addEventListener('click', function(){
            VERIF.verifForm('multiple-reservation-form',function(isOk){
                if(isOk) document.forms["multiple-reservation-form"].submit();
            });
        });
    </script>
@endsection
