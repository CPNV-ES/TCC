<?php
use Illuminate\Support\Facades\Auth;
use App\Reservation;
use App\Config;

function maxReservations() {
  $todayDateTime = date('Y-m-d H:i:s');
  $config = Config::first();
  $nbReservations = Reservation::where('dateTimeStart', '>=', $todayDateTime)
                      ->where(function($q) {
                          $Userid=Auth::user()->id;
                          $q->where('fkWho', $Userid);
                          $q->where('fkWithWho', '<>', 'null');
                          $q->orWhere('fkWithWho', $Userid);
                      })->count();

  $max_reservations = false;
  if ($nbReservations >= $config['nbReservations']) {
    $max_reservations = true;
  }
  return $max_reservations;
}

 ?>
