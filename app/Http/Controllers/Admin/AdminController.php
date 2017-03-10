<?php

namespace App\Http\Controllers\Admin;

//use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Subscription;

class AdminController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $notUser = User::where('validated', 0)->orWhere('username', "")->OrderBy('username')->get();
        $nb_users = User::where('validated', 1)->count();
        $status = Subscription::all(['id', 'paid'])->pluck('paid', 'id');

        // To display message when user is activate
        //-----------------------------------------
        if ($request->session()->has('message')) {
            $message = $request->session()->get('message');
            $request->session()->forget('message');
            return view('admin/home', [
                'notUser' => $notUser,
                'nb_users' => $nb_users,
                'status' => $status,
                'message' => $message,
            ]);
        }
        return view('admin/home', [
            'notUser' => $notUser,
            'nb_users' => $nb_users,
            'status' => $status,
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
