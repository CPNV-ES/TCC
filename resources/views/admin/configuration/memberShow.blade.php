@extends('layouts.admin')

@section('title')
    Détail de l'utilisateur {{$member->first_name}} {{$member->last_name}} ({{$member->login}})
@endsection
@section('content')
<div class="container">

<!--
TODO: - demander pour le numero de rue car inexistant actuellement (old db)
      -(implement client side verification)
-->


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

<!-- | id | last_name | first_name | address | zip_code | city | email         | mobile_phone | home_phone | birth_date | password
          | login | token | remember_token | active | to_verify | validate | administrator | created_at          | updated_at

 -->


@endsection
<!--
CHAMPS POSSIBLE
last_name | first_name | address | zip_code | city | email| mobile_phone | home_phone | birth_date

champ demandé
[firstnameV, lastnameV,streetV, streetnbrX, telephoneV, emailV, usernameV].
-->
