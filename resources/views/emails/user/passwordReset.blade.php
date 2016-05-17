@extends('layouts.mail')

@section('logo')
    Tennis Club Chavornay
@endsection

@section('title')
    Bonjour {{ $first_name }} {{ $last_name }}
@endsection


@section('content')
    Vous avez demandé à réinitialiser le mot de passe de votre compte. Voici les informations nécessaires:<br/><br/>


    Login : <b>{{ $login }}</b><br/>


    Veuillez procéder au choix du nouveau mot de passe via le lien suivant : <a href="{{ route('password.create', ['token' => $token, 'login' => $login]) }}"> {{ route('password.create', ['token' => $token, 'login' => $login]) }}  </a><br/><br/>
    <br/><br/>
    Merci
@endsection