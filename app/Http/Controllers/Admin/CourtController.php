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
    public function index()
    {
        $courts = Court::all();

        return view('admin/configuration/courts', compact('courts'));
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
                'name'                          => 'required|unique:courts,name',
                'start_time'                    => 'required|date_format:H:i',
                'end_time'                      => 'required|date_format:H:i|after:start_time',
                'booking_window_member'         => 'required|integer|min:1',
                'booking_window_not_member'     => 'required|integer|min:1',
            ],
            ['name.required' => 'Le champ \'Nom\' est obligatoire.',
             'name.unique' => 'Le \'Nom\' saisie existe déjà.',
             'start_time.required' => 'Le champ \'Heure d\'ouverture\' est obligatoire.',
             'end_time.required' => 'Le champ \'Heure de fermeture\' est obligatoire.',
             'end_time.after' => 'Le champ \'Heure de fermeture\' doit être supérieur à \'Heure d\'ouverture\'.',
             'booking_window_member.required' => 'Le champ \'Fenêtre de reservation membre\' est obligatoire.',
             'booking_window_member.min' => 'La valeur de \'Fenêtre de reservation membre\' doit être supérieure à 1.',
             'booking_window_not_member.required' => 'Le champ \'Fenêtre de reservation non membre\' est obligatoire.',
             'booking_window_not_member.min' => 'La valeur de \'Fenêtre de reservation non membre\' doit être supérieure à 1.'
            ]);
        /////////////////////////////////////////////

        // Display errors messages, return to the season page
        //-------------------------------------------------
        if($validator->fails())
        {
            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////

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
        $courts = Court::all();
        $singleCourt = Court::find($id);
        return view('admin/configuration/courts', compact('singleCourt', 'courts'));
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
        // Check form
        //-----------
        $validator = Validator::make($request->all(),
            [
                'name'                          => 'required|unique:courts,name,' . $id,
                'start_time'                    => 'required|date_format:H:i',
                'end_time'                      => 'required|date_format:H:i|after:start_time',
                'booking_window_member'         => 'required|integer|min:1',
                'booking_window_not_member'     => 'required|integer|min:1',
            ],
            ['name.required' => 'Le champ \'Nom\' est obligatoire.',
                'start_time.required' => 'Le champ \'Heure d\'ouverture\' est obligatoire.',
                'end_time.required' => 'Le champ \'Heure de fermeture\' est obligatoire.',
                'end_time.after' => 'Le champ \'Heure de fermeture\' doit être supérieur à \'Heure d\'ouverture\'.',
                'booking_window_member.required' => 'Le champ \'Fenêtre de reservation membre\' est obligatoire.',
                'booking_window_member.min' => 'La valeur de \'Fenêtre de reservation membre\' doit être supérieure à 1.',
                'booking_window_not_member.required' => 'Le champ \'Fenêtre de reservation non membre\' est obligatoire.',
                'booking_window_not_member.min' => 'La valeur de \'Fenêtre de reservation non membre\' doit être supérieure à 1.'
            ]);
        /////////////////////////////////////////////

        // Display errors messages, return to the season page
        //-------------------------------------------------
        if($validator->fails())
        {
            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////

        // Insert the court
        //-----------------------------------------------------
        $court = Court::findOrFail($id);
        $court->update($request->all());

        return redirect('admin/config/courts');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $court = Court::findOrFail($id);
        $court->delete();

        return redirect('admin/config/courts');
    }
}
