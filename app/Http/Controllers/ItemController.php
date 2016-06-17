<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ItemController extends Controller
{
    //
    public function index($id)
    {
        return view('item');
    }

    public function item($id)
    {
        return view('iteminfo');
    }
}
