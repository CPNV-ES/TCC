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
        if(Input::has('datetime-start') && Input::has('datetime-end'))
        {
            $validator = Validator::make($request->all(),
                [
                    'datetime-start'    => 'required|max:19',
                    'datetime-end'      => 'required|max:19',
                    'court'             => 'required|exists:courts,id',
                    'type-reservation'  => 'required'//|exists:type_subscriptions,id'
                ],
                [
                    'court.exists'          => 'Ce court n\'existe pas, veuillez choisir un court dans la liste déroulante',
                    'datetime-start.name'   => 'date de début choisie',
                    'datetime-end.name'     => 'date de fin choisie',
                    'type-reservation'      => 'le type de réservation'
                ]
            );

            $datetime_today = new \DateTime();
            $datetime_start = date_create_from_format('d.m.Y H:i', $request->input('datetime-start'));
            $datetime_intermediate =  clone $datetime_start;
            $datetime_end = date_create_from_format('d.m.Y H:i', $request->input('datetime-end'));
            $court = Court::find($request->input('court'));
            $typeReservation = $request->input('type-reservation');

            if($court->state == 1)
            {
                $validator->errors()->add('court', 'Le court sélectionné n\' est pas disponible');
            }
            if($datetime_start < $datetime_today)
            {
                $validator->errors()->add('datetime-start', 'La date de début n\'est pas valide, la date de début ne doit pas être passée');
            }
            if($datetime_start >= $datetime_end)
            {
                $validator->errors()->add('datetime-end', 'La date de fin doit être après dans le temps');
            }
            if($validator->fails())
            {
                return back()->withInput()->withErrors($validator);
            }

            $reservations = [];
            do
            {
                //hour_intermediate is a datetime but is used to add a reservation for each hour between start and end
                $hour_intermediate = clone $datetime_intermediate;

                do
                {
                    $reservation = Reservation::where('dateTimeStart', $hour_intermediate)->where('fkcourt', $request->input('court'));
                    if(!$reservation->count())
                    {
                        $reservationInfo = [
                            'dateTimeStart'        => $hour_intermediate,
                            'fkCourt'              => $court->id,
                            'fkWho'                => Auth::user()->fkPersonalInformation,
                            'fkTypeReservation'    => $typeReservation,
                            'fkWithWho'            => null,
                            'chargeAmount'         => 0,
                            'paid'                 => 1
                        ];
                        Reservation::create($reservationInfo);
                    }
                    else{
                        array_push($reservations, $reservation->first());
                    }
                    $hour_intermediate->modify('+1 hour');


                }while($hour_intermediate->format('H') <  $datetime_end->format('H'));

                // add the a day/week/month depending on the type of reservation
                switch ($typeReservation)
                {
                    case 2:
                        $datetime_intermediate->modify('+1 day');
                        break;
                    case 3:
                        $datetime_intermediate->modify('+1 week');
                        break;
                    case 4:
                        $datetime_intermediate->modify('+1 month');
                        break;
                }

            }while(($datetime_end > $datetime_intermediate));

            Session::flash('succeedMessage', "Vos réservation a bien été effectuée");
            return redirect('/staff_booking')->with('reservations');
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
