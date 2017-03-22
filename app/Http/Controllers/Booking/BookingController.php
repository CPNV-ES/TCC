<?php

namespace App\Http\Controllers\Booking;

//use App\Models\Reservation;
//use App\Models\Season;

use App\Reservation;
use App\Season;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Validator;

/*use App\Models\Member;
use App\Models\Booking;
use App\Models\Court;*/
use App\PersonalInformation;
use App\Court;
use App\Config;
use App\User;

//FOR DEBUGGING
use DB;

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
        if(Auth::check())
        {
          // print(PersonalInformation::find(Auth::user()->id)->id);
          // die();
          $allMember = PersonalInformation::where('id', '!=', PersonalInformation::find(Auth::user()->id)->id)->has('user')->get();
          $memberFav = PersonalInformation::leftJoin('reservations', 'reservations.fkWithWho', '=', 'personal_informations.id')
                                          ->leftJoin('reservations as reservations_who', 'reservations_who.fkWho', '=', 'personal_informations.id')
                                          ->has('user')->where('reservations_who.fkWithWho','=', PersonalInformation::find(Auth::user()->id)->id)
                                          ->orWhere('reservations.fkWho','=', PersonalInformation::find(Auth::user()->id)->id)
                                          ->groupBy('personal_informations.id')
                                          ->orderBy('reservations_count', 'DESC')
                                          ->get(['personal_informations.*', \DB::raw('COUNT(`' . \DB::getTablePrefix() . 'reservations_who`.`id`) + COUNT(`' . \DB::getTablePrefix() . 'reservations`.`id`) AS `reservations_count`')]);
          $membersList = $memberFav->merge($allMember);
          $courts = Court::where('state', 1)->get();
          return view('booking/home',compact('membersList', 'courts'));
        }
        else {
          return view('booking/home');
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

        if(Auth::check())
        {
          $fkCourt =   $request->input('fkCourt');
          Session::flash('currentCourt', $fkCourt);
          // check if the creator of the reservation has the invit right
          if(!Auth::user()->invitRight)
          {

            Session::flash('errorMessage', "Vous ne possédez pas le droit d'inviter des gens");
            return redirect('/booking');
          }

          //check if the creator of the reservation and the invited person
          $invalidatedDateWho = Auth::user()->invalidatedDate;
          $invalidatedDateWithWho = Auth::user()->invalidatedDate;

          $configs = Config::orderBy('created_at', 'desc')->first();
          $userWho = User::find(Auth::user()->id);
          $userWithWho = User::find($request->input('fkWithWho'));
          $todayDate   = date('Y-m-d');
          $todayDateTime = date('Y-m-d H:i:s');


          //datetime of the reservation
          $dateTimeStart = $request->input('dateTimeStart');
          $dateTimeEnd   = date("Y-m-d H:i:s", strtotime($dateTimeStart)+60*60-1);

          //check if the date isn't in th past
          if($todayDateTime > $dateTimeStart)
          {
            Session::flash('errorMessage', "Cette date est déjà passée");
            return redirect('/booking');
          }

          // We check if there's a date into the field invalidatedDate for the member who has made the reservation and the invited member.
          // If there is, we add the nb of days of the grace period. If the period has been exceeded we return an error
          if( Auth::user()->invalidatedDate != null &&
              strtotime(Auth::user()->invalidatedDate. ' + '. $configs->nbDaysGracePeriod.' days') < strtotime($todayDate))
          {
            Session::flash('errorMessage', "Votre compte n'est plus valide");
            return redirect('/booking');
          }
          else if( $userWithWho->invalidatedDate != null &&
              strtotime($userWithWho->invalidatedDate.' + '.$configs->nbDaysGracePeriod.' days') < strtotime($todayDate))
          {
              Session::flash('errorMessage', "Le compte de votre invité n'est plus valide");
              return redirect('/booking');
          }

          //Number of reservations of the creator of the reservation
          $startDate = new \DateTime();
          $endDate= (new \DateTime())->add(new \DateInterval('P5D'));
          $nbReservationWho = Reservation::whereBetween('dateTimeStart', [$startDate->format('Y-m-d H:i'), $endDate->format('Y-m-d').' 23:59'])
              ->where(function($q){
                  $Userid=Auth::user()->id;
                  $q->where('fkWho', $Userid);
                  $q->orWhere('fkWithWho', $Userid);
              })->count();

          //Number of reservations of the person invited;
          $nbReservationWithWho = Reservation::whereBetween('dateTimeStart', [$startDate->format('Y-m-d H:i'), $endDate->format('Y-m-d').' 23:59'])
              ->where(function($q)use ($request){
                  $Userid=$request->input('fkWithWho');
                  $q->where('fkWho', $Userid);
                  $q->orWhere('fkWithWho', $Userid);
              })->count();

          //check if the number of reservations of the creator of the reservations and the invited person has not been exceeded
          if ($nbReservationWho >= Config::orderBy('created_at', 'desc')->first()->nbReservations)
          {
              Session::flash('errorMessage', "Vous avez déjà atteint votre nombre maximum de reservations");
              return redirect('/booking');
          }
          else if ($nbReservationWithWho >= Config::orderBy('created_at', 'desc')->first()->nbReservations)
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
          // 13:00 -- 14:00+1
          $dateTimeStartLessDuration =  date("Y-m-d H:i:s", strtotime($dateTimeStart)-60*60+1);


          //check if the hour is the hour is free for the selected court
          // $freeHour = Reservation::where('fkCourt', $fkCourt)->whereBetween('dateTimeStart', [$dateTimeStart, $dateTimeEnd])
          //                        ->orWhereBetween('dateTimeStart', [$dateTimeStartLessDuration, $dateTimeStart])
          //                        ->count();

          $freeHour = Reservation::where('fkCourt', $fkCourt)->where(function($q) use ($dateTimeStartLessDuration,$dateTimeStart, $dateTimeEnd){
                        $q->whereBetween('dateTimeStart', [$dateTimeStart, $dateTimeEnd]);
                        $q->orWhereBetween('dateTimeStart', [$dateTimeStartLessDuration, $dateTimeStart]);
          })->count();
          
          if($freeHour!=0)
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

          // Insert in DB
          //-------------
          $data = ['dateTimeStart' => $dateTimeStart, 'fkCourt' => $fkCourt, 'fkWho' => PersonalInformation::find(Auth::user()->id)->id,
                  'fkTypeReservation' => 1, 'fkWithWho' => $request->input('fkWithWho'),
                  'chargeAmount' => $chargeAmount, 'paid' => 0];


          $reservation = Reservation::create($data);

          /////////////////////////////////////////////


          // Select the information of the two players froms the members
          //$members = User::whereIn('id', [Auth::user()->id, $request->input('fk_member_2')])->get(['last_name', 'first_name', 'email']);
          $members = [$userWho->load('personal_information'), $userWithWho->load('personal_information')];
          $court = Court::find($fkCourt);
          $dateHour = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('dateTimeStart'))->format('d.m.Y H:i');
          foreach ($members as $member)
          {
              $email = $member->personal_information->email;
              // Inform the players of the reservations
              //---------------------------------------------------------------------------------
              Mail::send('emails.user.reservation', ['last_name' => $member->personal_information->lastname, 'first_name' => $member->personal_information->firstname, 'court' => $court->name,
                  'joueur1' => $members[0]->personal_information->lastname." ".$members[0]->personal_information->firstname, 'joueur2' => $members[1]->personal_information->lastname." ".$members[1]->personal_information->firstname,
                  'date_hours' => $dateHour], function ($message) use($email)
              {
                  $message->to($email)->subject('Votre réservation au Tennis Club Chavornay');
              });
              /////////////////////////////////////////////
          }
          Session::flash('successMessage', "Votre réservation a bien été enregistrée");
          return redirect('/booking');
        }
        else {
          Session::flash('errorMessage', "Vous devez être membre pour faire une réservation");
          return redirect('/booking');
        }
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
        if (Auth::user()->id != $reservation->fk_member_1 && Auth::user()->id != $reservation->fk_member_2)
        {
            return "false";
        }
        $reservation->delete();

        // Select the information of the two players from the members

        $members = Member::whereIn('id', [$reservation->fk_member_1, $reservation->fk_member_2])->get(['last_name', 'first_name', 'email']);
        $court = Court::where('id', $reservation->fk_court)->get(['name']);
        $dateHour = Carbon::createFromFormat('Y-m-d H:i:s', $reservation->date_hours)->format('d.m.Y H:i');
        foreach ($members as $member)
        {
            $email = $member->email;

            // Inform the players of the reservations
            //---------------------------------------------------------------------------------
            Mail::send('emails.user.deletereservation', ['last_name' => $member->last_name, 'first_name' => $member->first_name, 'court' => $court[0]->name, 'joueur1' => $members[0]->last_name." ".$members[0]->first_name, 'joueur2' => $members[1]->last_name." ".$members[1]->first_name, 'date_hours' => $dateHour], function ($message) use($email)
            {
                $message->to($email)->subject('Suppression de votre réservation au Tennis Club Chavornay');
            });
            /////////////////////////////////////////////
        }
        return "true";
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
}
