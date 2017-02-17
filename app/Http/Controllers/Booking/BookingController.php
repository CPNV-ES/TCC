<?php

namespace App\Http\Controllers\Booking;

//use App\Models\Reservation;
//use App\Models\Season;

use App\Reservation;
use App\Season;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Validator;

/*use App\Models\Member;
use App\Models\Booking;
use App\Models\Court;*/
use App\PersonalInformation;
use App\Court;
use App\Config;

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
        return view('booking/home');
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
        //TODO: -- add a column nb of reservation for a user -- add a invalitated date in user for
        //
        // Check if one of the members have already a reservation
        //----------------------------------------------
        $date   = date('Y-m-j');
        $exist = Reservation::where('dateStart', '>', $date)->where(function ($query){
                 $query->where('fkWho', PersonalInformation::find(Auth::user()->id)->id)->orWhere('fkWithWho', PersonalInformation::find(Auth::user()->id)->id);
        })->count();



        if (!empty($exist))
        {
            return ["Vous ou votre partenaire avez déjà une réservation", false];
        }
        // Can't reserve with your self
        if (PersonalInformation::find(Auth::user()->id)->id == $request->input('fkWithWho'))
        {
            return ["Vous ne pouvez pas réservez avec vous même", false];
        }

        //For member-member reservation
        $dateStart = $request->input('dateStart');
        $dateEnd = $dateStart;

        $hourStart = date("H:i:s", strtotime($request->input('hourStart')));
        //hourEnd is the hourStart + 1 hour
        $hourEnd = date("H:i:s", strtotime($request->input('hourStart'))+60*60);

        DB::enableQueryLog();

        //check if the hour is the hour is free for the selected court
        $freeHour = Reservation::where(function($query) use($dateStart,$dateEnd) {
                                    $query->whereBetween('dateStart', [$dateStart, $dateEnd])->orWhereBetween('dateEnd',[$dateStart, $dateEnd]);
                                })->where(function ($query) use($hourStart, $hourEnd){
                                    $query->whereBetween('hourEnd',[$hourStart, $hourEnd])->orWhereBetween('hourStart', [$hourStart, $hourEnd]);
                                })->where('fkCourt', $request->input('fkCourt'))->count();

        if($freeHour!=0)
        {
            return ["Cette heure n'est pas libre, veuillez choisir un autre heure",false];
        }
        //Check if the court is available (in case of the court is in maintenance)
        $court = Court::find($request->input('fkCourt'));
        if($court->state != 1)
        {
            return ['Ce court n\'est pas disponible pour le moment, veuillez choisir un autre court'];
        }




/*        //NO NEEDED IN NEW VERSION -- Get the season corresponding to the date
        //-----------------------------------------
        $season = Season::where('begin_date', '<',  $request->input('date_hours'))->where('end_date', '>',  $request->input('date_hours'))->first();
*/




        //Get the actual price
        $chargeAmount = Config::first()->currentAmount;

        // Insert in DB
        //-------------
        $data = ['dateStart' => $dateStart,'dateEnd' => $dateEnd,'fkCourt' => $request->input('fkCourt'), 'fkWho' => PersonalInformation::find(Auth::user()->id)->id,
                'fkTypeReservation' => 1, 'fkWithWho' => $request->input('fkWithWho'), 'hourStart' => $hourStart,'hourEnd' => $hourEnd,
                'chargeAmount' => $chargeAmount, 'paid' => 0];


        $reservation = Reservation::create($data);
        $reservation->save();
        /////////////////////////////////////////////


        // Select the information of the two players froms the members
/*
        $members = Member::whereIn('id', [Auth::user()->id, $request->input('fk_member_2')])->get(['last_name', 'first_name', 'email']);
        $court = Court::where('id', $request->input('fk_court'))->get(['name']);
        $dateHour = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('date_hours'))->format('d.m.Y H:i');
        foreach ($members as $member)
        {
            $email = $member->email;

            // Inform the players of the reservations
            //---------------------------------------------------------------------------------
            Mail::send('emails.user.reservation', ['last_name' => $member->last_name, 'first_name' => $member->first_name, 'court' => $court[0]->name,
                'joueur1' => $members[0]->last_name." ".$members[0]->first_name, 'joueur2' => $members[1]->last_name." ".$members[1]->first_name,
                'date_hours' => $dateHour], function ($message) use($email)
            {
                $message->to($email)->subject('Votre reservation au Tennis Club Chavornay');
            });
            /////////////////////////////////////////////
        }*/

        return ["Votre réservation a bien été enregistrée, un e-mail de confirmation vous a été envoyé", true];
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
