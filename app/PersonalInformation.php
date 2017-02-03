<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Localitie;

class PersonalInformation extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'street',
        'streetNbr',
        'email',
        'telephone',
        'birthDate',
        'fkLocality'
    ];

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


    public static function setLocality($npa,$name){
        return Localitie::firstOrCreate(['NPA'=>$npa,'name'=>ucwords($name)])->id;
    }
}
