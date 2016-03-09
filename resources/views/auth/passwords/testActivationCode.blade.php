@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Choix du mot de passe</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password') }}">
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Login*</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="login">
                                </div>
                                @if ($errors->has('login'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('login') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('token') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Code d'activation*</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="token">
                                </div>
                                @if ($errors->has('token'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('token') }}</strong>
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
