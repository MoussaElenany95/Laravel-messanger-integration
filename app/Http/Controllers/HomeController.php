<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  Socialite;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function redirect(){

        // dd('ssssssssss');
        return Socialite::driver('facebook')->scopes(['pages_manage_engagement'])->redirect();
    }
    public function callback(){
        
        $user = Socialite::driver('facebook')->user();

        dd($user);
    }
}
