@extends('layouts.admin')

@section('title')
    Gestion des saisons
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#crud">Ajouter</button>
@endsection

@section('content')
    <div class="row">
        {{--<div id="jqxseasons"></div>--}}
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                </tr>
                </thead>
                <tbody>
                @foreach($seasons as $season)
                    <tr>
                        <td>{{date_format(date_create($season->begin_date), "d.m.Y")}}</td>
                        <td>{{date_format(date_create($season->end_date), "d.m.Y")}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="crud" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Ajouter une saison</h3>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/config/seasons') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('begin_date') ? ' has-error' : '' }}">
                            <label class="col-md-6 control-label">Date de début (format jj.mm.aaaa)*</label>

                            <div class="col-md-4">
                                <input type="date" class="form-control" name="begin_date" value="{{ old('begin_date') }}">

                                @if ($errors->has('begin_date'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('begin_date') }}</strong>
                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">
                            <label class="col-md-6 control-label">Date de fin (format jj.mm.aaaa)*</label>

                            <div class="col-md-4">
                                <input type="date" class="form-control" name="end_date" value="{{ old('end_date') }}">

                                @if ($errors->has('end_date'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('end_date') }}</strong>
                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group" align="center">
                            <button type="submit" class="btn btn-primary">
                                Sauvegarder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--<div class="row" align="center"><h3>Ajouter une saison</h3></div>--}}
    {{--<br/>--}}
    {{--<div class="row">--}}
        {{--<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/config/seasons') }}">--}}
            {{--{!! csrf_field() !!}--}}

            {{--<div class="form-group{{ $errors->has('begin_date') ? ' has-error' : '' }}">--}}
                {{--<label class="col-md-6 control-label">Date de début (format jj.mm.aaaa)*</label>--}}

                {{--<div class="col-md-4">--}}
                    {{--<input type="date" class="form-control" name="begin_date" value="{{ old('begin_date') }}">--}}

                    {{--@if ($errors->has('begin_date'))--}}
                        {{--<span class="help-block">--}}
                        {{--<strong>{{ $errors->first('begin_date') }}</strong>--}}
                        {{--</span>--}}
                    {{--@endif--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">--}}
                {{--<label class="col-md-6 control-label">Date de fin (format jj.mm.aaaa)*</label>--}}

                {{--<div class="col-md-4">--}}
                    {{--<input type="date" class="form-control" name="end_date" value="{{ old('end_date') }}">--}}

                    {{--@if ($errors->has('end_date'))--}}
                        {{--<span class="help-block">--}}
                        {{--<strong>{{ $errors->first('end_date') }}</strong>--}}
                        {{--</span>--}}
                    {{--@endif--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="form-group" align="center">--}}
                {{--<button type="submit" class="btn btn-primary">--}}
                    {{--Ajouter--}}
                {{--</button>--}}
            {{--</div>--}}
        {{--</form>--}}
    {{--</div>--}}

@endsection