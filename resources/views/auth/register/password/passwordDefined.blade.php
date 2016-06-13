@extends('layouts.app')

@section('content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Mot de passe défini</div>

                    <div class="panel-body">
                        Votre mot de passe a été défini avec succés.<br/>
                        Vous pouvez maintenant vous <a href="/login">connecter</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection