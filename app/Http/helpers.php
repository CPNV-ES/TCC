<?php
use Illuminate\Support\Facades\Auth;
use App\Reservation;
use App\Config;

function maxReservations() {
  $todayDateTime = date('Y-m-d H:i:s');
  $config = Config::first();
  $personal_info_id = Auth::user()->fkPersonalInformation;
  $nbReservations = Reservation::where('dateTimeStart', '>=', $todayDateTime)
                      ->where(function ($q) use ($personal_info_id) {
                          $q->where('fkWho', $personal_info_id);
                          $q->where('fkWithWho', '<>', 'null');
                          $q->orWhere('fkWithWho', $personal_info_id);
                      })->count();

  $max_reservations = false;
  if ($nbReservations >= $config['nbReservations']) {
    $max_reservations = true;
  }
  return $max_reservations;
}

 ?>
