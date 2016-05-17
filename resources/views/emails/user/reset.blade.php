@extends('layouts.mail')

@section('logo')
    Tennis Club Chavornay
@endsection

@section('title')
    Bonjour {{ $first_name }} {{ $last_name }}
@endsection


@section('content')
    Vous avez demandé la réinitialisation de votre mot de passe ou oublié votre login.<br/><br/>


    Login : <b>{{ $login }}</b><br/>


    Veuillez procéder au choix de votre nouveau mot de passe via le lien suivant : <a href="{{ route('password.create', ['token' => $token, 'login' => $login]) }}"> {{ route('password.create', ['token' => $token, 'login' => $login]) }}  </a><br/><br/>

    Merci
@endsection