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
        $cate_id = -1;
        $cate = Category::all();
        $data = ShopItem::orderBy('id','desc')->paginate(10);
        return view('admin.shop.index', compact('data', 'cate', 'cate_id'));
    }

    public function searchbycate($cate_id)
    {
        $cate = Category::all();
        $data = ShopItem::where('category', $cate_id)->orderBy('id','desc')->paginate(10);
        return view('admin.shop.index',compact('data', 'cate', 'cate_id'));
    }

    public function searchbyname($name)
    {
        $cate_id = -1;
        $cate = Category::all();
        $data = ShopItem::where('name','like','%'.$name.'%')->orderBy('id','desc')->paginate(10);
        return view('admin.shop.index', compact('data', 'cate', 'cate_id'));
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
            'content'=>'required',
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
            'content.required'=>'宝贝详情不能为空！',
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
            'content'=>'required',
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
            'content.required'=>'宝贝详情不能为空！',
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
                if ($data->activityimg != $input['activityimg']){
                    $this->removeFile($data->activityimg);
                }

                if ($data->showimg != $input['showimg']){
                    $oldShowImg =  json_decode($data->showimg);
                    $newShowImg = json_decode($input['showimg']);
                    $bHave = false;
                    for($i = 0; $i < count($oldShowImg); $i++){
                        $bHave = false;
                        for($j = 0; $j < count($newShowImg); $j++){
                            if ($oldShowImg[$i] == $newShowImg[$j]){
                                $bHave = true;
                                break;
                            }
                        }

                        if (!$bHave){
                            $this->removeFile($oldShowImg[$i]);
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
        if(1 == $item->display){
            $item->display = 0;
        }else{
            $item->display = 1;
        }
        $re = $item->update();
        if($re){
            $data = [
                'status' => 0,
                'msg' => '宝贝隐藏/显示成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '宝贝隐藏/显示失败，请稍后重试！',
            ];
        }
        return $data;
    }
}
