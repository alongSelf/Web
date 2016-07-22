<?php

namespace App\Http\Controllers\Admin;

use App\http\Model\Orders;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderController extends CommonController
{
    //
    public function index()
    {
        $type = 'all';
        $userID = '';
        $orderID = '';
        $data = Orders::orderBy('id','desc')->paginate(10);

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
        $data = Orders::where('status', $statues)->orderBy('id','desc')->paginate(10);

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
