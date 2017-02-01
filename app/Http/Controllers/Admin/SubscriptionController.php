<?php
/*
Author : -
Created : -
Modified by : S. Forsyth
Last Modif.: 01.02.2017
Description : Manages the requests from the Subscription view.
*/
namespace App\Http\Controllers\Admin;

use App\Models\Subscription_per_member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Validator;

class SubscriptionController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // SFH: Ordered them by latest first
        $subscriptions = Subscription::orderby('status', 'asc')->get();

        // SFH: Added compact for the subscriptions
        return view('/admin/configuration/subscriptions', compact('subscriptions'));
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
        // SFH: Added 'max', 'unique', 'min' validators for the check of a subscription
        //      also added a better french version of the error messages (max, unique, min)
        $validator = Validator::make($request->all(),
            [
                'status'    => 'required|max:50|unique:subscriptions,status',
                'amount'    => 'numeric|min:0'
            ],
            [
                'status.required' => 'Le champ \'Type\' est obligatoire.',
                'status.max' => 'Le texte de \'Type\' ne peut contenir plus de 50 caractères.',
                'status.unique' => 'La valeur du champ \'Type\' status est déjà utilisée.',
                'amount.numeric' => 'Le champ \'Montant\' doit contenir un nombre.',
                'amount.min' => 'La valeur du champ \'Montant\' doit être positif ou nul.'
            ]);
        /////////////////////////////////////////////

        // Display errors messages, return to the subscription page
        //-------------------------------------------------
        if($validator->fails()) {
            // SFH: Return an error message to be displayed
            $request->session()->flash('alert-danger', 'Veuillez vérifier les informations saisies!');

            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////

        // Insert the subscription
        //-----------------------------------------------------
        $subscription = Subscription::create($request->all());
        $subscription->save();
        /////////////////////////////////////////////

        // SFH: Return a success message to be displayed
        $request->session()->flash('alert-success', 'La cotisation a été ajoutée avec succès!');

        return redirect('admin/config/subscriptions');
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
        // SFH: Added the edit function
        $subscriptions = Subscription::orderby('status', 'asc')->get();
        $singleSubscription = Subscription::findOrFail($id);

        return view("/admin/configuration/subscriptions", compact('subscriptions', 'singleSubscription'));
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
        // SFH: Added 'max', 'unique', 'min' validators for the check of a subscription
        //      also added a better french version of the error messages (max, unique, min)
        $validator = Validator::make($request->all(),
            [
                'status'    => 'required|max:50|unique:subscriptions,status,' . $id,
                'amount'    => 'numeric|min:0'
            ],
            [
                'status.required' => 'Le champ \'Type\' est obligatoire.',
                'status.max' => 'Le texte de \'Type\' ne peut contenir plus de 50 caractères.',
                'status.unique' => 'La valeur du champ \'Type\' status est déjà utilisée.',
                'amount.numeric' => 'Le champ \'Montant\' doit contenir un nombre.',
                'amount.min' => 'La valeur du champ \'Montant\' doit être positif ou nul.'
            ]);
        /////////////////////////////////////////////

        // Display errors messages, return to the subscription page
        //-------------------------------------------------
        if($validator->fails()) {
            // SFH: Return an error message to be displayed
            $request->session()->flash('alert-danger', 'Veuillez vérifier les informations saisies!');

            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////

        // Update the subscription
        //-----------------------------------------------------
        $subscription = Subscription::findOrFail($id);
        $subscription->update($request->all());
        /////////////////////////////////////////////

        // SFH: Return a success message to be displayed
        $request->session()->flash('alert-success', 'La cotisation a été modifiée avec succès!');

        return redirect('admin/config/subscriptions');
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
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        // SFH: Return a success message to be displayed
        $request->session()->flash('alert-success', 'La cotisation a été supprimée avec succès!');

        return redirect("/admin/config/subscriptions");
        // SFH: End
    }

}
