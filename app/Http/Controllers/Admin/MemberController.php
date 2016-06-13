<?php

namespace App\Http\Controllers\Admin;

use App\Models\Reservation;
use App\Models\Season;
use App\Models\Subscription_per_member;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class MemberController extends Controller
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
            //If the request has a hours input, it's from the booking plugin
            if($request->has('hours'))
            {
                //Select all the reservation in the futur
                $reservations = Reservation::where('date_hours', '>', Carbon::now())->get(['fk_member_1', 'fk_member_2']);

                //Get all the id from the members having a reservation in the futur
                $idMember = [];
                foreach ($reservations as $reservation)
                {
                    array_push($idMember, $reservation->fk_member_1);
                    array_push($idMember, $reservation->fk_member_2);
                }
                //Add the id of the connected user so he isn't in the dropdowmlist
                array_push($idMember, Auth::user()->id);
                $idMember = array_unique($idMember);

                //Select all the members that don't have a reservation in the futur
                $members = Member::where('active', 1)->where('login', "!=", "")->where('validate', 1)->whereNotIn('id', $idMember)->orderBy('last_name')->orderBy('first_name')->get();
                return response()->json($members);
            }
            
            $members = Member::where('login', "!=", "")->orderBy('active', 'desc')->get();
            foreach ($members as $member)
            {
                $member->currentStatusName = $member->CurrentStatus->status;
            }
            return response()->json($members);
        }
        return view('admin/member');
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
        //
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
    public function edit(Request $request, $id)
    {
        if($request->ajax())
        {
            $members = Member::all();
            return response()->json($members);
        }

    }

    public function messages()
    {
        return [
            'login.required' => 'Le champ login est obligatoire.',
            'status.required' => 'Le champ statut est obligatoire.',
        ];
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
            $member = Member::find($id);
            $field = $request->input('name');
            //Special process for the status
            if($request->has('status_id'))
            {
                $member = Member::where('email', $id)->first();
                $currentSeason = Season::where('begin_date', '<', Carbon::today())->where('end_date', '>', Carbon::today())->first();
                $currentStatus = Subscription_per_member::where('fk_member', $member->id)->where('fk_season', $currentSeason->id)->first();
                $currentStatus->fk_subscription = (int)$request->input('status_id');
                $currentStatus->save();
                return 'true';
            }

            //Special process for the boolean value
            if($request->input('value') == 'true')
            {
                $member->$field = '1';
                $member->save();
                return 'true';
            }
            else if($request->input('value') == 'false')
            {
                //Disable the possibility to self-remove from the admin
                if($member->id == Auth::user()->id && $field == 'administrator')
                {
                    return 'false';
                }
                $member->$field = '0';
                $member->save();
                return 'true';
            }
            return 'error';

        }
        // Check form
        //-----------
        $validator = Validator::make($request->all(),
            [
                'login'.$id     => 'required',
                'status'.$id    => 'required|filled'
            ],
            ['login'.$id.'.required' => 'Le champ login est obligatoire.', 'status'.$id.'.required' => 'Le champ statut est obligatoire.']);
        /////////////////////////////////////////////


        // Verify if login is not already in DB for no duplicate information
        //------------------------------------------------------------------
        $validator->after(function($validator) use ($request, $id)
        {
            $duplicate = Member::where('login', $request->input('login'.$id))->count();

            if(!empty($duplicate))
            {
                $validator->errors()->add('login'.$id, 'Ce login est déjà utilisé.');
            }
        });
        /////////////////////////////////////////////

        // Display errors messages, return to register page
        //-------------------------------------------------
        if($validator->fails())
        {
            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////

        // Insert the login, status, token and validate account
        //-----------------------------------------------------
        $member = Member::find($id);
        $member->UpdateLogin($request->input('login'.$id), $request->input('status'.$id));
        $member->save();
        /////////////////////////////////////////////

        $emailMember = $member->email;

        // Send email to the user to choose password
        //-------------------------------------------------
        Mail::send('emails.user.password', ['last_name'  => $member->last_name,
                                            'first_name' => $member->first_name,
                                            'login'      => $member->login,
                                            'token'      => $member->token],
        function ($message) use($emailMember)
        {
            $message->to($emailMember)->subject('Votre compte du Tennis Club Chavornay a été activé');
        });
        /////////////////////////////////////////////

        return redirect('admin')->with('message', 'Le login a été créé avec succès, un mail lui a été envoyé');
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