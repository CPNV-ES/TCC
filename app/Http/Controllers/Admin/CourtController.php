<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Court;
use Validator;

class CourtController extends Controller
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
            $courts = Court::all();
            return response()->json($courts);
        }
        return view('admin/configuration/courts');
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
        // Check form
        //-----------
        $validator = Validator::make($request->all(),
            [
                'name'                          => 'required',
                'start_time'                    => 'required',
                'end_time'                      => 'required',
                'booking_window_member'         => 'required|integer',
                'booking_window_not_member'     => 'required|integer',
            ],
            ['name.required' => 'Le champ \'Nom\' est obligatoire.',
             'start_time.required' => 'Le champ \'Heure d\'ouverture\' de début est obligatoire.',
             'end_time.required' => 'Le champ \'Heure de fermeture\' est obligatoire.',
             'booking_window_member.required' => 'Le champ \'Fenêtre de reservation membre\' est obligatoire.',
             'booking_window_not_member.required' => 'Le champ \'Fenêtre de reservation membre\' est obligatoire.']);
        /////////////////////////////////////////////

        // Display errors messages, return to the season page
        //-------------------------------------------------
        if($validator->fails())
        {
            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////

        dd($request->check('indor'));

        // Insert the court
        //-----------------------------------------------------
        $court = Court::create($request->all());

        $court->save();
        /////////////////////////////////////////////


        return redirect('admin/config/courts');
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
        if($request->ajax())
        {
            $court = Court::find($id);
            $field = $request->input('name');
            //if for the indoor field wich is a boolean
            if($request->input('name') == 'indor')
            {
                if($request->input('value') == '0')
                {
                    $court->$field = '0';
                    $court->save();
                    return 'false';
                }
                else
                {
                    $court->$field = '1';
                    $court->save();
                    return 'true';
                }
            }
            else
            {
                $court->$field = $request->input('value');
                $court->save();
                return 'success';
            }

        }
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
