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
                            <li role="presentation" class="active"><a href="#simple" aria-controls="home" role="tab" data-toggle="tab">Réservation simple</a></li>
                            <li role="presentation"><a href="#multiple" aria-controls="profile" role="tab" data-toggle="tab">Réservation multiple</a></li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="simple">
                                <form method="post" role="form" method="POST" action="{{ url('/staff_booking')}}" name="simple-reservation-form">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <div class="form-group @if($errors->has('datetime-start')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label">
                                            Date*:</label>
                                        <input class="form-control datetime-picker"  type="date" name="datetime-start" id="datetime-start" value="{{old('date-start')}}" data-verif="required|date_time"/>
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
                            <div role="tabpanel" class="tab-pane" id="multiple">
                                <form method="post" role="form" method="POST" action="{{ url('/staff_booking')}}" name="simple-reservation-form">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <div class="form-group @if($errors->has('datetime-start')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label">
                                            Date début*:</label>
                                        <input class="form-control datetime-picker" type="text" name="datetime-start" id="datetime-start" value="{{old('date-start')}}" data-verif="required|date_time"/>
                                        @if ($errors->has('datetime-start'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('datetime-start') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('datetime-end')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label">
                                            Date fin*:</label>
                                        <input class="form-control datetime-picker" type="text" name="datetime-end" id="datetime-end" value="{{old('date-end')}}" data-verif="required|date_time"/>
                                        @if ($errors->has('datetime-end'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('datetime-end') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('court')) {{'has-error'}} @endif">
                                        <label for="recipient-name" class="control-label">
                                            Type de réservation (fréquence)*:</label>
                                        <select class="form-control" name="court">
                                            <option value="0">Quotidienne</option>
                                            <option value="0">Menusel</option>
                                            <option value="0">Hebodmadaire</option>
                                            <option value="0">Quotidienne</option>

                                        </select>
                                        @if ($errors->has('court'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('court') }}</strong>
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
    <script>
        var hasError = false;
        @if($errors->has('datetime-start') || $errors->has('court')) {{"hasError = true;"}} @else {{"hasError = false;"}} @endif
        if(hasError)  $("#reservation-modal").modal('show');

        date = new Date();
        $('.datetime-picker').datetimepicker({
            hourMin: 8,
            hourMax: 16,
            format: 'dd.mm.yyyy hh:00',
            startDate: date.toISOString().substr(0, 19),
            minView: 'day', //to choose hour
            autoclose: true
        });

        document.querySelector('#btn-simple-reservation').addEventListener('click', function(){
            VERIF.verifForm('simple-reservation-form',function(isOk){
                if(isOk) document.forms["simple-reservation-form"].submit();
            });
        });
    </script>
@endsection
