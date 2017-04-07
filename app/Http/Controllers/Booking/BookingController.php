<?php

namespace App\Http\Controllers\Booking;

//use App\Models\Reservation;
//use App\Models\Season;

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

/*use App\Models\Member;
use App\Models\Booking;
use App\Models\Court;*/
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
        if ($request->ajax())
        {
            // Do we want for only one court
            if ($request->has('courtID'))
            {
                // Get court configuration
                //------------------------
                $configCourt = Court::find($request->input('courtID'));

                // Request doesn't work
                $reservations = Reservation::where('date_hours', 'LIKE', Carbon::today()->format('Y-m-d').'%')->where('fk_court', $configCourt->id)->get();
                $data['config'] = $configCourt;

                $i = 0;
                foreach ($reservations as $reservation)
                {
                    $member1 = Member::find($reservation->fk_member_1);
                    $member2 = Member::find($reservation->fk_member_2);
                    $data['reservation'][$i]['data'] = $reservation;
                    $data['reservation'][$i]['member_1'] = $member1->last_name;
                    $data['reservation'][$i]['member_2'] = $member2->last_name;
                    $i++;
                }

                return response()->json($data);
            }


            // Get court configuration
            //------------------------
            $configCourts = Court::all();

            // Not Member
            //-----------
            if (!Auth::check())
            {
                $i = 0;
                foreach ($configCourts as $configCourt)
                {
                    $bookingWindow = Carbon::today()->addDay($configCourt->booking_window_not_member);
                    $bookingCourt = Reservation::where('date_hours', '>', Carbon::today())->where('date_hours', '<', $bookingWindow)->where('fk_court', $configCourt->id)->get();
                    $data[$i]['config'] = $configCourt;
                    $data[$i]['config']['booking_window'] = $configCourt->booking_window_not_member;
                    $j = 0;
                    foreach ($bookingCourt as $reservation)
                    {
                        $member1 = Member::find($reservation->fk_member_1);
                        $member2 = Member::find($reservation->fk_member_2);
                        $data[$i]['reservation'][$j]['data'] = $reservation;
                        $data[$i]['reservation'][$j]['member_1'] = $member1->last_name;
                        $data[$i]['reservation'][$j]['member_2'] = $member2->last_name;
                        $j++;
                    }
                    $i++;
                }
                return response()->json($data);
            }

            // Member
            //-------
            $i = 0;
            foreach ($configCourts as $configCourt)
            {
                $bookingWindow = Carbon::today()->addDay($configCourt->booking_window_member);
                $bookingCourt = Reservation::where('date_hours', '>', Carbon::today())->where('date_hours', '<', $bookingWindow)->where('fk_court', $configCourt->id)->get();
                $data[$i]['config'] = $configCourt;
                $data[$i]['config']['booking_window'] = $configCourt->booking_window_member;
                $j = 0;
                foreach ($bookingCourt as $reservation)
                {
                    $member1 = Member::find($reservation->fk_member_1);
                    $member2 = Member::find($reservation->fk_member_2);
                    $data[$i]['reservation'][$j]['data'] = $reservation;
                    $data[$i]['reservation'][$j]['member_1'] = $member1->last_name;
                    $data[$i]['reservation'][$j]['member_2'] = $member2->last_name;
                    $j++;
                }
                $i++;
            }
            return response()->json($data);
        }
        // if(Auth::check())
        // {
        //   $members = PersonalInformation::reservations->where('fkWithWho', Auth::user()->id)->orWhere('fkWho', Auth::user()->id)
        //
        // }


        $courts = Court::where('state', 1)->get()->sortBy('name');
        if(Auth::check())
        {

          // print(PersonalInformation::find(Auth::user()->id)->id);
          // die();
          // $allMember = PersonalInformation::where('id', '!=', PersonalInformation::find(Auth::user()->id)->id)->has('user')->get()->sortBy('firstname');

          // $memberFav =PersonalInformation::leftjoin('reservations', 'reservations.fkWithWho', '=', 'personal_informations.id')
          //                                 ->leftjoin('reservations as reservations_who', 'reservations_who.fkWho', '=', 'personal_informations.id')->has('user')
          //                                 ->rightJoin('users', 'users.fkPersonalInformation', '=', 'personal_informations.id')
          //                                 ->where('reservations_who.fkWithWho','=', PersonalInformation::find(Auth::user()->id)->id)
          //                                 ->orWhere('reservations.fkWho','=', PersonalInformation::find(Auth::user()->id)->id)
          //                                 ->groupBy('personal_informations.id')
          //                                 ->orderBy('reservations_count', 'DESC')
          //                                 ->get(['personal_informations.*', \DB::raw('COUNT(`' . \DB::getTablePrefix() . 'reservations_who`.`id`) + COUNT(`' . \DB::getTablePrefix() . 'reservations`.`id`) AS `reservations_count`')]);
          //

          $queryWho = DB::table('personal_informations')
                        ->join('reservations AS r', 'r.fkWho', '=', 'personal_informations.id')
                        ->where('r.fkWithWho', '=', PersonalInformation::find(Auth::user()->id)->id)
                        ->groupBy('personal_informations.id')
                        ->select(['personal_informations.*', \DB::raw('COUNT(r.fkWho) AS nb_times_played')]);

          $queryBoth = DB::table('personal_informations')
                        ->join('reservations AS r', 'r.fkWithWho', '=', 'personal_informations.id')
                        ->where('r.fkWho', '=', PersonalInformation::find(Auth::user()->id)->id)
                        ->union($queryWho)
                        ->groupBy('personal_informations.id')
                        ->select(['personal_informations.*', \DB::raw('COUNT(r.fkWithWho) AS nb_times_played')]);

          $memberFav = PersonalInformation::selectRaw('ps.id, ps.firstname, ps.lastname, ps.street, ps.streetNbr, ps.telephone, ps.email, ps.toVerify, ps.birthDate, ps._token, ps.fkLocality, ps.created_at, ps.updated_at, ps.deleted_at, SUM(ps.nb_times_played) AS reservations_count')
                                            ->from(\DB::raw('('.$queryBoth->toSql().') AS ps'))
                                            ->mergeBindings($queryBoth)
                                            ->groupBy('ps.id')
                                            ->get()
                                            ->sortByDesc('reservations_count');

          $id_member_fav = [(int)PersonalInformation::find(Auth::user()->id)->id];
          foreach ($memberFav as $value) {
            $id_member_fav[] = $value['id'];
          }

          $allMember = PersonalInformation::whereNotIn('id', $id_member_fav)->get()->sortBy('firstname');

          $startDate = new \DateTime();
          $endDate= (new \DateTime())->add(new \DateInterval('P5D'));
          $ownreservs = \App\Reservation::whereBetween('dateTimeStart', [$startDate->format('Y-m-d H:i'), $endDate->format('Y-m-d').' 23:59'])
               ->where(function($q){
                   $Userid=Auth::user()->id;
                   $q->where('fkWho', $Userid);
                   $q->orWhere('fkWithWho', $Userid);
               })->get();

          //we merge the two collections of members then we sort by reservations_count (desc)
          // $membersList = $allMember->merge($memberFav);
          // $membersList = $membersList->sortByDesc('reservations_count');
          $membersList = $memberFav->merge($allMember);

          return view('booking/home',compact('membersList', 'courts', 'ownreservs'));
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

            $endDate= (new \DateTime())->add(new \DateInterval('P'.($court->nbDays-1).'D'));
            $userWho = User::find(Auth::user()->id);
            $personalInfoWho = User::find(Auth::user()->id)->personal_information;

            $nbReservationWho = Reservation::whereBetween('dateTimeStart', [$startDate->format('Y-m-d H:i'), $endDate->format('Y-m-d').' 23:59'])
                ->where(function($q){
                    $Userid=Auth::user()->id;
                    $q->where('fkWho', $Userid);
                    $q->orWhere('fkWithWho', $Userid);
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
            else{

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
                if (PersonalInformation::find(Auth::user()->id)->id == $request->input('fkWithWho'))
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
            $validator = Validator::make($request->all(),
                [
                    'firstname' => 'required|max:50',
                    'lastname' => 'required|max:50',
                    'street' => 'max:100',
                    'streetNbr' => 'max:45',
                    'phone' => 'required',
                    'email' => 'required|email|max:255',
                    'locality' => 'required|exists:localities,id',
                ],
                [
                    'locality.exists' => 'Cette localité n\'existe pas, si vous ne trouvez pas votre localité veuillez choisir "autre"',
                    'streetNbr.name' => 'numéro de rue'
                ]
                );
            $validator->setAttributeNames([
                'firstname' => 'prénom',
                'lastname' => 'nom',
                'street' => 'rue',
                'streetNbr' => 'numéro de rue',
                'phone' => 'téléphone',
                'locality' => 'localité'
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
        if($todayDateTime > $dateTimeStart)
        {
            Session::flash('errorMessage', "Cette date est déjà passée");
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

        /* if($request->input('first_name'))*/

/*        $freeHour = Reservation::where('fkCourt', $fkCourt)->where(function($q) use ($dateTimeStartLessDuration,$dateTimeStart, $dateTimeEnd){
            $q->whereBetween('dateTimeStart', [$dateTimeStart, $dateTimeEnd]);
            $q->orWhereBetween('dateTimeStart', [$dateTimeStartLessDuration, $dateTimeStart]);
        })->count();

        if($freeHour!=0)
        {
            Session::flash('errorMessage', "Cette heure n'est pas libre, veuillez choisir une autre heure.");
            return redirect('/booking');
        }*/

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


        if(isset($personalInfoWho))$personalInfoWho->save();
        // Insert in DB
        //-------------
        $reservationInfo = [
                 'dateTimeStart' => $dateTimeStart,
                 'fkCourt' => $fkCourt,
                 'fkWho' => $personalInfoWho->id,
                 'fkTypeReservation' => 1,
                 'fkWithWho' => (isset($personalInfoWithWho->id) ? $personalInfoWithWho->id : null), //check if there is a invited person (in case it's a reservation is created by a member)
                 'chargeAmount' => $chargeAmount,
                 'paid' => 0
        ];


        $reservation = Reservation::create($reservationInfo);

        /////////////////////////////////////////////


        // Select the information of the two players froms the members

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
                    'last_name' => $personalInfoWho->lastname,
                    'first_name' => $personalInfoWho->firstname,
                    'court' => $court->name,
                    'player' => $personalInfoWho->lastname." ".$personalInfoWho->firstname,
                    'date_hours' => $dateHour,
                    'token' => $reservation->confirmation_token
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
                            'last_name' => $member->lastname,
                            'first_name' => $member->firstname,
                            'court' => $court->name,
                            'joueur1' => $members[0]->lastname." ".$members[0]->firstname,
                            'joueur2' => $members[1]->lastname." ".$members[1]->firstname,
                            'date_hours' => $dateHour
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
            Session::flash('errorMessage', "La reservation que vous essayer de supprimer n'existe pas ou plus");
            return redirect('/booking');
        }
        if (Auth::user()->id != $reservation->fkWho && Auth::user()->id != $reservation->fkWithWho)
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

    public function MyBookingIndex(Request $request)
    {
        // Get all the reservations of the member
        $reservations = Reservation::where('fk_member_1', Auth::user()->id)->orWhere('fk_member_2', Auth::user()->id)->orderBy('date_hours', 'DESC')->get();

        $data = [];
        $i = 0;
        foreach ($reservations as $reservation)
        {
            $member1 = Member::find($reservation->fk_member_1);
            $member2 = Member::find($reservation->fk_member_2);
            $data[$i]['id']    = $reservation->id;
            $data[$i]['first_name_1']   = $member1->first_name;
            $data[$i]['last_name_1']    = $member1->last_name;
            $data[$i]['first_name_2']   = $member2->first_name;
            $data[$i]['last_name_2']    = $member2->last_name;
            $data[$i]['date']           = Carbon::createFromFormat('Y-m-d H:i:s', $reservation['date_hours'])->format('d.m.Y H:i');
            $data[$i]['court']          = $reservation['fk_court'];
            if ($reservation['date_hours'] > Carbon::now())
            {
                $data[$i]['deletable']  = true;
            }
            else
            {
                $data[$i]['deletable']  = false;
            }
            $i++;
        }
        return view('myBooking/home')->with('bookings', $data);
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
