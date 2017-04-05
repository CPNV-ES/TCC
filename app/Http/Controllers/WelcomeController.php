<?php

namespace App\Http\Controllers;

use App\Court;
use App\Locality;
use App\PersonalInformation;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

      $courts = Court::All();
      $localities = Locality::all();
      if (Auth::check()) {

          $allMember = PersonalInformation::where('id', '!=', PersonalInformation::find(Auth::user()->id)->id)->has('user')->get()->sortBy('firstname');
          $memberFav =PersonalInformation::leftjoin('reservations', 'reservations.fkWithWho', '=', 'personal_informations.id')
              ->leftjoin('reservations as reservations_who', 'reservations_who.fkWho', '=', 'personal_informations.id')->has('user')
              ->rightJoin('users', 'users.fkPersonalInformation', '=', 'personal_informations.id')
              ->where('reservations_who.fkWithWho','=', PersonalInformation::find(Auth::user()->id)->id)
              ->orWhere('reservations.fkWho','=', PersonalInformation::find(Auth::user()->id)->id)
              ->groupBy('personal_informations.id')
              ->orderBy('reservations_count', 'DESC')
              ->get(['personal_informations.*', \DB::raw('COUNT(`' . \DB::getTablePrefix() . 'reservations_who`.`id`) + COUNT(`' . \DB::getTablePrefix() . 'reservations`.`id`) AS `reservations_count`')]);


          //we merge the two collections of members then we sort by reservations_count (desc)
          $membersList = $allMember->merge($memberFav);
          $membersList = $membersList->sortByDesc('reservations_count');


        return view('welcome', compact('membersList','courts', 'localities'));
      }
      else {
        return view('welcome', compact('courts', 'localities'));
      }

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
