<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function getHome()
    {
        return view('pages.home');
    }
}