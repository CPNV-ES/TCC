@extends('layouts.app')

@section('content')
    <h1>Réservation</h1>
    <div id="calendar" class="row"></div>

    <!-- Modal -->
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
    {!! Html::script('/Ajax/calendar.js') !!}
    {!! Html::script('/Ajax/booking.js') !!}
@endsection

