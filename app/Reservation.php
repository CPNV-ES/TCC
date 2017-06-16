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


    /**
     * Génère la configuration pour le calendrier (visualCalendar)
     * @param  integer $nbDays    le nombre de jours suivant $startDate a inclure dans le calendrier
     * @param  integer $courtId   l'id du court qui doit générer le calendrier
     * @param  string  $anchor    la regle css du conteneur du clendrier
     * @param  boolean $readOnly  définit si le calendrier est en read only ou pas
     * @param  boolean $multiple  définit si le calendrier est en mode de sélection multiple
     * @param  Date    $startDate définit la date de début
     * @return string/JSON        la configuration du calendrier en JSON
     */
    public static function getVcConfigJSON($nbDays = null, $courtId = null, $anchor = "div#vc-anchor", $readOnly = false, $multiple = false, $startDate = null)
    {
        // récupère la configuration
        $config = Config::first();
        // obtient l̈́'heure de debut et de fin doverture d'un court
        $openTime = date('H:i', strtotime($config->courtOpenTime));
        $closeTime = date('H:i', strtotime($config->courtCloseTime));
        // si pas de court definit, prend le premier de la bdd
        if($courtId == null) $court = Court::first();
        // sinon le cherhe dans la bdd
        else $court = Court::find($courtId);
        // si la date de début est null, la définit sur aujourd'hui
        if ($startDate == null)  $startDate = new \DateTime();
        // définit la date de fin en fonction de l'authentification et du nbdays donné en parametre.
        if (is_int($nbDays) && $nbDays > 0) $endDate =(new \DateTime());
        elseif (Auth::check()) $endDate= (new \DateTime())->add(new \DateInterval('P'.($court->nbDays-1).'D'));
        else $endDate = (new \DateTime())->add(new \DateInterval('P'.($config->nbDaysLimitNonMember-1).'D'));

        // récupèrere toutes les réservations du court
        $planifiedReservations = Reservation::where('fkCourt', $court->id)
                                            ->whereNull('confirmation_token')
                                            ->whereBetween('dateTimeStart', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d').' 23:59'])
                                            ->get();
        // genere la date de commencement avec l'pheure douverture du court
        $zeroDate=new \DateTime($startDate->format('Y-m-d').' '.$openTime);
        // obtient la différence d'heure entre l'heure de début et celle d'ouverture du court
        $hdiff=$startDate->diff($zeroDate)->format('%H');
        // cree le ltableu de reservation vide
        $res=[];

        // Si l'utilisateur est authentifié :
        if (Auth::check()) {
          // obtient l id de personal information 
          $personal_info_id = Auth::user()->fkPersonalInformation;

          // récupere les réservations de la personne authentifiée pour la periode convenue
          $myReservs=Reservation::where('dateTimeStart', '>',$startDate->format('Y-m-d H:i'))
          ->whereNull('confirmation_token')
          ->where(function($q) use ($personal_info_id){
              $q->where('fkWho', $personal_info_id);
              $q->where('fkWithWho', '<>', 'null');
              $q->orWhere('fkWithWho', $personal_info_id);
          })->get();

          // récupere les réservations de la personne authentifiée pour la periode convenue en fonction du court
          $myReservsByCourts=Reservation::whereBetween('dateTimeStart', [$startDate->format('Y-m-d H:i'), $endDate->format('Y-m-d').' 23:59'])
          ->where('fkCourt',$court->id)
          ->whereNull('confirmation_token')
          ->where(function($q) use ($personal_info_id){
              $q->where('fkWho', $personal_info_id);
              $q->orWhere('fkWithWho', $personal_info_id);
          })->get();

          // si le nombre des prochaines réservation de la personne dépasse celles 
          //    autorisee pas la configuration: passe en mode read only
          if(count($myReservs)>=$config->nbReservations ) $readOnly=true;
          // insere la configuration pour chacune des réservations personnelles
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
        // si l'utilisateur n' est pas authentifié : 
        else{

            //récupere toutes les réservations de non-membre
            $nonMemberReservation = Reservation::whereHas('personal_information_who' ,function($q){
                $q->has('user', '<', 1);
            })->where('confirmation_token', null)->whereBetween('dateTimeStart', [$startDate->format('Y-m-d H:1'), $endDate->format('Y-m-d').' 23:59'])->get();

           // insère toutes les res. de non-membre dans la config
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
        // insère toutes les autres réservations dans la config
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
        // joute des réservations fictive afin de refuser un nouvelle réservation
        //    si la heure est déja passée
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

        // génère les éléments principaux de la config et insère les réservations dedans
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
        // retourne la config au format JSON // PS: bien de lamusement si tu dois modifier ça 
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
