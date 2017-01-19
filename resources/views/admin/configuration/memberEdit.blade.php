@extends('layouts.admin')

@section('title')
    Modification de l'utilisateur {{$member->first_name}} {{$member->last_name}}
@endsection
@section('content')
<div class="container">
{{--@if (count($errors) > 0)--}}
    {{--<div class="alert alert-danger">--}}
        {{--<ul>--}}
            {{--@foreach ($errors->all() as $error)--}}
                {{--<li>{{ $error }}</li>--}}
            {{--@endforeach--}}
        {{--</ul>--}}
    {{--</div>--}}
{{--@endif--}}
<!--
TODO: - demander pour le numero de rue car inexistant actuellement (old db)
      -(implement client side verification)


-->
<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/members/'.$member->id) }}">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Pseudonyme*</label>
        <div class="col-10">
            <input class="form-control" name="username" type="text" value="{{$member->login}}" >
            @if ($errors->has('username'))
                <span class="help-block">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group  @if($errors->has('first_name')) {{'has-error'}} @endif row">
        <label for="example-text-input" class="col-2 col-form-label">Prénom*</label>
        <div class="col-10">
            <input class="form-control" name="first_name" type="text" value="{{$member->first_name}}" >
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
            <input class="form-control" name="last_name" type="text" value="{{$member->last_name}}" >
            @if ($errors->has('last_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('last_name') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group row @if($errors->has('email')) {{'has-error'}} @endif">
        <label for="example-text-input" name="lbl_last_name" class="col-2 col-form-label">Mail*</label>
        <div class="col-10">
            <input class="form-control" name="email" type="text" value="{{$member->email}}" >
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
            <input class="form-control" name="address" type="text" value="{{$member->address}}" >
            @if ($errors->has('address'))
                <span class="help-block">
                    <strong>{{ $errors->first('address') }}</strong>
                </span>
            @endif
        </div>
    </div>
    {{--<div class="form-group row">--}}
        {{--<label for="example-text-input" name="lbl_adresse" class="col-2 col-form-label">Numéro de rue</label>--}}
        {{--<div class="col-10">--}}
            {{--<input class="form-control" name="address" type="text" value="{{$member->address}}" >--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="form-group row @if($errors->has('zip_code')) {{'has-error'}} @endif">
        <label for="example-text-input" name="lbl_zip_code" class="col-2 col-form-label">NPA*</label>
        <div class="col-10">
            <input class="form-control" name="zip_code" type="text" value="{{$member->zip_code}}" >
            @if ($errors->has('zip_code'))
                <span class="help-block">
                    <strong>{{ $errors->first('zip_code') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row @if($errors->has('city')) {{'has-error'}} @endif">
        <label for="example-text-input" name="lbl_city "class="col-2 col-form-label">Localité*</label>
        <div class="col-10">
            <input class="form-control" name="city" type="text" value="{{$member->city}}" >
            @if ($errors->has('city'))
                <span class="help-block">
                    <strong>{{ $errors->first('city') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group row @if($errors->has('mobile_phone')) {{'has-error'}} @endif">
        <label for="example-text-input" name="lbl_mobile_phone" class="col-2 col-form-label">Téléphone portable*</label>
        <div class="col-10">
            <input class="form-control" name="mobile_phone" type="text" value="{{$member->mobile_phone}}" >
            @if ($errors->has('mobile_phone'))
                <span class="help-block">
                    <strong>{{ $errors->first('mobile_phone') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group row" @if($errors->has('first_name')) {{'has-error'}} @endif>
        <label for="example-text-input" name="lbl_home_phone" class="col-2 col-form-label">Téléphone fixe*</label>
        <div class="col-10">
            <input class="form-control" name="home_phone" type="text" value="{{$member->home_phone}}" >
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
                    <input type="checkbox" id="checkboxError" value="1" name="active" @if($member->active) {{'checked'}} @endif>
                    Rendre le compte actif
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="checkboxError" value="1" name="to_verify" @if($member->to_verify) {{'checked'}} @endif>
                    Demander une vérification
                </label>

            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="checkboxError" value="1" name="validate" @if($member->validate) {{'checked'}} @endif>
                    Valider le compte
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="checkboxError" value="1" name="administrator" @if($member->administrator) {{'checked'}} @endif>
                    Droit administrateur
                </label>
            </div>

    </div>
    <div class="form-group row">
        <button type="submit" class="btn btn-primary">Sauvegarder</button>
    </div>
</form>
</div>
@endsection
<!--
CHAMPS POSSIBLE
last_name | first_name | address | zip_code | city | email| mobile_phone | home_phone | birth_date

champ demandé
[firstnameV, lastnameV,streetV, streetnbrX, telephoneV, emailV, usernameV].
-->
