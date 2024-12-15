<?php

namespace App\Http\Controllers;

class QrController extends Controller
{

    public function index()
    {
        return view('scanner');
    }

}