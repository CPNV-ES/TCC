@extends('layouts.app')

@section('content')
    <div class="background"></div>
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="head-panel">
                    <div class="title-panel">Compte crée</div>

                    <div class="body-panel">
                        Votre compte a été crée avec succès, il est maintenant
                        en attente de validation de la part d'un administrateur.
                        <br/>
                        Un mail de confirmation (informatif) a été envoyé à
                        <b>{{ $email }}</b>. Un second mail sera envoyé avec les
                        informations de connexion une fois que votre compte aura
                        été validé par un administrateur.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
