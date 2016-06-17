<?php

namespace App\Http\Controllers;

use App\Http\Model\Category;

class IndexController extends Controller
{
    public function index()
    {
        $category = Category::where('display', 1)->orderBy('sort')->get();
        
        return view('index', compact('category'));
    }
}
