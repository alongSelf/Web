<?php

namespace App\Http\Controllers;

use App\Http\Model\Category;
use App\http\Model\Config;

class IndexController extends Controller
{
    public function index()
    {
        $category = Category::where('display', 1)->orderBy('sort')->get();
        $config = Config::all()[0];
        
        return view('index', compact('category', 'config'));
    }
}
