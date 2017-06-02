<!-- table of own reservations -->
@if(isset($ownreservs) AND count($ownreservs)>0)

<div>
<h4>Mes réservations </h4>
<table id="own-reservations" class="table table-striped" style="text-align:center;">
  <thead>
    <tr>
      <th>Date et heure</th>
      <th>Court</th>
      <th>Par qui ?</th>
      <th>Avec qui ?</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach($ownreservs as $reserv)
    <tr>
      <td>{{ date('d.m.Y H:i', strtotime($reserv->dateTimeStart)) }}</td>
      <td>{{ $reserv->court->name }}</td>
      <td>{{ $reserv->personal_information_who->firstname }} {{ $reserv->personal_information_who->lastname }}</td>
      <td>@if($reserv->personal_information_with_who){{ $reserv->personal_information_with_who->firstname }} {{ $reserv->personal_information_with_who->lastname }}@else - @endif</td>
      <td>
        <button type="button" id="btn-del-res-{{ $reserv->id }}" class="btn btn-danger">
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
  </tbody>
</table>
</div>
@endif

@if (isset($oldReservations) && $oldReservations->count() > 0)

<div id="old-reservations-div">
  <h4>Mes réservations passées </h4>
  <table class="table table-striped" style="text-align:center;">
    <thead>
      <tr>
        <th>Date et heure</th>
        <th>Court</th>
        <th>Par qui ?</th>
        <th>Avec qui ?</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($oldReservations as $reservation)
        <tr >
          <td> {{ date('d.m.Y H:i', strtotime($reservation->dateTimeStart)) }}</td>
          <td> {{ $reservation->court->name }}</td>
          <td>{{ $reservation->personal_information_who->firstname }} {{ $reservation->personal_information_who->lastname }}</td>
          <td>@if($reservation->personal_information_with_who){{ $reservation->personal_information_with_who->firstname }} {{ $reservation->personal_information_with_who->lastname }}@else - @endif</td>
          <td> - </td>
        </tr>
      @endforeach
    </tbody>
  </table>

</div>
  <div class="text-center">
    <button class="btn btn-primary" id="btnOldReservation" data-show="false" style="align:center;">Afficher les réservations passées</button>
  </div>
<script>
    $("#btnOldReservation").click(function(){
        $("#old-reservations-div").toggle(300, function() {
          footerAlign();
        });
        if($("#btnOldReservation").data("show") == false) $("#btnOldReservation").data("show", true).text("Cacher les réservations passées");
        else $("#btnOldReservation").text("Afficher les réservations passées").data("show", false);
    });

</script>
@endif
