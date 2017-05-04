@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1>Réservation Staff</h1>
        @if (Session::has('successMessage'))
            <div class="alert alert-success">
                {{ Session::get('successMessage') }}

            </div>
        @endif

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
                                    <div class="form-group @if($errors->has('hour-start')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label">
                                            Heure début*:
                                        </label>
                                        <select class="form-control" name="hour-start">
                                            <option value="8" @if(old('hour-start') == 8) selected @endif >08:00</option>
                                            <option value="9" @if(old('hour-start') == 9) selected @endif >09:00</option>
                                            <option value="10" @if(old('hour-start') == 10) selected @endif>10:00</option>
                                            <option value="11" @if(old('hour-start') == 11) selected @endif>11:00</option>
                                            <option value="12" @if(old('hour-start') == 12) selected @endif>12:00</option>
                                            <option value="13" @if(old('hour-start') == 13) selected @endif>13:00</option>
                                            <option value="14" @if(old('hour-start') == 14) selected @endif>14:00</option>
                                            <option value="15" @if(old('hour-start') == 15) selected @endif>15:00</option>
                                            <option value="16" @if(old('hour-start') == 16) selected @endif>16:00</option>
                                            <option value="17" @if(old('hour-start') == 17) selected @endif>17:00</option>
                                            <option value="18" @if(old('hour-start') == 18) selected @endif>18:00</option>
                                            <option value="19" @if(old('hour-start') == 19) selected @endif>19:00</option>
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
                                              <option value="9" @if(old('hour-end') == 9) selected @endif >09:00</option>
                                              <option value="10" @if(old('hour-end') == 10) selected @endif>10:00</option>
                                              <option value="11" @if(old('hour-end') == 11) selected @endif>11:00</option>
                                              <option value="12" @if(old('hour-end') == 12) selected @endif>12:00</option>
                                              <option value="13" @if(old('hour-end') == 13) selected @endif>13:00</option>
                                              <option value="14" @if(old('hour-end') == 14) selected @endif>14:00</option>
                                              <option value="15" @if(old('hour-end') == 15) selected @endif>15:00</option>
                                              <option value="16" @if(old('hour-end') == 16) selected @endif>16:00</option>
                                              <option value="17" @if(old('hour-end') == 17) selected @endif>17:00</option>
                                              <option value="18" @if(old('hour-end') == 18) selected @endif>18:00</option>
                                              <option value="19" @if(old('hour-end') == 19) selected @endif>19:00</option>
                                              <option value="20" @if(old('hour-end') == 20) selected @endif>20:00</option>
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
            <td>{{$reservation->type_reservation->type}}</td>
          </tr>
          @endforeach
        </table>

        @endif
        <h4>Réservations effectuées</h4>
        <table class="table" style="text-align:center;">
          <tr>
            <th>Date et heure</th>
            <th>Court</th>
            <th>Type de réservation</th>
            <th>Action</th>
          </tr>

          @if(count($ownReservations)> 0)
            @foreach($ownReservations as $reservation)
            <tr>
              <td>{{ date('H:i d-m-Y', strtotime($reservation->dateTimeStart)) }}</td>
              <td>{{ $reservation->court->name }}</td>
              <td>{{$reservation->type_reservation->type}}</td>
            </tr>
            @endforeach
          @else
              <tr><td colspan="4">Aucune réservation</td><tr>
          @endif
        </table>


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
