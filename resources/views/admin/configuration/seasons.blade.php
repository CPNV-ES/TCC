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
        {{-- SFH: End --}}

        {{-- SFH: Added simple display table for the seasons --}}
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Option</th>
                </tr>
                </thead>
                <tbody>
                @foreach($seasons as $season)
                    <tr>
                        <td>{{date_format(date_create($season->dateStart), "d.m.Y")}}</td>
                        <td>{{date_format(date_create($season->dateEnd), "d.m.Y")}}</td>
                        {{-- SFH: This zone is used for the 'delete' buttons --}}
                        <td class="option-zone">
                            {{-- SFH: Check if allowed to delete --}}
                            @if(sizeof($season->subscriptions) == 0)
                                {{-- SFH: Only methode found to call the 'destroy' methode in the controler. Trying to find a better way. --}}
                                <form class="delete" role="form" method="POST" action="/admin/config/seasons/{{$season->id}}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button class="btn btn-danger option" data-action="delete-season"
                                            data-seasonstart="{{date_format(date_create($season->dateStart), "d.m.Y")}}"
                                            data-seasonend="{{date_format(date_create($season->dateEnd), "d.m.Y")}}">
                                        <span class="fa fa-trash"></span>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{-- SFH: End --}}
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
        <form class="form-horizontal" name="seasonForm" role="form" method="POST" action="{{ url('/admin/config/seasons') }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('dateStart') ? ' has-error' : '' }}">
                <label class="col-md-6 control-label">Date de début (format jj.mm.aaaa)*</label>

                <div class="col-md-4">

                    <input id="dateStart" type="date" class="form-control" name="dateStart" data-verif="required|date_us" data-verif-group="seasonCheck"
                           value="{{ (old('dateStart') != '' ? old('dateStart') : (!empty($newSeasonStart) ? $newSeasonStart : '')) }}">

                    @if ($errors->has('dateStart'))
                        <p class="help-block">
                            {{ $errors->first('dateStart') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('dateEnd') ? ' has-error' : '' }}">
                <label class="col-md-6 control-label">Date de fin (format jj.mm.aaaa)*</label>

                <div class="col-md-4">
                    <input type="date" class="form-control" name="dateEnd" data-verif="required|date_us|date_us_greater:dateStart" data-verif-group="seasonCheck"
                           value="{{ (old('dateEnd') != '' ? old('dateEnd') : (!empty($newSeasonEnd) ? $newSeasonEnd : '')) }}">

                    @if ($errors->has('dateEnd'))
                        <p class="help-block">
                            {{ $errors->first('dateEnd') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="form-group" align="center">
                <button id="btnSeasonCheck" type="button" class="btn btn-primary">
                    Ajouter
                </button>
            </div>

            {{-- SFH: Added to check form before send --}}
            <script type="text/javascript">
              VERIF.onClickSubmitAfterVerifForm(document.querySelector('#btnSeasonCheck'),'seasonForm');
            </script>
            {{-- SFH: End --}}

        </form>
    </div>

@endsection
