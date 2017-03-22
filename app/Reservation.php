<?php

namespace App;

use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Config;

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
    public static function getVcConfigJSON($court = null, $anchor = "div#vc-anchor", $readOnly = false, $multiple = false, $startDate = null)
    {

        if ($startDate == null)  $startDate = new \DateTime();
        $endDate= (new \DateTime())->add(new \DateInterval('P5D'));
        if($court == null) $court = Court::first()->id;

        $planifiedReservations = Reservation::where('fkCourt', $court)->whereBetween('dateTimeStart', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d').' 23:59'])->get();
        $Userid=Auth::user()->id;
        $myReservs=Reservation::whereBetween('dateTimeStart', [$startDate->format('Y-m-d H:i'), $endDate->format('Y-m-d').' 23:59'])
        ->where(function($q){
            $Userid=Auth::user()->id;
            $q->where('fkWho', $Userid);
            $q->orWhere('fkWithWho', $Userid);
        })->get();
        $myReservsByCourts=Reservation::whereBetween('dateTimeStart', [$startDate->format('Y-m-d H:i'), $endDate->format('Y-m-d').' 23:59'])
        ->where('fkCourt',$court)
        ->where(function($q){
            $Userid=Auth::user()->id;
            $q->where('fkWho', $Userid);
            $q->orWhere('fkWithWho', $Userid);
        })->get();
        //print_r(count($myReservs));die;
        if(count($myReservs)>=Config::first()->nbReservations )$readOnly=true;
        $res=[];
        $zeroDate=new \DateTime($startDate->format('Y-m-d').' 08:00');
        $hdiff=$startDate->diff($zeroDate)->format('%H');
        foreach($myReservsByCourts as $planifiedReservation )
        {
            $res[]=[
                'datetime' => $planifiedReservation->dateTimeStart,
                'type' => $planifiedReservation->type_reservation->type.' vc-own-planif', // that's going to the class of the box
                'title' => $planifiedReservation->personal_information_who->firstname.' '.$planifiedReservation->personal_information_who->lastname,
                'clickable' => ((new \DateTime($planifiedReservation->dateTimeStart))->getTimestamp() > $startDate->getTimestamp())
            ];
        }
        // for clickable ->
        foreach($planifiedReservations as $planifiedReservation )
        {
            $res[]=[
                'datetime' => $planifiedReservation->dateTimeStart,
                'type' => $planifiedReservation->type_reservation->type, // that's going to the class of the box
                'title' => $planifiedReservation->personal_information_who->firstname.' '.$planifiedReservation->personal_information_who->lastname,
                'clickable' => false
            ];
        }
        for($i=0;$i<$hdiff;$i++)
        {
            $res[]=[
                'datetime' => ((new \DateTime($zeroDate->format('Y-m-d H:i')))->add(new \DateInterval('PT'.$i.'H')))->format('Y-m-d H:i'),
                'type' => 'vc-passed', // that's going to the class of the box
                'title' => '',
                'clickable' => false
            ];
        }


        $config = [
            'anchor' => $anchor,
            'params' => [
                'readonly' => $readOnly,
                'multiple' => $multiple
            ],
            'dates' => [[$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]],
            'hours' => [
                'ranges' =>[['08:00','20:00']],
                'period' => '01:00'
            ],
            'planified' => $res

        ];
        return json_encode($config);
    }
}
