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
    <form class="form-inline" name="other_options_form" role="form" method="POST" action="{{url('/admin/config/other_options/1')}}">
        {!! csrf_field() !!}
        {!! method_field('PUT') !!}
        <div class="form-group {{ $errors->has('nbDaysGracePeriod') ? ' has-error' : '' }}">
            <label class="control-label" for="nbDaysGracePeriod">Période de grâce :</label>

            <input id="nbDaysGracePeriod" name="nbDaysGracePeriod" type="number" class="form-control" value="{{$config->nbDaysGracePeriod}}" disabled="">

            @if ($errors->has('nbDaysGracePeriod'))
                <p class="help-block">{{ $errors->first('nbDaysGracePeriod') }}</p>
            @endif
        </div>
        <div class="form-group">
            <button id="btn_grace_period_edit" class="btn btn-primary" type="button">Modifier</button>
            <button id="btn_grace_period_save" class="btn btn-primary" type="submit" style="display: none;">Sauvegarder</button>
        </div>
    </form>

    <script type="text/javascript">
        $(document).ready(function () {
            lockedForm = true;
            $("#btn_grace_period_edit").click(function () {
                if(lockedForm)
                {
                    $("#btn_grace_period_save").show();
                    $("#btn_grace_period_edit").html('Annuler');
                    $("#nbDaysGracePeriod").prop('disabled',false);
                    lockedForm = false;
                }
                else
                {
                    $("#btn_grace_period_save").hide();
                    $("#btn_grace_period_edit").html('Modifier');
                    $("#nbDaysGracePeriod").prop('disabled',true);
                    lockedForm = true;
                }
            });
        });
    </script>

@endsection
