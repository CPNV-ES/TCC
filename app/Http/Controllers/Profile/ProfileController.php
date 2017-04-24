<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\PersonalInformation;
use Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $infosUser = User::find(Auth::user()->id)->personal_information;

        if ($request->session()->has('message')) {
            $message = $request->session()->get('message');
            $request->session()->forget('message');
            return view('profile/home',[
                'message' => $message,
                'infosUser' => $infosUser
            ]);
        }
        return view('profile/home', compact('infosUser'));
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
        // Check form
        //-----------
        $validator = Validator::make($request->all(),
            [
                'firstname'   => 'required',
                'lastname'    => 'required',
                'street'      => 'required',
                'streetNbr'   => 'required',
                'npa'         => 'required',
                'locality'    => 'required',
                'email'       => 'required|email',
                'telephone'   => 'required',
                'birthDate'   => 'required|date'
            ],
            [
                'firstname.required' => 'Le champ \'Prénom\' est obligatoire.',
                'lastname.required' => 'Le champ \'Nom\' est obligatoire.',
                'street.required' => 'Le champ \'Rue\' est obligatoire.',
                'streetNbr.required' => 'Le champ \'Numéro\' est obligatoire.',
                'npa.required' => 'Le champ \'NPA\' est obligatoire.',
                'locality.required' => 'Le champ \'Ville\' est obligatoire.',
                'email.required' => 'Le champ \'E-mail\' est obligatoire.',
                'email.email' => 'Le champ \'E-mail\' doit être une adresse email valide.',
                'telephone.required' => 'Le champ \'Téléphone\' est obligatoire.',
                'birthDate.required' => 'Le champ \'Date de naissance\' est obligatoire.',
                'birthDate.date' => 'Le champ \'Date de naissance\' n\'est pas une date valide.'
            ]
          );
        /////////////////////////////////////////////


        // Verify if email is not already in DB for to not duplicate the information
        //------------------------------------------------------------------
        $validator->after(function($validator) use($request)
        {
            $duplicate = PersonalInformation::where('email', $request->input('email'))->get();

            if (count($duplicate) != 0 && $duplicate[0]->id != Auth::user()->fkPersonalInformation)
            {
                $validator->errors()->add('email', 'Cet e-mail est déjà utilisé.');
            }
        });
        /////////////////////////////////////////////


        // Display errors messages, return to profile page
        //-------------------------------------------------
        if ($validator->fails())
        {
            return back()->withInput()->withErrors($validator);
        }
        /////////////////////////////////////////////


        // Update in DB
        //-------------
        $infosUser = PersonalInformation::find(Auth::user()->fkPersonalInformation);
        $locid = PersonalInformation::setLocality($request->input('npa'),$request->input('locality'));
        $request['fkLocality'] = $locid;
        $request['toVerify'] = 0;

        $infosUser->update($request->all());
        $infosUser->save();
        /////////////////////////////////////////////

        return redirect("profile")->with('message', 'Vos informations ont été mises à jour');
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
