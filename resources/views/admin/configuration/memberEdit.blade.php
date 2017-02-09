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
    <div class=" ">
      @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
      @endif
    </div>

    <form id="form-edit-member" class="form-vertical" role="form" method="POST" action="{{ url('/admin/members/'.$user->id) }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input class="form-control" name="member-id" type="hidden" value="{{$user->id}}">

        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
            <div >
                <h4>Pseudo: {{$user->username}}</h4>
            </div>
        </div>

        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 @if($errors->has('firstname')) {{'has-error'}} @endif  ">
            <label for="example-text-input" class="col-2 col-form-label">Prénom*</label>
            <div >
                <input class="form-control" name="firstname" data-verif-group="edit-group-form" data-verif="required|text|min_l:2|max_l:50" type="text" value="{{ ((old('firstname'))) ? old('firstname') : $user->personal_information->firstname }}" >
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
                <input class="form-control" name="lastname" data-verif-group="edit-group-form" data-verif="required|text|min_l:2|max_l:50" type="text" value="{{ old('lastname') ? old('lastname') : $user->personal_information->lastname }}" >
                @if ($errors->has('lastname'))
                    <span class="help-block">
                        <strong>{{ $errors->first('last_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12   @if($errors->has('email')) {{'has-error'}} @endif">
            <label for="example-text-input" name="lbl_email" class="col-2 col-form-label">Mail*</label>
            <div >
                <input class="form-control" name="email" data-verif-group="edit-group-form" data-verif="required|email|min_l:4|max_l:100" type="email" value="{{ old('email') ? old('email') : $user->personal_information->email }}" >
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12   @if($errors->has('street')) {{'has-error'}} @endif">
            <label for="example-text-input" name="lbl_street" class="col-2 col-form-label">Rue*</label>
            <div>
                <input class="form-control" name="street" data-verif-group="edit-group-form" data-verif="required|text|min_l:2|max_l:50" type="text" value="{{ old('street') ? old('street') : $user->personal_information->street }}" >
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
                <input class="form-control" name="streetNbr" data-verif-group="edit-group-form" data-verif="required|text|min_l:1|max_l:45" type="text" value="{{ old('streetNbr') ? old('streetNbr') : $user->personal_information->streetNbr }}" >
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
                <select class="form-control" name="locality">
                    @foreach($localities as $locality)
                     <!-- we select the value in the city of the member. If the form as been return with error the old value is selected -->
                     <option value="{{$locality->name}}" {{(old('locality') == $locality->id) ? 'selected': $user->personal_information->fkLocality == $locality->id && old('locality') =='' ? 'selected' : ''}} > {{$locality->name}} </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12  @if($errors->has('telephone')) {{'has-error'}} @endif">
            <label for="example-text-input" name="lbl_telephone class="col-2 col-form-label">Téléphone*</label>
            <div >
                <input class="form-control" name="telephone" placeholder="0244542112" data-verif-group="edit-group-form" data-verif="required|phone" type="text" value="{{ old('telephone') ? old('telephone') : $user->personal_information->telephone }}" >
                @if ($errors->has('mobile_phone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('telephone') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        {{--<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 " @if($errors->has('home_phone')) {{'has-error'}} @endif>
            <label for="example-text-input" name="lbl_home_phone" class="col-2 col-form-label">Téléphone fixe*</label>
            <div >
                <input class="form-control" name="home_phone" data-verif-group="edit-group-form" data-verif="required|phone" type="text" value="{{ old('home_phone') ? old('home_phone') : $user->home_phone }}" >
                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('home-phone') }}</strong>
                    </span>
                @endif
            </div>
        </div> --}}
        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
            <label>Type de compte</label>
            <select class="form-control" name="typeCompte">
                <!-- we select the value in the city of the member. If the form as been return with error the old value is selected -->
                    <option value="0" {{old('localitie') == 0 ? 'selected': $user->personal_information->isMember ==1 ? 'selected':''}} >Membre</option>
                    <option value="1" {{old('localitie') == 1 ? 'selected': $user->personal_information->isTrainer ==1 ? 'selected':''}} >Responsable</option>
                    <option value="2" {{old('localitie') == 2 ? 'selected': $user->personal_information->isAdmin ==1 ? 'selected':''}} >Administrateur</option>
            </select>
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
            <label for="example-text-input" name="lbl_account_options" class="col-2 col-form-label">Options du compte</label>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="checkboxError" value="1" name="active" {{ old('active')==1 ? 'checked' : $user->active==1 ? 'checked':'' }}>
                        Rendre le compte actif
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="checkboxError" value="1" name="toVerify" {{ old('toVerify')==1 ? 'checked' : $user->personal_information->toVerify==1 ? 'checked':'' }}>
                        Demander une vérification
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="checkboxError" value="1" name="validated" {{ old('validated')==1 ? 'checked' : $user->validated==1 ? 'checked':'' }}>
                        Valider le compte
                    </label>
                </div>
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
            <a class="btn btn-primary" href="/admin/members" >Retour à la liste</a>
            <button id="btn-member-edit"  class="btn btn-primary" type="button">Modifier</button>
            <button id="btn-member-save" type="button"  class="btn btn-primary">Sauvegarder</button>
        </div>
    </form>
    <script>
        lockForm('#form-edit-member', '#btn-member-edit','#btn-member-save', {{($errors->any()) ? 'false' : 'true' }});
        let btn=document.getElementById('btn-member-save');
        VERIF.verifOnCLick(btn,'form-edit-member','edit-group-form');
    </script>



@endsection
