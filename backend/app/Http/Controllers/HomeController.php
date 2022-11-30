<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function welcome()
    {
        return view('welcome');
    }

    public function index()
    {
        return view('home');
    }
}
