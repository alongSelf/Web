@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 微信公众平台设置
    </div>
    <!--面包屑导航 结束-->
    <div class="result_wrap">
        <div class="result_title">
            <a href="https://mp.weixin.qq.com/" target="_blank"><h3>微信公众平台</h3></a>
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
        <form action="{{url('admin/other/setWXSet')}}" method="post">
            <input type="hidden" name="id" value="{{$config->id}}">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                <tr>
                    <th>Token(令牌)：</th>
                    <td>
                        <input type="text" placeholder="令牌"  name="Token" value="{{$config->wx->Token}}">
                    </td>
                </tr>
                <tr>
                    <th>AppID(应用ID)：</th>
                    <td>
                        <input type="text" placeholder="应用ID" name="AppID" value="{{$config->wx->AppID}}">
                    </td>
                </tr>
                <tr>
                    <th>AppSecret(应用密钥)：</th>
                    <td>
                        <input type="text" placeholder="应用密钥" name="AppSecret" value="{{$config->wx->AppSecret}}">
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
