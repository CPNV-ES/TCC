<?php
/*
Author : -
Created : -
Modified by : S. Forsyth
Last Modif.: 01.02.2017
Description : Manages the requests from the Season view.
*/
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Season;
use Validator;

class SeasonController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // SFH: Ordered them by latest first
        $seasons = Season::orderBy('begin_date', 'desc')->get();

        // SFH: Check if there is an entry in the database
        //      define new season stat and end dates
        if (sizeof($seasons) > 0) {
            $newSeasonStart = date('Y-m-d', strtotime($seasons->first()->end_date . " +1 day"));
            $newSeasonEnd = date('Y-m-d', strtotime($newSeasonStart . " +1 year -1 day"));
        }

        // SFH: Added compact for the seasons, newSeasonStart and newSeasonEnd
        return view('/admin/configuration/seasons', compact('seasons', 'newSeasonStart', 'newSeasonEnd'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        // Check form
        //-----------
        // SFH: Added 'date', 'after' validators for the check of a season
        //      also added a better french version of the error messages (date, after)
        $validator = Validator::make($request->all(),
            [
                'begin_date'  => 'required|date',
                'end_date'    => 'required|date|after:' . date('Y-m-d', strtotime($request->begin_date . " +6 months -1 day"))
            ],
            [
                'begin_date.required'   => 'Le champ \'Date de début\' est obligatoire.',
                'begin_date.date'   => 'Le champ \'Date de début\' n\'est pas une date valide.',
                'end_date.required'     => 'Le champ \'Date de fin\' est obligatoire.',
                'end_date.date'     => 'Le champ \'Date de fin\' n\'est pas une date valide.',
                'end_date.after'       => 'Le champ \'Date de fin\' doit être postérieure au ' . date('d.m.Y', strtotime($request->begin_date . " +6 months")) . "."
            ]);
        /////////////////////////////////////////////

        // Display errors messages, return to the season page
        //-------------------------------------------------
        if($validator->fails()) {
            // SFH: Return an error message to be displayed
            $request->session()->flash('alert-danger', 'Veuillez vérifier les informations saisies!');

            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////

        // Insert the season
        //-----------------------------------------------------
        $season = Season::create($request->all());
        $season->save();
        /////////////////////////////////////////////

        // SFH: Return a success message to be displayed
        $request->session()->flash('alert-success', 'La saison a été ajoutée avec succès!');

        return redirect('admin/config/seasons');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        // SFH: Added the delete function
        $season = Season::findOrFail($id);
        $season->delete();

        // SFH: Return a success message to be displayed
        $request->session()->flash('alert-success', 'La saison a été supprimée avec succès!');

        return redirect("/admin/config/seasons");
        // SFH: End
    }
}
