<!-- Author: i.Goujgali
Date: 12.01.17
Last modif.: 20.01.17

Description: Displays a form with the informations of a member. The inputs of the form are disabled unless you click on
            'déverrouiller'. If you saved the forms and a error occured, the value of the last form are displayed. In this way
            you don't have to fill again the form.
 -->
@extends('layouts.admin')

@section('title')
    Membre : {{$user->personal_information->firstname}} {{$user->personal_information->lastname}}
@endsection
@section('content')
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
      @endif
    </div>
<div class="row" style="padding-left: 10px; padding-right:10px;">
    <form id="form-edit-member" class="form-vertical" role="form" method="POST" action="{{ url('/admin/members/'.$user->id) }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input class="form-control"  name="member-id" type="hidden" value="{{$user->id}}">

        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
            <div >
                <h4>Pseudo: {{$user->username}}</h4>
            </div>
        </div>
        <div class="row">
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 @if($errors->has('firstname')) {{'has-error'}} @endif  ">
              <label for="example-text-input" class="col-2 col-form-label">Prénom*</label>
              <div >
                  <input class="form-control" id="firstname" name="firstname" data-verif-group="edit-group-form" data-verif="required|text|min_l:2|max_l:50" type="text" value="{{ ((old('firstname'))) ? old('firstname') : $user->personal_information->firstname }}" >
                  @if ($errors->has('firstname'))
                      <span class="help-block">
                          <strong>{{ $errors->first('firstname') }}</strong>
                      </span>
                  @endif
              </div>
          </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12   @if($errors->has('lastname')) {{'has-error'}} @endif">
              <label for="example-text-input" name="lbl_lastname" class="col-2 col-form-label">Nom*</label>
              <div >
                  <input class="form-control" id="lastname" name="lastname" data-verif-group="edit-group-form" data-verif="required|text|min_l:2|max_l:50" type="text" value="{{ old('lastname') ? old('lastname') : $user->personal_information->lastname }}" >
                  @if ($errors->has('lastname'))
                      <span class="help-block">
                          <strong>{{ $errors->first('lastname') }}</strong>
                      </span>
                  @endif
              </div>
            </div>
        </div>
        <div class="row">
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12   @if($errors->has('email')) {{'has-error'}} @endif">
              <label for="example-text-input" name="lbl_email" class="col-2 col-form-label">E-Mail*</label>
              <div >
                  <input class="form-control" name="email" id="email" data-verif-group="edit-group-form" data-verif="required|email|min_l:4|max_l:100" type="email" value="{{ old('email') ? old('email') : $user->personal_information->email }}" >
                  @if ($errors->has('email'))
                      <span class="help-block">
                          <strong>{{ $errors->first('email') }}</strong>
                      </span>
                  @endif
              </div>
          </div>
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12   @if($errors->has('street')) {{'has-error'}} @endif">
              <label for="example-text-input" name="lbl_street" class="col-2 col-form-label">Rue</label>
              <div>
                  <input class="form-control" name="street" id="street" data-verif-group="edit-group-form" data-verif="text|min_l:2|max_l:50" type="text" value="{{ old('street') ? old('street') : $user->personal_information->street }}" >
                  @if ($errors->has('street'))
                      <span class="help-block">
                          <strong>{{ $errors->first('street') }}</strong>
                      </span>
                  @endif
              </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12  @if($errors->has('streetNbr')) {{'has-error'}} @endif">
              <label for="example-text-input" name="lbl_adresse" class="col-2 col-form-label">Numéro de rue</label>
              <div >
                  <input class="form-control" name="streetNbr" id="streetNbr" data-verif-group="edit-group-form" data-verif="text|min_l:1|max_l:45" type="text" value="{{ old('streetNbr') ? old('streetNbr') : $user->personal_information->streetNbr }}" >
              </div>
          </div>

