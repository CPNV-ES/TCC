<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\PersonalInformation;
use App\Locality;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Session;
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
        //infoUser are information of member and no-members
        $infoUsers = PersonalInformation::all();
        return view('admin/member',compact('infoUsers'));
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
        $personal_information = PersonalInformation::find($id);
        $localities = Locality::all();
        return view('admin/configuration/memberEdit',compact('personal_information','localities'));
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
        // Check form
        //-----------
        //IGI - added needed rules
        $validator = Validator::make($request->all(),
            [
                'firstname' => 'required|max:50',
                'lastname' => 'required|max:50',
                'street' => 'max:100',
                'streetNbr' => 'max:45',
                'telephone' => 'required',
                'email' => 'required|email|max:255|unique:personal_informations,email,' . $id,
                'locality' => 'required|max:100',
            ]);

        /////////////////////////////////////////////

        // Verify if login is not already in DB for no duplicate information
        //------------------------------------------------------------------
        $validator->after(function($validator) use ($request, $id)
        {
            $regexTel = "/^(((\+|00)\d{2,3})|0)([.\/ -]?\d){9}$/";
            if(!preg_match($regexTel, $request->input('telephone')))
            {
                $validator->errors()->add('telephone', 'Ce numéro n\'est pas valide (format: 012 123 12 12)');
            }

            /** Check for a minimum of one admin in the database **/
            $adminUsers = User::where([['isAdmin', 1], ['validated', 1], ['active', 1]])->get();
            $userID = $adminUsers[0]->fkPersonalInformation;
            $currentID = $request->input("member-id");

            if ($adminUsers->count() == 1 && $userID == $currentID) {
              if ($request->input('isAdmin') == null) {
                $validator->errors()->add('adminRole', 'Il doit au moins y avoir un admin dans le système.');
              }
              elseif ($request->input("active") == null) {
                $validator->errors()->add('accountActive', 'Il doit au moins y avoir un admin dans le système.');
              }
            }

        });

        /////////////////////////////////////////////



        // Display errors messages, return to update page
        //-------------------------------------------------
        if($validator->fails())
        {
            return back()->withInput()->withErrors($validator);
        }

        /////////////////////////////////////////////
        $userInfo = PersonalInformation::findOrFail($id);
        $userAccount = User::where('fkPersonalInformation', $id)->first();

        if ($userAccount) {
          //IGI- Update member info and member account parameters and save it
          //-----------------------------------------------------
          $userAccount->UpdateAccountParam($request->all());
          $userAccount->save();
        }

        //sorry...
        ($request->exists("toVerify")) ? $request["toVerify"]= "1": $request["toVerify"]= "0" ;
        $request["birthDate"] = $userInfo->birthDate;

        $userInfo->update($request->all());
        //$member->UpdateAccount($request->all());
        $userInfo->save();

        //IGI - flash message and come back to the edit member page
        Session::flash('message', "Les modifications ont bien été enregistrées");
        return redirect('admin/members/'.$userInfo->id.'/edit');
    }


    //IGI- actually not used - will be used to check (in AJAX) in the edit member form if the email is already used in the db.
    public function checkMailUse(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $duplicate = Member::where([['email','=',$request->input('email')],
            ['id','<>', $request->input('idMember')]])->count();
            if($duplicate>0)
            {
                return response()->json(['response' => false]);
            }
            else{
                return response()->json(['response' => true]);
            }
        }
        else{
            return false;
        }
    }
    /*
     * Update the login of a specific members
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id member id
     * @return \Illuminate\Http\Response
     */
    public function updateLogin(Request $request, $id)
    {

        // Check form
        //-----------
        $validator = Validator::make($request->all(),
            ['login'.$id     => 'required'],
            ['login'.$id.'.required' => 'Le champ login est obligatoire.']);

        /////////////////////////////////////////////
        // Verify if login is not already in DB for no duplicate information
        //------------------------------------------------------------------
        $validator->after(function($validator) use ($request, $id)
        {
            $duplicate = User::where('username', $request->input('login'.$id))->count();
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

        $member = User::where('fkPersonalInformation', $id)->first();
        $member->UpdateLogin($request->input('login'.$id));
        $member->save();

        /////////////////////////////////////////////
        $emailMember = $member->personal_information->email;
        // Send email to the user to choose password
        //-------------------------------------------------
        Mail::send('emails.user.password', ['last_name'  => $member->personal_information->lastname,
            'first_name' => $member->personal_information->firstname,
            'login'      => $member->username,
            'token'      => $member->personal_information->_token],
            function ($message) use($emailMember)
            {
                $message->to($emailMember)->subject('Votre compte du Tennis Club Chavornay a été activé');
            });
        /////////////////////////////////////////////
        return redirect('admin')->with('message', 'Le login a été créé avec succès, un mail lui a été envoyé');
    }
        /////////////////////////////////////////////

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
