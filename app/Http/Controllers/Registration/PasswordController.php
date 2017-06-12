<?php

namespace App\Http\Controllers\Registration;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;

use App\User;
use App\PersonalInformation;

use Validator;
use Hash;

class PasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth/register/password/reset');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        // Verify if url contain login and token
        //--------------------------------------
        if($request->has('login') && $request->has('token'))
        {

            // Check in DB if exist
            //---------------------
            $member = User::where('username', $request->input('login'))->get()[0];
            // If validator pass, show the page to define password, and store login in session
            //--------------------------------------------------------------------------------
            if(!empty($member) && $member->personal_information->_token == $request->input('token') )
            {
                $request->session()->put('login', $request->input('login'));
                return view('auth/register/password/definePassword');
            }
            else
            {
                return redirect('home');
            }
        }
        else
        {
            return redirect('home');
        }
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
                'email'     => 'required'
            ]);
        /////////////////////////////////////////////

        // Verify if login and activation code are corresponding with the DB
        //------------------------------------------------------------------
        $validator->after(function($validator) use ($request)
        {
            $user = PersonalInformation::where('email', $request->input('email'))->count();

            if(empty($user))
            {
                $validator->errors()->add('email', "Cet e-mail n'existe pas.");
            }
        });
        /////////////////////////////////////////////


        // Display errors messages, return to password page
        //-------------------------------------------------
        if($validator->fails())
        {
            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////


        // If validator pass, add token in database and send email
        //--------------------------------------------------------------------------------
        $member = PersonalInformation::where('email', $request->input('email'))->get()->first();
        //Generate the token
        $validationCode = str_random(20);

        $member->_token = $validationCode;
        $member->save();

        // Send email to the user to choose password
        //-------------------------------------------------
        $mailMember = $member->email;
        Mail::send('emails.user.passwordReset', ['last_name'  => $member->lastname,
            'first_name' => $member->firstname,
            'login'      => $member->user->username,
            'token'      => $member->_token],
            function ($message) use($mailMember)
            {
                $message->to($mailMember)->subject('Réinitialisation du mot de passe');
            });
        /////////////////////////////////////////////

        return redirect('/login')->with('message', 'Un e-mail vous a été envoyé.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('/auth/passwords/reset');
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$request->session()->has('login'))
        {
            dd('Bien essayé :)');
        }

        // Check form
        //-----------
        $validator = Validator::make($request->all(),
            [
                'password'                  => 'required|confirmed|min:6',
                'password_confirmation'     => 'required'
            ]);

        // Display errors messages, return to password page
        //-------------------------------------------------
        if($validator->fails())
        {
            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////


        //Update the member in the DB with the password and remove the token
        //------------------------------------------------------------------
        $member = User::where('username', $request->session()->get('login'))->get();


        // Test if the member exists
        //--------------------------
        if(empty($member))
        {
            return back();
        }

        // Insert the password
        //--------------------
        $member[0]->UpdatePassword($request->input('password'));
        $member[0]->save();
        ///////////////////////


        // Disabled possibility to redifine password through to /password/create
        //----------------------------------------------------------------------
        $request->session()->forget('login');

        return view("auth/register/password/passwordDefined");
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
