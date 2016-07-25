<?php

namespace App\Http\Controllers\Admin;

use App\http\Model\Orders;
use App\http\Model\ShopItem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderController extends CommonController
{
    private function getIntemInfo($data)
    {
        foreach ($data as $key=>$val){
            $items =  json_decode($val['iteminfo']);
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

            $data[$key]->iteminfo = $info;
        }

        return $data;
    }
    //
    public function index()
    {
        $type = 'all';
        $userID = '';
        $orderID = '';
        $data = $this->getIntemInfo(Orders::orderBy('id','desc')->paginate(10));

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
        $data = $this->getIntemInfo(Orders::where('status', $statues)->orderBy('id','desc')->paginate(10));

        return view('admin.order.index', compact('data', 'type', 'orderID', 'userID'));
    }

    public function searchByOrderID($orderID)
    {
        $userID = '';
        $type = 'all';
        $data = Orders::where('id', $orderID)->paginate(10);

        return view('admin.order.index', compact('data', 'type', 'orderID', 'userID'));
    }

    public function searchByUserID($userID)
    {
        $orderID = '';
        $type = 'all';
        $data = Orders::where('userid', $userID)->paginate(10);

        return view('admin.order.index', compact('data', 'type', 'orderID', 'userID'));
    }

    public function show($orderID)
    {
        $data = Orders::find($orderID);
        return view('admin.order.show', compact('data'));
    }
}
