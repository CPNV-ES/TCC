@extends('layouts.app')

@section('content')
    <div class="background"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="head-panel">
                    <div class="title-panel">Veuillez entrer l'adresse e-mail associée à votre compte</div>
                    <div class="body-panel">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password') }}">
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label mandatory">E-mail</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="email">
                                </div>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
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
