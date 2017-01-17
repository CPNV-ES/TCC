<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subscription_per_member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Http\Requests;
use Validator;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptions = Subscription::all();
        $payedSubscriptions = Subscription_per_member::all();

        for ($i = 0; $i < sizeof($subscriptions); $i++) {

            $hasSubscription = false;

            foreach ($payedSubscriptions as $payedSubscription) {
                if ($payedSubscription->fk_subscription == $subscriptions[$i]->id) {
                    $hasSubscription = true;
                }
            }

            $subscriptions[$i]['hasSubscription'] = $hasSubscription;

        }

        return view('/admin/configuration/subscriptions', compact('subscriptions'));
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
                'status'    => 'required|unique:subscriptions,status',
                'amount'    => 'numeric|min:0'
            ],
            [
                'status.required' => 'Le champ \'Type\' est obligatoire.',
                'status.unique' => 'La valeur du champ \'Type\' status est déjà utilisée.',
                'amount.numeric' => 'Le champ \'Montant\' doit contenir un nombre.',
                'amount.min' => 'La valeur du champ \'Montant\' doit être positif ou nul.'
            ]);
        /////////////////////////////////////////////

        // Display errors messages, return to the season page
        //-------------------------------------------------
        if($validator->fails())
        {
            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////

        // Insert the subscription
        //-----------------------------------------------------
        $subscription = Subscription::create($request->all());

        $subscription->save();
        /////////////////////////////////////////////

        return redirect('admin/config/subscriptions');
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
        $subscriptions = Subscription::all();
        $payedSubscriptions = Subscription_per_member::all();

        for ($i = 0; $i < sizeof($subscriptions); $i++) {

            $hasSubscription = false;

            foreach ($payedSubscriptions as $payedSubscription) {
                if ($payedSubscription->fk_subscription == $subscriptions[$i]->id) {
                    $hasSubscription = true;
                }
            }

            $subscriptions[$i]['hasSubscription'] = $hasSubscription;

        }

        $singleSubscription = Subscription::findOrFail($id);

        return view("/admin/configuration/subscriptions", compact('subscriptions', 'singleSubscription'));
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
                'status'    => 'required|unique:subscriptions,status,' . $id,
                'amount'    => 'numeric|min:0'
            ],
            [
                'status.required' => 'Le champ \'Type\' est obligatoire.',
                'status.unique' => 'La valeur du champ \'Type\' status est déjà utilisée.',
                'amount.numeric' => 'Le champ \'Montant\' doit contenir un nombre.',
                'amount.min' => 'La valeur du champ \'Montant\' doit être positif ou nul.'
            ]);
        /////////////////////////////////////////////

        // Display errors messages, return to the season page
        //-------------------------------------------------
        if($validator->fails())
        {
            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////

        // Insert the subscription
        //-----------------------------------------------------
        $subscription = Subscription::findOrFail($id);
        $subscription->update($request->all());

        $subscription->save();
        /////////////////////////////////////////////

        return redirect('admin/config/subscriptions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return redirect("/admin/config/subscriptions");
    }
}
