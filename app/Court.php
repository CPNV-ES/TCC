<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Court extends Model
{

    protected $fillable = [
        'name',
        'state',
        'nbDays'
    ];

    public function reservations() {
        return $this->hasMany('App\Reservation', 'fkCourt');
    }
}
