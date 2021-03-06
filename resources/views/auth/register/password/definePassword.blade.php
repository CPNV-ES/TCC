@extends('layouts.app')

@section('content')
    <div class="background"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="head-panel">
                    <div class="title-panel">Choix du mot de passe</div>
                    <div class="body-panel">
                        <div class="password-info">
                          Votre mot de passe doit faire minimum 6 caractères et contenir des lettres et des chiffres.
                          <div class="pull-right"><span class="mandatory"></span> obligatoire</div>
                        </div>
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/login') }}">
                            {!! csrf_field() !!}
                            {!! method_field('put') !!}

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label mandatory">Mot de passe</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" data-verif="required|min_l:6" name="password">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                              </div>
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label mandatory">Confirmer le mot de passe</label>
                                <div class="col-md-6">
                                    <input type="password" data-verif="required|min_l:6" class="form-control" name="password_confirmation">
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                              </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Valider
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

<style>
.password-info {
  margin-bottom: 15px;
}
</style>
