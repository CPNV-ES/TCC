@extends('layouts.admin')

@section('title')
    Gestion des cotisations
    {{-- SFH: Display a return button if in edit mode --}}
    @if(!empty($singleSubscription))
        <a type="button" class="btn btn-primary" href="/admin/config/subscriptions">Retour</a>
    @endif
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

        {{-- SFH: Added simple display table for the subscriptions --}}
        <div class="table-responsive">
            <table class="table table-striped">
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
                        {{-- SFH: This zone is used for the 'edit' and 'delete' buttons --}}
                        <td class="option-zone">
                            {{-- SFH: Check if allowed to edit and delete --}}
                            @if(sizeof($subscription->seasons) == 0)
                                <button class="btn btn-warning option" data-action="edit" data-url="/admin/config/subscriptions/{{$subscription->id}}/edit">
                                    <span class="fa fa-edit"></span>
                                </button>
                                {{-- SFH: Only methode found to call the 'destroy' methode in the controler. Trying to find a better way. --}}
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
        {{-- End --}}
    </div>

    <div class="row" align="center">
        {{-- SFH: Added to change the title if editing or adding --}}
        <h3>{{(!empty($singleSubscription) ? 'Modifier' : 'Ajouter')}} une cotisation</h3>
    </div>

    {{--
        SFH: Added the conditions in the 'value' fields.
        1) If the was an old value display it.
        2) If in edit mode display the data from the database.
        3) Display nothing.
    --}}
    <div class="row">
        {{-- SFH: Change the url if editing or adding --}}
        <form class="form-horizontal" name="subscriptionForm" role="form" method="POST"
              action="{{ url('/admin/config/subscriptions') . (!empty($singleSubscription) ? '/' . $singleSubscription->id : '') }}">

            {!! csrf_field() !!}
            {{-- SFH: Used to know which methode in the controller to call --}}
            @if(!empty($singleSubscription))
                {!! method_field('PUT') !!}
            @endif

            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label" for="status">Type</label>

                <div class="col-md-4">
                    <input id="status" type="text" class="form-control" name="status" data-verif="required|max_l:50" data-verif-group="subscriptionCheck"
                           value="{{ (old('status') != '' ? old('status') : (!empty($singleSubscription) ? $singleSubscription->status : '')) }}">

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
                    <input id="amount" type="number" step="0.05" class="form-control" name="amount" data-verif="double_neg|min:0" data-verif-group="subscriptionCheck"
                           value="{{ (old('amount') != '' ? old('amount') : (!empty($singleSubscription) ? $singleSubscription->amount : '')) }}">
                    @if ($errors->has('amount'))
                        <p class="help-block">
                            {{ $errors->first('amount') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="form-group" align="center">
                <button type="btnSubscriptionCheck" class="btn btn-primary">
                    {{-- SFH: Change button text if editing or adding --}}
                    {{ (!empty($singleSubscription) ? 'Sauvegarder' : 'Ajouter') }}
                </button>
            </div>
        </form>
    </div>
@endsection