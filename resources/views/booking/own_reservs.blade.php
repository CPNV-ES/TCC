<!-- table of own reservations -->
@if(isset($ownreservs) AND count($ownreservs)>0)
<div>
<h4>Mes réservations </h4>
<table class="table" style="text-align:center;">
  <tr>
    <th>Date et heure</th>
    <th>Court</th>
    <th>qui ?</th>
    <th>Avec qui ?</th>
    <th>Action</th>
  </tr>
  @foreach($ownreservs as $reserv)
  <tr>
    <td>{{ $reserv->dateTimeStart }}</td>
    <td>{{ $reserv->court->name }}</td>
    <td>{{ $reserv->personal_information_who->firstname }} {{ $reserv->personal_information_who->lastname }}</td>
    <td>{{ $reserv->personal_information_with_who->firstname }} {{ $reserv->personal_information_with_who->lastname }}</td>
    <td>
     Delete_id : {{ $reserv->id }}
    </td>
  </tr>
  @endforeach
</table>
</div>
@endif
