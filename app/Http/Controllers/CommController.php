<?php

namespace App\Http\Controllers;

use App\http\Model\Config;
use App\http\Model\Income;
use App\http\Model\Orders;
use App\http\Model\ShopItem;
use App\http\Model\Users;
use App\http\Model\Follower;

class CommController extends Controller
{
    public function checkPhone($phone)
    {
        if(preg_match('/^0?1[3|4|5|8][0-9]\d{8}$/', $phone)){
            return true;
        }else{
            return false;
        }
    }
    public function checkMail($mail)
    {
        if(preg_match('/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/', $mail)){
            return true;
        }else{
            return false;
        }
    }
    public function checkStr($val)
    {
        if(preg_match("/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/", $val)){
            return true;
        }else{
            return false;
        }
    }
    
    public function isLogIn()
    {
        if (session(FSessionNam)){
            return true;
        }else{
            return false;
        }
    }
    
    public function numPerPage()
    {
        return 16;
    }

    public function getID()
    {
        return 'L'.strtoupper(uniqid());
    }

    public function pswMin()
    {
        return 6;
    }
    public function pswMax()
    {
        return 12;
    }

    public function addBuyNum($orderID)
    {
        $order = Orders::find($orderID);
        if (!$order){
            return;
        }

        $items = json_decode($order['iteminfo']);
        for ($i = 0; $i < count($items->items); $i++) {
            $info = $items->items[$i];
            $shopitem = ShopItem::find($info->id);
            if ($shopitem){
                $shopitem->increment('buynum', $info->num);
            }
        }
    }

    public function addIncome($userID, $orderID){
        $user = Users::find($userID);
        $order = Orders::find($orderID);
        if (!$user || !$order){
            return;
        }
        if (1 != $order['status']){
            return;
        }

        $follower = new Follower;
        $chief = $follower->getChief($userID);
        if (!$chief){
            return;
        }

        $config = Config::first();
        $myFollower = $follower->getMy($userID);
        foreach ($chief as $key=>$val){
            $income = Income::where('userid', $val['userid'])->where('orderid', $orderID)->first();
            if ($income){
                continue;
            }
            $chiefUser = Users::find($val['userid']);
            if (!$chiefUser){
                continue;
            }

            $income = 0;
            $lv = $myFollower['layer'] - $val['layer'];
            switch ($lv){
                case 1:
                    $income = $order['price'] * ($config['commission1']/100);
                    break;
                case 2:
                    $income = $order['price'] * ($config['commission2']/100);
                    break;
                case 3:
                    $income = $order['price'] * ($config['commission3']/100);
                    break;
            }

            $data = [
                'userid' => $chiefUser['id'],
                'followerid' => $userID,
                'followernam' => $user['nickname'],
                'orderid' => $orderID,
                'consume' => $order['price'],
                'income' => $income,
                'balance' => $income + $chiefUser['income'],
                'time' => $order['createtime'],
            ];

            if (Income::create($data)){
                $chiefUser->income = $data['balance'];
                $chiefUser->update();
            }
        }
    }
}
