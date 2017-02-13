<!--
Author: ...
Date:
Modified by : I.Goujgali
Last Modif.: 20.01.17
Description : Displays a table of the members implements with Datatable https://datatables.net/.
              to view/edit a member click on 'Voir info' or simply on the row
-->
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
                <th>Validé</th>

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
              <th>Validé</th>

            </tr>
        </tfoot>
      <tbody>

          @foreach($infoUsers as $infoUser)
          <tr>
              <td>{{$infoUser->id}}</td>
              <td>{{$infoUser->user->username}}</td>
              <td>{{$infoUser->lastname}}</td>
              <td>{{$infoUser->firstname}}</td>
              <td>{{$infoUser->localities->name}}</td>
              <td>@if($infoUser->user->active == 1) Oui @else Non @endif</td>
              <td>@if($infoUser->toVerify == 1) Oui @else Non @endif</td>
              <!-- <td>{!! $infoUser->user->username != null ? '<a class="btn btn-primary" href="/admin/members/'.$infoUser->user->id.'/edit" >Voir info</a>' : "" !!}  </td> -->

          </tr>
          @endforeach
      </tbody>
      </table>
      {!! Html::script('/js/datatables/dataTables.min.js') !!}
      {!! Html::script('/js/datatables/MemberTable.js') !!}
{{-- END IGI--}}
@endsection
