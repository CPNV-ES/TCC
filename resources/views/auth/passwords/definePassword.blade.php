@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Choix du mot de passe</div>
                    <div class="panel-body">
                        <div>Votre mot de passe doit faire minimum 6 caractères et contenir des lettres et des chiffres<br /></div>
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/login') }}">
                            {!! csrf_field() !!}
                            {!! method_field('put') !!}


                            <div class="form-group{{ $errors->has('password1') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Mot de passe*</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password1">
                                </div>
                                @if ($errors->has('password1'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password1') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('password2') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Repetez le mot de passe*</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password2">
                                </div>
                                @if ($errors->has('password2'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password2') }}</strong>
                                    </span>
                                @endif
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
