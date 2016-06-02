<?php

namespace App\Http\Controllers\Booking;

use App\Models\Reservation;
use App\Models\Season;
use Carbon\Carbon;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Validator;
use App\Models\Member;
use App\Models\Booking;
use App\Models\Court;
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
        if($request->ajax())
        {
            //Do we want for only one court
            if($request->has('courtID'))
            {
                // Get court configuration
                //------------------------
                $configCourt = Court::find($request->input('courtID'));

                $bookingWindow = Carbon::today()->addDay($configCourt->booking_window_member);
                $bookingCourt = Reservation::where('date_hours', '>', Carbon::today())->where('date_hours', '<', $bookingWindow)->where('fk_court', $configCourt->id)->get();
                $data['config'] = $configCourt;
                $data['config']['booking_window'] = $configCourt->booking_window_member;
                $data['reservation'] = $bookingCourt;

                return response()->json($data);
            }


            // Get court configuration
            //------------------------
            $configCourts = Court::all();

            // Not Member
            //-----------
            if(!Auth::check())
            {
                $i = 0;
                foreach ($configCourts as $configCourt)
                {
                    $bookingWindow = Carbon::today()->addDay($configCourt->booking_window_not_member);
                    $bookingCourt = Reservation::where('date_hours', '>', Carbon::today())->where('date_hours', '<', $bookingWindow)->where('fk_court', $configCourt->id)->get();
                    $data[$i]['config'] = $configCourt;
                    $data[$i]['config']['booking_window'] = $configCourt->booking_window_not_member;
                    $data[$i]['reservation'] = $bookingCourt;
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
                $data[$i]['reservation'] = $bookingCourt;
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
        // Check if the member already has a reservation
        //----------------------------------------------
        $dateTime   = date('Y-m-j H-i-s');
        $exist      = Reservation::where('fk_member_1', Auth::user()->id)->where('date_hours', '>', $dateTime)->first();

        if(!empty($exist))
        {
            return ["Vous avez déjà une réservation", false];
        }

        // Get the season corresponding to the date
        //-----------------------------------------
        $season = Season::where('begin_date', '<',  $request->input('date_hours'))->where('end_date', '>',  $request->input('date_hours'))->first();


        // Insert in DB
        //-------------
        $data = ['fk_court' => $request->input('fk_court'), 'fk_member_1' => Auth::user()->id, 'fk_member_2' => $request->input('fk_member_2'), 'fk_season' => $season->id, $request->input('date_hours')];
        $reservation = Reservation::create($data);
        $reservation->date_hours = $request->input('date_hours');
        $reservation->save();
        /////////////////////////////////////////////


        //Select the information of the two players froms the members

        $members = Member::whereIn('id', [Auth::user()->id, $request->input('fk_member_2')])->get(['last_name', 'first_name', 'email']);
        $court = Court::where('id', $request->input('fk_court'))->get(['name']);
        //If the player is "playing" with himself
        if(count($members) == 1)
        {
            $email = $members[0]->email;
            $dateHour = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('date_hours'))->format('d.m.Y H:i');

            // Inform the players of the reservations
            //---------------------------------------------------------------------------------
            Mail::send('emails.user.reservation', ['last_name' => $members[0]->last_name, 'first_name' => $members[0]->first_name, 'court' => $court[0]->name, 'joueur1' => $members[0]->last_name." ".$members[0]->first_name, 'joueur2' => $members[0]->last_name." ".$members[0]->first_name, 'date_hours' => $dateHour], function ($message) use($email)
            {
                $message->to($email)->subject('Votre reservation au Tennis Club Chavornay');
            });
            /////////////////////////////////////////////
            return ["Votre réservation a bien été enregistrée, un e-mail de confirmation vous a été envoyé", true];
        }
        foreach ($members as $member)
        {
            $email = $member->email;
            $dateHour = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('date_hours'))->format('d.m.Y H:i');

            // Inform the players of the reservations
            //---------------------------------------------------------------------------------
            Mail::send('emails.user.reservation', ['last_name' => $member->last_name, 'first_name' => $member->first_name, 'court' => $court[0]->name, 'joueur1' => $members[0]->last_name." ".$members[0]->first_name, 'joueur2' => $members[1]->last_name." ".$members[1]->first_name, 'date_hours' => $dateHour], function ($message) use($email)
            {
                $message->to($email)->subject('Votre reservation au Tennis Club Chavornay');
            });
            /////////////////////////////////////////////
        }

        return ["Votre réservation a bien été enregistrée, un e-mail de confirmation vous a été envoyé", true];
//        return (new Response("Votre réservation a bien été enregistrée, un e-mail de confirmation vous a été envoyé", 201));
//        return "Votre réservation a bien été enregistrée, un e-mail de confirmation vous a été envoyé";
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
        //
    }

    public function MyBookingIndex(Request $request)
    {
        //Get all the reservation of the member
        $reservations = Reservation::where('fk_member_1', Auth::user()->id)->orWhere('fk_member_2', Auth::user()->id)->orderBy('date_hours', 'DESC')->get();
//        $reservations = Reservation::where(function($query){
//            $query->where('fk_member_1', Auth::user()->id)->orWhere('fk_member_2', Auth::user()->id);
//        })->get();

        $data = [];
        $i = 0;
        foreach($reservations as $reservation)
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
            if($reservation['date_hours'] > Carbon::now())
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
