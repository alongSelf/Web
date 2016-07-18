<?php

namespace App\Http\Controllers;

use App\http\Model\Config;
use App\http\Model\Orders;
use App\http\Model\ShopItem;
use App\Http\Model\Users;

class OrderController extends CommController
{
    private function checkOrderParam($orderItem)
    {
        if (!is_numeric($orderItem->id)
            || !is_numeric($orderItem->num)
            || 0 >= $orderItem->num
            || !is_int($orderItem->num)
            || !is_numeric($orderItem->price)){
            return false;
        }

        return true;
    }

    public function newOrder($order)
    {
        if (!$this->isLogIn()){
            return $this->rtnMsg(-1, '请先登录!');
        }

        $orderJson = json_decode($order);
        $totalPrice = 0;
        for ($i = 0; $i < count($orderJson->items); $i++) {
            $orderItem = $orderJson->items[$i];
            if (!$this->checkOrderParam($orderItem)) {
                return $this->rtnMsg(1, '参数错误!');
            }

            $item = ShopItem::where('id', $orderItem->id)->where('display', 1)->first();
            if (!$item) {
                return $this->rtnMsg(1, '数据错误,未找到商品!');
            }

            //检查价格
            $spec = json_decode($item['spec']);
            $bHave = false;
            $bPriceCheck = false;
            for ($j = 0; $j < count($orderItem->spec); $j++) {
                $bHave = false;
                $specName = $orderItem->spec[$j]->name;
                $specVal = $orderItem->spec[$j]->val;
                $dbSpec = $spec->$specName;
                for ($k = 0; $k < count($dbSpec); $k++){
                    if ($specVal == $dbSpec[$k]->val){
                        if (property_exists($dbSpec[$k], 'price')){
                            $dbPrice = $dbSpec[$k]->price;
                            if($dbPrice != $orderItem->price){
                                return $this->rtnMsg(1, '参数错误!');
                            }
                            else{
                                $bPriceCheck = true;
                                $totalPrice +=  $orderItem->price * $orderItem->num;
                            }
                        }
                        $bHave = true;
                        break;
                    }
                }

                if (!$bHave){
                    return $this->rtnMsg(1, '参数错误!');
                }
            }

            if (!$bPriceCheck){
                if ($orderItem->price != $item['cur_price']){
                    return $this->rtnMsg(1, '参数错误!');
                }

                $totalPrice += $orderItem->price * $orderItem->num;
            }
        }

        if ($totalPrice != $orderJson->price){
            return $this->rtnMsg(1, '参数错误!');
        }

        $user = session('user');
        $orderid = $this->getID();

        $input = [
            'id'=>$orderid,
            'userid'=>$user['id'],
            'iteminfo'=>$order,
            'createtime'=>time(),
            'status'=>0,
        ];

        $re = Orders::create($input);
        if ($re){
            return $this->rtnMsg(0, $orderid);
        }else{
            return $this->rtnMsg(1, '创建订单失败，请稍候再试!');
        }
    }
}
