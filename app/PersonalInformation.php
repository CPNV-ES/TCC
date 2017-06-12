<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Locality;
use App\Config;

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
        'fkLocality',
        '_token',
        'toVerify'
    ];

    public function localities() {
        return $this->belongsTo('App\Locality', 'fkLocality');
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
        return Locality::firstOrCreate(['NPA'=>$npa,'name'=>ucwords($name)])->id;
    }
    
    public function hasRightToReserve($dateStart, $courtId)
    {
        $config = Config::first();

        if($this->user) $nbDays = Court::find($courtId)->nbDays;
        else $nbDays = $config->nbDaysLimitNonMember;

        $dateMax = (new \DateTime())->add(new \DateInterval('P'.($nbDays-1).'D'));

        if(strtotime($dateStart->format('Y-m-d')) <= strtotime($dateMax->format('Y-m-d'))) return true;
        else return false;
    }
}
