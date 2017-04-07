<?php

namespace App\Http\Controllers;

use App\Court;
use App\Locality;
use App\PersonalInformation;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use DB;

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

        $queryWho = DB::table('personal_informations')
                      ->join('reservations AS r', 'r.fkWho', '=', 'personal_informations.id')
                      ->where('r.fkWithWho', '=', PersonalInformation::find(Auth::user()->id)->id)
                      ->groupBy('personal_informations.id')
                      ->select(['personal_informations.*', \DB::raw('COUNT(r.fkWho) AS nb_times_played')]);

        $queryBoth = DB::table('personal_informations')
                      ->join('reservations AS r', 'r.fkWithWho', '=', 'personal_informations.id')
                      ->where('r.fkWho', '=', PersonalInformation::find(Auth::user()->id)->id)
                      ->union($queryWho)
                      ->groupBy('personal_informations.id')
                      ->select(['personal_informations.*', \DB::raw('COUNT(r.fkWithWho) AS nb_times_played')]);

        $memberFav = PersonalInformation::selectRaw('ps.id, ps.firstname, ps.lastname, ps.street, ps.streetNbr, ps.telephone, ps.email, ps.toVerify, ps.birthDate, ps._token, ps.fkLocality, ps.created_at, ps.updated_at, ps.deleted_at, SUM(ps.nb_times_played) AS reservations_count')
                                          ->from(\DB::raw('('.$queryBoth->toSql().') AS ps'))
                                          ->mergeBindings($queryBoth)
                                          ->groupBy('ps.id')
                                          ->get();

        //we merge the two collections of members then we sort by reservations_count (desc)
        $startDate = new \DateTime();
        $endDate= (new \DateTime())->add(new \DateInterval('P5D'));
        $ownreservs = \App\Reservation::whereBetween('dateTimeStart', [$startDate->format('Y-m-d H:i'), $endDate->format('Y-m-d').' 23:59'])
             ->where(function($q){
                 $Userid=Auth::user()->id;
                 $q->where('fkWho', $Userid);
                 $q->orWhere('fkWithWho', $Userid);
             })->get();

        $membersList = $allMember->merge($memberFav);
        $membersList = $membersList->sortByDesc('reservations_count');
        return view('welcome', compact('membersList','courts', 'ownreservs'));
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
