@extends('layouts.mail')

@section('logo')
    Tennis Club Chavornay
@endsection

@section('title')
    Bonjour {{ $first_name }} {{ $last_name }}
@endsection


@section('content')
    Vous recevez ce mail car un compte été crée en utilisant l'adresse suivante : {{ $email }}<br/><br/>

    Avant de pouvoir utiliser votre compte, celui-ci doit être validé par un administrateur, un second mail vous sera envoyé à ce moment.<br/><br/>

    Si vous n'avez pas crée de compte sur notre site, veuillez contactez cpnv.es.web@gmail.com en transférant ce mail.<br/><br/>
    Merci
@endsection