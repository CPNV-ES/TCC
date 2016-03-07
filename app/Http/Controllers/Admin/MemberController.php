<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController;
use App\Models\Member;
use Validator;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $members = Member::where('active', 0)->OrderBy('first_name')->OrderBy('last_name')->get();

        return view('admin/home',[
            'members' => $members,
        ]);
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
    public function edit($id)
    {
        //
    }

    public function messages()
    {
        return [
            'login.required' => 'Le champ login est obligatoire.',
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
        $validator = Validator::make($request->all(),
            [
                'login'.$id     => 'required'
            ],
            ['required' => 'Le champ login est obligatoire.']);
        /////////////////////////////////////////////


        // Verify if login is not already in DB for no duplicate information
        //------------------------------------------------------------------
        $validator->after(function($validator)
        {

            extract($_POST);

            $duplicate = Member::where('login', $_POST['login'.$id])->count();

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


        // Insert the login in the database and activate the account
        //-------------------------------------------------
        $member = Member::find($id);

        $member->login = $_POST['login'.$id];
        $member->active = 1;
        $member->save();
        return redirect('admin');
        /////////////////////////////////////////////


        // Send email to the user to choose password
        //-------------------------------------------------
        //Generate the token
        //Generer ici le token
        Mail::send('emails.user.password', ['last_name' => $member->last_name, 'first_name' => $member->first_name, 'email' => $member->email], function ($message) use($email)
        {
            $message->to($email)->subject('Votre compte du Tennis Club Chavornay a été activé');
        });
        /////////////////////////////////////////////
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
