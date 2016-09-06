<?php

namespace App\Http\Controllers;

class BuildController extends CommController
{
    public function index()
    {
        return view('build');
    }
}