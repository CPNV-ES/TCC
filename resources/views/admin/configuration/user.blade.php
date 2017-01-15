@extends('layouts.admin')

@section('title')
    Modification de l'utilisateur {{$user->first_name}} {{$user->last_name}}
@endsection
@section('content')
<div class="container">
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!--
TODO: - add other field if asked (address - zip code - etc.)
      - make client side verification.


-->
<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/members/'.$user->id) }}">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Pseudonyme</label>
        <div class="col-10">
            <input class="form-control" name="login{{$user->id}}" type="text" value="{{$user->login}}" id="example-text-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Prénom</label>
        <div class="col-10">
            <input class="form-control" name="first_name" type="text" value="{{$user->first_name}}" id="example-text-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="example-text-input" name="lbl_last_name" class="col-2 col-form-label">Nom</label>
        <div class="col-10">
            <input class="form-control" name="last_name" type="text" value="{{$user->last_name}}" id="example-text-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="example-text-input" name="lbl_adresse" class="col-2 col-form-label">Adresse</label>
        <div class="col-10">
            <input class="form-control" name="address" type="text" value="{{$user->adress}}" id="example-text-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="example-text-input" name="lbl_zip_code" class="col-2 col-form-label">NPA</label>
        <div class="col-10">
            <input class="form-control" name="zip_code" type="text" value="{{$user->adress}}" id="example-text-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="example-text-input" name="lbl_city "class="col-2 col-form-label">Localité</label>
        <div class="col-10">
            <input class="form-control" name="city" type="text" value="{{$user->city}}" id="example-text-input">
        </div>
    </div>
    <div class="form-group row">
        <button type="submit" class="btn btn-primary">Modifier</button>
    </div>
</form>
</div>
@endsection
<!--
CHAMPS POSSIBLE
last_name | first_name | address | zip_code | city | email| mobile_phone | home_phone | birth_date
-->
