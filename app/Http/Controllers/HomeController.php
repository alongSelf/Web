<?php

namespace App\Http\Controllers;

use App\http\model\ShopItem;
use Illuminate\Http\Request;

use App\Http\Requests;

class HomeController extends Controller
{
    //
    public function index()
    {
        $activityItem = ShopItem::where('display', 1)->where('activity', 1)->get();
        $homeItem = ShopItem::where('display', 1)->where('showindex', 1)->get();

        $retVal = ['homeItem'=>$homeItem,
        'activityItem'=>$activityItem];

        return $retVal;
    }
}
