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
                    <tr class="clickable-row" data-url="/admin/config/courts/{{$court->id}}/edit">
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


    @php
    if (!empty($singleCourt)) {
        $prefix = 'Editer';
    }
    else {
        $prefix = 'Ajouter';
    }
    @endphp
    <div class="row" align="center"><h3>{{$prefix}} un court</h3></div>
    <br/>
    <div class="row">
        @php
        if (!empty($singleCourt)) {
            $url = '/admin/config/courts/' . $singleCourt->id;
        }
        else {
            $url = '/admin/config/courts';
        }
        @endphp
        <form class="form-horizontal" role="form" method="POST" action="{{ $url }}">
            {!! csrf_field() !!}
            @if(!empty($singleCourt))
                {!! method_field('PUT') !!}
            @endif

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
                        <p class="help-block">{{ $errors->first('name') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('indor') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Indor</label>
                @php
                    $checked = '';
                    if (!empty($singleCourt)) {
                        $indor = $singleCourt->indor;
                        if ($indor == 1) {
                            $indor = 'on';
                            $checked = 'checked';
                        }
                    }
                    else{
                        $indor = old('indor');
                    }
                @endphp
                <div class="col-md-4">
                    {{ Form::checkbox('indor', $indor, null, ['type' => 'checkbox', 'class' => 'form-control', $checked]) }}
                    @if ($errors->has('indor'))
                        <p class="help-block">{{ $errors->first('indor') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Heure d'ouverture</label>
                @php
                    if (!empty($singleCourt)) {
                        $startTime = date_format(date_create($singleCourt->start_time), "H:i");
                    }
                    else{
                        $startTime = old('start_time');
                    }
                @endphp
                <div class="col-md-4">
                    <input type="time" class="form-control" name="start_time" value="{{ $startTime }}">
                    @if ($errors->has('start_time'))
                        <p class="help-block">{{ $errors->first('start_time') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Heure de fermeture</label>
                @php
                    if (!empty($singleCourt)) {
                        $endTime = date_format(date_create($singleCourt->end_time), "H:i");
                    }
                    else{
                        $endTime = old('end_time');
                    }
                @endphp
                <div class="col-md-4">
                    <input type="time" class="form-control" name="end_time" value="{{ $endTime }}">

                    @if ($errors->has('end_time'))
                        <p class="help-block">{{ $errors->first('end_time') }}</p>
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
                        <p class="help-block">{{ $errors->first('booking_window_member') }}</p>
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
                        <p class="help-block">{{ $errors->first('booking_window_not_member') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group" align="center">
                <button type="submit" class="btn btn-primary" name="save">
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