<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Requests;
use App\Reservation;
use Illuminate\Support\Facades\Session;

class StaffBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courts = Court::where('state', true)->get();

        return view('staffBooking.home', compact('courts'));
    }

    /**
     * Show the form for creating a new resource. NOT USED : the creation form is into the modal into home view
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
        //if there is a datetime-end it's a multiple reservation otherwise it's a simple reservation
        if(Input::has('datetime-end'))
        {
            #for the validator
            #after:start_date'
        }
        else
        {
            $validator = Validator::make($request->all(),
                [
                    'datetime-start' => 'required|max:50',
                    'court' => 'required|exists:courts,id'
                ],
                [
                    'court.exists' => 'Ce court n\'existe pas, veuillez choisir un court dans la liste déroulante',
                    'datetime-start.name' => 'date choisie'
                ]
            );

            if($validator->fails())
            {
                return back()->withInput()->withErrors($validator);
            }

            $court = Court::find($request->input('court'));

            //we check the court exist or if its state isn't 1
            if(!$court || $court->state != 1)
            {
                $validator->errors()->add('court', 'Le court choisi n\'existe pas ou est en réparation');
                return back()->withInput()->withErrors($validator);
            }

            $datetime_today = new \DateTime();
            $datetime_start = date_create_from_format('d.m.Y H:i', $request->input('datetime-start'));

            if($datetime_start < $datetime_today)
            {
                $validator->errors()->add('datetime-start', 'La date/heure choisie n\'est pas valide');
                return back()->withInput()->withErrors($validator);
            }

            $reservationInfo = [
                     'dateTimeStart'        => $datetime_start,
                     'fkCourt'              => $court->id,
                     'fkWho'                => Auth::user()->fkPersonalInformation,
                     'fkTypeReservation'    => 1,
                     'fkWithWho'            => null, //check if there is a invited person (in case it's a reservation is created by a member)
                     'chargeAmount'         => 0,
                     'paid'                 => 1
            ];
            Reservation::create($reservationInfo);

            Session::flash('successMessage', "Votre réservation a bien été enregistrée.");
            return redirect('/staff_booking');
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
        //
    }
}
