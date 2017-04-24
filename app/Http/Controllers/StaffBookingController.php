<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Requests;

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

        }
        else
        {
            $validator = Validator::make($request->all(),
                [
                    'datetime-start' => 'required|max:50',
                    'court' => 'required|exists:courts,id'
                ],
                [
                    'court.exists' => 'Cette localité n\'existe pas, si vous ne trouvez pas votre localité veuillez choisir "autre"',
                    'datetime-start.name' => 'date choisie'
                ]
            );
            if($validator->fails())
            {
                return back()->withInput()->withErrors($validator);
            }
            $court = Court::find($request->input('court'));
            if(!$court)
            {
                $validator->errors()->add('court', 'Le court choisit n\'existe pas ou est en réparation');
                return back()->withInput()->withErrors($validator);
            }
            $datetime_start = data_create_from_format('dd.mm.yyyy HH:00', $request->input('datetime-start'));
            if($datetime_start)


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
