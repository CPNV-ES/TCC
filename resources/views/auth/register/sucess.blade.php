@extends('layouts.app')

@section('content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Compte crée</div>

                    <div class="panel-body">
                        Votre compte a été crée avec succès, il est maintenant en attente de validation de la part
                        d'un administrateur.<br />
                        Un mail de confirmation a été envoyé à <b>{{ $email  }}</b>. Un second mail sera envoyé lors de la validation du compte.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection