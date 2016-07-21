<?php

namespace App\Http\Controllers;

use App\http\Model\Config;
use App\http\Model\Orders;
use App\http\Model\ShopItem;
use App\Http\Model\Users;
use Illuminate\Support\Facades\Input;

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

    public function newOrder()
    {
        if (!$this->isLogIn()){
            return rtnMsg(errLogin(), '请先登录!');
        }

        $order = Input::get('order');
        $orderJson = json_decode($order);
        $totalPrice = 0;
        for ($i = 0; $i < count($orderJson->items); $i++) {
            $orderItem = $orderJson->items[$i];
            if (!$this->checkOrderParam($orderItem)) {
                return rtnMsg(1, '参数错误!');
            }

            $item = ShopItem::where('id', $orderItem->id)->where('display', 1)->first();
            if (!$item) {
                return rtnMsg(1, '数据错误,未找到商品!');
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
                                return rtnMsg(1, '参数错误!');
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
                    return rtnMsg(1, '参数错误!');
                }
            }

            if (!$bPriceCheck){
                if ($orderItem->price != $item['cur_price']){
                    return rtnMsg(1, '参数错误!');
                }

                $totalPrice += $orderItem->price * $orderItem->num;
            }
        }

        if ($totalPrice != $orderJson->price){
            return rtnMsg(1, '参数错误!');
        }

        $user = session('user');
        $orderid = $this->getID();

        $input = [
            'id'=>$orderid,
            'userid'=>$user['id'],
            'price'=>$orderJson->price,
            'iteminfo'=>$order,
            'createtime'=>time(),
            'status'=>0,
        ];

        $re = Orders::create($input);
        if ($re){
            return rtnMsg(0, $orderid);
        }else{
            return rtnMsg(1, '创建订单失败，请稍候再试!');
        }
    }

    //$type 0全部  1 待付款 2待评价 3售后
    //0待付款  1待发货 2待评价 3完成 4售后 5取消
    public function showOrder($page, $type)
    {
        $user = session('user');

        switch ($type){
            case 0://全部
            {
                $order = Orders::where('userid', $user['id'])->where('status','<>', 4)->orderBy('createtime','desc')->
                    skip($page * $this->numPerPage())->take($this->numPerPage())->get();
            }
            break;
            case 1://待付款
            {
                $order = Orders::where('userid', $user['id'])->where('status', 0)->orderBy('createtime','desc')->
                    skip($page * $this->numPerPage())->take($this->numPerPage())->get();
            }
            break;
            case 2://待评价
            {
                $order = Orders::where('userid', $user['id'])->where('status', 2)->orderBy('createtime','desc')->
                    skip($page * $this->numPerPage())->take($this->numPerPage())->get();
            }
            break;
            case 3://售后
            {
                $order = Orders::where('userid', $user['id'])->where('status', 5)->orderBy('createtime','desc')->
                    skip($page * $this->numPerPage())->take($this->numPerPage())->get();
            }
            break;
            case 4://待发货
            {
                $order = Orders::where('userid', $user['id'])->where('status', 1)->orderBy('createtime','desc')->
                skip($page * $this->numPerPage())->take($this->numPerPage())->get();
            }
            break;
            default:
                break;
        }

        if ($order){
            for ($i = 0; $i < count($order); $i++){
                $iteminfo = json_decode($order[$i]['iteminfo']);
                for ($j = 0; $j < count($iteminfo->items); $j++){
                    $id = $iteminfo->items[$j]->id;
                    $shop = ShopItem::find($id);
                    $iteminfo->items[$j]->name = $shop['name'];
                    $iteminfo->items[$j]->img = $shop['indeximg'];
                }
                $order[$i]['iteminfo'] = json_encode($iteminfo);
            }
        }

        return rtnMsg(0, $order);
    }
}
