<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type_reservation extends Model
{

    public function reservations() {
        return $this->hasMany('App\Reservation', 'fkTypeReservation');
    }
}
