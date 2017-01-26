<!-- Author: i.Goujgali
Date: 12.01.17
Last modif.: 20.01.17

Description: Displays a form with the informations of a member. The inputs of the form are disabled unless you click on
            'déverrouiller'. If you saved the forms and a error occured, the value of the last form are displayed. In this way
            you don't have to fill again the form.
 -->
@extends('layouts.admin')

@section('title')
    Membre : {{$member->first_name}} {{$member->last_name}}
@endsection
@section('content')
    <div class="row">
      @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
      @endif
    </div>

    <form id="form-edit-member" class="form-vertical" role="form" method="POST" action="{{ url('/admin/members/'.$member->id) }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input class="form-control" name="member-id" type="hidden" value="{{$member->id}}">

        <div class="form-group row">
            <div class="col-10">
                <h4>Pseudo: {{$member->login}}</h4>

            </div>
        </div>

        <div class="form-group  @if($errors->has('first_name')) {{'has-error'}} @endif row">
            <label for="example-text-input" class="col-2 col-form-label">Prénom*</label>
            <div class="col-10">
                <input class="form-control" name="first_name" data-verif-group="edit-group-form" data-verif="required|text|min_l:2|max_l:50" type="text" value="{{ ((old('first_name'))) ? old('first_name') : $member->first_name }}" >
                @if ($errors->has('first_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('first_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row @if($errors->has('last_name')) {{'has-error'}} @endif">
            <label for="example-text-input" name="lbl_last_name" class="col-2 col-form-label">Nom*</label>
            <div class="col-10">
                <input class="form-control" name="last_name" data-verif-group="edit-group-form" data-verif="required|text|min_l:2|max_l:50" type="text" value="{{ old('last_name') ? old('last_name') : $member->last_name }}" >
                @if ($errors->has('last_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('last_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row @if($errors->has('email')) {{'has-error'}} @endif">
            <label for="example-text-input" name="lbl_email" class="col-2 col-form-label">Mail*</label>
            <div class="col-10">
                <input class="form-control" name="email" data-verif-group="edit-group-form" data-verif="required|email|min_l:4|max_l:100" type="email" value="{{ old('email') ? old('email') : $member->email }}" >
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row @if($errors->has('address')) {{'has-error'}} @endif">
            <label for="example-text-input" name="lbl_adresse" class="col-2 col-form-label">Rue*</label>
            <div class="col-10">
                <input class="form-control" name="address" data-verif-group="edit-group-form" data-verif="required|text|min_l:2|max_l:50" type="text" value="{{ old('address') ? old('address') : $member->address }}" >
                @if ($errors->has('address'))
                    <span class="help-block">
                        <strong>{{ $errors->first('address') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <!-- ********************************************** -->
        <!-- Doesn't exist in the actual database -->

        <!--<div class="form-group row">-->
            <!--<label for="example-text-input" name="lbl_adresse" class="col-2 col-form-label">Numéro de rue</label>-->
            <!--<div class="col-10">-->
                <!--<input class="form-control" name="address" type="text" value="{{$member->address}}" >-->
            <!--</div>-->
        <!--</div>-->

        <!-- ********************************************** -->
        <div class="form-group row @if($errors->has('zip_code')) {{'has-error'}} @endif">
            <label for="example-text-input" name="lbl_zip_code" class="col-2 col-form-label">NPA*</label>
            <div class="col-10">
                <input class="form-control" name="zip_code" data-verif-group="edit-group-form" data-verif="required|int|min_l:4|max_l:4" type="number" value="{{ old('zip_code') ? old('zip_code') : $member->zip_code }}" >
                @if ($errors->has('zip_code'))
                    <span class="help-block">
                        <strong>{{ $errors->first('zip_code') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row @if($errors->has('city')) {{'has-error'}} @endif">
            <label for="example-text-input" name="lbl_city "class="col-2 col-form-label">Localité*</label>
            <div class="col-10" >
                <select class="form-control" name="city">
                    @foreach($localities as $locality)
                     <!-- we select the value in the city of the member. If the form as been return with error the old value is selected -->
                     <option value="{{$locality}}" {{(old('city') == $locality) ? 'selected': $member->city == $locality && old('city') =='' ? 'selected' : ''}} > {{$locality}} </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row @if($errors->has('mobile_phone')) {{'has-error'}} @endif">
            <label for="example-text-input" name="lbl_mobile_phone" class="col-2 col-form-label">Téléphone mobile*</label>
            <div class="col-10">
                <input class="form-control" name="mobile_phone" data-verif-group="edit-group-form" data-verif="required|phone" type="text" value="{{ old('mobile_phone') ? old('mobile_phone') : $member->mobile_phone }}" >
                @if ($errors->has('mobile_phone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('mobile_phone') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row" @if($errors->has('home_phone')) {{'has-error'}} @endif>
            <label for="example-text-input" name="lbl_home_phone" class="col-2 col-form-label">Téléphone fixe*</label>
            <div class="col-10">
                <input class="form-control" name="home_phone" data-verif-group="edit-group-form" data-verif="required|phone" type="text" value="{{ old('home_phone') ? old('home_phone') : $member->home_phone }}" >
                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('home-phone') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="example-text-input" name="lbl_home_phone" class="col-2 col-form-label">Options du compte</label>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="checkboxError" value="1" name="active" {{ old('active')==1 ? 'checked' : $member->active==1 ? 'checked':'' }}>
                        Rendre le compte actif
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="checkboxError" value="1" name="to_verify" {{ old('to_verify')==1 ? 'checked' : $member->to_verify==1 ? 'checked':'' }}>
                        Demander une vérification
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="checkboxError" value="1" name="validate" {{ old('validate')==1 ? 'checked' : $member->validate==1 ? 'checked':'' }}>
                        Valider le compte
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="checkboxError" value="1" name="administrator" {{ old('administrator')==1 ? 'checked' : $member->administrator==1 ? 'checked':'' }}>
                        Droit administrateur
                    </label>
                </div>
        </div>
        <div class="form-group row">
            <a class="btn btn-primary" href="/admin/members" >Retour à la liste</a>
            <button id="btn-member-edit"  class="btn btn-primary" type="button">Déverrouiller</button>
            <button id="btn-member-save" type="button"  class="btn btn-primary">Sauvegarder</button>
        </div>
    </form>
    <script>
        lockForm('#form-edit-member', '#btn-member-edit','#btn-member-save');
        let btn=document.getElementById('btn-member-save');
        VERIF.verifOnCLick(btn,'form-edit-member','edit-group-form');
    </script>



@endsection
