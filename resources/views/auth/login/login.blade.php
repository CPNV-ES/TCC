@extends('layouts.app')

@section('content')
<div class="background"></div>
<div class="container">
    <div class="row">
        <div class="row">
            @if (!empty($message))

                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ $message }}
                </div>

            @endif
        </div>
        <div class="col-md-8 col-md-offset-2">
            <div class="head-panel">
                <div class="title-panel">
                    Login
                </div>
                <div class="body-panel">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Login</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="login" value="{{ old('login') }}">

                                @if ($errors->has('login'))
                                <span class="help-block"> <strong>{{ $errors->first('login') }}</strong> </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Mot de passe</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                <span class="help-block"> <strong>{{ $errors->first('password') }}</strong> </span>
                                @endif

                                <a href="{{ url('/password/reset') }}">Mot de passe/login oublié ?</a>
                            </div>
                        </div>

                        {{--<div class="form-group">--}}
                            {{--<div class="col-md-6 col-md-offset-4">--}}
                                {{--<div class="checkbox">--}}
                                    {{--<label>--}}
                                        {{--<input type="checkbox" name="remember">--}}
                                        {{--Se souvenir </label>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--<div class="col-md-6 col-md-offset-4">--}}
                                {{--<a class="btn btn-link" href="{{ '/password/reset' }}">Mot de passe/login oublié ?</a>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i>Connexion
                                </button>

                                {{--<a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>--}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
