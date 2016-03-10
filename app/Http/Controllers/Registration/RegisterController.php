<?php

namespace App\Http\Controllers\Registration;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Member;

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

            $duplicate = Member::where('email', $email)->count();

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
        $member = new Member;

        $member->last_name          = $last_name;
        $member->first_name         = $first_name;
        $member->address            = $address;
        $member->city               = $city;
        $member->email              = $email;
        $member->phone              = $phone;
        $member->zip_code           = $zip_code;
        $member->inscription_date   = time();
        $member->active             = 0;
        $member->administrator      = 0;
        $member->validate           = 0;

        $member->save();
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
        return view('auth/register/success', ['email' => $email]);
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
