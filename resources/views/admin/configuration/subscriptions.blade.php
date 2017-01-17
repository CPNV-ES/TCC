@extends('layouts.admin')

@section('title')
    Gestion des statuts & cotisations
@endsection

@section('content')

    <div class="row">
        <div id="message"></div>
        {{--<div id="jqxsubscriptions"></div>--}}
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Montant</th>
                    <th>Options</th>
                </tr>
                </thead>
                <tbody>
                @foreach($subscriptions as $subscription)
                    <tr>
                        <td>{{$subscription->status}}</td>
                        <td>{{$subscription->amount}}</td>
                        <td class="option-zone">
                            <button class="btn btn-warning option" data-action="edit" data-url="/admin/config/subscriptions/{{$subscription->id}}/edit">
                                <span class="fa fa-edit"></span>
                            </button>
                            <form class="delete" role="form" method="POST" action="/admin/config/subscriptions/{{$subscription->id}}">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button class="btn btn-danger option" data-action="delete-subscription" data-subscription="{{$subscription->status}}">
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

    <div class="row" align="center"><h3>Ajouter un statut et sa cotisation</h3></div>
    <br/>
    <div class="row">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/config/subscriptions') }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="status">Type</label>

                <div class="col-md-4">
                    <input id="status" type="text" class="form-control" name="status" value="{{ old('status') }}">

                    @if ($errors->has('status'))
                        <span class="help-block">
                        <strong>{{ $errors->first('status') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="amount">Montant</label>

                <div class="col-md-4">
                    <input id="amount" type="number" class="form-control" name="amount" value="{{ old('amount') }}">
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