<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

use App\Http\Requests;

use Validator;

class PasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('auth/passwords/testActivationCode');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        //dd($request->session()->get('login'));
        return view('auth/passwords/definePassword');
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
                'login'     => 'required',
                'token'     => 'required'
            ]);
        /////////////////////////////////////////////


        // Verify if login and activation code are corresponding with the DB
        //------------------------------------------------------------------
        $validator->after(function($validator)
        {

            extract($_POST);

            $member = Member::where('login', $login)->where('token', $token)->count();

            if(empty($member))
            {
                $validator->errors()->add('token', 'Cette combinaison de login/code d\'activation n\'est pas valable.');
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

        //If validator pass, show the page to define password, and store login in session
        extract($_POST);
        $request->session()->put('login', $login);
//        return redirect()->action('PasswordController@create')->with('login', $login);
        return redirect()->action('PasswordController@create');

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
        // Check form
        //-----------
        $validator = Validator::make($request->all(),
            [
                'password1'     => 'required',
                'password2'     => 'required'
            ]);
        /////////////////////////////////////////////


        // Verify if password are strong enough
        //------------------------------------------------------------------
        $validator->after(function($validator)
        {
            extract($_POST);

            if($password1 != $password2)
            {
                $validator->errors()->add('password2', 'Vos mots de passe ne sont pas identiques.');
            }
            if(strlen($password1) < 6 || !preg_match('/[A-Z]+[a-z]+[0-9]+/', $password1))
            {
                $validator->errors()->add('password2', 'Ce mot de passe n\'est pas assez fort.');
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

        return ('poney');
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
