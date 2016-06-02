<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subscription;
use App\Models\Subscription_per_member;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Season;
class AdminController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $notMembers = Member::where('validate', 0)->orWhere('login', "")->OrderBy('first_name')->OrderBy('last_name')->get();
        $members    = Member::where('validate', 1)->count();
        $status     = Subscription::all(['id', 'status'])->pluck('status', 'id');

        // To display message when user is activate
        //-----------------------------------------
        if($request->session()->has('message'))
        {
            $message = $request->session()->get('message');
            $request->session()->forget('message');
            return view('admin/home',[
                'notmembers' => $notMembers,
                'members'   => $members,
                'status'  => $status,
                'message' => $message,
            ]);
        }
        return view('admin/home',[
            'notmembers' => $notMembers,
            'members'   => $members,
            'status'  => $status,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        dd('create');
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
        dd('store');
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
        dd('show');
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
        dd('edit');
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
        dd('update');
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
        dd('destroy');
    }
}
