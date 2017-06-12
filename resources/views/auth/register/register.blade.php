@extends('layouts.app')

@section('content')
    <div class="background"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="head-panel">
                    <div class="title-panel">Inscription</div>
                    <div class="body-panel">
                        <form class="form-horizontal" name="register_form" role="form" method="POST" action="{{ url('/register') }}">
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label mandatory">Nom</label>

                                <div class="col-md-6">
                                    <input type="text" data-verif-group="register_form" data-verif="required|min_l:2|max_l:30" class="form-control" name="lastname"
                                           value="{{ old('lastname') }}">
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
                                    <input type="text" data-verif-group="register_form" class="form-control" name="firstname"
                                          data-verif="required|min_l:2|max_l:30"
                                           value="{{ old('firstname') }}">
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
                                    <input type="text" data-verif-group="register_form" data-verif="min_l:2|max_l:60" class="form-control" name="street" value="{{ old('street') }}">
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
                                    <input type="text" data-verif-group="register_form" data-verif="min_l:2|max_l:10" class="form-control" name="streetNbr" value="{{ old('streetNbr') }}">
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
                                    <input type="text" data-verif-group="register_form" data-verif="required|int|min_l:4|max_l:4" class="form-control" name="npa"
                                           value="{{ old('npa') }}">
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
                                    <input type="text" data-verif-group="register_form" data-verif="required|min_l:2|max_l:45" class="form-control" name="locality" value="{{ old('locality') }}">
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
                                    <input type="email" data-verif-group="register_form" data-verif="required|email" class="form-control" name="email" value="{{ old('email') }}">
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
                                    <input type="text" data-verif-group="register_form" data-verif="required|phone" class="form-control" name="telephone"
                                           placeholder="123 456 78 90" value="{{ old('telephone') }}">
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
                                    <input type="text" data-verif-group="register_form" data-verif="required|date_us|date_past|date_more_diff:72" class="form-control" id="birth_date" name="birthDate"
                                           value="{{ old('birthDate') }}">
                                           @if ($errors->has('birthDate'))

                                        <span class="help-block">
                                        <strong>{{ $errors->first('birthDate') }}</strong>
                                    </span>

                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button id="btn_register"  type="button" class="btn btn-primary">
                                        <i class="fa fa-user" aria-hidden="true"></i> S'inscrire
                                    </button>
                                </div>
                            </div>
                            <script type="text/javascript">
                                document.querySelector('#btn_register').addEventListener('click', function(e) {
                                    VERIF.verifGroup('register_form',function(isok){if(isok)document.forms["register_form"].submit();});
                                });
                                $(document).ready(function () {

                                    $('#birth_date').datepicker({
                                        format: "yyyy-mm-dd",
                                        language: "fr"
                                    });

                                });
                            </script>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
