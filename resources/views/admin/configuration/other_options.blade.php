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
          <div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12 {{ $errors->has('nbDaysGracePeriod') ? ' has-error' : '' }}">
            <label class="control-label" for="nbDaysGracePeriod">Période de grâce :</label>

            <input id="nbDaysGracePeriod" class="form-control" name="nbDaysGracePeriod" type="number" value="{{$config->nbDaysGracePeriod}}" data-verif="required|int|min:0">

            @if ($errors->has('nbDaysGracePeriod'))
              <p class="help-block">{{ $errors->first('nbDaysGracePeriod') }}</p>
            @endif
          </div>
        </div>
        <div class="row">
          <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 {{ $errors->has('nbDaysLimitNonMember') ? ' has-error' : '' }}">
            <label class="control-label" for="nbDaysLimitNonMember">Nombre de jours à l'avance qu'un non-membre peut réserver :</label>

            <input id="nbDaysLimitNonMember" class="form-control" name="nbDaysLimitNonMember" type="number" value="{{$config->nbDaysLimitNonMember}}" data-verif="required|int|min:1">

            @if ($errors->has('nbDaysLimitNonMember'))
              <p class="help-block">{{ $errors->first('nbDaysLimitNonMember') }}</p>
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
      }
    });
    lockForm('#form-edit-other-options', '#btn-edit','#btn-save',{{($errors->any()) ? 'false' : 'true' }});
    let btn = document.getElementById('btn-save');
    VERIF.onClickSubmitAfterVerifForm(btn,'other-options-form');
    </script>

@endsection
