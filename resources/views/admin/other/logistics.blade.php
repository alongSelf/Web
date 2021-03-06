@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 快鸟物流信息
    </div>
    <!--面包屑导航 结束-->
    <div class="result_wrap">
        <div class="result_title">
            <a href="http://www.kdniao.com/reg" target="_blank"><h3>快鸟物流信息(即时接口)</h3></a>
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
        <form action="{{url('admin/other/setLogistics')}}" method="post">
            <input type="hidden" name="id" value="{{$id}}">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                <tr>
                    <th>用户ID：</th>
                    <td>
                        <input type="text" name="userID" value="{{$logistics->userID}}">
                    </td>
                </tr>
                <tr>
                    <th>apiKey：</th>
                    <td>
                        <input type="text" style="width: 50%" name="apiKey" value="{{$logistics->apiKey}}">
                    </td>
                </tr>
                <tr>
                    <th>姓名：</th>
                    <td>
                        <input type="text" style="width: 50%" name="name" value="{{$logisticsAddr->name}}">
                    </td>
                </tr>
                <tr>
                    <th>电话：</th>
                    <td>
                        <input type="text" style="width: 50%" name="phone" value="{{$logisticsAddr->phone}}">
                    </td>
                </tr>
                <tr>
                    <th>地址：</th>
                    <td>
                        <input type="text" placeholder="省" name="province" value="{{$logisticsAddr->province}}">
                        <input type="text" placeholder="市" name="city" value="{{$logisticsAddr->city}}">
                        <input type="text" placeholder="区/县" name="county" value="{{$logisticsAddr->county}}">
                        <input type="text" placeholder="详细地址" name="address" value="{{$logisticsAddr->address}}">
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
