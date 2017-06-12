<?php

namespace App\Http\Controllers\Booking;

use App\Locality;
use App\Reservation;
use App\Season;

use Carbon\Carbon;
use Faker\Provider\ar_JO\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Validator;
use DB;

use App\PersonalInformation;
use App\Court;
use App\Config;
use App\User;

use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $courts = Court::where('state', 1)->get();
        if(Auth::check())
        {
          $personal_info_id = Auth::user()->fkPersonalInformation;
          $queryWho = DB::table('personal_informations')
            ->join('reservations AS r', 'r.fkWho', '=', 'personal_informations.id')
            ->where('r.fkWithWho', '=', $personal_info_id)
            ->groupBy('personal_informations.id')
            ->select(['personal_informations.*', \DB::raw('COUNT(r.fkWho) AS nb_times_played')]);

          $queryBoth = DB::table('personal_informations')
            ->join('reservations AS r', 'r.fkWithWho', '=', 'personal_informations.id')
            ->rightJoin('users AS u', 'u.fkPersonalInformation', '=', 'personal_informations.id')
            ->where('r.fkWho', '=', $personal_info_id)
            ->unionAll($queryWho)
            ->groupBy('personal_informations.id')
            ->select(['personal_informations.*', \DB::raw('COUNT(r.fkWithWho) AS nb_times_played')]);

          // Get favorit member orderd by the amount of times played with
          $memberFav = PersonalInformation::selectRaw('ps.id, ps.firstname, ps.lastname, ps.street, ps.streetNbr, ps.telephone, ps.email, ps.toVerify, ps.birthDate, ps._token, ps.fkLocality, ps.created_at, ps.updated_at, ps.deleted_at, SUM(ps.nb_times_played) AS reservations_count')
            ->from(\DB::raw('('.$queryBoth->toSql().') AS ps'))
            ->mergeBindings($queryBoth)
            ->groupBy('ps.id')
            ->get()
            ->sortByDesc('reservations_count');

          // Create a table of the member id that the user has already played with
          $id_member_fav = [(int)$personal_info_id];
          foreach ($memberFav as $value) {
            $id_member_fav[] = $value['id'];
          }

          // Get all members that the user hasn't played with
          $allMember = PersonalInformation::whereNotIn('personal_informations.id', $id_member_fav)
            ->rightJoin('users AS u', 'u.fkPersonalInformation', '=', 'personal_informations.id')
            ->get()
            ->sortBy('firstname');

          $startDate = new \DateTime();
          $endDate= (new \DateTime())->add(new \DateInterval('P5D'));
          $ownreservs = Reservation::whereBetween('dateTimeStart', [$startDate->format('Y-m-d H:i'), $endDate->format('Y-m-d').' 23:59'])->has('personal_information_with_who')
            ->where(function($q) use ($personal_info_id){
                $q->where('fkWho', $personal_info_id);
                $q->orWhere('fkWithWho', $personal_info_id);
            })
            ->orderBy('dateTimeStart', 'asc')
            ->get();

          $oldReservations = Reservation::where('dateTimeStart', '<', $startDate->format('Y-m-d H:i'))->has('personal_information_with_who')
              ->where(function($q) use ($personal_info_id){
                  $q->where('fkWho', $personal_info_id);
                  $q->orWhere('fkWithWho', $personal_info_id);
              })
              ->orderBy('dateTimeStart', 'desc')
              ->get();

          //we merge the two collections of members
          $membersList = $memberFav->merge($allMember);

          return view('booking/home',compact('membersList', 'courts', 'ownreservs', 'oldReservations'));
        }
        else {

          $localities = Locality::all();
          return view('booking/home', compact('courts' , 'localities'));
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $configs = Config::orderBy('created_at', 'desc')->first();

        $todayDate   = date('Y-m-d');
        $todayDateTime = date('Y-m-d H:i:s');

        $fkCourt =   $request->input('fkCourt');
        $court = Court::find($request->input('fkCourt'));

        $personalInfoWithWho = null;
        //Number of reservations of the creator of the reservation
        $startDate = new \DateTime($request->input('dateTimeStart'));
        $config = Config::first();


        Session::flash('currentCourt', $fkCourt);
        if(Auth::check())
        {
          $personal_info_id = Auth::user()->fkPersonalInformation;
          $user_id = Auth::user()->id;
          $is_non_member = false;
            $endDate= (new \DateTime())->add(new \DateInterval('P'.($court->nbDays-1).'D'));
            $userWho = User::find($user_id);
            $personalInfoWho = User::find($user_id)->personal_information;

            $nbReservationWho = Reservation::whereBetween('dateTimeStart', [$startDate->format('Y-m-d H:i'), $endDate->format('Y-m-d').' 23:59'])
                ->where(function($q) use ($personal_info_id){
                    $q->where('fkWho', $personal_info_id);
                    $q->where('fkWithWho', '<>', 'null');
                    $q->orWhere('fkWithWho', $personal_info_id);
                })->count();


            //check if the creator of the reservation and the invited person
            $invalidatedDateWho = Auth::user()->invalidatedDate;


            if($request->input('fkWithWho') == null)
            {
                $validator = Validator::make($request->all(),
                [
                    'invitFirstname' => 'required|max:50',
                    'invitLastname' => 'required|max:50'
                ],
                [
                    'invitFirstname.required' => 'Le champ prénom est obligatoire.',
                    'invitLastname.required' => 'Le champ nom est obligatoire.'
                ]);
                if($validator->fails())
                {

                    return back()->withInput()->withErrors($validator);
                }

                // check if the creator of the reservation has the invit right
                if(!Auth::user()->invitRight)
                {
                    Session::flash('errorMessage', "Vous ne possédez pas le droit d'inviter des gens");
                    return redirect('/booking');
                }

                //We don't store the personal informations now
                $personalInfoWithWho = new PersonalInformation();
                $personalInfoWithWho->firstname = $request->input('invitFirstname');
                $personalInfoWithWho->lastname = $request->input('invitLastname');
            }
            else {

                if(!$userWithWho = User::find($request->input('fkWithWho')))
                {
                    Session::flash('errorMessage', "Votre invité n'existe pas");
                    return redirect('/booking');
                }

                //check if the number of reservations of the creator of the reservations and the invited person has not been exceeded
                if ($nbReservationWho >= Config::orderBy('created_at', 'desc')->first()->nbReservations)
                {
                    Session::flash('errorMessage', "Vous avez déjà atteint votre nombre maximum de reservations");
                    return redirect('/booking');
                }

                $invalidatedDateWithWho = Auth::user()->invalidatedDate;


                //Number of reservations of the person invited;
                $nbReservationWithWho = Reservation::where('dateTimeStart', '>', $todayDate)->where(function ($query) use ($request){
                    $query->where('fkWho', $request->input('fkWithWho'))
                        ->orWhere('fkWithWho', $request->input('fkWithWho'));
                })->count();

                if( $userWithWho->invalidatedDate != null &&
                    strtotime($userWithWho->invalidatedDate.' + '.$configs->nbDaysGracePeriod.' days') < strtotime($todayDate))
                {
                    Session::flash('errorMessage', "Le compte de votre invité n'est plus valide");
                    return redirect('/booking');
                }
                if ($nbReservationWithWho >= Config::orderBy('created_at', 'desc')->first()->nbReservations)
                {
                    Session::flash('errorMessage', "Votre partenaire a déjà atteint son nombre maximum de reservations");
                    return redirect('/booking');
                }
                // Can't reserve with your self
                if (PersonalInformation::find($personal_info_id)->id == $request->input('fkWithWho'))
                {
                    Session::flash('errorMessage', "Impossible de faire une réservation avec vous même");
                    return redirect('/booking');
                }
                $personalInfoWithWho = PersonalInformation::find($userWithWho->id);
                // We check if there's a date into the field invalidatedDate for the member who has made the reservation and the invited member.
                // If there is, we add the nb of days of the grace period. If the period has been exceeded we return an error
                if( Auth::user()->invalidatedDate != null &&
                    strtotime(Auth::user()->invalidatedDate. ' + '. $configs->nbDaysGracePeriod.' days') < strtotime($todayDate))
                {
                    Session::flash('errorMessage', "Votre compte n'est plus valide");
                    return redirect('/booking');
                }
            }

        }
        else {
          $is_non_member = true;
            $validator = Validator::make($request->all(),
                [
                  'lastname'  => 'required|max:50',
                  'firstname' => 'required|max:50',
                  'street'    => 'max:100',
                  'streetNbr' => 'max:45',
                  'npa'       => 'required|integer|digits:4',
                  'locality'  => 'required',
                  'email'     => 'required|email|max:255',
                  'telephone' => 'required'
                ]
                );
            $validator->setAttributeNames([
              'lastname'  => 'Nom',
              'firstname' => 'Prénom',
              'street'    => 'Rue',
              'streetNbr' => 'Numéro de rue',
              'npa'       => 'NPA',
              'locality'  => 'Localité',
              'telephone' => 'Téléphone'
            ]);
            if($validator->fails())
            {
                return back()->withInput()->withErrors($validator);
            }

            $endDate = (new \DateTime())->add(new \DateInterval('P'.($config->nbDaysLimitNonMember-1).'D'));

            //We don't store the personal informations now
            $personalInfoWho = new PersonalInformation($request->all());
        }
        //datetime of the reservation
        $dateTimeStart = $request->input('dateTimeStart');
        $dateTimeEnd   = date("Y-m-d H:i:s", strtotime($dateTimeStart)+60*60-1);

        //check if the date isn't in th past
        if($todayDateTime > $dateTimeEnd)
        {
            Session::flash('errorMessage', "Cette heure/date est déjà passée");
            return redirect('/booking');
        }

        //Number of reservations of the person invited;
        $nbReservationWithWho = Reservation::whereBetween('dateTimeStart', [$startDate->format('Y-m-d H:i'), $endDate->format('Y-m-d').' 23:59'])
            ->where(function($q)use ($request){
                $Userid=$request->input('fkWithWho');
                $q->where('fkWho', $Userid);
                $q->orWhere('fkWithWho', $Userid);
            })->count();
        //dd($startDate->format('Y-m-d H:i'));

        if (!$personalInfoWho->hasRightToReserve($startDate, $court->id))
        {
            Session::flash('errorMessage', "Vous ne pouvez pas réservez aussi loin dans le temps");
            return redirect('/booking');

        }


        // 13:00 -- 14:00+1
        $dateTimeStartLessDuration =  date("Y-m-d H:i:s", strtotime($dateTimeStart)-60*60+1);


        if(!Reservation::isHourFree($fkCourt, $dateTimeStart))
        {
            Session::flash('errorMessage', "Cette heure n'est pas libre, veuillez choisir une autre heure.");
            return redirect('/booking');
        }

        //Check if the court is available (in case of the court is in maintenance)
        $court = Court::find($fkCourt);
        if($court->state != 1)
        {
            Session::flash('errorMessage', "Ce court n'est pas disponible pour le moment, veuillez choisir un autre court");
            return redirect('/booking');
        }

        //Get the actual price
        $chargeAmount = Config::first()->currentAmount;

        if(isset($personalInfoWithWho))$personalInfoWithWho->save();


        if(isset($personalInfoWho)) {
          if ($is_non_member) {
            $locid = PersonalInformation::setLocality($request->input('npa'),$request->input('locality'));
            $information['fkLocality'] = $locid;
            $information = $request->except("_token");
            $personalInfoWho = new PersonalInformation($information);
          }
          $personalInfoWho->save();
        }
        // Insert in DB
        //-------------
        $reservationInfo = [
                 'dateTimeStart'        => $dateTimeStart,
                 'fkCourt'              => $fkCourt,
                 'fkWho'                => $personalInfoWho->id,
                 'fkTypeReservation'    => 1,
                 'fkWithWho'            => (isset($personalInfoWithWho->id) ? $personalInfoWithWho->id : null), //check if there is a invited person (in case it's a reservation is created by a member)
                 'chargeAmount'         => $chargeAmount,
                 'paid'                 => 0
        ];


        $reservation = Reservation::create($reservationInfo);

        $dateHour = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('dateTimeStart'))->format('d.m.Y H:i');

        //this var is used to add message into falsh message
        $additionalMessage = '';

        //check if the user who has made a reservation is member by checking if the personal informations are binded to a account
        if(is_null($personalInfoWho->user))
        {
            $reservation->confirmation_token = str_random(20);
            $reservation->save();

            Mail::send('emails.user.reservation_non-member',
                [
                    'last_name'     => $personalInfoWho->lastname,
                    'first_name'    => $personalInfoWho->firstname,
                    'court'         => $court->name,
                    'player'        => $personalInfoWho->lastname." ".$personalInfoWho->firstname,
                    'date_hours'    => $dateHour,
                    'token'         => $reservation->confirmation_token
                ],
                function ($message) use($personalInfoWho)
                {
                    $message->to($personalInfoWho->email)->subject('Votre réservation au Tennis Club Chavornay');
                });
            $additionalMessage = ' Vous devez confirmez votre réservation via l\'email qui vous a été envoyé';
        }
        else{
            $members = [$personalInfoWho , $personalInfoWithWho];
            $court = Court::find($fkCourt);

            foreach ($members as $member)
            {

                if(isset($member->email))
                {
                    $email = $member->email;

                    // Inform the players of the reservations
                    //---------------------------------------------------------------------------------
                    Mail::send('emails.user.reservation',
                        [
                            'last_name'     => $member->lastname,
                            'first_name'    => $member->firstname,
                            'court'         => $court->name,
                            'joueur1'       => $members[0]->lastname." ".$members[0]->firstname,
                            'joueur2'       => $members[1]->lastname." ".$members[1]->firstname,
                            'date_hours'    => $dateHour
                        ],
                        function ($message) use($email)
                        {
                            $message->to($email)->subject('Votre réservation au Tennis Club Chavornay');
                        });
                }
            }
        }

        Session::flash('successMessage', "Votre réservation a bien été enregistrée.".$additionalMessage);
        return redirect('/booking');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $reservation = Reservation::find($id);
        if($reservation==null){
            dd($id);
            Session::flash('errorMessage', "La reservation que vous essayer de supprimer n'existe pas ou plus");
            return redirect('/booking');
        }
        if (Auth::user()->fkPersonalInformation != $reservation->fkWho && Auth::user()->fkPersonalInformation != $reservation->fkWithWho)
        {
            Session::flash('errorMessage', "Vous essayer de supprimer une réservation qui ne vous appartient pas");
            return redirect('/booking');
        }
        $reservation->delete();

        // Select the information of the two players from the members

        $members = User::whereIn('id', [$reservation->fkWho, $reservation->fkWithWho])->with('personal_information');
        $court = Court::where('id', $reservation->fk_court)->get(['name']);
        $dateHour = Carbon::createFromFormat('Y-m-d H:i:s', $reservation->dateTimeStart)->format('d.m.Y H:i');
        foreach ($members as $member)
        {
            $email = $member->personal_information->email;

            // Inform the players of the reservations
            //---------------------------------------------------------------------------------
            Mail::send('emails.user.deletereservation', ['last_name' => $member->personal_information->lastname, 'first_name' => $member->personal_information->firstname, 'court' => $court[0]->name, 'joueur1' => $members[0]->personal_information->lastname." ".$members[0]->personal_information->firstname, 'joueur2' => $members[1]->personal_information->lastname." ".$members[1]->personal_information->firstname, 'date_hours' => $dateHour], function ($message) use($email)
            {
                $message->to($email)->subject('Suppression de votre réservation au Tennis Club Chavornay');
            });
            /////////////////////////////////////////////
        }
        Session::flash('successMessage', "Votre réservation a bien été supprimée");
        return redirect('/booking');
    }

    public function askCancellation(Request $request,$id)
    {
        $reservations = Reservation::where('id',$id);
        if($reservations->count())
        {
            $reservation = $reservations->first();
            if($reservation->personal_information_who->email == $request->email)
            {
                $reservation->remove_token = str_random(20);
                $reservation->save();

                $email= $reservation->personal_information_who->email;

                Mail::send('emails.user.deletereservationnonmember',
                    [
                        'last_name' => $reservation->personal_information_who->firstname,
                        'first_name' => $reservation->personal_information_who->lastname,
                        'court' => $reservation->court->name,
                        'date_hours' => $reservation->dateTimeStart,
                        'token' => $reservation->remove_token
                    ],function ($message) use ($email)
                    {
                        $message->to($email)->subject('Suppression de votre réservation au Tennis Club Chavornay');
                    });

                Session::flash('successMessage', "Un email de confirmation vous a été envoyé. Veuillez le consulter pour supprimer définitivement la réservation");
                return redirect('/booking');
            }
            else{
                Session::flash('errorMessage', "L'email ne correspond pas à celui fourni lors de la réservation");
                return redirect('/booking');
            }

        }
        else{
            Session::flash('errorMessage', "Cette réservation n'existe pas ou a déjà été supprimé");
            return redirect('/booking');

        }

    }

    public function cancellation(Request $request)
    {

        $reservations = Reservation::where('remove_token', $request->token)->get();
        if($reservations->count())
        {
            $reservations->first()->delete();
            Session::flash('successMessage', "Votre réservation a bien été supprimé");
            return redirect('/booking');

        }
        else{
            Session::flash('errorMessage', "Aucune demande de suppression pour cette réservation");
            return redirect('/booking');
        }
    }

    public function confirmation(Request $request)
    {
        $reservations = Reservation::where('confirmation_token' , $request->token);
        if($reservations->count())
        {
            $reservation = $reservations->first();

            if(Reservation::isHourFree($reservation->fkCourt, $reservation->dateTimeStart))
            {
                $reservation->confirmation_token = null;
                $reservation->save();
                Session::flash('successMessage', "Votre réservation a bien été confirmée");
                return redirect('/booking');
            }
            else
            {
                Session::flash('errorMessage', "L'heure a été prise par quelqu'un d'autre. Votre réservation va être supprimé. Veuillez en faire une nouvelle");
                return redirect('/booking');
            }
        }
        else{
            Session::flash('errorMessage', "Cette réservation n'existe pas ou a déjà été supprimé");
            return redirect('/booking');

        }
    }
}
