@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
        <h1>Réservation Staff</h1>
        @if (Session::has('successMessage'))
            <div class="alert alert-success">
                {{ Session::get('successMessage') }}
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            </div>
        @endif
        @if (Session::has('errorMessage'))
            <div class="alert alert-danger">
                {{ Session::get('errorMessage') }}
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
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
                        <h4 class="modal-title" id="myModalLabel">Réservation Staff</h4>
                    </div>
                    <div class="modal-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" @if(session()->has('showSimpleResForm')) class="active" @elseif(!session()->has('showMultResForm')) class="active" @endif><a href="#simple" aria-controls="home" role="tab" data-toggle="tab">Réservation simple</a></li>
                            <li role="presentation" @if(session()->has('showMultResForm')) class="active" @endif><a href="#multiple" aria-controls="profile" role="tab" data-toggle="tab">Réservation multiple</a></li>
                        </ul>
                        <!-- Simple staff Reservation -->
                        <div class="tab-content">
                          <div class="pull-right"><span class="mandatory"></span> obligatoire</div>
                            <div role="tabpanel" class="tab-pane @if(session()->has('showSimpleResForm') || !session()->has('showMultResForm')) active @endif" id="simple">

                                <form method="post" role="form" method="POST" action="{{ url('/staff_booking')}}" name="simple-reservation-form">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <div class="form-group @if($errors->has('title-simple-res')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label mandatory">
                                            Libellé
                                          </label>
                                        <input class="form-control"  type="text" name="title-simple-res" id="title-simple-res" value="{{old('title-simple-res')}}" data-verif="required|text"/>
                                        @if ($errors->has('title-simple-res'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('title-simple-res') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group @if($errors->has('hour-start')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label mandatory">
                                            Heure début
                                        </label>
                                        @php($start_hour = new DateTime($config->courtOpenTime))
                                        @php($end_hour = new \DateTime($config->courtCloseTime))

                                        <select class="form-control" name="hour-start-simple-res">
                                            {{--Loop between start and end hour configure in configs table--}}
                                            @for ($i = $start_hour->format('G'); $i < $end_hour->format('G'); $i++)
                                                <option value="{{$i}}" @if(old('hour-start-simple-res') == $i) selected @endif >{{$start_hour->format('H:i')}}</option>
                                                @php($start_hour->modify('+1 hour'))
                                            @endfor
                                        </select>
                                        @if ($errors->has('hour-start-simple-res'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('hour-start-simple-res') }}</strong>
                                            </span>
                                        @endif
                                    </div>


                                    <div class="form-group @if($errors->has('date-start-simple-res')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label mandatory">
                                            Date
                                          </label>
                                        <input class="form-control date-picker"  type="text" name="date-start-simple-res" id="date-start-simple-res" readonly value="{{old('date-start-simple-res')}}"
                                         data-verif="required"
                                         data-verif-on-blur="false"/>
                                        @if ($errors->has('date-start-simple-res'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('date-start-simple-res') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('court-simple-res')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label mandatory">
                                            Choix du court
                                          </label>
                                        <select class="form-control" name="court-simple-res">
                                            @foreach($courts as $court)
                                                <option value="{{$court->id}}"> {{$court->name}} </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('court-simple-res'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('court-simple-res') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group push-to-bottom ">
                                        <button type="button" id="btn-simple-reservation" class="btn btn-success btn-block" name="btn-reserver">
                                          <i class="fa fa-check" aria-hidden="true"></i>
                                            Réserver
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- Multiple staff reservations -->
                            <div role="tabpanel" class="tab-pane @if(session()->has('showMultResForm')) active @endif" id="multiple">
                                <form method="post" role="form" method="POST" action="{{ url('/staff_booking')}}" name="multiple-reservation-form">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <div class="form-group @if($errors->has('title-multiple-res')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label mandatory">
                                            Libellé
                                          </label>
                                        <input class="form-control"  type="text" name="title-multiple-res" id="title-multiple-res" value="{{old('title-multiple-res')}}" data-verif="required|text"/>
                                        @if ($errors->has('title-multiple-res'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('title-multiple-res') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('hour-start-multiple-res')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label mandatory">
                                            Heure début
                                        </label>
                                        @php($start_hour = new DateTime($config->courtOpenTime))
                                        @php($end_hour = new \DateTime($config->courtCloseTime))

                                        <select id="hour-start-dropdown" class="form-control" name="hour-start-multiple-res">
                                            {{--Loop between start and end hour configure in configs table--}}
                                            @for ($i = $start_hour->format('G'); $i < $end_hour->format('G'); $i++)
                                                <option value="{{$i}}" @if(old('hour-start-multiple-res') == $i) selected @endif >{{$start_hour->format('H:i')}}</option>
                                                @php($start_hour->modify('+1 hour'))
                                            @endfor
                                        </select>
                                        @if ($errors->has('hour-start-multiple-res'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('hour-start-multiple-res') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('hour-end-multiple-res')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label mandatory">
                                            Heure fin
                                          </label>
                                          <select id="hour-end-dropdown" class="form-control" name="hour-end-multiple-res">
                                              @php($start_hour = new DateTime($config->courtOpenTime))
                                              @for ($i = $start_hour->format('G'); $i < $end_hour->format('G'); $i++)
                                                  @php($start_hour->modify('+1 hour'))
                                                  <option value="{{$i+1}}" @if(old('hour-end-multiple-res') == $i+1) selected @endif >{{$start_hour->format('H:i')}}</option>

                                              @endfor
                                          </select>
                                        @if ($errors->has('hour-end-multiple-res'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('hour-end-multiple-res') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group @if($errors->has('date-start-multiple-res')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label mandatory">
                                            Date début
                                          </label>
                                        <input class="form-control date-picker" type="text" name="date-start-multiple-res" id="date-start-multiple-res" readonly  value="{{old('date-start-multiple-res')}}"
                                          data-verif="required"
                                          data-verif-on-blur="false"
                                        />
                                        @if ($errors->has('date-start-multiple-res'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('date-start-multiple-res') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('date-end-multiple-res')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label mandatory">
                                            Date fin
                                          </label>
                                        <input class="form-control date-picker" type="text" name="date-end-multiple-res" id="date-end-multiple-res" readonly  value="{{old('date-end-multiple-res')}}"
                                            data-verif="required"
                                            data-verif-on-blur="false"
                                          />
                                        @if ($errors->has('date-end-multiple-res'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('date-end-multiple-res') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('type-reservation')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label mandatory">
                                            Type de réservation (fréquence)
                                          </label>
                                        <select class="form-control" name="type-reservation">
                                            <option value="2">Quotidienne</option>
                                            <option value="3">Hebodmadaire</option>
                                        </select>
                                        @if ($errors->has('type-reservation'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('type-reservation') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('court-multiple-res')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label mandatory">
                                            Choix du court
                                          </label>
                                        <select class="form-control" name="court-multiple-res">
                                            @foreach($courts as $court)
                                                <option value="{{$court->id}}"> {{$court->name}} </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('court-multiple-res'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('court-multiple-res') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group push-to-bottom ">
                                        <button type="button" id="btn-multiple-reservation" class="btn btn-success btn-block" name="btn-reserver">
                                          <i class="fa fa-check" aria-hidden="true"></i>
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
            <td>{{ ucfirst($reservation->type_reservation->type) }}</td>
          </tr>
          @endforeach
        </table>

        @endif
        <div class="row spacer">
        <h4>Réservations effectuées</h4>
        <button class="btn btn-danger" id="delete-selection-reservation" data-csrf="{{csrf_token()}}">Supprimer la sélection</button>
        <table class="table table-striped" style="text-align:center;" id="future-reservation">
            <thead>
                <tr>
                    <th>Sélection</th>
                    <th>Libellé</th>
                    <th>Date et heure</th>
                    <th>Court</th>
                    <th>Type de réservation</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @if(count($ownReservations) > 0)
                @foreach($ownReservations as $reservation)
                    <tr>
                        <td>
                            <div class="form-group @if($errors->has('date-start-multiple-res')) {{'has-error'}} @endif">
                                <input type="checkbox" class="form-control selectedReservations" name="selectedReservations[]" value="{{$reservation->id}}" />
                            </div>
                        </td>
                        <td> {{ $reservation->title }}</td>
                        <td> {{ date('d.m.Y H:i', strtotime($reservation->dateTimeStart)) }}</td>
                        <td> {{ $reservation->court->name }}</td>
                        <td> {{ ucfirst($reservation->type_reservation->type) }}</td>
                        <td class="option-zone">
                            <!-- <button class="btn btn-warning option" data-action="edit" >
                                <i class="fa fa-edit" aria-hidden="true"></i>
                            </button> -->
                            <form class="delete" role="form" method="POST" action="/staff_booking/{{$reservation->id}}">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button class="btn btn-danger option" data-action="delete-reservation" data-court="{{$court->title}}">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="6">Aucune réservation future</td><tr>
            @endif
            @if (count($oldReservations) > 0)
              @foreach($oldReservations as $reservation)
                <tr class="old-reservations" style="display:none;">
                  <td>-</td>
                  <td> {{ $reservation->title }}</td>
                  <td> {{ date('d.m.Y H:i', strtotime($reservation->dateTimeStart)) }}</td>
                  <td> {{ $reservation->court->name }}</td>
                  <td> {{ ucfirst($reservation->type_reservation->type) }}</td>
                  <td class="option-zone">-</td>
                </tr>
              @endforeach
              <tr>
                <td colspan="6">
                  <button class="btn btn-primary" id="btnOldReservation" data-show="false" >Afficher les anciennes réservations</button>
                </td>
              <tr>
            @endif
            </tbody>
        </table>

    </div>
    </div>
    <script>
        //if a error occurs we display back the modal
        $(document).ready(function()
        {
            var hasError = false;
            @if(session()->has('showMultResForm') || session()->has('showSimpleResForm')) {{"hasError = true;"}} @else {{"hasError = false;"}} @endif
            if(hasError)  $("#reservation-modal").modal('show');

            //datepicker configuration
            $('.date-picker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                language: "fr"
            });

            $("#hour-start-dropdown option:selected").each(function(){
                disabledPastHour(parseInt($(this).val()));
            });
            //This function is used to disable the endhour that are before the starthour
            function disabledPastHour(startHour)
            {
                $("#hour-end-dropdown option").each(function(){
                    var endHour = parseInt($(this).val());
                    if(startHour < endHour) $(this).attr('disabled', false);
                    else $(this).attr('disabled', true);
                });
            }

            //if the hour start is after the end hour we set the end hour one hour after start hour.
            $("#hour-start-dropdown").change(function(){
                $("#hour-start-dropdown option:selected" ).each(function() {
                    var startHour = parseInt($(this).val());
                    $("#hour-end-dropdown option:selected").each(function(){
                        var endHour = parseInt($(this).val());
                        if(startHour >= endHour) {
                            var nextHour = startHour + 1;
                            $("#hour-end-dropdown").val(nextHour);
                        }
                    });
                    disabledPastHour(startHour);
                });
            });

            //if we click on a row of table the checkbox of the row we checked it.
            $(".selectedReservations").click(function(){
                toggleChkBox(this);
            });
            $("table tr").click(function()
            {
                chkBox = $(".selectedReservations", this);
                toggleChkBox(chkBox);
            });
            function toggleChkBox(checkbox)
            {
                $(checkbox).prop("checked", !$(checkbox).prop("checked"));
            }
            //when we click on the button "delete-selection-reservation"
            $("#delete-selection-reservation").click(function(){
                var aryReservations = [];
                $("#future-reservation input:checkbox:checked").each(function(){
                    aryReservations.push($(this).val());
                });
                if(aryReservations.length > 0)
                {
                    var url = '/staff_booking/'+aryReservations.join(',');
                    var csrfToken = $("meta[name=csrf-token]").attr("content");
                    generateAndSendDeleteForm(url, csrfToken);

                }
            });

            //display old reservations
            $("#btnOldReservation").click(function(){
                $(".old-reservations").toggle();
                if($("#btnOldReservation").data("show") == false) $("#btnOldReservation").data("show", true).text("Cacher les anciennes réservations");
                else $("#btnOldReservation").text("Afficher les anciennes réservations").data("show", false);
            });

            //make verifJS then send the form to the backend
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
        });
    </script>
@endsection
