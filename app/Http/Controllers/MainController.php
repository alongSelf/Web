<?php

namespace App\Http\Controllers;

use App\Http\Model\Category;
use App\http\Model\Config;
use App\http\model\ShopItem;

class MainController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function getConfig()
    {
        $config = Config::all()[0];
        return $config;
    }

    public function categorys()
    {
        $categs = Category::select('id', 'title', 'img')->where('display', 1)->orderBy('sort')->get();
        return $categs;
    }

    public function indexItem()
    {
        $activityItem = ShopItem::select('id', 'name', 'prime_price', 'cur_price', 'buynum', 'indeximg')->
            where('display', 1)->where('activity', 1)->where('stock', '<>', 0)->get();
        $homeItem = ShopItem::select('id', 'name', 'prime_price', 'cur_price', 'buynum', 'indeximg')->
        where('display', 1)->where('showindex', 1)->where('stock', '<>', 0)->get();

        return compact('activityItem', 'homeItem');
    }

    public function categoryInfo($id)
    {
        $categoryInfo = ShopItem::where('display', 1)->where('category', $id)->where('stock', '<>', 0)->get();

        return $categoryInfo;
    }

    public function itemInfo($id)
    {
        $itemInfo = ShopItem::where('id', $id)->get();

        return $itemInfo;
    }

    public function search($param)
    {
        $searchInfo = ShopItem::select('id', 'name')->
            where('display', 1)->where('stock', '<>', 0)->
            where('name','like','%'.$param.'%')->get();

        return $searchInfo;
    }
}
