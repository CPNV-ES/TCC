@extends('layouts.app')

@section('content')
    <h1>Réservation</h1>

    <div style="width:510px;color:#000;margin:auto;">
        <iframe height="85" frameborder="0" width="510" scrolling="no" src="http://www.prevision-meteo.ch/services/html/chavornay-vd/horizontal" allowtransparency="true"></iframe>
        <a style="text-decoration:none;font-size:0.75em;" title="Prévisions à 4 jours pour Chavornay (VD)" href="http://www.prevision-meteo.ch/meteo/localite/chavornay-vd">Prévisions à 4 jours pour Chavornay (VD)</a>
    </div>

    <div id="calendar" class="row"></div>
    <form method="post" role="form" method="POST" action="{{ url('/booking')}}">
        {{ csrf_field() }}
        {{ method_field('POST') }}


        <input type="text" name="dateStart">
        <input type="text" name="hourStart">
        <input type="text" name="fkWithWho">
        <input type="text" name="fkCourt">
        <input type="submit">
    </form>

{{--    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                </div>
                <div class="modal-body">
                    <div id="modal-resume" class="notice notice-info"></div>
                    <div id="modal-panel"></div>
                    <div id="modal-content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="button" id="booking" class="btn btn-success">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Réserver
                    </button>
                </div>
            </div>
        </div>
    </div>
    {!! Html::script('/ajax/calendar.js') !!}
    {!! Html::script('/ajax/booking.js') !!}--}}
@endsection

