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
      <button type="button" id="btn-del-res-{{ $reserv->id }}" class="btn btn-danger btn-block">
        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
      </button>
      <script type="text/javascript">
        $("#btn-del-res-{{ $reserv->id }}").click(function(){
          var myDate = parseDate("{{ $reserv->dateTimeStart }}");
          $("#date-del-reserv").val("{{ $reserv->dateTimeStart }}");
          $("#id-del-reserv").val("{{ $reserv->id}}");
          $("#court-del-reserv").val("{{ $reserv->court}}");
          $("#modal-del-resume").html('Supprimer la réservation du ' +myDate.getUTCDate().toStringN(2)+ "-" + (myDate.getMonth() + 1).toStringN(2) + "-" + myDate.getFullYear()+' à '+myDate.getHours().toStringN(2) + ":" + myDate.getMinutes().toStringN(2) );
          $('#del-resevation-modal').modal('show');
        });
      </script>
    </td>
  </tr>
  @endforeach
</table>
</div>
@endif
