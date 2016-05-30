@extends('layouts.mail')

@section('logo')
    Tennis Club Chavornay
@endsection

@section('title')
    Bonjour {{ $first_name }} {{ $last_name }}
@endsection


@section('content')
    Votre réservation sur le <b>{{ $court }}</b> a bien été enregistrée avec les détais suivants.<br /><br />

    Date heure : {{ $date_hours }}<br />
    Joueur 1 : {{ $joueur1 }}<br />
    Joueur 2 : {{ $joueur2 }}<br /><br />

    Sportivement<br /><br />
    Tennis Club Chavornay
@endsection