@extends('layouts.mail')

@section('logo')
    Tennis Club Chavornay
@endsection

@section('title')
    Bonjour {{ $first_name }} {{ $last_name }}
@endsection


@section('content')
    Votre réservation sur le <b>{{ $court }}</b> a bien été prise en compte.<br /><br />

    Afin de valider votre réservation, veuillez cliquez sur ce lien: <br />
    <a href="{{route('booking.confirmation',['token' => $token])}}">{{route('booking.confirmation',['token' => $token])}}</a><br /><br />
    Date heure : {{ $date_hours }}<br />
    Joueur 1 : {{ $player }}<br />


    Sportivement<br /><br />
    Tennis Club Chavornay
@endsection