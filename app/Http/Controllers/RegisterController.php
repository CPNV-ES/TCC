<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Validator;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth/register/register');
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
        //
        // Check form
        //-----------
        $validator = Validator::make($request->all(),
            [
                'last_name'     => 'required',
                'first_name'    => 'required',
                'address'       => 'required',
                'city'          => 'required',
                'zip_code'      => 'required|integer',
                'email'         => 'required|email',
                'phone'         => 'required'
            ]);
        /////////////////////////////////////////////

        extract($_POST);

        // Verify if email is not already in DB for no duplicate information
        //------------------------------------------------------------------
        $validator->after(function($validator)
        {
            extract($_POST);

            $duplicate = DB::table('members')
                ->select('email')
                ->having('email', '=', $email)
                ->get();

            if(!empty($duplicate))
            {
                $validator->errors()->add('email', 'Cet e-mail est déjà utilisé.');
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


        // Insert in DB
        //-------------
        DB::table('members')->insert(
            [
                'last_name'         => $last_name,
                'first_name'        => $first_name,
                'address'           => $address,
                'city'              => $city,
                'email'             => $email,
                'phone'             => $phone,
                'zip_code'          => $zip_code,
                'inscription_date'  => time(),
                'active'            => 0,
                'administrator'     => 0,
                'validate'          => 0
            ]
        );
        /////////////////////////////////////////////


        // Inform the user that the account has been created and wait validation by admin
        //---------------------------------------------------------------------------------
        Mail::send('emails.user.register', ['last_name' => $last_name, 'first_name' => $first_name, 'email' => $email], function ($message) use($email)
        {
            $message->to($email)->subject('Votre inscription au Tennis Club Chavornay');
        });
        /////////////////////////////////////////////

        // Inform the admins that an account has been created and wait validation
        //---------------------------------------------------------------------------------
        $admins = DB::table('members')->where('administrator', 1)->get();

        foreach($admins as $admin)
        {
            Mail::send('emails.admin.register', ['last_name' => $admin->last_name, 'first_name' => $admin->first_name, 'email' => $admin->email], function ($message) use($admin)
            {
                $message->to($admin->email)->subject('Nouveau compte sur Tennis Club Chavornay');
            });
        }
        /////////////////////////////////////////////

        // Return to register page with success message
        //---------------------------------------------
        return view('auth/register/sucess', ['email' => $email]);
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
    public function destroy($id)
    {
        //
    }
}