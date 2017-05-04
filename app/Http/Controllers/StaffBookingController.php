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
        $startDate = new \DateTime();
        $courts = Court::where('state', true)->get();
        $ownReservations = Reservation::where('fkWho' , Auth::user()->fkPersonalInformation)->where('fkWithWho', null)->where('dateTimeStart' , '>=', $startDate->format('Y-m-d H:i'))->get();
        return view('staffBooking.home', compact('courts', 'ownReservations'));
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
        if(Input::has('date-start') && Input::has('date-end'))
        {
            $validator = Validator::make($request->all(),
                [
                    'hour-start'        => 'required|integer|between:8,19',
                    'hour-end'          => 'required|integer|between:9,20',
                    'date-start'        => 'required|max:10',
                    'date-end'          => 'required|max:10',
                    'court'             => 'required|exists:courts,id',
                    'type-reservation'  => 'required'
                ],
                [
                    'hour-start'        => 'Heure de début',
                    'hour-end'          => 'Heure de fin',
                    'court.exists'      => 'Ce court n\'existe pas, veuillez choisir un court dans la liste déroulante',
                    'date-start.name'   => 'date de début choisie',
                    'date-end.name'     => 'date de fin choisie',
                    'type-reservation'  => 'le type de réservation'
                ]
            );

            $datetime_today = new \DateTime();

            $strDatetime_start = $request->input('date-start').' '.$request->input('hour-start');
            $strDatetime_end   = $request->input('date-end').' '.$request->input('hour-end');

            $date_start = date_create_from_format('d-m-Y', $request->input('date-start') );
            $date_end   = date_create_from_format('d-m-Y', $request->input('date-end') );


            $datetime_start = date_create_from_format('d-m-Y H', $strDatetime_start );
            $datetime_intermediate =  clone $datetime_start;

            $datetime_end = date_create_from_format('d-m-Y H', $strDatetime_end);
            $court = Court::find($request->input('court'));
            $typeReservation = $request->input('type-reservation');

            if($court->state != 1)
            {
                $validator->errors()->add('court', 'Le court sélectionné n\' est pas disponible');
            }
            if($datetime_start < $datetime_today)
            {
                $validator->errors()->add('date-start', 'La date de début n\'est pas valide, la date de début ne doit pas être passée');
            }
            if($date_start > $date_end)
            {
                $validator->errors()->add('date-end', 'La date de fin doit être après ou en même temps que la date de début');
            }

            if($request->input('hour-start') > $request->input('hour-end'))
            {
                $validator->errors()->add('hour-end', 'L\'heure de fin doit être après dans l\'heure de début');
            }

            if(count($validator->errors()->all()))
            {
              $val = true;
                return  back()->with('showMultResForm', 'true')->withInput()->withErrors($validator);
            }
            if($validator->fails())
            {
                return back()->with('showMultResForm', 'true')->withInput()->withErrors($validator);
            }

            $conflictReservations = [];
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
                        array_push($conflictReservations, $reservation->first());

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

            return redirect()->back()->with('conflictReservations', $conflictReservations);
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

            }
            if(!Reservation::isHourFree($court->id, $datetime_start->format('Y-m-d H:i:s')))
            {
              $validator->errors()->add('datetime-start', 'Cette heure n\'est pas libre');
            }
            if(count($validator->errors()->all()))
            {
              return back()->with('showSimpleResForm', 'true')->withInput()->withErrors($validator);
            }
            if($validator->fails())
            {
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
