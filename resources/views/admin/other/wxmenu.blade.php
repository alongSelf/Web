@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 微信菜单设置
    </div>
    <!--面包屑导航 结束-->
    <div class="result_wrap">
        <div class="result_title">
            <a href="https://mp.weixin.qq.com/wiki/10/0234e39a2025342c17a7d23595c6b40a.html" target="_blank"><h3>微信菜单文档</h3></a>
            <a href="http://www.bejson.com/" target="_blank"><h3>json在线格式化</h3></a>
            @if(count($errors)>0)
                <div class="mark">
                    @if(is_object($errors))
                        @foreach($errors->all() as $error)
                            <p>{{$error}}</p>
                        @endforeach
                    @else
                        <p>{{$errors}}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!--搜索结果页面 列表 开始-->
    <div class="result_wrap">
        <form action="{{url('admin/other/createWXMenu')}}" method="post">
            <input type="hidden" name="id" value="{{$menu->id}}">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                <tr>
                    <th style="width: 100px">微信菜单(json)：</th>
                    <td>
                        <textarea style="width: 30%; height: 500px" name="menu">{{$menu->menu}}</textarea>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <input type="submit" value="提交">
                        <input type="button" class="back" onclick="history.go(-1)" value="返回">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>

@endsection
