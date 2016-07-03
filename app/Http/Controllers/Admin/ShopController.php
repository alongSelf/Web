<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Category;
use App\http\Model\ShopItem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ShopController extends CommonController
{
    //
    public function index()
    {
        $data = ShopItem::orderBy('id','desc')->paginate(3);
        return view('admin.shop.index',compact('data'));
    }

    public function create()
    {

    }

    public function store()
    {

    }

    public function edit($item_id)
    {
        $item = ShopItem::find($item_id);
        $cate = Category::select('id', 'title')->get();
        $spec = explode(';',$item->spec);

        return view('admin.shop.edit', compact('item', 'cate', 'spec'));
    }

    public function update($item_id)
    {

    }

    public function show()
    {

    }

    public function destroy($item_id)
    {
        $item = ShopItem::find($item_id);
        $re = ShopItem::where('id', $item_id)->delete();
        if($re){
            $this->removeFile($item->indeximg);
            $showImg = explode(';',$item->showimg);
            $contentImg = explode(';',$item->content);
            for($i = 0; $i < count($showImg); $i++){
                $this->removeFile($showImg[$i]);
            }
            for($i = 0; $i < count($contentImg); $i++){
                $this->removeFile($contentImg[$i]);
            }

            $data = [
                'status' => 0,
                'msg' => '宝贝删除成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '宝贝删除失败，请稍后重试！',
            ];
        }
        return $data;
    }
}
