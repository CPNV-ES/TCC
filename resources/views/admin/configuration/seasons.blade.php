@extends('layouts.admin')

@section('title')
    Gestion des saisons
@endsection

@section('content')
    <div class="row">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Option</th>
                </tr>
                </thead>
                <tbody>
                @foreach($seasons as $season)
                    <tr>
                        <td>{{$season->id}}</td>
                        <td>{{date_format(date_create($season->begin_date), "d.m.Y")}}</td>
                        <td>{{date_format(date_create($season->end_date), "d.m.Y")}}</td>
                        <td class="option-zone">
                            <form class="delete" role="form" method="POST" action="/admin/config/seasons/{{$season->id}}">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button class="btn btn-danger option" data-action="delete-season" data-season="{{$season->id}}">
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

    <div class="row" align="center"><h3>Ajouter une saison</h3></div>
    <br/>
    <div class="row">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/config/seasons') }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('begin_date') ? ' has-error' : '' }}">
                <label class="col-md-6 control-label">Date de début (format jj.mm.aaaa)*</label>

                <div class="col-md-4">
                    <input type="date" class="form-control" name="begin_date" value="{{ (old('begin_date') != '' ? old('begin_date') : (!empty($newSeasonStart) ? $newSeasonStart : '')) }}">

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
                    <input type="date" class="form-control" name="end_date" value="{{ (old('end_date') != '' ? old('end_date') : (!empty($newSeasonEnd) ? $newSeasonEnd : '')) }}">

                    @if ($errors->has('end_date'))
                        <span class="help-block">
                        <strong>{{ $errors->first('end_date') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group" align="center">
                <button type="submit" class="btn btn-primary">
                    Ajouter
                </button>
            </div>
        </form>
    </div>

@endsection