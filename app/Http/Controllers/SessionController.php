<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth/login/login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
                'login'     => 'required',
                'password'  => 'required'
            ]);
        /////////////////////////////////////////////

        // Validate connexion user and if account is active
        //-------------------------------------------------
        $validator->after(function($validator) use ($request)
        {
            $userDbPassword = User::where('username', $request->input('login'))->get();

            if (count($userDbPassword) AND Hash::check($request->input('password'), $userDbPassword[0]->password))
            {
                $unActive = User::where('username', $request->input('login'))->where('validated', 0)->count();

                if ($unActive)
                {
                    $validator->errors()->add('password', 'Le compte est désactivé.');
                }

            }
            else
            {
                $validator->errors()->add('password', 'Les informations fournies sont incorrectes.');
            }
        });
        /////////////////////////////////////////////


        // Display errors messages, return to register page
        //-------------------------------------------------
        if ($validator->fails())
        {
            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////


        // All OK, start session and redirect
        //-------------------------------------------------------
        if (Auth::attempt(['username' => $request->input('login'), 'password' => $request->input('password')]))
        {
            //If the member has to verify information, redirect to profile, test here because we can't use the middleware
            /*if (Auth::user()->personal_information->toVerify)
            {
                return redirect('profile');
            }*/
            // If the member is administrator, redirect to administration panel
            if (Auth::user()->isAdmin)
            {
                return redirect('admin');
            }
            return redirect('home');
        }
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        Auth::logout();
        return view('welcome');
    }
}
