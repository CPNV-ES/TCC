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

    public static function getVcConfigHomeJSON($court, $anchor = "div#vc-anchor", $readOnly = false, $multiple = false, $startDate = null)
    {

        if ($startDate == null)  $startDate = new \DateTime();
        $endDate = new \DateTime();

        $planifiedReservations = Reservation::where('fkCourt', $court)->whereBetween('dateTimeStart', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d').' 23:59'])->get();
        $zeroDate=new \DateTime($startDate->format('Y-m-d').' 08:00');
        $hdiff=$startDate->diff($zeroDate)->format('%H');

        if (Auth::check()) {
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
          foreach($myReservsByCourts as $planifiedReservation )
          {
              $res[]=[
                  'datetime' => $planifiedReservation->dateTimeStart,
                  'type' => $planifiedReservation->type_reservation->type.' vc-own-planif', // that's going to the class of the box
                  'title' => $planifiedReservation->personal_information_who->firstname.' '.$planifiedReservation->personal_information_who->lastname,
                  'clickable' => ((new \DateTime($planifiedReservation->dateTimeStart))->getTimestamp() > $startDate->getTimestamp())
              ];
          }
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
