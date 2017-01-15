@extends('layouts.admin')

@section('title')
    Gestion des membres
@endsection

@section('content')
    <!--<div class="row">
        <div id="message"></div>
        <div id="jqxmember">
        </div>
    </div>-->
    <div id="">

      <table id="members-table" class="display" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Pseudonyme</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Ville</th>
                <th>Actif</th>
                <th>Validé</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
              <th> Pseudonyme</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Ville</th>
              <th>Actif</th>
              <th>Validé</th>
              <th></th>
            </tr>
        </tfoot>
      <tbody>
          @foreach($members as $member)
          <tr>
              <td>{{$member->login}}</td>
              <td>{{$member->last_name}}</td>
              <td>{{$member->first_name}}</td>
              <td>{{$member->city}}</td>
              <td>{{$member->active}}</td>
              <td>{{$member->validate}}</td>
              <td><a class="btn btn-primary" href="/admin/members/{{$member->id}}/edit" >Modifier</a></td>
          </tr>
          @endforeach
    </div>
@endsection
