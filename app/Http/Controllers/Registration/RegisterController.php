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
                'zip_code'      => 'required|integer|digits:4',
                'email'         => 'required|email',
                'mobile_phone'  => 'required',
                'home_phone'    => 'required',
                'birth_date'    => 'required|date'
            ]);
        /////////////////////////////////////////////

        // Verify if email is not already in DB for no duplicate information
        //------------------------------------------------------------------
        $validator->after(function($validator) use ($request)
        {
            $duplicate = Member::where('email', $request->input('email'))->count();

            // ESO : verify if the user is older than 6 years
            if(!$validator->errors()->has('birth_date')){
              $bdate= new \DateTime($request->input('birth_date'));
              $now= new \DateTime();
              if($bdate->format('Ymd') > $now->format('Ymd')) {
                $validator->errors()->add('birth_date', 'Il est difficile de naître dans le futur.');
              }
              if($bdate->diff($now)->y<6){
                $validator->errors()->add('birth_date', 'Vous êtes trop jeunes pour vous inscrire. (6 ans révolus minimum)');
              }
            }
            // END OF ESO

            if (!empty($duplicate))
            {
                $validator->errors()->add('email', 'Cet e-mail est déjà utilisé.');
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

        // Insert in DB
        //-------------
        $member = Member::create($request->all());

        $member->SetBirthDate($request->input('birth_date'));

        $member->save();
        /////////////////////////////////////////////

        // Inform the user that the account has been created and to wait for the admin validation
        //---------------------------------------------------------------------------------------
        Mail::send('emails.user.register', ['last_name' => $request->input('last_name'), 'first_name' => $request->input('first_name'), 'email' => $request->input('email')], function ($message) use($request)
        {
            $message->to($request->input('email'))->subject('Votre inscription au Tennis Club Chavornay');
        });
        /////////////////////////////////////////////

        // Inform the admins that an account has been created and wait validation
        //---------------------------------------------------------------------------------
        $admins = DB::table('members')->where('administrator', 1)->get();

        foreach ($admins as $admin)
        {
            Mail::send('emails.admin.register', ['last_name' => $admin->last_name, 'first_name' => $admin->first_name, 'email' => $admin->email], function ($message) use($admin)
            {
                $message->to($admin->email)->subject('Nouveau compte sur Tennis Club Chavornay');
            });
        }
        /////////////////////////////////////////////

        // Return to register page with success message
        //---------------------------------------------
        return view('auth/register/success', ['email' => $request->input('email')]);
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
