<!-- Author: i.Goujgali
Date: 12.01.17
Last modif.: 20.01.17

Description: Displays a form with the informations of a member. The inputs of the form are disabled unless you click on
            'déverrouiller'. If you saved the forms and a error occured, the value of the last form are displayed. In this way
            you don't have to fill again the form.
 -->
@extends('layouts.admin')

@section('title')
  @if ($personal_information->user)
    Member :
  @else
    Non-Member :
  @endif
    {{$personal_information->firstname}} {{$personal_information->lastname}}
@endsection
@section('content')
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      @if (Session::has('message'))
        <div class="alert alert-success">
          {{ Session::get('message') }}
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        </div>
      @endif
    </div>
<div class="row" style="padding-left: 10px; padding-right:10px;">
    <form id="form-edit-member" class="form-vertical" role="form" method="POST" action="{{ url('/admin/members/'.$personal_information->id) }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input class="form-control"  name="member-id" type="hidden" value="{{$personal_information->id}}">

        @if ($personal_information->user)
          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
              <div >
                  <h4>Pseudo: {{$personal_information->user->username}}</h4>
              </div>
          </div>
        @endif
        <div class="row">
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12   @if($errors->has('lastname')) {{'has-error'}} @endif">
            <label for="example-text-input" name="lbl_lastname" class="col-2 col-form-label mandatory">Nom</label>
            <div >
                <input class="form-control" id="lastname" name="lastname" data-verif-group="edit-group-form" data-verif="required|text|min_l:2|max_l:50" type="text" value="{{ old('lastname') ? old('lastname') : $personal_information->lastname }}" >
                @if ($errors->has('lastname'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lastname') }}</strong>
                    </span>
                @endif
            </div>
          </div>
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 @if($errors->has('firstname')) {{'has-error'}} @endif  ">
              <label for="example-text-input" class="col-2 col-form-label mandatory">Prénom</label>
              <div >
                  <input class="form-control" id="firstname" name="firstname" data-verif-group="edit-group-form" data-verif="required|text|min_l:2|max_l:50" type="text" value="{{ ((old('firstname'))) ? old('firstname') : $personal_information->firstname }}" >
                  @if ($errors->has('firstname'))
                      <span class="help-block">
                          <strong>{{ $errors->first('firstname') }}</strong>
                      </span>
                  @endif
              </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12   @if($errors->has('street')) {{'has-error'}} @endif">
              <label for="example-text-input" name="lbl_street" class="col-2 col-form-label">Rue</label>
              <div>
                  <input class="form-control" name="street" id="street" data-verif-group="edit-group-form" data-verif="text|min_l:2|max_l:50" type="text" value="{{ old('street') ? old('street') : $personal_information->street }}" >
                  @if ($errors->has('street'))
                      <span class="help-block">
                          <strong>{{ $errors->first('street') }}</strong>
                      </span>
                  @endif
              </div>
          </div>
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12  @if($errors->has('streetNbr')) {{'has-error'}} @endif">
              <label for="example-text-input" name="lbl_adresse" class="col-2 col-form-label">Numéro de rue</label>
              <div >
                  <input class="form-control" name="streetNbr" id="streetNbr" data-verif-group="edit-group-form" data-verif="text|min_l:1|max_l:45" type="text" value="{{ old('streetNbr') ? old('streetNbr') : $personal_information->streetNbr }}" >
                  @if ($errors->has('streetNbr'))
                      <span class="help-block">
                          <strong>{{ $errors->first('streetNbr') }}</strong>
                      </span>
                  @endif
              </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12  @if($errors->has('locality')) {{'has-error'}} @endif">
              <label for="example-text-input" name="lbl_locality "class="col-2 col-form-label mandatory">Ville</label>
              <div  >
                  <select class="form-control" id="locality" name="locality">
                      @foreach($localities as $locality)
                       <!-- we select the value in the city of the member. If the form as been return with error the old value is selected -->
                       <option id="locality{{$locality->id}}" value="{{$locality->name}}" {{(old('locality') == $locality->id) ? 'selected': $personal_information->fkLocality == $locality->id && old('locality') =='' ? 'selected' : ''}} > {{$locality->npa.' - '.$locality->name}} </option>
                      @endforeach
                  </select>
              </div>
          </div>
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12   @if($errors->has('email')) {{'has-error'}} @endif">
              <label for="example-text-input" name="lbl_email" class="col-2 col-form-label mandatory">E-mail</label>
              <div >
                  <input class="form-control" name="email" id="email" data-verif-group="edit-group-form" data-verif="required|email|min_l:4|max_l:100" type="email" value="{{ old('email') ? old('email') : $personal_information->email }}" >
                  @if ($errors->has('email'))
                      <span class="help-block">
                          <strong>{{ $errors->first('email') }}</strong>
                      </span>
                  @endif
              </div>
          </div>
        </div>

      <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12  @if($errors->has('telephone')) {{'has-error'}} @endif">
            <label for="example-text-input" name="lbl_telephone" class="col-2 col-form-label mandatory">Téléphone (format : 012 123 12 12)</label>
            <div >
                <input class="form-control" name="telephone" id="telephone" placeholder="024 454 21 12" data-verif-group="edit-group-form" data-verif="required|phone" type="text" value="{{ old('telephone') ? old('telephone') : $personal_information->telephone }}" >
                @if ($errors->has('telephone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('telephone') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        @if ($personal_information->user)
          <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12 ">
              <label for="example-text-input" name="lbl_account_options" class="col-2 col-form-label">Rôles du compte</label>
              <div class="checkbox @if($errors->has('adminRole')) {{'has-error'}} @endif">
                  <div class="col-lg-4">
                      <label>
                          <input type="checkbox"  value="1" id="isAdmin" name="isAdmin" {{ old('isAdmin') == 1 ? 'checked' : (($personal_information->user) ? ($personal_information->user->isAdmin == 1 ? 'checked':'') : '')}}>
                          Administrateur
                      </label>
                      @if ($errors->has('adminRole'))
                          <span class="help-block">
                              <strong>{{ $errors->first('adminRole') }}</strong>
                          </span>
                      @endif
                  </div>
                  <div class="col-lg-4">
                      <label>
                          <input type="checkbox"  value="1"  id="isTrainer" name="isTrainer" {{ old('isTrainer')==1 ? 'checked' : (($personal_information->user) ? ($personal_information->user->isTrainer==1 ? 'checked':'') : '')}}>
                          Staff
                      </label>
                  </div>
                  <div class="col-lg-4">
                      <label>
                          <input type="checkbox" id="isMember" value="1" name="isMember" {{ old('isMember')==1 ? 'checked' : (($personal_information->user) ? ($personal_information->user->isMember==1 ? 'checked':'') : '')}}>
                          Membre
                      </label>
                  </div>
              </div>
          </div>
        @endif
      </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
            <label for="example-text-input" name="lbl_account_options" class="col-2 col-form-label">Options du compte</label>
            @if ($personal_information->user)
              <div class="checkbox @if($errors->has('accountActive')) {{'has-error'}} @endif">
                  <label>
                      <input type="checkbox" id="active" value="1" name="active" {{ old('active')==1 ? 'checked' : ($personal_information->user->active == 1 ? 'checked' : '')}}>
                      Compte activé
                  </label>
                  @if ($errors->has('accountActive'))
                      <span class="help-block">
                          <strong>{{ $errors->first('accountActive') }}</strong>
                      </span>
                  @endif
              </div>
            @endif
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="toVerify" value="1" name="toVerify" {{ old('toVerify')==1 ? 'checked' : $personal_information->toVerify==1 ? 'checked':'' }}>
                    Compte à vérifier
                </label>
            </div>
            @if ($personal_information->user)
              <div class="checkbox">
                  <label>
                      <input type="checkbox" id="invitRight" value="1" name="invitRight" {{ old('invitRight')==1 ? 'checked' : ($personal_information->user->invitRight == 1 ? 'checked' : '')}}>
                      Droit d'invitation
                  </label>
              </div>
            @endif
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
            <a class="btn btn-primary" href="/admin/members" >Retour à la liste</a>
            <button id="btn-member-edit" type="button"  class="btn btn-primary" >Modifier</button>
            <button id="btn-member-save" type="button"  class="btn btn-primary">Sauvegarder</button>
        </div>
    </form>
  </div>
  <script>

        $(document).ready(function(){
            function restoreOldValue(lockedForm)
            {
                if(lockedForm == false)
                {
                    $("#firstname").val("{{$personal_information->firstname}}");
                    $("#lastname").val("{{$personal_information->lastname}}");
                    $("#email").val("{{$personal_information->email}}");
                    $("#street").val("{{$personal_information->street}}");
                    $("#telephone").val("{{$personal_information->telephone}}");
                    $("#streetNbr").val("{{$personal_information->streetNbr}}");
                    $("#isAdmin").prop('checked', {{(($personal_information->user) ? (($personal_information->user->isAdmin == 1) ? "true": "false") : 'false')}});
                    $("#isTrainer").prop('checked', {{(($personal_information->user) ? (($personal_information->user->isTrainer == 1) ? "true": "false") : 'false')}});
                    $("#isMember").prop('checked', {{(($personal_information->user) ? (($personal_information->user->isMember == 1) ? "true": "false") : 'false')}});
                    $("#active").prop('checked', {{(($personal_information->user) ? (($personal_information->user->isMember == 1) ? "true": "false") : 'false')}});
                    $("#toVerify").prop('checked', {{($personal_information->toVerify == 1) ? "true": "false"}});
                    $("#invitRight").prop('checked', {{(($personal_information->user) ? (($personal_information->user->invitRight == 1) ? "true": "false") : 'false')}});
                    $("#validated").prop('checked', {{(($personal_information->user) ? (($personal_information->user->validated == 1) ? "true": "false") : 'false')}})
                    $("#locality{{$personal_information->fkLocality}}").prop('selected', true);
                    $(".help-block").remove();
                    $(".has-error").removeClass("has-error");
                    $(".verif_message_error").remove();
                    $(".verif_error").removeClass("verif_error");
                }
            }

            lockForm('#form-edit-member', '#btn-member-edit','#btn-member-save',{{($errors->any()) ? 'false' : 'true' }}, restoreOldValue);

            var btn=document.getElementById('btn-member-save');
            VERIF.onClickSubmitAfterVerifGroup(btn,'form-edit-member','edit-group-form');
        });
    </script>
@endsection
