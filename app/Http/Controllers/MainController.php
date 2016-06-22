<?php

namespace App\Http\Controllers;

use App\Http\Model\Category;
use App\http\Model\Config;
use App\http\model\ShopItem;

class MainController extends Controller
{
    public function index()
    {
        $config = Config::all()[0];
        return view('index', compact('config'));
    }

    public function category()
    {
        $category = Category::where('display', 1)->orderBy('sort')->get();
        return $category;
    }

    public function indexItem()
    {
        $activityItem = ShopItem::where('display', 1)->where('activity', 1)->get();
        $homeItem = ShopItem::where('display', 1)->where('showindex', 1)->get();

        $retVal = ['homeItem'=>$homeItem,
            'activityItem'=>$activityItem];

        return $retVal;
    }

    public function getCar()
    {

    }

    public function categoryInfo($id)
    {

    }

    public function itemInfo($id)
    {
        $itemInfo = ShopItem::where('id', $id)->get();
        return $itemInfo;
    }
}
