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
                        <td class="option-zone">
                            <button class="btn btn-warning option" data-action="edit" data-url="/admin/config/courts/{{$court->id}}/edit">
                                <span class="fa fa-edit"></span>
                            </button>
                            <form class="delete" role="form" method="POST" action="/admin/config/courts/{{$court->id}}">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button class="btn btn-danger option" data-action="delete" data-court="{{$court->name}}">
                                    <span class="fa fa-trash"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row" align="center"><h3>{{(!empty($singleCourt) ? 'Modifier' : 'Ajouter')}} un court</h3></div>
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
                <label class="col-md-4 control-label" for="name">Nom</label>
                <div class="col-md-4">
                    <input id="name" type="text" class="form-control" name="name"
                           value="{{ (old('name') != '' ? old('name') : (!empty($singleCourt) ? $singleCourt->name : '')) }}">

                    @if ($errors->has('name'))
                        <p class="help-block">{{ $errors->first('name') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('indor') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="indor">Indor</label>
                @php
                    $checked = '';
                    if (!empty($singleCourt)) {
                        if ($singleCourt->indor == 1) {
                            $checked = 'checked';
                        }
                    }
                @endphp
                <div class="col-md-4">
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
                    <input id="start_time" type="time" class="form-control" name="start_time"
                           value="{{ (old('start_time') != '' ? old('start_time') : (!empty($singleCourt) ? date_format(date_create($singleCourt->start_time), "H:i") : '')) }}">
                    @if ($errors->has('start_time'))
                        <p class="help-block">{{ $errors->first('start_time') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="end_time">Heure de fermeture</label>
                <div class="col-md-4">
                    <input id="end_time" type="time" class="form-control" name="end_time"
                           value="{{ (old('end_time') != '' ? old('end_time') : (!empty($singleCourt) ? date_format(date_create($singleCourt->end_time), "H:i") : '')) }}">

                    @if ($errors->has('end_time'))
                        <p class="help-block">{{ $errors->first('end_time') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('booking_window_member') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="booking_window_member">Fenêtre de réservation membre</label>
                <div class="col-md-4">
                    <input id="booking_window_member" type="number" class="form-control" name="booking_window_member"
                           value="{{ (old('booking_window_member') != '' ? old('booking_window_member') : (!empty($singleCourt) ? $singleCourt->booking_window_member : '')) }}">

                    @if ($errors->has('booking_window_member'))
                        <p class="help-block">{{ $errors->first('booking_window_member') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('booking_window_not_member') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="booking_window_not_member">Fenêtre de réservation non membre</label>
                <div class="col-md-4">
                    <input id="booking_window_not_member" type="number" class="form-control" name="booking_window_not_member"
                           value="{{ (old('booking_window_not_member') != '' ? old('booking_window_not_member') : (!empty($singleCourt) ? $singleCourt->booking_window_not_member : '')) }}">

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