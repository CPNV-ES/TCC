<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;
use App\User;
use Validator;

class ProfileController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('profile/home');
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
                'last_name'     => 'required',
                'first_name'    => 'required',
                'address'       => 'required',
                'city'          => 'required',
                'zip_code'      => 'required|integer|digits:4',
                'email'         => 'required|email',
                'phone'         => 'required'
            ]);
        /////////////////////////////////////////////

        // Verify if email is not already in DB for no duplicate information
        //------------------------------------------------------------------
        $validator->after(function($validator)
        {
            extract($_POST);

            $duplicate = Member::where('email', $email)->get();

            if(count($duplicate) != 0 && $duplicate[0]->id != Auth::user()->id)
            {
                $validator->errors()->add('email', 'Cet e-mail est déjà utilisé.');
            }
        });
        /////////////////////////////////////////////


        // Display errors messages, return to profil page
        //-------------------------------------------------
        if($validator->fails())
        {
            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////

        // Update in DB
        //-------------
        $member = Member::find(Auth::user()->id);

        $member->UpdateUser($_POST);

        $member->save();
        /////////////////////////////////////////////

        return redirect("/profile");
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
