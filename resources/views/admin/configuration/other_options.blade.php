@extends('layouts.admin')

@section('title')
    Gestion des autres configurations
@endsection

@section('content')
    <div class="row">
        <div class="flash-message">
            @foreach (['danger', 'warning', 'success', 'info'] as $message)
                @if(Session::has('alert-' . $message))
                    <p class="alert alert-{{ $message }} fade in">
                        {{ Session::get('alert-' . $message) }}
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    </p>
                @endif
            @endforeach
        </div>
    </div>
    <form id="form-edit-other-options" class="form" name="other-options-form" role="form" method="POST" action="{{url('/admin/config/other_options/1')}}">
        {!! csrf_field() !!}
        {!! method_field('PUT') !!}

        <div class="row">
          <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12 {{ $errors->has('nbReservations') ? ' has-error' : '' }}">
            <label class="control-label" for="nbReservations">Nombre de réservation simultanées :</label>
            <input id="nbReservations" class="form-control" name="nbReservations" type="number"
            value="{{ (old('nbReservations') != '' ? old('nbReservations') : (!empty($config) ? $config->nbReservations : '')) }}" data-verif="required|int|min:1">
            @if ($errors->has('nbReservations'))
              <p class="help-block">{{ $errors->first('nbReservations') }}</p>
            @endif
          </div>
        </div>

        <div class="row">
          <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12 {{ $errors->has('nbDaysLimitNonMember') ? ' has-error' : '' }}">
            <label class="control-label" for="nbDaysLimitNonMember">Fenêtre de réservation non-membre :</label>
            <input id="nbDaysLimitNonMember" class="form-control" name="nbDaysLimitNonMember" type="number"
            value="{{ (old('nbDaysLimitNonMember') != '' ? old('nbDaysLimitNonMember') : (!empty($config) ? $config->nbDaysLimitNonMember : '')) }}" data-verif="required|int|min:1">
            @if ($errors->has('nbDaysLimitNonMember'))
              <p class="help-block">{{ $errors->first('nbDaysLimitNonMember') }}</p>
            @endif
          </div>
        </div>

        <div class="row">
          <div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12 {{ $errors->has('nbDaysGracePeriod') ? ' has-error' : '' }}">
            <label class="control-label" for="nbDaysGracePeriod">Période de grâce :</label>
            <input id="nbDaysGracePeriod" class="form-control" name="nbDaysGracePeriod" type="number"
            value="{{ (old('nbDaysGracePeriod') != '' ? old('nbDaysGracePeriod') : (!empty($config) ? $config->nbDaysGracePeriod : '')) }}" data-verif="required|int|min:0">
            @if ($errors->has('nbDaysGracePeriod'))
              <p class="help-block">{{ $errors->first('nbDaysGracePeriod') }}</p>
            @endif
          </div>
        </div>

        <div class="row">
          <div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12 {{ $errors->has('courtOpenTime') ? ' has-error' : '' }}">
            <label class="control-label" for="courtOpenTime">Heure d'ouverture :</label>
            <input id="courtOpenTime" class="form-control" name="courtOpenTime" type="time"
            value="{{ (old('courtOpenTime') != '' ? old('courtOpenTime') : (!empty($config) ? $config->courtOpenTime : '')) }}" data-verif="required|time_long"/>
            @if ($errors->has('courtOpenTime'))
              <p class="help-block">{{ $errors->first('courtOpenTime') }}</p>
            @endif
          </div>
          <div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12 {{ $errors->has('courtCloseTime') ? ' has-error' : '' }}">
            <label class="control-label" for="courtCloseTime">Heure de fermeture :</label>
            <input id="courtCloseTime" class="form-control" name="courtCloseTime" type="time"
            value="{{ (old('courtCloseTime') != '' ? old('courtCloseTime') : (!empty($config) ? $config->courtCloseTime : '')) }}" data-verif="required|time_long"/>
            @if ($errors->has('courtCloseTime'))
              <p class="help-block">{{ $errors->first('courtCloseTime') }}</p>
            @endif
          </div>
        </div>

        <div class="row">
          <div id="" class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button id="btn-edit" class="btn btn-warning" type="button">Modifier</button>
            <button id="btn-save" class="btn btn-success" type="button">Sauvegarder</button>
          </div>
        </div>

    </form>

    <script type="text/javascript">
    $("#btn-edit").click(function () {
      if (lockedForm != true) {
        $("#nbDaysGracePeriod").val("{{$config->nbDaysGracePeriod}}");
        $("#nbDaysLimitNonMember").val("{{$config->nbDaysLimitNonMember}}");
        $("#courtOpenTime").val("{{$config->courtOpenTime}}");
        $("#courtCloseTime").val("{{$config->courtCloseTime}}");
        $('.verif_message_error').remove();
        $('.verif_error').removeClass('verif_error');
        $('.help-block').remove();
        $('.has-error').removeClass('has-error');
      }
    });
    lockForm('#form-edit-other-options', '#btn-edit','#btn-save',{{($errors->any()) ? 'false' : 'true' }});
    let btn = document.getElementById('btn-save');
    VERIF.onClickSubmitAfterVerifForm(btn,'other-options-form');
    </script>

@endsection
