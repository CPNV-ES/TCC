@extends('layouts.admin')

@section('title')
    Gestion des statuts & cotisations

@endsection

@section('content')

    <div class="row">
        <div id="message"></div>
        <div id="jqxsubscriptions"></div>
    </div>

    <div class="row" align="center"><h3>Ajouter un statut et sa cotisation</h3></div>
    <br/>

    <div class="row">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/config/subscriptions') }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Type</label>

                <div class="col-md-4">
                    <input type="text" class="form-control" name="status" value="{{ old('status') }}">

                    @if ($errors->has('status'))
                        <span class="help-block">
                        <strong>{{ $errors->first('status') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Montant</label>

                <div class="col-md-4">
                    <input type="number" class="form-control" name="amount" value="{{ old('amount') }}">
                    @if ($errors->has('amount'))
                        <span class="help-block">
                        <strong>{{ $errors->first('amount') }}</strong>
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