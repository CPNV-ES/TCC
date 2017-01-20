@extends('layouts.admin')

@section('title')
    Gestion des saisons
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

        {{-- SFH: Added simple display table for the seasons --}}
        <div class="table-responsive">
            <table class="table table-striped">
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
                        {{-- SFH: This zone is used for the 'delete' buttons --}}
                        <td class="option-zone">
                            {{-- SFH: Only methode found to call the 'destroy' methode in the controler. Trying to find a better way. --}}
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
        {{-- End --}}
    </div>

    <div class="row" align="center">
        <h3>Ajouter une saison</h3>
    </div>

    {{--
        SFH: Added the conditions in the 'value' fields.
        1) If the was an old value display it.
        2) If in edit mode display the data from the database.
        3) Display nothing.
    --}}
    <div class="row">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/config/seasons') }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('begin_date') ? ' has-error' : '' }}">
                <label class="col-md-6 control-label">Date de début (format jj.mm.aaaa)*</label>

                <div class="col-md-4">

                    <input type="date" class="form-control" name="begin_date"
                           value="{{ (old('begin_date') != '' ? old('begin_date') : (!empty($newSeasonStart) ? $newSeasonStart : '')) }}">

                    @if ($errors->has('begin_date'))
                        <p class="help-block">
                            {{ $errors->first('begin_date') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">
                <label class="col-md-6 control-label">Date de fin (format jj.mm.aaaa)*</label>

                <div class="col-md-4">
                    <input type="date" class="form-control" name="end_date"
                           value="{{ (old('end_date') != '' ? old('end_date') : (!empty($newSeasonEnd) ? $newSeasonEnd : '')) }}">

                    @if ($errors->has('end_date'))
                        <p class="help-block">
                            {{ $errors->first('end_date') }}
                        </p>
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