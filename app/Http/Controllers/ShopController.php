<?php

namespace App\Http\Controllers;

use App\Http\Model\Category;
use App\Http\Model\Config;
use App\Http\Model\Evaluates;
use App\Http\Model\Notice;
use App\Http\Model\ShopItem;
use App\Http\Model\Users;
use Illuminate\Support\Facades\Input;

class ShopController extends CommController
{
    public function index()
    {
        $state = wxLogIn();

        $jsToken = getJSToken();
        $randomStr= genRandomString();
        $timeNow = time();
        $qrcID = 0;
        $user = session(FSessionNam);
        if ($user){
            $user = Users::find($user['id']);
            $config = Config::all()[0];
            if ($user['consume'] >= $config['openspread']) {
                $qrcID = $user['id'];
            }
        }
        $url = getUrl().'/shareTo/'.$qrcID;

        $signStr = 'jsapi_ticket='.$jsToken.'&noncestr='.$randomStr.'&timestamp='.$timeNow.'&url='.$url.'';
        $sign = sha1($signStr);
        $jsParam = [
            'appId'=>getWXConfig()->AppID,
            'timestamp'=>$timeNow,
            'nonceStr'=>$randomStr,
            'url'=>$url,
            'signature'=>$sign,
        ];

        $wx = getWXConfig();
        $jsParam['title'] = $wx->sharetitle;
        $jsParam['memo'] = $wx->sharememo;

        return view('index', compact('state', 'jsParam'));
    }

    public function getConfig()
    {
        $config = Config::select('title', 'contactus', 'agent', 'spread', 'openspread', 'cash', 'onlywx')->first();
        return $config;
    }

    public function categorys()
    {
        $categs = Category::orderBy('sort')->get();
        return $categs;
    }

    private function getIndexItem($page)
    {
        if (!is_numeric($page)){
            return;
        }
        
        return ShopItem::where('showindex', 1)->where('stock', '<>', 0)->where('display', 1)->
            orderBy('sort')->orderBy('category')->
            skip($page * $this->numPerPage())->take($this->numPerPage())->get();
    }

    public function indexItem()
    {
        $activityItem = ShopItem::where('activity', 1)->where('stock', '<>', 0)->where('display', 1)->get();
        $homeItem = $this->getIndexItem(0);
        $notice = Notice::orderBy('id','desc')->first();
        $category = Category::get();

        return compact('activityItem', 'homeItem', 'notice', 'category');
    }

    public function loadMoreIndexItem($page)
    {
        if (!is_numeric($page)){
            return;
        }
        
        return $this->getIndexItem($page);
    }

    private function getCategoryInfo($id, $page)
    {
        if (!is_numeric($id)
            || !is_numeric($page)){
            return;
        }
        
        return ShopItem::where('category', $id)->where('stock', '<>', 0)->where('display', 1)->
            skip($page * $this->numPerPage())->take($this->numPerPage())->get();
    }

    public function categoryInfo($id, $page)
    {
        if (!is_numeric($id)
            || !is_numeric($page)){
            return;
        }

        if (0 == $page){
            $category = Category::select('id', 'backimg')->where('id', $id)->get();
        }
        $items = $this->getCategoryInfo($id, $page);
        if (0 == $page) {
            return compact('items', 'category');
        }else{
            return $items;
        }
    }

    public function itemInfo($id)
    {
        if (!is_numeric($id)){
            return;
        }
        
        $itemInfo = ShopItem::find($id);
        $comment = Evaluates::where('itemid', $id)->where('display', 1)->count();
        $itemInfo->commentNum = $comment;
        return $itemInfo;
    }

    public function itemEvaluate($id, $page)
    {
        if (!is_numeric($id) 
            || !is_numeric($page)){
            return;
        }
        
        return Evaluates::where('itemid', $id)->where('display', 1)->
            skip($page * $this->numPerPage())->take($this->numPerPage())->orderBy('createtime','desc')->get();
    }

    public function search($param)
    {
        if ($this->checkStr($param)){
            return;
        }
        
        $searchInfo = ShopItem::select('id', 'name')->
            where('stock', '<>', 0)->where('name','like','%'.$param.'%')->get();

        return $searchInfo;
    }
}
