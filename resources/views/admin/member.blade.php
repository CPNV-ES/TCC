<!--
Author: ...
Date:
Modified by : I.Goujgali, S. Forsyth
Last Modif.: 17.02.2017
Description : Displays a table of the members implements with Datatable https://datatables.net/.
              to view/edit a member click on 'Voir info' or simply on the row
Modified : Added checks when displaying user information in case a user doesn't have an account.
-->
@extends('layouts.admin')

@section('title')
    Gestion des membres
    <a href="/register" class="btn btn-success">Ajouter</a>
@endsection

@section('content')

{{--IGI - display the table of members--}}
      <table id="members-table" class="display table member-list" width="100%" cellspacing="0">
        <thead>
            <tr>
              <th>id</th>
              <th>Pseudonyme</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Ville</th>
              <th>Actif</th>
              <th>Infos à validé</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
              <th>id</th>
              <th>Pseudonyme</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Ville</th>
              <th>Actif</th>
              <th>Infos à validé</th>
            </tr>
        </tfoot>
      <tbody>

          @foreach($infoUsers as $infoUser)
          <tr>
              <td>{{$infoUser->id}}</td>
              <td>{{(($infoUser->user) ? $infoUser->user->username : '-')}}</td>
              <td>{{$infoUser->lastname}}</td>
              <td>{{$infoUser->firstname}}</td>
              <td>{{(($infoUser->localities) ? $infoUser->localities->name : '-')}}</td>
              <td>{{(($infoUser->user) ? (($infoUser->user->active == 1) ? 'Oui' : 'Non') : '-')}}</td>
              <td>{{(($infoUser->toVerify == 1) ? 'Oui' : 'Non')}}</td>

          </tr>
          @endforeach
      </tbody>
      </table>
      {!! Html::script('/js/datatables/dataTables.min.js') !!}
      {!! Html::script('/js/datatables/memberTable.js') !!}
{{-- END IGI--}}
@endsection
