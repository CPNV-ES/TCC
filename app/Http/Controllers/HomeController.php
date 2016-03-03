<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
//     */
//    public function __construct()
//    {
//        if(!Auth::check())
//        {
//            $this->middleware('auth');
//        }
//
//    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        return view('profile/home');
    }
}