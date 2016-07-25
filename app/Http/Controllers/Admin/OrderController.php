<?php

namespace App\Http\Controllers\Admin;

use App\http\Model\Orders;
use App\http\Model\ShipperCode;
use App\http\Model\ShopItem;
use App\http\Model\Users;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class OrderController extends CommonController
{
    private function getItemInfo($data)
    {
        $items =  json_decode($data);
        $info = '';
        foreach ($items->items as $key1=>$val1) {
            $spec = '';
            foreach ($val1->spec as  $key2=>$val2) {
                $spec = $spec . $val2->name . ':' . $val2->val . '&nbsp';
            }

            $shopItem = ShopItem::find($val1->id);
            $info = $info .'物品：'. $shopItem['name'] . '&nbsp&nbsp数量：'
                . $val1->num . '&nbsp&nbsp规格：（' . $spec . '）&nbsp&nbsp单价：'
                . $val1->price.'<br>';

        }

        return $info;
    }
    private function getLogistics($data)
    {
        $logistics = json_decode($data);
        $info = '';
        if ($logistics){
            $shippercode = ShipperCode::where('code', $logistics->ShipperCode)->first();
            if ($shippercode){
                $info = '承运：'.$shippercode['name'].'&nbsp&nbsp运单号：'.$logistics->LogisticCode;
            }
        }

        return $info;
    }

    private function getJsonInfo($data)
    {
        foreach ($data as $key=>$val){
            $data[$key]->iteminfo = $this->getItemInfo($val['iteminfo']);
            $data[$key]->logistics = $this->getLogistics($val['logistics']);
        }

        return $data;
    }
    //
    public function index()
    {
        $type = 'all';
        $userID = '';
        $orderID = '';
        $data = $this->getJsonInfo(Orders::orderBy('id','desc')->paginate(10));

        return view('admin.order.index', compact('data', 'type', 'orderID', 'userID'));
    }

    public function searchByStatues($type)
    {
        $statues = 0;
        switch ($type){
            case 'pay':
                $statues = 0;
                break;
            case 'delivery':
                $statues = 1;
                break;
            case 'evaluate':
                $statues = 2;
                break;
            case 'complete':
                $statues = 3;
                break;
            case 'cancel':
                $statues = 4;
                break;
            default:
                break;
        }

        $userID = '';
        $orderID = '';
        $data = $this->getJsonInfo(Orders::where('status', $statues)->orderBy('id','desc')->paginate(10));

        return view('admin.order.index', compact('data', 'type', 'orderID', 'userID'));
    }

    public function searchByOrderID($orderID)
    {
        $userID = '';
        $type = 'all';
        $data = $this->getJsonInfo(Orders::where('id', $orderID)->paginate(10));

        return view('admin.order.index', compact('data', 'type', 'orderID', 'userID'));
    }

    public function searchByUserID($userID)
    {
        $orderID = '';
        $type = 'all';
        $data = $this->getJsonInfo(Orders::where('userid', $userID)->paginate(10));

        return view('admin.order.index', compact('data', 'type', 'orderID', 'userID'));
    }

    public function show($orderID)
    {
        $data = Orders::find($orderID);
        $data->iteminfo = $this->getItemInfo($data['iteminfo']);
        $logistics = $data['logistics'];
        $data->logistics = $this->getLogistics($logistics);
        $user = Users::find($data['userid']);
        $data->userinfo = $user;
        $data->addr = json_decode($data['addr']);
        if ($data['status'] == 1){
            $shippercode = ShipperCode::all();
        }
        
        return view('admin.order.show', compact('data', 'shippercode'));
    }

    public function delivery()
    {
        $input = Input::except('_token');
        $rules = [
            'ShipperCode'=>'required',
            'LogisticCode'=>'required',
            'orderID'=>'required',
        ];
        $message = [
            'ShipperCode.required'=>'承运公司不能为空！',
            'LogisticCode.required'=>'运单号不能为空！',
            'orderID.required'=>'订单ID不能为空！',
        ];

        $validator = Validator::make($input,$rules,$message);
        if (!$validator->passes()){
            return [
                'status' => 1,
                'msg' => '参数错误！',
            ];
        }

        $order = Orders::where('id', $input['orderID'])->where('status', 1)->first();
        if (!$order){
            return [
                'status' => 1,
                'msg' => '查找订单失败！',
            ];
        }

        $logistics['ShipperCode'] = $input['ShipperCode'];
        $logistics['LogisticCode'] = $input['LogisticCode'];
        $order['status'] = 2;
        $order['logistics'] = json_encode($logistics);
        if (!$order->update()){
            return [
                'status' => 1,
                'msg' => '发货失败，请稍候再试！',
            ];
        }

        return [
            'status' => 0,
            'msg' => '发货成功！',
        ];
    }
}
