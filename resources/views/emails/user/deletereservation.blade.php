@extends('layouts.mail')

@section('logo')
    Tennis Club Chavornay
@endsection

@section('title')
    Bonjour {{ $first_name }} {{ $last_name }}
@endsection


@section('content')
    Une réservation dont vous faisiez partie a été supprimée. Veuillez trouver les détails de cette réservation ci-dessous :<br /><br />

    Court : {{ $court }}<br />
    Date heure : {{ $date_hours }}<br />
    Joueur 1 : {{ $joueur1 }}<br />
    Joueur 2 : {{ $joueur2 }}<br /><br />

    Sportivement<br /><br />
    Tennis Club Chavornay
@endsection