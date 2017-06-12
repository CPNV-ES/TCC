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
                        @if ($infosUser->toVerify == 1)
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
                    <div class="pull-right"><span class="mandatory"></span> obligatoire</div>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/profile/update') }}">
                        {!! method_field('put') !!}
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label mandatory">Nom</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="lastname" value="{{ (old('lastname') != '' ? old('lastname') : (!empty($infosUser) ? $infosUser->lastname : '')) }}">
                                @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label mandatory">Prénom</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="firstname" value="{{ (old('firstname') != '' ? old('firstname') : (!empty($infosUser) ? $infosUser->firstname : '')) }}">
                                @if ($errors->has('firstname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Rue</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="street" value="{{ (old('street') != '' ? old('street') : (!empty($infosUser) ? $infosUser->street : '')) }}">
                                @if ($errors->has('street'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('street') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('streetNbr') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Numéro de rue</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="streetNbr" value="{{ (old('streetNbr') != '' ? old('streetNbr') : (!empty($infosUser) ? $infosUser->streetNbr : '')) }}">
                                @if ($errors->has('streetNbr'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('streetNbr') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('npa') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label mandatory">NPA</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="npa" value="{{ (old('npa') != '' ? old('npa') : (($infosUser->localities != null) ? $infosUser->localities->npa : '')) }}">
                                @if ($errors->has('npa'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('npa') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('locality') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label mandatory">Ville</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="locality" value="{{ (old('locality') != '' ? old('locality') : (($infosUser->localities != null) ? $infosUser->localities->name : '')) }}">
                                @if ($errors->has('locality'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('locality') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label mandatory">E-mail</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ (old('email') != '' ? old('email') : (!empty($infosUser) ? $infosUser->email : '')) }}">
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('telephone') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label mandatory">Téléphone</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="telephone" value="{{ (old('telephone') != '' ? old('telephone') : (!empty($infosUser) ? $infosUser->telephone : '')) }}">
                                @if ($errors->has('telephone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('telephone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('birthDate') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label mandatory">Date de naissance</label>

                            <div class="col-md-6">
                                <input id="birthDate" type="text" class="form-control" name="birthDate" value="{{ (old('birthDate') != '' ? old('birthDate') : (!empty($infosUser) ? date("Y-m-d", strtotime($infosUser->birthDate)) : '')) }}">
                                @if ($errors->has('birthDate'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('birthDate') }}</strong>
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

    <script type="text/javascript">
    $(function () {
      $('#birthDate').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        language: "fr"
      });
    });
    </script>

</div>
@endsection
