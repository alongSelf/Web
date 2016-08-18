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
                    <th style="width: 200px">python设置token密码：</th>
                    <td>
                        <input type="text" style="width: 50%" placeholder="python设置token密码"  name="accessToken" value="{{$config->wx->accessToken}}">
                    </td>
                </tr>
                <tr>
                    <th>Token(令牌)：</th>
                    <td>
                        <input type="text" style="width: 50%" placeholder="令牌"  name="Token" value="{{$config->wx->Token}}">
                    </td>
                </tr>
                <tr>
                    <th>公众号ID：</th>
                    <td>
                        <input type="text" style="width: 50%" placeholder="公众号ID"  name="ghID" value="{{$config->wx->ghID}}">
                    </td>
                </tr>
                <tr>
                    <th>AppID(应用ID)：</th>
                    <td>
                        <input type="text" style="width: 50%" placeholder="应用ID" name="AppID" value="{{$config->wx->AppID}}">
                    </td>
                </tr>
                <tr>
                    <th>AppSecret(应用密钥)：</th>
                    <td>
                        <input type="text" style="width: 50%" placeholder="应用密钥" name="AppSecret" value="{{$config->wx->AppSecret}}">
                    </td>
                </tr>
                <tr>
                    <th>商户ID(支付用)：</th>
                    <td>
                        <input type="text" style="width: 50%" placeholder="商户ID" name="payID" value="{{$config->wx->payID}}">
                    </td>
                </tr>
                <tr>
                    <th>商户名称：</th>
                    <td>
                        <input type="text" style="width: 50%" placeholder="商户名称" name="mchName" value="{{$config->wx->mchName}}">
                    </td>
                </tr>
                <tr>
                    <th>商户签名密钥：</th>
                    <td>
                        <input type="text" style="width: 50%" placeholder="商户签名密钥" name="payKey" value="{{$config->wx->payKey}}">
                    </td>
                </tr>
                <tr>
                    <th>关注问候语：</th>
                    <td>
                        <input type="text" style="width: 50%" placeholder="关注问候语" name="welcome" value="{{$config->wx->welcome}}">
                    </td>
                </tr>
                <tr>
                    <th>分享标题：</th>
                    <td>
                        <input type="text" style="width: 50%" placeholder="分享标题" name="sharetitle" value="{{$config->wx->sharetitle}}">
                    </td>
                </tr>
                <tr>
                    <th>分享描述：</th>
                    <td>
                        <input type="text" style="width: 50%" placeholder="分享描述" name="sharememo" value="{{$config->wx->sharememo}}">
                    </td>
                </tr>
                <tr>
                    <th>是否通过微信服务器验证：</th>
                    <td>
                        <select name="wxcheck">
                            <option value="0" @if(0==$config->wx->wxcheck) selected @endif>否</option>
                            <option value="1" @if(1==$config->wx->wxcheck) selected @endif>是</option>
                        </select>
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
