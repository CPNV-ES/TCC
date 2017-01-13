@extends('layouts.admin')

@section('title')
    Gestion des courts
    {{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#crud">Ajouter</button>--}}
    @if(!empty($singleCourt))
        <a type="button" class="btn btn-primary" href="/admin/config/courts">Retour</a>
    @endif
@endsection

@section('content')
    <div class="row">
        <div id="message"></div>
        {{--<div id="jqxcourts"></div>--}}
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Interieur</th>
                    <th>Heure d'ouverture</th>
                    <th>Heure de fermeture</th>
                    <th>Fenêtre de réservation membre</th>
                    <th>Fenêtre de réservation non-membre</th>
                </tr>
                </thead>
                <tbody>
                @foreach($courts as $court)
                    <tr class="clickable-row" data-url="/admin/config/courts/{{$court->id}}">
                        <td>{{$court->name}}</td>
                        <td>{{$court->indor}}</td>
                        <td>{{$court->start_time}}</td>
                        <td>{{$court->end_time}}</td>
                        <td>{{$court->booking_window_member}}</td>
                        <td>{{$court->booking_window_not_member}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{--<div id="crud" class="modal fade" role="dialog">--}}
        {{--<div class="modal-dialog">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                    {{--<h3 class="modal-title">Ajouter un court</h3>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/config/courts') }}">--}}
                        {{--{!! csrf_field() !!}--}}

                        {{--<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">--}}
                            {{--<label class="col-md-4 control-label">Nom</label>--}}

                            {{--<div class="col-md-8">--}}
                                {{--<input type="text" class="form-control" name="name" value="{{ old('name') }}">--}}

                                {{--@if ($errors->has('name'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('name') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group{{ $errors->has('indor') ? ' has-error' : '' }}">--}}
                            {{--<label class="col-md-4 control-label">Indor</label>--}}

                            {{--<div class="col-md-8">--}}
                                {{--{{ Form::checkbox('indor', old('indor'), null, ['type' => 'checkbox', 'class' => 'form-control']) }}--}}
                                {{--@if ($errors->has('indor'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('indor') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">--}}
                            {{--<label class="col-md-4 control-label">Heure d'ouverture</label>--}}

                            {{--<div class="col-md-8">--}}
                                {{--<input type="time" class="form-control" name="start_time" value="{{ old('start_time') }}">--}}
                                {{--@if ($errors->has('start_time'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('start_time') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }}">--}}
                            {{--<label class="col-md-4 control-label">Heure de fermeture</label>--}}

                            {{--<div class="col-md-8">--}}
                                {{--<input type="time" class="form-control" name="end_time" value="{{ old('end_time') }}">--}}

                                {{--@if ($errors->has('end_time'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('end_time') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group{{ $errors->has('booking_window_member') ? ' has-error' : '' }}">--}}
                            {{--<label class="col-md-4 control-label">Fenêtre de réservation membre</label>--}}

                            {{--<div class="col-md-8">--}}
                                {{--<input type="number" class="form-control" name="booking_window_member"--}}
                                       {{--value="{{ old('booking_window_member') }}">--}}

                                {{--@if ($errors->has('booking_window_member'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('booking_window_member') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group{{ $errors->has('booking_window_not_member') ? ' has-error' : '' }}">--}}
                            {{--<label class="col-md-4 control-label">Fenêtre de réservation non membre</label>--}}

                            {{--<div class="col-md-8">--}}
                                {{--<input type="number" class="form-control" name="booking_window_not_member"--}}
                                       {{--value="{{ old('booking_window_not_member') }}">--}}

                                {{--@if ($errors->has('booking_window_not_member'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('booking_window_not_member') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group" align="center">--}}
                            {{--<button type="submit" class="btn btn-primary">--}}
                                {{--Sauvegarder--}}
                            {{--</button>--}}
                        {{--</div>--}}
                    {{--</form>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    <div class="row" align="center"><h3>Ajouter un court</h3></div>
    <br/>
    <div class="row">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/config/courts') }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Nom</label>
                @php
                    if (!empty($singleCourt)) {
                        $name = $singleCourt->name;
                    }
                    else{
                        $name = old('name');
                    }
                @endphp
                <div class="col-md-4">
                    <input type="text" class="form-control" name="name" value="{{ $name }}">

                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('indor') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Indor</label>
                @php
                    if (!empty($singleCourt)) {
                        $indor = $singleCourt->indor;
                    }
                    else{
                        $indor = old('indor');
                    }

                    if($indor == 1) {
                        $checked = true;
                    }
                    else {
                        $checked = false;
                    }
                @endphp
                <div class="col-md-4">
                    {{ Form::checkbox('indor', $indor, $checked, ['type' => 'checkbox', 'class' => 'form-control']) }}
                    @if ($errors->has('indor'))
                        <span class="help-block">
                            <strong>{{ $errors->first('indor') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Heure d'ouverture</label>
                @php
                    if (!empty($singleCourt)) {
                        $startTime = $singleCourt->start_time;
                    }
                    else{
                        $startTime = old('start_time');
                    }
                @endphp
                <div class="col-md-4">
                    <input type="time" class="form-control" name="start_time" value="{{ $startTime }}">
                    @if ($errors->has('start_time'))
                        <span class="help-block">
                            <strong>{{ $errors->first('start_time') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Heure de fermeture</label>
                @php
                    if (!empty($singleCourt)) {
                        $endTime = $singleCourt->end_time;
                    }
                    else{
                        $endTime = old('end_time');
                    }
                @endphp
                <div class="col-md-4">
                    <input type="time" class="form-control" name="end_time" value="{{ $endTime }}">

                    @if ($errors->has('end_time'))
                        <span class="help-block">
                            <strong>{{ $errors->first('end_time') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('booking_window_member') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Fenêtre de réservation membre</label>
                @php
                    if (!empty($singleCourt)) {
                        $member = $singleCourt->booking_window_member;
                    }
                    else{
                        $member = old('booking_window_member');
                    }
                @endphp
                <div class="col-md-4">
                    <input type="number" class="form-control" name="booking_window_member"
                           value="{{ $member }}">

                    @if ($errors->has('booking_window_member'))
                        <span class="help-block">
                            <strong>{{ $errors->first('booking_window_member') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('booking_window_not_member') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Fenêtre de réservation non membre</label>
                @php
                if (!empty($singleCourt)) {
                    $nonMember = $singleCourt->booking_window_not_member;
                }
                else{
                    $nonMember = old('booking_window_not_member');
                }
                @endphp
                <div class="col-md-4">
                    <input type="number" class="form-control" name="booking_window_not_member"
                           value="{{ $nonMember }}">

                    @if ($errors->has('booking_window_not_member'))
                        <span class="help-block">
                            <strong>{{ $errors->first('booking_window_not_member') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group" align="center">
                <button type="submit" class="btn btn-primary">
                    @if(!empty($singleCourt))
                        Sauvegarder
                    @else
                        Ajouter
                    @endif
                </button>
            </div>
        </form>
    </div>
@endsection