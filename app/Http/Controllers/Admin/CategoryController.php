<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Category;
use App\http\Model\ShopItem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CategoryController extends CommonController
{
    public function index()
    {
        $categorys = Category::all();
        return view('admin.category.index')->with('data', $categorys);
    }

    public function changeOrder()
    {
        $input = Input::all();
        if ($input){
            $cate = Category::find($input['id']);
            $cate->sort = $input['sort'];
            $re = $cate->update();
            if($re){
                ShopItem::where('category', $cate['id'])->update(['sort'=>$input['sort']]);
                $data = [
                    'status' => 0,
                    'msg' => '分类排序更新成功！',
                ];
            }else{
                $data = [
                    'status' => 1,
                    'msg' => '分类排序更新失败，请稍后重试！',
                ];
            }
            return $data;
        }
    }

    //get.admin/category/create   添加分类
    public function create()
    {
        return view('admin.category.add');
    }

    //post.admin/category  添加分类提交
    public function store()
    {
        $input = Input::except('_token');
        $rules = [
            'title'=>'required',
            'sort'=>'required',
            'img'=>'required',
            'describe'=>'required',
        ];

        $message = [
            'title.required'=>'分类名称不能为空！',
            'sort.required'=>'分类排序不能为空！',
            'img.required'=>'分类图标不能为空！',
            'describe.required'=>'分类简介不能为空！',
        ];

        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){
            $re = Category::create($input);
            if($re){
                return redirect('admin/category');
            }else{
                return back()->with('errors','数据填充失败，请稍后重试！');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    //get.admin/category/{category}/edit  编辑分类
    public function edit($cate_id)
    {
        $data = Category::find($cate_id);

        return view('admin/category/edit', compact('data'));
    }

    //put.admin/category/{category}    更新分类
    public function update($cate_id)
    {
        $input = Input::except('_token','_method');
        $rules = [
            'title'=>'required',
            'sort'=>'required',
            'img'=>'required',
            'describe'=>'required',
        ];

        $message = [
            'title.required'=>'分类名称不能为空！',
            'sort.required'=>'分类排序不能为空！',
            'img.required'=>'分类图标不能为空！',
            'describe.required'=>'分类简介不能为空！',
        ];

        $validator = Validator::make($input,$rules,$message);
        if($validator->passes()){
            $data = Category::find($cate_id);
            $re = Category::where('id', $cate_id)->update($input);
            if($re){
                if($data['sort'] != $input['sort']){
                    ShopItem::where('category', $cate_id)->update(['sort'=>$input['sort']]);
                }
                if ($data->img != $input['img']){
                    $this->removeFile($data->img);
                }
                return redirect('admin/category');
            }else{
                return back()->with('errors','分类信息更新失败，请稍后重试！');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    //get.admin/category/{category}  显示单个分类信息
    public function show()
    {

    }

    //delete.admin/category/{category}   删除单个分类
    public function destroy($cate_id)
    {
        $shopItem = ShopItem::where('category', $cate_id)->get();
        if (0 != count($shopItem)){
            return [
                'status' => 1,
                'msg' => '该类别下还有商品！',
            ];
        }

        $img = Category::select('img')->where('id',$cate_id)->first()->img;
        $re = Category::where('id',$cate_id)->delete();
        if($re){
            $this->removeFile($img);
            $data = [
                'status' => 0,
                'msg' => '分类删除成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '分类删除失败，请稍后重试！',
            ];
        }
        return $data;
    }
}
