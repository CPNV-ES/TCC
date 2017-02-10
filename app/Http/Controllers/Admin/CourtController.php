<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Court;
use Validator;

class CourtController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // SFH: Ordered them alphabetically
        $courts = Court::orderby('name', 'asc')->get();

        // SFH: Added compact for the courts
        return view('admin/configuration/courts', compact('courts'));
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
        // SFH: Added 'max', 'unique', 'date_format', 'after' and 'min' validators for the check of a court
        //      also added a better french version of the error messages (max, unique, date_format, after, integer, min)
        $validator = Validator::make($request->all(),
            [
                'name'      => 'required|max:50|unique:courts,name',
                'nbDays'    => 'required|integer|min:1',
            ],
            [
                'name.required' => 'Le champ \'Nom\' est obligatoire.',
                'name.max' => 'Le texte de \'Nom\' ne peut contenir plus de 50 caractères.',
                'name.unique' => 'Le \'Nom\' saisie existe déjà.',
                'nbDays.required' => 'Le champ \'Fenêtre de reservation membre\' est obligatoire.',
                'nbDays.integer' => 'Le champ \'Fenêtre de reservation membre\' doit contenir des chiffres.',
                'nbDays.min' => 'La valeur de \'Fenêtre de reservation membre\' doit être supérieure à 1.'
            ]);
        /////////////////////////////////////////////

        // Display errors messages, return to the court page
        //-------------------------------------------------
        if($validator->fails()) {
            // SFH: Return an error message to be displayed
            $request->session()->flash('alert-danger', 'Veuillez vérifier les informations saisies!');

            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////

        // Insert the court
        //-----------------------------------------------------
        $court = Court::create($request->all());
        $court->save();
        /////////////////////////////////////////////

        // SFH: Return a success message to be displayed
        $request->session()->flash('alert-success', 'Le court a été ajouté avec succès!');

        return redirect('admin/config/courts');
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
        // SFH: Ordered them alphabetically
        $courts = Court::orderby('name', 'asc')->get();
        $singleCourt = Court::findOrFail($id);

        return view('admin/configuration/courts', compact('singleCourt', 'courts'));
        // SFH: End
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        // Check form
        //-----------
        // SFH: Added 'max', 'unique', 'date_format', 'after' and 'min' validators for the check of a court
        //      also added a better french version of the error messages (max, unique, date_format, after, integer, min)
        $validator = Validator::make($request->all(),
            [
                'name'      => 'required|max:50|unique:courts,name,' . $id,
                'nbDays'    => 'required|integer|min:1',
            ],
            [
                'name.required' => 'Le champ \'Nom\' est obligatoire.',
                'name.max' => 'Le texte de \'Nom\' ne peut contenir plus de 50 caractères.',
                'name.unique' => 'Le \'Nom\' saisie existe déjà.',
                'nbDays.required' => 'Le champ \'Fenêtre de reservation membre\' est obligatoire.',
                'nbDays.integer' => 'Le champ \'Fenêtre de reservation membre\' doit contenir des chiffres.',
                'nbDays.min' => 'La valeur de \'Fenêtre de reservation membre\' doit être supérieure à 1.'
            ]);
        /////////////////////////////////////////////

        // Display errors messages, return to the court page
        //-------------------------------------------------
        if($validator->fails()) {
            // SFH: Return an error message to be displayed
            $request->session()->flash('alert-danger', 'Veuillez vérifier les informations saisies!');

            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////

        // Insert the court
        //-----------------------------------------------------
        // SFH: Added court update
        $court = Court::findOrFail($id);
        $court->update($request->all());
        // SFH: End

        ////////////////////////////////////////////

        // SFH: Return a success message to be displayed
        $request->session()->flash('alert-success', 'Le court a été modifié avec succès!');

        return redirect('admin/config/courts');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        // SFH: Added the delete function
        $court = Court::findOrFail($id);
        $court->delete();

        // SFH: Return a success message to be displayed
        $request->session()->flash('alert-success', 'Le court a été supprimé avec succès!');

        return redirect('admin/config/courts');
        // SFH: End
    }
}
