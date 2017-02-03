<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{

    public function reservations() {
        return $this->hasMany('App\Reservation', 'fkCourt');
    }
}
