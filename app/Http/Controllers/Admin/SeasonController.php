<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Season;
use App\Http\Requests;

use Validator;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seasons = Season::orderBy('begin_date', 'desc')->get();

        if (sizeof($seasons) > 0) {
            $newSeasonStart = date('Y-m-d', strtotime($seasons->first()->end_date . " +1 day"));
            $newSeasonEnd = date('Y-m-d', strtotime($newSeasonStart . " +1 year"));
        }

        return view('/admin/configuration/seasons', compact('seasons', 'newSeasonStart', 'newSeasonEnd'));
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
                'begin_date'  => 'required|date',
                'end_date'    => 'required|date|after:' . date('Y-m-d', strtotime($request->begin_date . " +6 months -1 day"))
            ],
            [
                'begin_date.required'   => 'Le champ \'Date de début\' est obligatoire.',
                'end_date.required'     => 'Le champ \'Date de fin\' est obligatoire.',
                'end_date.after'       => 'Le champ \'Date de fin\' doit être postérieure au ' . date('d.m.Y', strtotime($request->begin_date . " +6 months")) . "."
            ]);
        /////////////////////////////////////////////

        // Display errors messages, return to the season page
        //-------------------------------------------------
        if($validator->fails())
        {
            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////
        
        // Insert the season
        //-----------------------------------------------------
        $season = Season::create($request->all());

        $season->save();
        /////////////////////////////////////////////

        return redirect('admin/config/seasons');
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
        return redirect("/admin/config/seasons");
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
        $season = Season::findOrFail($id);
        $season->delete();

        return redirect("/admin/config/seasons");
    }
}
