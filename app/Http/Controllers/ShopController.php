<?php

namespace App\Http\Controllers;

use App\Http\Model\Category;
use App\http\Model\Config;
use App\http\Model\Evaluates;
use App\http\Model\Notice;
use App\http\model\ShopItem;

class ShopController extends Controller
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
        $categs = Category::orderBy('sort')->get();
        return $categs;
    }

    private function numPerPage()
    {
        return 10;
    }

    private function getIndexItem($page)
    {
        return ShopItem::select('id', 'name', 'prime_price', 'cur_price', 'buynum', 'indeximg')->
            where('showindex', 1)->where('stock', '<>', 0)->
            skip($page * $this->numPerPage())->take($this->numPerPage())->get();
    }

    public function indexItem()
    {
        $activityItem = ShopItem::select('id', 'name', 'prime_price', 'cur_price', 'buynum', 'indeximg')->
            where('activity', 1)->where('stock', '<>', 0)->get();
        $homeItem = $this->getIndexItem(0);
        $notice = Notice::orderBy('id','desc')->first();


        return compact('activityItem', 'homeItem', 'notice');
    }

    public function loadMoreIndexItem($page)
    {
        return $this->getIndexItem($page);
    }

    private function getCategoryInfo($id, $page)
    {
        return ShopItem::where('category', $id)->where('stock', '<>', 0)->
            skip($page * $this->numPerPage())->take($this->numPerPage())->get();
    }

    public function categoryInfo($id, $page)
    {
        return $this->getCategoryInfo($id, $page);
    }

    public function itemInfo($id)
    {
        $itemInfo = ShopItem::find($id);
        $comment = Evaluates::where('itemid', $id)->count();
        $itemInfo->commentNum = $comment;
        return $itemInfo;
    }

    public function itemEvaluate($id, $page)
    {
        return Evaluates::where('itemid', $id)->
            skip($page * $this->numPerPage())->take($this->numPerPage())->orderBy('id','desc')->get();
    }

    public function search($param)
    {
        $searchInfo = ShopItem::select('id', 'name')->
            where('stock', '<>', 0)->where('name','like','%'.$param.'%')->get();

        return $searchInfo;
    }
}
