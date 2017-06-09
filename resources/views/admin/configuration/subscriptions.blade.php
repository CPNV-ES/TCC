<!--
Author : -
Created : -
Modified by : S. Forsyth
Last Modif.: 01.02.2017
Description : Displays a table with the inforamtion of the subscriptions form the database.
              The user has the possibility to edit and delete the subscriptions.
-->
@extends('layouts.admin')

@section('title')
    Gestion des cotisations
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
                @foreach($typeSubscriptions as $typeSubscription)
                    <tr>
                        <td>{{$typeSubscription->status}}</td>
                        <td>{{$typeSubscription->amount}}</td>
                        {{-- SFH: This zone is used for the 'edit' and 'delete' buttons --}}
                        <td class="option-zone">
                            {{-- SFH: Check if allowed to edit and delete --}}
                            @php
                            $paid = false;
                            $subscriptions = $typeSubscription->subscriptions;
                            foreach($subscriptions as $subscription) {
                                if ($subscription->paid == 1) {
                                    $paid = true;
                                }
                            }
                            @endphp
                            @if(!$paid)
                                <button class="btn btn-warning option" data-action="edit" data-url="/admin/config/subscriptions/{{$typeSubscription->id}}/edit">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                </button>
                                {{-- SFH: Only methode found to call the 'destroy' methode in the controler. Trying to find a better way. --}}
                                <form class="delete" role="form" method="POST" action="/admin/config/subscriptions/{{$typeSubscription->id}}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button class="btn btn-danger option" data-action="delete-subscription" data-subscription="{{$typeSubscription->status}}">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
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
        <h3>{{(!empty($singleTypeSubscription) ? 'Modifier' : 'Ajouter')}} une cotisation</h3>
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
              action="{{ url('/admin/config/subscriptions') . (!empty($singleTypeSubscription) ? '/' . $singleTypeSubscription->id : '') }}">

            {!! csrf_field() !!}
            {{-- SFH: Used to know which methode in the controller to call --}}
            @if(!empty($singleTypeSubscription))
                {!! method_field('PUT') !!}
            @endif

            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label mandatory" for="status">Type</label>

                <div class="col-md-4">
                    <input id="status" type="text" class="form-control" name="status" data-verif="required|max_l:50" data-verif-group="subscriptionCheck"
                           value="{{ (old('status') != '' ? old('status') : (!empty($singleTypeSubscription) ? $singleTypeSubscription->status : '')) }}">

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
                           value="{{ (old('amount') != '' ? old('amount') : (!empty($singleTypeSubscription) ? $singleTypeSubscription->amount : '')) }}">
                    @if ($errors->has('amount'))
                        <p class="help-block">
                            {{ $errors->first('amount') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="form-group" align="center">
              {{-- SFH: Display a return button if in edit mode --}}
              @if(!empty($singleTypeSubscription))
                <a type="button" class="btn btn-warning" href="/admin/config/subscriptions">Annuler</a>
              @endif
              <button id="btnSubscriptionCheck" type="button" class="btn btn-success">
                  {{-- SFH: Change button text if editing or adding --}}
                  {{ (!empty($singleTypeSubscription) ? 'Sauvegarder' : 'Ajouter') }}
              </button>
            </div>

            {{-- SFH: Added to check form before send --}}
            <script type="text/javascript">
                VERIF.onClickSubmitAfterVerifForm(document.querySelector('#btnSubscriptionCheck'),'subscriptionForm');
            </script>
            {{-- SFH: End --}}

        </form>
    </div>
@endsection
