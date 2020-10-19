<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
//        dd(Auth::user()->id);
        return view('home');
    }

    public function saveClient(Request $request)
    {
        Validator::make($request->only('email'), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if($user) {
            Auth::login($user);
            return redirect('home');
        }

         $create = User::create([
            'name' => $request->input('email'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('email')),
        ]);
        Auth::login($create);

        return redirect('home');
    }
}
