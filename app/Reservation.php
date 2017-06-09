<?php

namespace App;

use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Config;
use App\Court;

class Reservation extends Model
{
    protected $fillable = [
        "dateTimeStart",
        "fkWho",
        "fkWithWho",
        "fkTypeReservation",
        "fkCourt",
        "paid",
        "chargeAmount",
        "title"
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
    public static function getVcConfigJSON($nbDays = null, $courtId = null, $anchor = "div#vc-anchor", $readOnly = false, $multiple = false, $startDate = null)
    {
        $config = Config::first();
        $openTime = date('H:i', strtotime($config->courtOpenTime));
        $closeTime = date('H:i', strtotime($config->courtCloseTime));
        if($courtId == null) $court = Court::first();
        else $court = Court::find($courtId);
        if ($startDate == null)  $startDate = new \DateTime();


        if (is_int($nbDays) && $nbDays > 0) $endDate =(new \DateTime());
        elseif (Auth::check()) $endDate= (new \DateTime())->add(new \DateInterval('P'.($court->nbDays-1).'D'));
        else $endDate = (new \DateTime())->add(new \DateInterval('P'.($config->nbDaysLimitNonMember-1).'D'));




        $planifiedReservations = Reservation::where('fkCourt', $court->id)
                                            ->whereNull('confirmation_token')
                                            ->whereBetween('dateTimeStart', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d').' 23:59'])
                                            ->get();
        $zeroDate=new \DateTime($startDate->format('Y-m-d').' '.$openTime);
        $hdiff=$startDate->diff($zeroDate)->format('%H');
        $res=[];

        if (Auth::check()) {
          $user_id = Auth::user()->fkPersonalInformation;

          $myReservs=Reservation::where('dateTimeStart', '>',$startDate->format('Y-m-d H:i'))
          ->whereNull('confirmation_token')
          ->where(function($q) use ($user_id){
              $q->where('fkWho', $user_id);
              $q->where('fkWithWho', '<>', 'null');
              $q->orWhere('fkWithWho', $user_id);
          })->get();

          $myReservsByCourts=Reservation::whereBetween('dateTimeStart', [$startDate->format('Y-m-d H:i'), $endDate->format('Y-m-d').' 23:59'])
          ->where('fkCourt',$court->id)
          ->whereNull('confirmation_token')
          ->where(function($q) use ($user_id){
              $q->where('fkWho', $user_id);
              $q->orWhere('fkWithWho', $user_id);
          })->get();

          if(count($myReservs)>=$config->nbReservations ) $readOnly=true;
          foreach($myReservsByCourts as $planifiedReservation )
          {
              $res[]=[
                  'datetime' => $planifiedReservation->dateTimeStart,
                  'type' => $planifiedReservation->type_reservation->type.' vc-own-planif', // that's going to the class of the box
                  'title' => ($planifiedReservation->title != null) ? $planifiedReservation->title : $planifiedReservation->personal_information_who->firstname.' '.$planifiedReservation->personal_information_who->lastname,
                  'description' => $planifiedReservation->id,
                  'clickable' => ((new \DateTime($planifiedReservation->dateTimeStart))->getTimestamp() > $startDate->getTimestamp() && $planifiedReservation->personal_information_with_who != null)
              ];
          }
        }
        else{

            $nonMemberReservation = Reservation::whereHas('personal_information_who' ,function($q){
                $q->has('user', '<', 1);
            })->where('confirmation_token', null)->whereBetween('dateTimeStart', [$startDate->format('Y-m-d H:1'), $endDate->format('Y-m-d').' 23:59'])->get();

           foreach($nonMemberReservation as $planifiedReservation )
            {

                $res[]=[
                    'datetime' => $planifiedReservation->dateTimeStart,
                    'type' => $planifiedReservation->type_reservation->type.' vc-own-planif', // that's going to the class of the box
                    'title' => $planifiedReservation->personal_information_who->firstname.' '.$planifiedReservation->personal_information_who->lastname,
                    'description' => $planifiedReservation->id,
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
                'title' => ($planifiedReservation->title != null) ? $planifiedReservation->title : $planifiedReservation->personal_information_who->firstname.' '.$planifiedReservation->personal_information_who->lastname,
                'description' => $planifiedReservation->id,
                'clickable' => false
            ];
        }
        for($i=0;$i<$hdiff;$i++)
        {
            $res[]=[
                'datetime' => ((new \DateTime($zeroDate->format('Y-m-d H:i')))->add(new \DateInterval('PT'.$i.'H')))->format('Y-m-d H:i'),
                'type' => 'vc-passed', // that's going to the class of the box
                'title' => '',
                'description' => 'void',
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
                'ranges' =>[[$openTime, $closeTime]],
                'period' => '01:00'
            ],
            'planified' => $res

        ];
        return json_encode($config);
    }
    public static function isHourFree($fkCourt, $dateTimeStart)
    {
        $dateTimeStartLessDuration =  date("Y-m-d H:i:s", strtotime($dateTimeStart)-60*60+1);
        $dateTimeEnd   = date("Y-m-d H:i:s", strtotime($dateTimeStart)+60*60-1);

        $nbReservations = Reservation::where('fkCourt', $fkCourt)->whereNull('confirmation_token')->where(function($query) use ($dateTimeStartLessDuration,$dateTimeStart, $dateTimeEnd){
            $query->whereBetween('dateTimeStart', [$dateTimeStart, $dateTimeEnd]);
            $query->orWhereBetween('dateTimeStart', [$dateTimeStartLessDuration, $dateTimeStart]);
        })->count();

        if(!$nbReservations)
            return true;
        else{
            return false;
        }
    }
}
