<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ChatController extends Controller
{
    public function index()
    {
        if (Auth::user()== null){
            return redirect('/');
        }

        if (Auth::user()->id == 1) {
            return view('admin');
        } else {
            return view('home');
        }
    }

    public function welcome()
    {
        if (Auth::user()!== null){
            return redirect('/home');
        }
        return view('welcome');
    }
}
