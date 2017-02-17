<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        "dateStart",
        "dateEnd",
        "hourStart",
        "hourEnd",
        "fkWho",
        "fkWithWho",
        "fkTypeReservation",
        "fkCourt",
        "paid",
        "chargeAmount"
    ];

    public function court() {
        return $this->belongsTo('App\Court', 'fkCourt');
    }

    public function type_reservation() {
        return $this->belongsTo('App\Type_reservation', 'fkTypeReservation');
    }

    public function personal_information_who() {
        return $this->belongsTo('App\Personal_information', 'fkWho');
    }

    public function personal_information_with_who() {
        return $this->belongsTo('App\Personal_information', 'fkWithWho');
    }

    public function invitation() {
        return $this->hasOne('App\Invitation', 'fkReservation');
    }
}
