<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personal_information extends Model
{

    public function localities() {
        return $this->belongsTo('App\Localitie', 'fkLocality');
    }

    public function user() {
        return $this->hasOne('App\User', 'fkPersonalInformation');
    }

    public function reservations_who() {
        return $this->hasMany('App\Reservation', 'fkWho');
    }

    public function reservations_with_who() {
        return $this->hasMany('App\Reservation', 'fkWithWho');
    }
}
