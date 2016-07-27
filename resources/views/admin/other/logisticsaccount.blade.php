@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 物流账号管理
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 开始-->
    <div class="result_wrap">
        <div class="result_title">
            <h3>物流账号</h3>
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
    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="{{url('admin/other/setLAccount')}}" method="post">
            <input type="hidden" name="id" value="{{$id}}">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                <tr>
                    <th>账号：</th>
                    <td>
                        <input type="text" name="CustomerName" value="{{$account->CustomerName}}">
                    </td>
                </tr>
                <tr>
                    <th>密码：</th>
                    <td>
                        <input type="text" style="width: 50%" name="CustomerPwd" value="{{$account->CustomerPwd}}">
                    </td>
                </tr>
                <tr>
                    <th>网点标识：</th>
                    <td>
                        <input type="text" style="width: 50%" name="SendSite" value="{{$account->SendSite}}">
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
