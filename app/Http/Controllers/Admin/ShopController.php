<?php

namespace App\Http\Controllers\Admin;


use App\http\Model\ShopItem;
use App\Http\Model\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ShopController extends CommonController
{
    //
    public function index()
    {
        $cate = Category::all();
        $data = ShopItem::orderBy('id','desc')->paginate(10);
        return view('admin.shop.index',compact('data', 'cate'));
    }

    public function searchbycate($cate_id)
    {
        $cate = Category::all();
        $data = ShopItem::where('category', $cate_id)->orderBy('id','desc')->paginate(10);
        return view('admin.shop.index',compact('data', 'cate'));
    }

    public function searchbyname($name)
    {
        $cate = Category::all();
        $data = ShopItem::where('name','like','%'.$name.'%')->orderBy('id','desc')->paginate(10);
        return view('admin.shop.index',compact('data', 'cate'));
    }

    public function create()
    {
        $cate = Category::select('id', 'title')->get();
        return view('admin.shop.add', compact('cate'));
    }

    public function store()
    {
        $input = Input::except('_token', 'spec_name', 'spec_value');
        $rules = [
            'name'=>'required',
            'category'=>'required',
            'describe'=>'required',
            'prime_price'=>'required',
            'cur_price'=>'required',
            'stock'=>'required',
            'indeximg'=>'required',
            'showimg'=>'required',
        ];
        $message = [
            'name.required'=>'物品名称不能为空！',
            'category.required'=>'物品分类不能为空！',
            'describe.required'=>'物品文字描述不能为空！',
            'prime_price.required'=>'原价不能为空！',
            'cur_price.required'=>'当前价格不能为空！',
            'stock.required'=>'库存不能为空！',
            'indeximg.required'=>'主页图片不能为空！',
            'showimg.required'=>'物品详情轮播图片不能为空！',
        ];

        $validator = Validator::make($input,$rules,$message);
        if($validator->passes()){
            $re = ShopItem::create($input);
            if($re){
                return redirect('admin/shop');
            }else{
                return back()->with('errors','数据填充失败，请稍后重试！');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    public function edit($item_id)
    {
        $item = ShopItem::find($item_id);
        $cate = Category::select('id', 'title')->get();

        return view('admin.shop.edit', compact('item', 'cate'));
    }

    public function update($item_id)
    {
        $input = Input::except('_token', '_method', 'spec_name', 'spec_value');
        $rules = [
            'name'=>'required',
            'category'=>'required',
            'describe'=>'required',
            'prime_price'=>'required',
            'cur_price'=>'required',
            'stock'=>'required',
            'indeximg'=>'required',
            'showimg'=>'required',
        ];
        $message = [
            'name.required'=>'物品名称不能为空！',
            'category.required'=>'物品分类不能为空！',
            'describe.required'=>'物品文字描述不能为空！',
            'prime_price.required'=>'原价不能为空！',
            'cur_price.required'=>'当前价格不能为空！',
            'stock.required'=>'库存不能为空！',
            'indeximg.required'=>'主页图片不能为空！',
            'showimg.required'=>'物品详情轮播图片不能为空！',
        ];

        $validator = Validator::make($input,$rules, $message);
        if($validator->passes()){
            $data = ShopItem::find($item_id);
            $re = ShopItem::where('id', $item_id)->update($input);
            if($re){
                if ($data->indeximg != $input['indeximg']){
                    $this->removeFile($data->indeximg);
                }

                if ($data->showimg != $input['showimg']){
                    $oldShowImg =  json_decode($data->showimg);
                    $newShowImg = json_decode($input['showimg']);
                    $bHave = false;
                    foreach ($oldShowImg as $old){
                        foreach ($newShowImg as $new){
                            if ($old == $new){
                                $bHave = true;
                                break;
                            }
                        }

                        if (!$bHave){
                            $this->removeFile($old);
                        }
                    }
                }

                if ($data->content != $input['content']) {
                    $oldContentImg = json_decode($data->content);
                    $newContentImg = json_decode($input['content']);
                    $bHave = false;
                    foreach ($oldContentImg as $old){
                        foreach ($newContentImg as $new){
                            if ($old == $new){
                                $bHave = true;
                                break;
                            }
                        }

                        if (!$bHave){
                            $this->removeFile($old);
                        }
                    }
                }

                return redirect('admin/shop');
            }else{
                return back()->with('errors','数据更新失败，请稍后重试！');
            }
        }else{
            return back()->withErrors($validator);
        }
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
            $showImg =  json_decode($item->showimg);
            $contentImg = json_decode($item->content);
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
