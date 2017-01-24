@extends('layouts.admin')

@section('title')
    Gestion des courts
    {{-- SFH: Display a return button if in edit mode --}}
    @if(!empty($singleCourt))
        <a type="button" class="btn btn-primary" href="/admin/config/courts">Retour</a>
    @endif
@endsection

@section('content')
    <div class="row">

        {{-- SFH: Added to informe the user if an action was successful or not --}}
        <div class="flash-message">
            @foreach (['danger', 'warning', 'success', 'info'] as $message)
                @if(Session::has('alert-' . $message))
                    <p class="alert alert-{{ $message }} fade in">
                        {{ Session::get('alert-' . $message) }}
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    </p>
                @endif
            @endforeach
        </div>
        {{-- End --}}

        {{-- SFH: Added simple display table for the courts --}}
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Interieur</th>
                    <th>Heure d'ouverture</th>
                    <th>Heure de fermeture</th>
                    <th>Fenêtre de réservation membre</th>
                    <th>Fenêtre de réservation non-membre</th>
                    <th>Options</th>
                </tr>
                </thead>
                <tbody>
                @foreach($courts as $court)
                    <tr>
                        <td>{{$court->name}}</td>
                        <td>{{$court->indor}}</td>
                        <td>{{$court->start_time}}</td>
                        <td>{{$court->end_time}}</td>
                        <td>{{$court->booking_window_member}}</td>
                        <td>{{$court->booking_window_not_member}}</td>
                        {{-- SFH: This zone is used for the 'edit' and 'delete' buttons --}}
                        <td class="option-zone">
                            <button class="btn btn-warning option" data-action="edit" data-url="/admin/config/courts/{{$court->id}}/edit">
                                <span class="fa fa-edit"></span>
                            </button>
                            {{-- SFH: Only methode found to call the 'destroy' methode in the controler. Trying to find a better way. --}}
                            <form class="delete" role="form" method="POST" action="/admin/config/courts/{{$court->id}}">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button class="btn btn-danger option" data-action="delete-court" data-court="{{$court->name}}">
                                    <span class="fa fa-trash"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{-- End --}}
    </div>

    <div class="row" align="center">
        {{-- SFH: Added to change the title if editing or adding --}}
        <h3>{{(!empty($singleCourt) ? 'Modifier' : 'Ajouter')}} un court</h3>
    </div>

    {{--
        SFH: Added the conditions in the 'value' fields.
        1) If the was an old value display it.
        2) If in edit mode display the data from the database.
        3) Display nothing.
    --}}
    <div class="row">
        {{-- SFH: Change the url if editing or adding --}}
        <form class="form-horizontal" name="courtForm" role="form" method="POST"
              action="{{ url('/admin/config/courts') . (!empty($singleCourt) ? '/' . $singleCourt->id : '') }}">

            {!! csrf_field() !!}
            {{-- SFH: Used to know which methode in the controller to call --}}
            @if(!empty($singleCourt))
                {!! method_field('PUT') !!}
            @endif

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="name">Nom</label>
                <div class="col-md-4">
                    <input id="name" type="text" class="form-control" name="name" data-verif="required|max_l:50" data-verif-group="courtCheck"
                           value="{{ (old('name') != '' ? old('name') : (!empty($singleCourt) ? $singleCourt->name : '')) }}">

                    @if ($errors->has('name'))
                        <p class="help-block">{{ $errors->first('name') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('indor') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="indor">Indor</label>
                {{-- SFH: Checks if its an indoor court --}}
                @php
                    $checked = '';
                    if (!empty($singleCourt)) {
                        if ($singleCourt->indor == 1) {
                            $checked = 'checked';
                        }
                    }
                @endphp

                <div class="col-md-4">
                    {{-- SFH: Hidden value is overridden if the checkbox if checked --}}
                    <input type="hidden" name="indor" value="0">
                    <input id="indor" type="checkbox" class="form-control" name="indor"
                           value="1" {{$checked}}>

                    @if ($errors->has('indor'))
                        <p class="help-block">{{ $errors->first('indor') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="start_time">Heure d'ouverture</label>
                <div class="col-md-4">
                    <input id="start_time" type="time" class="form-control" name="start_time" data-verif="required|time" data-verif-group="courtCheck"
                           value="{{ (old('start_time') != '' ? old('start_time') : (!empty($singleCourt) ? date_format(date_create($singleCourt->start_time), "H:i") : '')) }}">

                    @if ($errors->has('start_time'))
                        <p class="help-block">{{ $errors->first('start_time') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="end_time">Heure de fermeture</label>
                <div class="col-md-4">
                    <input id="end_time" type="time" class="form-control" name="end_time" data-verif="required|time|time_greater:start_time" data-verif-group="courtCheck"
                           value="{{ (old('end_time') != '' ? old('end_time') : (!empty($singleCourt) ? date_format(date_create($singleCourt->end_time), "H:i") : '')) }}">

                    @if ($errors->has('end_time'))
                        <p class="help-block">{{ $errors->first('end_time') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('booking_window_member') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="booking_window_member">Fenêtre de réservation membre</label>
                <div class="col-md-4">
                    <input id="booking_window_member" type="number" class="form-control" name="booking_window_member" data-verif="required|int_neg|min:1" data-verif-group="courtCheck"
                           value="{{ (old('booking_window_member') != '' ? old('booking_window_member') : (!empty($singleCourt) ? $singleCourt->booking_window_member : '')) }}">

                    @if ($errors->has('booking_window_member'))
                        <p class="help-block">{{ $errors->first('booking_window_member') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('booking_window_not_member') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="booking_window_not_member">Fenêtre de réservation non membre</label>
                <div class="col-md-4">
                    <input id="booking_window_not_member" type="number" class="form-control" name="booking_window_not_member" data-verif="required|int_neg|min:1" data-verif-group="courtCheck"
                           value="{{ (old('booking_window_not_member') != '' ? old('booking_window_not_member') : (!empty($singleCourt) ? $singleCourt->booking_window_not_member : '')) }}">

                    @if ($errors->has('booking_window_not_member'))
                        <p class="help-block">{{ $errors->first('booking_window_not_member') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group" align="center">
                <button id="btnCourtCheck" type="button" class="btn btn-primary" name="save">
                    {{-- SFH: Change button text if editing or adding --}}
                    {{ (!empty($singleCourt) ? 'Sauvegarder' : 'Ajouter') }}
                </button>
            </div>
        </form>
    </div>
@endsection