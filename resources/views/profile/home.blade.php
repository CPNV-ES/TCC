@extends('layouts.app')

@section('content')
<div class="background"></div>
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="head-panel">
                <div class="title-panel">Edition de profil</div>

                <div class="body-panel">
                    <div class="row">
                        @if (Auth::user()->to_verify == 1)

                            <div class="alert alert-danger alert-dismissible" role="alert">
                                Veuillez vérifier vos informations puis cliquez sur le bouton <b><i>Mettre à jour</i></b> en bas de la page.
                                Merci
                            </div>

                        @endif
                    </div>

                    <div class="row">
                        @if (!empty($message))

                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                {{ $message }}
                            </div>

                        @endif
                    </div>
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

                        <div class="form-group{{ $errors->has('home_phone') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Téléphone privé</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="home_phone" value="{{ Auth::user()->home_phone }}">

                                @if ($errors->has('home_phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('home_phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('mobile_phone') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Téléphone mobile</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="mobile_phone" value="{{ Auth::user()->mobile_phone }}">

                                @if ($errors->has('mobile_phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mobile_phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('birth_date') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Date de naissance</label>

                            <div class="col-md-6">
                                <input id="birth_date" type="text" class="form-control" name="birth_date" value="{{ date("d.m.Y", strtotime(Auth::user()->birth_date)) }}">

                                @if ($errors->has('birth_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('birth_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Mettre à jour
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
