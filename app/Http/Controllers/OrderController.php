<?php

namespace App\Http\Controllers;

use App\http\Model\Addr;
use App\http\Model\Config;
use App\http\Model\Evaluates;
use App\http\Model\Orders;
use App\http\Model\ShipperCode;
use App\http\Model\ShopItem;
use App\Http\Model\Users;
use Illuminate\Contracts\Logging\Log;
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

        $user = session('user');
        $order = Input::get('order');
        $orderJson = json_decode($order);

        //地址
        $addr = Addr::where('id', $orderJson->addrID)->where('userid', $user['id'])->first();
        if (!$addr){
            return rtnMsg(1, '参数错误!');
        }

        $jsonAddr = [];
        $jsonAddr['name'] = $addr['name'];
        $jsonAddr['phone'] = $addr['phone'];
        $jsonAddr['addr'] = json_decode($addr['addr']);

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

        $orderid = $this->getID();
        //微信创建订单
        $result = wxCreateOrder($orderid, $orderJson->price);
        if (!$result){
            return rtnMsg(1, '创建订单失败，请稍候再试!');
        }
        //微信返回解析
        if ('SUCCESS' != $result['return_code']){
            return rtnMsg(1, '创建订单失败，请稍候再试!');
        }
        if ('SUCCESS' != $result['result_code']){
            return rtnMsg(1, $result['err_msg']);
        }

        $payinfo = [
            'prepay_id'=>$result['prepay_id'],
            'appPayParams'=>wxAppPayParams($result['prepay_id']),
        ];
        $input = [
            'id'=>$orderid,
            'userid'=>$user['id'],
            'price'=>$orderJson->price,
            'iteminfo'=>$order,
            'addr'=>json_encode($jsonAddr),
            'createtime'=>time(),
            'status'=>0,
            'payinfo'=>json_encode($payinfo),//微信订单号
            'paychannel'=>1
        ];

        $re = Orders::create($input);
        if ($re){
            return rtnMsg(0, $orderid);
        }else{
            return rtnMsg(1, '创建订单失败，请稍候再试!');
        }
    }

    public function cancelOrder()
    {
        $user = session('user');
        $orderID = Input::get('id');

        $order = Orders::where('id', $orderID)->where('userid', $user['id'])->where('status', 0)->first();
        if (!$order){
            return rtnMsg(1, '无效的订单!');
        }

        if (0 != strlen($order['payinfo'])){
            $payInfo = json_decode($order['payinfo']);
            $result = wxCloseOrder($payInfo->prepay_id);
            if (!$result){
                return rtnMsg(1, '订单取消失败，请稍后重试！');
            }
            if ('SUCCESS' != $result['return_code']){
                return rtnMsg(1, '订单取消失败，请稍后重试！');
            }
            if ('ORDERPAID' == $result['result_code']){
                $order->status = 1;
                if ($order->update()){
                    $this->addIncome($user['id'], $orderID);
                }

                return rtnMsg(1, '订单已支付！');
            }

            if ('SYSTEMERROR' == $result['result_code']
                || 'SIGNERROR' == $result['result_code']
                || 'REQUIRE_POST_METHOD' == $result['result_code']
                || 'XML_FORMAT_ERROR' == $result['result_code']
                || 'MCHID_NOT_EXIST' == $result['result_code']){
                return rtnMsg(1, '订单取消失败，请稍后重试！');
            }
        }

        $order->status = 4;
        $res = $order->update();
        if ($res) {
            return rtnMsg(0, '订单已取消!');
        } else {
            return rtnMsg(1, '订单取消失败，请稍后重试！');
        }
    }

    //查询核对
    public function queryOrder($orderID)
    {
        $user = session('user');
        $order = Orders::where('id', $orderID)->where('userid', $user['id'])->where('status', 0)->first();
        if (!$order){
            return rtnMsg(1, '无效的订单!');
        }
        if (0 == strlen($order['payinfo'])) {
            return rtnMsg(1, '无效的订单!');
        }

        $payInfo = json_decode($order['payinfo']);

        if (wxQueryOrderPay($payInfo->prepay_id)){
            $order->status = 1;
            if ($order->update()){
                $this->addIncome($user['id'], $orderID);
            }

            return rtnMsg(0, '订单已支付，请下拉刷新!');
        }

        return rtnMsg(1, '订单未支付!');
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
                $order[$i]['logistics'] = '';
                $order[$i]['payinfo'] = (0 == strlen($order[$i]['payinfo']) ? 0 : 1);
            }
        }

        return rtnMsg(0, $order);
    }

    public function getOrder($id, $showEv)
    {
        $user = session('user');
        $order = Orders::where('id', $id)->where('userid', $user['id'])->first();
        if (!$order){
            return rtnMsg(1, '无效的订单!');
        }

        $iteminfo = json_decode($order['iteminfo']);
        for ($j = 0; $j < count($iteminfo->items); $j++){
            $itemID = $iteminfo->items[$j]->id;
            $shop = ShopItem::find($itemID);
            if (1 == $showEv){
                $evaluates = Evaluates::where('itemid', $itemID)->where('orderid', $id)->first();
                if ($evaluates){
                    $iteminfo->items[$j]->showEV = false;
                }else{
                    $iteminfo->items[$j]->showEV = true;
                }
                $iteminfo->items[$j]->index = $j;
                $iteminfo->items[$j]->pfid = 'ps'.$j;
                $iteminfo->items[$j]->evid = 'ev'.$j;
            }
            
            $iteminfo->items[$j]->name = $shop['name'];
            $iteminfo->items[$j]->img = $shop['indeximg'];
        }
        $order['iteminfo'] = json_encode($iteminfo);

        return rtnMsg(0, $order);
    }

    public function evaluate()
    {
        $user = session('user');
        $input = Input::except('_token');
        if (!is_numeric($input['star']) ||
            !is_numeric($input['itemid'])){
            return rtnMsg(1, '参数错误');
        }
        if ($input['star'] < 1 || $input['star'] > 5){
            return rtnMsg(1, '参数错误!');
        }
        if ($this->checkStr($input['evaluate'])){
            return rtnMsg(1, '参数错误!');
        }
        
        $order = Orders::where('id', $input['orderid'])->where('userid', $user['id'])->where('status', 2)->first();
        if (!$order){
            return rtnMsg(1, '无效的订单!');
        }

        $orderItem = json_decode($order['iteminfo']);
        $itemCount = count($orderItem->items);

        $evaluate = Evaluates::where('itemid', $input['itemid'])->where('orderid', $input['orderid'])
            ->where('userid', $user['id'])->first();
        if ($evaluate){
            return rtnMsg(1, '已经评价过了!');
        }

        $user = Users::find($user['id']);
        $input['userid'] = $user['id'];
        $input['nickname'] = $user['nickname'];
        $input['createtime'] = time();
        $input['display'] = 0;

        $re = Evaluates::create($input);
        if ($re){
            $evCount = Evaluates::where('orderid', $input['orderid'])
                ->where('userid', $user['id'])->count();
            if ($evCount >= $itemCount){
                $order = Orders::find($input['orderid']);
                $order->status = 3;
                $order->update();
            }
            return rtnMsg(0, '评价成功');
        }else{
            return rtnMsg(1, '评价失败，请稍候再试!');
        }
    }

    public function logistics($orderID)
    {
        $user = session('user');
        $order = Orders::select('logistics')->where('id', $orderID)->where('userid', $user['id'])->first();
        if (!$order){
            return rtnMsg(1, '查询失败，请稍候再试!');
        }
        $userLogistics = json_decode($order['logistics']);
        if (!$userLogistics){
            return rtnMsg(1, '查询失败，请稍候再试!');
        }
        $shipperCode = ShipperCode::select('name')->where('code', $userLogistics->ShipperCode)->first();
        if (!$shipperCode){
            return rtnMsg(1, '查询失败，请稍候再试!');
        }

        $result = selectLogistics($userLogistics->ShipperCode, $userLogistics->LogisticCode, $orderID);
        if (!$result->Success){
            return rtnMsg(1, $result->Reason);
        }

        $rtnMsg = array();
        $rtnMsg['logistics'] = $result->Traces;
        $rtnMsg['shipperName'] = $shipperCode['name'];
        $rtnMsg['logisticCode'] = $userLogistics->LogisticCode;

        return rtnMsg(0, $rtnMsg);
    }
}
