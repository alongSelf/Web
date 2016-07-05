@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 用户管理
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->

    <div class="result_wrap">
        <!--快捷导航 开始-->
        <div class="result_content">
            <div class="short_wrap">
            <!--<a href="{{url('admin/category/create')}}"><i class="fa fa-plus"></i>添加分类</a>-->
            </div>
        </div>
        <!--快捷导航 结束-->
    </div>



@endsection
