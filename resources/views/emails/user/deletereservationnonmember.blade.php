@extends('layouts.mail')

@section('logo')
    Tennis Club Chavornay
@endsection

@section('title')
    Bonjour {{ $first_name }} {{ $last_name }}
@endsection


@section('content')
   Une demande d'annulation de réservation a été effectué pour la réservation suivante : <br/>

    Court : {{ $court }}<br />
    Date heure : {{ $date_hours }}<br />

   Cliquez sur ce lien pour confirmer la suppression de la réservation:<br/>
   <a href="{{route('booking.cancellation',['token' => $token])}}">{{route('booking.cancellation',['token' => $token])}}</a><br /><br />


    Sportivement<br /><br />
    Tennis Club Chavornay
@endsection