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
use App\Config;
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
        $ownReservations = Reservation::where('fkWho' , Auth::user()->fkPersonalInformation)->where('fkWithWho', null)->where('dateTimeStart' , '>=', $startDate->format('Y-m-d H:i'))->orderBy('dateTimeStart', 'asc')->get();
        $config = Config::orderBy('created_at', 'desc')->first();

        $oldReservations = Reservation::where('fkWho' , Auth::user()
                                       ->fkPersonalInformation)->where('fkWithWho', null)
                                       ->where('dateTimeStart' , '<', $startDate->format('Y-m-d H:i'))
                                       ->orderBy('dateTimeStart', 'asc')->get();
        return view('staffBooking.home', compact('courts', 'ownReservations', 'config', 'oldReservations'));
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
    public function     store(Request $request)
    {
        //if there is a datetime-end it's a multiple reservation otherwise it's a simple reservation
        if(Input::has('date-end-multiple-res'))
        {
            $validator = Validator::make($request->all(),
                [
                    'title-multiple-res'       => 'required|max:50',
                    'hour-start-multiple-res'  => 'required|integer|between:8,19',
                    'hour-end-multiple-res'    => 'required|integer|between:9,20',
                    'date-start-multiple-res'  => 'required|max:10',
                    'date-end-multiple-res'    => 'required|max:10',
                    'court-multiple-res'       => 'required|exists:courts,id',
                    'type-reservation'         => 'required'
                ],
                [
                    'title-multiple-res'            => 'Libellé',
                    'hour-start-multiple-res.name'  => 'Heure de début',
                    'hour-end-multiple-res.name'    => 'Heure de fin',
                    'court-multiple-res.exists'     => 'Ce court n\'existe pas, veuillez choisir un court dans la liste déroulante',
                    'date-start-multiple-res.name'  => 'date de début choisie',
                    'date-end-multiple-res.name'    => 'date de fin choisie',
                    'type-reservation'              => 'le type de réservation'
                ]
            );

            $datetime_today = new \DateTime();

            $strDatetime_start = $request->input('date-start-multiple-res').' '.$request->input('hour-start-multiple-res');
            $strDatetime_end   = $request->input('date-end-multiple-res').' '.$request->input('hour-end-multiple-res');

            $date_start = date_create_from_format('d-m-Y', $request->input('date-start-multiple-res') );
            $date_end   = date_create_from_format('d-m-Y', $request->input('date-end-multiple-res') );


            $datetime_start = date_create_from_format('d-m-Y H', $strDatetime_start );
            $datetime_intermediate =  clone $datetime_start;

            $datetime_end = date_create_from_format('d-m-Y H', $strDatetime_end);
            $court = Court::find($request->input('court-multiple-res'));
            $typeReservation = $request->input('type-reservation');

            if($court->state != 1)
            {
                $validator->errors()->add('court-multiple-res', 'Le court sélectionné n\' est pas disponible');
            }
            if($datetime_start < $datetime_today)
            {
                $validator->errors()->add('date-start-multiple-res', 'La date de début n\'est pas valide, la date de début ne doit pas être passée');
            }
            if($date_start > $date_end)
            {
                $validator->errors()->add('date-end-multiple-res', 'La date de fin doit être après ou en même temps que la date de début');
            }

            if($request->input('hour-start-multiple-res') > $request->input('hour-end-multiple-res'))
            {
                $validator->errors()->add('hour-end-multiple-res', 'L\'heure de fin doit être après dans l\'heure de début');
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
                    $reservations = Reservation::where('dateTimeStart', $hour_intermediate)->where('fkcourt', $request->input('court-multiple-res'));
                    if(!$reservations->count())
                    {

                        $reservationInfo = [
                            'dateTimeStart'        => $hour_intermediate,
                            'fkCourt'              => $court->id,
                            'fkWho'                => Auth::user()->fkPersonalInformation,
                            'fkTypeReservation'    => $typeReservation,
                            'fkWithWho'            => null,
                            'chargeAmount'         => 0,
                            'paid'                 => 1,
                            'title'                => $request->input('title-multiple-res')
                        ];
                        Reservation::create($reservationInfo);
                    }
                    else{

                        $reservation = $reservations->first();
                        array_push($conflictReservations, $reservation);
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
                    'title-simple-res'       => 'required|max:50',
                    'date-start-simple-res'  => 'required|max:16',
                    'hour-start-simple-res'  => 'required|max:2',
                    'court-simple-res'       => 'required|exists:courts,id'
                ],
                [
                    'title-simple-res.name'       => 'Libellé',
                    'hour-start-simple-res.name'  => 'heure de début',
                    'court-simple-res.exists'     => 'Ce court n\'existe pas, veuillez choisir un court dans la liste déroulante',
                    'date-start-simple-res.name'  => 'date choisie'
                ]
            );

            $court = Court::find($request->input('court-simple-res'));

            //we check the court exist or if its state isn't 1
            if(!$court || $court->state != 1)
            {
                $validator->errors()->add('court-simple-res', 'Le court choisi n\'existe pas ou est en réparation');
                return back()->withInput()->withErrors($validator);
            }

            $datetime_today = new \DateTime();
            $datetime_start = date_create_from_format('d-m-Y G', $request->input('date-start-simple-res')." ".$request->input('hour-start-simple-res'));



            if($datetime_start < $datetime_today)
            {
                $validator->errors()->add('date-start-simple-res', 'La date/heure choisie n\'est pas valide');

            }
            if(!Reservation::isHourFree($court->id, $datetime_start->format('Y-m-d H:i:s')))
            {
              $validator->errors()->add('date-start-simple-res', 'Cette heure n\'est pas libre');
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
                     'title'                => $request->input('title-simple-res'),
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
        $reservation = Reservation::find($id);

        if(!$reservation)
        {
            Session::flash('errorMessage', "La réservation sélectionnée n'existe pas");
            return redirect('/staff_booking');
        }
        if($reservation->fkWho != Auth::user()->fkPersonalInformation)
        {
            Session::flash('errorMessage', "La réservation sélectionnée ne vous appartient pas");
            return redirect('/staff_booking');
        }

        $dateTimeToday = new \DateTime();
        $dateTimeStart = date_create_from_format('Y-m-d H:i:s', $reservation->dateTimeStart);
        if($dateTimeStart < $dateTimeToday)
        {
            Session::flash('errorMessage', "La réservation sélectionnée est déjà passée");
            return redirect('/staff_booking');
        }
        $reservation->delete();
        Session::flash('successMessage', "La réservation sélectionnée a bien été supprimée");
        return redirect('/staff_booking');
    }
}
