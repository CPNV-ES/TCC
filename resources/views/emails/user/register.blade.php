@extends('layouts.mail')

@section('logo')
    Tennis Club Chavornay
@endsection

@section('title')
    Bonjour {{ $firstname }} {{ $lastname }}
@endsection


@section('content')
    Vous recevez ce mail car un compte a été créé en utilisant l'adresse suivante : {{ $email }}<br/><br/>

    Avant de pouvoir utiliser votre compte, celui-ci doit être validé par un administrateur, un second mail vous sera envoyé à ce moment.<br/><br/>

    Si vous n'avez pas créé de compte sur notre site, veuillez contacter {{ env('ADMIN_MAIL','admin@tcc.ch') }} en transférant ce mail.<br/><br/>
    Merci
@endsection
