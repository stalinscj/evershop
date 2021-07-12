<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Display the home page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('home');
    }
}
