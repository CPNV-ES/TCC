<?php

namespace App;

use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        "dateTimeStart",
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
        return $this->belongsTo('App\PersonalInformation', 'fkWho');
    }

    public function personal_information_with_who() {
        return $this->belongsTo('App\PersonalInformation', 'fkWithWho');
    }

    public function invitation() {
        return $this->hasOne('App\Invitation', 'fkReservation');
    }
    //return the config for the visual calendar
    public static function getVcConfigJSON($readOnly = false, $multiple = false, $startDate = null)
    {

        if ($startDate == null)  $startDate = new \DateTime();
        $endDate= (new \DateTime())->add(new \DateInterval('P5D'));

        $planifiedReservations = Reservation::whereBetween('dateTimeStart', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d').' 23:59'])->get();
        $res=[];
        foreach($planifiedReservations as $planifiedReservation )
        {
            $res[] =[
                'datetime' => $planifiedReservation->dateTimeStart,
                'type' => $planifiedReservation->type_reservation->type, // that's going to the class of the box
                'title' => $planifiedReservation->personal_information_who->firstname.' '.$planifiedReservation->personal_information_who->lastname
            ];
        }

        $config = [
            'anchor' => 'div#vc-anchor',
            'params' => [
                'readonly' => $readOnly,
                'multiple' => $multiple
            ],
            'dates' => [[$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]],
            'hours' => [
                'ranges' =>[['08:00','12:00'],['14:00','20:00']],
                'period' => '01:00'
            ],
            'planified' => $res

        ];
        return json_encode($config);
    }
}
