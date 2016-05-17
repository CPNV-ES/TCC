<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminController;
use App\Models\Member;
use Validator;
use Illuminate\Support\Facades\Mail;

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
            $members = Member::all();
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
//        dd('poney'.$id);
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
            if($request->input('value') == 'true')
            {
                $member->$field = '0';
                $member->save();
                return 'false';
            }
            else
            {
                $member->$field = '1';
                $member->save();
                return 'true';
            }

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


        // Insert the login, status, token and validate account
        //-----------------------------------------------------
        $member = Member::find($id);
        $member->UpdateLogin($_POST['login'.$id], $_POST['status'.$id]);
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