{{--        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12  @if($errors->has('npa')) {{'has-error'}} @endif">
            <label for="example-text-input" name="lbl_npa" class="col-2 col-form-label">NPA*</label>
            <div >
                <input class="form-control" name="npa" data-verif-group="edit-group-form" data-verif="required|int|min_l:4|max_l:4" type="number" value="{{ old('npa') ? old('npa') : $user->npa }}" >
                @if ($errors->has('npa'))
                    <span class="help-block">
                        <strong>{{ $errors->first('npa') }}</strong>
                    </span>
                @endif
            </div>
        </div>--}}

        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12  @if($errors->has('locality')) {{'has-error'}} @endif">
            <label for="example-text-input" name="lbl_locality "class="col-2 col-form-label">Localité*</label>
            <div  >
                <select class="form-control" id="locality" name="locality">
                    @foreach($localities as $locality)
                     <!-- we select the value in the city of the member. If the form as been return with error the old value is selected -->
                     <option id="locality{{$user->personal_information->fkLocality}}" value="{{$locality->name}}" {{(old('locality') == $locality->id) ? 'selected': $user->personal_information->fkLocality == $locality->id && old('locality') =='' ? 'selected' : ''}} > {{$locality->npa.' - '.$locality->name}} </option>
                    @endforeach
                </select>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12  @if($errors->has('telephone')) {{'has-error'}} @endif">
            <label for="example-text-input" name="lbl_telephone class="col-2 col-form-label">Téléphone (format : 012 123 12 12)*</label>
            <div >
                <input class="form-control" name="telephone" id="telephone" placeholder="024 454 21 12" data-verif-group="edit-group-form" data-verif="required|phone" type="text" value="{{ old('telephone') ? old('telephone') : $user->personal_information->telephone }}" >
                @if ($errors->has('telephone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('telephone') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        {{--  ACCOUNT TYPE WITH DROPDOWN LIST
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                  <label>Type de compte</label>
                  <select class="form-control" name="typeCompte">
                      <!-- we select the value in the city of the member. If the form as been return with error the old value is selected -->
                          <option value="0" {{old('localitie') == 0 ? 'selected': $user->personal_information->isMember ==1 ? 'selected':''}} >Membre</option>
                          <option value="1" {{old('localitie') == 1 ? 'selected': $user->personal_information->isTrainer ==1 ? 'selected':''}} >Responsable</option>
                          <option value="2" {{old('localitie') == 2 ? 'selected': $user->personal_information->isAdmin ==1 ? 'selected':''}} >Administrateur</option>
                  </select>
              </div>--}}
        <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12 ">
            <label for="example-text-input" name="lbl_account_options" class="col-2 col-form-label">Type de compte</label>
            <div class="checkbox">
                <div class="col-lg-4">
                    <label>
                        <input type="checkbox"  value="1" id="isAdmin" name="isAdmin" {{ old('isAdmin')==1 ? 'checked' : $user->isAdmin==1 ? 'checked':'' }}>
                        Administrateur
                    </label>
                </div>
                <div class="col-lg-4">
                    <label>
                        <input type="checkbox"  value="1"  id="isTrainer" name="isTrainer" {{ old('isTrainer')==1 ? 'checked' : $user->isTrainer==1 ? 'checked':'' }}>
                        Responsable
                    </label>
                </div>
                <div class="col-lg-4">
                    <label>
                        <input type="checkbox" id="isMember" value="1" name="isMember" {{ old('isMember')==1 ? 'checked' : $user->isMember==1 ? 'checked':'' }}>
                        Membre
                    </label>
                </div>
            </div>
        </div>
      </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
            <label for="example-text-input" name="lbl_account_options" class="col-2 col-form-label">Options du compte</label>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="active" value="1" name="active" {{ old('active')==1 ? 'checked' : $user->active==1 ? 'checked':'' }}>
                        Rendre le compte actif
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="toVerify" value="1" name="toVerify" {{ old('toVerify')==1 ? 'checked' : $user->personal_information->toVerify==1 ? 'checked':'' }}>
                        Demander une vérification
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="validated" value="1" name="validated" {{ old('validated')==1 ? 'checked' : $user->validated==1 ? 'checked':'' }}>
                        Valider le compte
                    </label>
                </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="invitRight" value="1" name="invitRight" {{ old('invitRight')==1 ? 'checked' : $user->invitRight==1 ? 'checked':'' }}>
                    Donner le droit d'invitation
                </label>
            </div>
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
            <a class="btn btn-primary" href="/admin/members" >Retour à la liste</a>
            <button id="btn-member-edit" type="button"  class="btn btn-primary" >Modifier</button>
            <button id="btn-member-save" type="button"  class="btn btn-primary">Sauvegarder</button>
        </div>
    </form>
  </div>
    <script>
        $("#btn-member-edit").click(function(){
            if(lockedForm == false)
            {
                $("#firstname").val("{{$user->personal_information->firstname}}");
                $("#lastname").val("{{$user->personal_information->lastname}}");
                $("#email").val("{{$user->personal_information->email}}");
                $("#street").val("{{$user->personal_information->street}}");
                $("#telephone").val("{{$user->personal_information->telephone}}");
                $("#streetNbr").val("{{$user->personal_information->streetNbr}}");
                $("#isAdmin").prop('checked', {{($user->isAdmin == 1) ? "true": "false"}});
                $("#isTrainer").prop('checked', {{($user->isTrainer == 1) ? "true": "false"}});
                $("#isMember").prop('checked', {{($user->isMember == 1) ? "true": "false"}});
                $("#active").prop('checked', {{($user->isMember == 1) ? "true": "false"}});
                $("#toVerify").prop('checked', {{($user->personal_information->toVerify == 1) ? "true": "false"}});
                $("#invitRight").prop('checked', {{($user->invitRight == 1) ? "true": "false"}});
                $("#validated").prop('checked', {{($user->validated == 1) ? "true": "false"}})
                $("#locality{{$user->personal_information->fkLocality}}").prop('selected', true);
            }
        });
        lockForm('#form-edit-member', '#btn-member-edit','#btn-member-save',{{($errors->any()) ? 'false' : 'true' }});
        let btn=document.getElementById('btn-member-save');
        VERIF.onClickSubmitAfterVerifGroup(btn,'form-edit-member','edit-group-form');



    </script>



@endsection
