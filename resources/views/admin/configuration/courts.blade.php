<!--
Author : -
Created : -
Modified by : S. Forsyth
Last Modif.: 01.02.2017
Description : Displays a table with the inforamtion of the courts form the database.
              The user has the possibility to edit and delete the courts.
-->
@extends('layouts.admin')

@section('title')
    Gestion des courts
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
                    <th>Ouvert</th>
                    <th>Fenêtre de réservation membre</th>
                    <th>Options</th>
                </tr>
                </thead>
                <tbody>
                @foreach($courts as $court)
                    <tr>
                        <td>{{$court->name}}</td>
                        <td>{{$court->state}}</td>
                        <td>{{$court->nbDays}}</td>
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

            <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="state">Ouvert</label>
                {{-- SFH: Checks if its an indoor court --}}
                @php
                    $checked = '';
                    if (!empty($singleCourt)) {
                        if ($singleCourt->state == 1) {
                            $checked = 'checked';
                        }
                    }
                @endphp

                <div class="col-md-4">
                    {{-- SFH: Hidden value is overridden if the checkbox if checked --}}
                    <input type="hidden" name="state" value="0">
                    <input id="state" type="checkbox" class="form-control" name="state"
                           value="1" {{$checked}}>

                    @if ($errors->has('state'))
                        <p class="help-block">{{ $errors->first('state') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('nbDays') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="nbDays">Fenêtre de réservation membre</label>
                <div class="col-md-4">
                    <input id="nbDays" type="number" class="form-control" name="nbDays" data-verif="required|int_neg|min:1" data-verif-group="courtCheck"
                           value="{{ (old('nbDays') != '' ? old('nbDays') : (!empty($singleCourt) ? $singleCourt->nbDays : '')) }}">

                    @if ($errors->has('nbDays'))
                        <p class="help-block">{{ $errors->first('nbDays') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group" align="center">
              {{-- SFH: Display a return button if in edit mode --}}
              @if(!empty($singleCourt))
                <a type="button" class="btn btn-warning" href="/admin/config/courts">Annuler</a>
              @endif
              <button id="btnCourtCheck" type="button" class="btn btn-success" name="save">
                  {{-- SFH: Change button text if editing or adding --}}
                  {{ (!empty($singleCourt) ? 'Sauvegarder' : 'Ajouter') }}
              </button>
            </div>

            {{-- SFH: Added to check form before send --}}
            <script type="text/javascript">
                    VERIF.onClickSubmitAfterVerifForm(document.querySelector('#btnCourtCheck'),'courtForm');
            </script>
            {{-- SFH: End --}}

        </form>
    </div>
@endsection
