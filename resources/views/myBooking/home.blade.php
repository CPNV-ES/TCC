@extends('layouts.app')

@section('content')
    <div class="background"></div>
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="head-panel">
                    <div class="title-panel">Mes réservations</div>

                    <div class="body-panel">
                        <div id="message"></div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th style="text-align: left;">N° de court</th>
                                <th style="text-align: left;">Joueur</th>
                                <th style="text-align: left;">Joueur</th>
                                <th style="text-align: left;">Date et heure</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>{{ $booking['court'] }}</td>
                                    <td>{{ $booking['first_name_1'] }} {{ $booking['last_name_1'] }}</td>
                                    <td>{{ $booking['first_name_2'] }} {{ $booking['last_name_2'] }}</td>
                                    <td>{{ $booking['date'] }}</td>
                                    @if ($booking['deletable'])
                                        <td>
                                            <button class="confirm btn btn-danger" data-id="{{ $booking['id'] }}"><span
                                                        class="glyphicon glyphicon-remove"></span></button>
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Html::script('/Ajax/mybooking.js') !!}
@endsection
