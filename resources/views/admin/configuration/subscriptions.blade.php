@extends('layouts.admin')

@section('title')
    Gestion des cotisations
    @if(!empty($singleSubscription))
        <a type="button" class="btn btn-primary" href="/admin/config/subscriptions">Retour</a>
    @endif
@endsection

@section('content')

    <div class="row">
        <div id="message"></div>
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
                            @if(!$subscription->hasSubscription)
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
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row" align="center"><h3>{{(!empty($singleSubscription) ? 'Modifier' : 'Ajouter')}} une cotisation</h3></div>
    <br/>
    <div class="row">
        @php
            if (!empty($singleSubscription)) {
                $url = '/admin/config/subscriptions/' . $singleSubscription->id;
            }
            else {
                $url = '/admin/config/subscriptions';
            }
        @endphp
        <form class="form-horizontal" role="form" method="POST" action="{{ $url }}">
            {!! csrf_field() !!}
            @if(!empty($singleSubscription))
                {!! method_field('PUT') !!}
            @endif

            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="status">Type</label>

                <div class="col-md-4">
                    <input id="status" type="text" class="form-control" name="status" value="{{ (old('status') != '' ? old('status') : (!empty($singleSubscription) ? $singleSubscription->status : '')) }}">

                    @if ($errors->has('status'))
                        <p class="help-block">
                            {{ $errors->first('status') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="amount">Montant</label>

                <div class="col-md-4">
                    <input id="amount" type="number" class="form-control" name="amount" value="{{ (old('amount') != '' ? old('amount') : (!empty($singleSubscription) ? $singleSubscription->amount : '')) }}">
                    @if ($errors->has('amount'))
                        <p class="help-block">
                            {{ $errors->first('amount') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="form-group" align="center">
                <button type="submit" class="btn btn-primary">
                    @if(!empty($singleSubscription))
                        Sauvegarder
                    @else
                        Ajouter
                    @endif
                </button>
            </div>
        </form>
    </div>
@endsection