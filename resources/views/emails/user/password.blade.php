@extends('layouts.mail')

@section('logo')
    Tennis Club Chavornay
@endsection

@section('title')
    Bonjour {{ $first_name }} {{ $last_name }}
@endsection


@section('content')
    Votre compte a été validé par un administrateur, voici les informations concernant votre compte:<br/><br/>


    Login : <b>{{ $login }}</b><br/>
    Code d'activation : <b>{{ $validationCode }}</b><br/>


    Veuillez procéder au choix du mot de passe via le lien suivant : <a href="tcc.dev/password"> tcc.dev/password </a><br/><br/>

    Si vous n'avez pas crée de compte sur notre site, veuillez contactez cpnv.es.web@gmail.com en transférant ce mail.<br/><br/>
    Merci
@endsection