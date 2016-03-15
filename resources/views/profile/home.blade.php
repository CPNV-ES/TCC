@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Edition de profile</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/profile/update') }}">
                        {!! method_field('put') !!}
                        {!! csrf_field() !!}
                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Prénom</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="first_name" value="{{ Auth::user()->first_name }}">

                                @if ($errors->has('first_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Nom</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="last_name" value="{{ Auth::user()->last_name }}">

                                @if ($errors->has('last_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Adresse</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="address" value="{{ Auth::user()->address }}">

                                @if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('zip_code') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">NPA</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="zip_code" value="{{ Auth::user()->zip_code }}">

                                @if ($errors->has('zip_code'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('zip_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Ville</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="city" value="{{ Auth::user()->city }}">

                                @if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">E-mail</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">N° téléphone</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="phone" value="{{ Auth::user()->phone }}">

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user">Mettre à jour</i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
