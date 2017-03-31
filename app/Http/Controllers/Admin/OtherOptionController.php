<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Config;
use Validator;

class OtherOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $config = Config::all()->first();

        return view('admin/configuration/other_options', compact('config'));
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
      $validator = Validator::make($request->all(),
          [
              'nbDaysGracePeriod' => 'required|integer|min:0',
              'nbDaysLimitNonMember' => 'required|integer|min:1',
              'courtOpenTime' => 'required',
              'courtCloseTime' => 'required|after:courtOpenTime',
              'nbReservations' => 'required|integer|min:1'
          ],
          [
              'nbDaysGracePeriod.required' => 'Le champ \'Période de grâce\' est obligatoire.',
              'nbDaysGracePeriod.integer' => 'Le champ \'Période de grâce\' doit contenir des chiffres.',
              'nbDaysGracePeriod.min' => 'La valeur de \'Période de grâce\' doit être positif.',
              'nbDaysLimitNonMember.required' => 'Le champ \'Fenêtre de réservation non-membre\' est obligatoire.',
              'nbDaysLimitNonMember.integer' => 'Le champ \'Fenêtre de réservation non-membre\' doit contenir des chiffres.',
              'nbDaysLimitNonMember.min' => 'La valeur de \'Fenêtre de réservation non-membre\' doit être supérieur à 0.',
              'courtOpenTime.required' => 'Le champ \'Heure d\'ouverture\' est obligatoire.',
              'courtCloseTime.required' => 'Le champ \'Heure de fermeture\' est obligatoire.',
              'courtCloseTime.after' => 'Le champ \'Heure de fermeture\' doit être une date postérieure au \'Heure d\'ouverture\'.',
              'nbDaysGracePeriod.required' => 'Le champ \'Nombre de réservation simultanées\' est obligatoire.',
              'nbDaysGracePeriod.integer' => 'Le champ \'Nombre de réservation simultanées\' doit contenir des chiffres.',
              'nbDaysGracePeriod.min' => 'La valeur de \'Nombre de réservation simultanées\' doit  être supérieur à 0.'
          ]);

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
      $config = Config::findOrFail($id);
      $config->update($request->all());
      // $config = Config::create($request->all());
      // $config->save();
      /////////////////////////////////////////////

      // SFH: Return a success message to be displayed
      $request->session()->flash('alert-success', 'La config a été changé avec succès!');

      return redirect('admin/config/other_options');
    }

}
