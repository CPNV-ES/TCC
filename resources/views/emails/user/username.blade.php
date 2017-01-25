@extends('layouts.mail')

@section('logo')
    Tennis Club Chavornay
@endsection

@section('title')
    Bonjour {{ $first_name }} {{ $last_name }}
@endsection


@section('content')
    Votre compte a été modifié par un administrateur, voici votre nouveau nom de compte:<br/><br/>


    Login : <b>{{ $login }}</b><br/>


    Si vous avez des questions, veuillez contacter cpnv.es.web@gmail.com<br/><br/>
    Merci
@endsection
