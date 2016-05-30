@extends('layouts.admin')

@section('title')
    Gestion des courts
@endsection

@section('content')
    <div class="row">
    <div id="jqxcourts"></div>
    </div>

    <div class="row" align="center"><h3>Ajouter un court</h3></div>
    <br />
    <div class="row">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/config/courts') }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Nom</label>

                <div class="col-md-4">
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">

                    @if ($errors->has('name'))
                        <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('indor') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Indor</label>

                <div class="col-md-4">
                    <input type="checkbox" class="form-control" name="indor" value="{{ old('indor') }}">
{{--                    {{ Form::checkbox('indor', old('indor'), null, ['type' => 'checkbox', 'class' => 'form-control']) }}--}}
                    @if ($errors->has('indor'))
                        <span class="help-block">
                        <strong>{{ $errors->first('indor') }}</strong>
                        </span>
                    @endif
                </div>
            </div>



            <div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Heure d'ouverture</label>

                <div class="col-md-4">
                    <input type="time" class="form-control" name="start_time" value="{{ old('start_time') }}">
                    @if ($errors->has('start_time'))
                        <span class="help-block">
                        <strong>{{ $errors->first('start_time') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Heure de fermeture</label>

                <div class="col-md-4">
                    <input type="time" class="form-control" name="end_time" value="{{ old('end_time') }}">

                    @if ($errors->has('end_time'))
                        <span class="help-block">
                        <strong>{{ $errors->first('end_time') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('booking_window_member') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Fenêtre de reservation membre</label>

                <div class="col-md-4">
                    <input type="number" class="form-control" name="booking_window_member" value="{{ old('booking_window_member') }}">

                    @if ($errors->has('booking_window_member'))
                        <span class="help-block">
                        <strong>{{ $errors->first('booking_window_member') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('booking_window_not_member') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Fenêtre de reservation non membre</label>

                <div class="col-md-4">
                    <input type="number" class="form-control" name="booking_window_not_member" value="{{ old('booking_window_not_member') }}">

                    @if ($errors->has('booking_window_not_member'))
                        <span class="help-block">
                        <strong>{{ $errors->first('booking_window_not_member') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group" align="center">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-btn">Ajouter</i>
                </button>
            </div>
        </form>
    </div>
@endsection