@extends('layouts.admin')

@section('title')
    Détail de l'utilisateur {{$member->first_name}} {{$member->last_name}} ({{$member->login}})
@endsection
@section('content')
<div class="container">
      <table class="table table-user-information">
        <tbody>
          <tr>
            <td>Pseudonyme:</td>
            <td>{{$member->login}}</td>
          </tr>
          <tr>
            <td>Prénom:</td>
            <td>{{$member->first_name}}</td>
          </tr>
          <tr>
            <td>Nom:</td>
            <td>{{$member->last_name}}</td>
          </tr>
          <tr>
            <td>Adresse:</td>
            <td>{{$member->address}}</td>
          </tr>
          <tr>
            <td>NPA:</td>
            <td>{{$member->zip_code}}</td>
          </tr>
          <tr>
            <td>Localité</td>
            <td>{{$member->city}}</td>
          </tr>
          <tr>
            <td>Téléphone mobile:</td>
            <td>{{$member->mobile_phone}}</td>
          </tr>
          <tr>
            <td>Téléphone fixe</td>
            <td>{{$member->home_phone}}</td>
          </tr>
          <tr>
            <td>E-mail</td>
            <td>{{$member->email}}</td>
          </tr>


        </tbody>
      </table>
      <a href="/admin/members" class="btn btn-primary">Retour</a>
      <a href="/admin/members/{{$member->id}}/edit" class="btn btn-primary">Modifier</a>
</div>
@endsection
