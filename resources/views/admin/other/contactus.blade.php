@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 联系我们
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 开始-->
    <div class="result_wrap">
        <div class="result_title">
            <h3>联系我们</h3>
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
        <form action="{{url('admin/other/changeContactus')}}" method="post">
            <input type="hidden" name="id" value="{{$config->id}}">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                <tr>
                    <th width="200">服务热线：</th>
                    <td>
                        <textarea style="width: 30%" name="phone">{{$config->contactus->phone}}</textarea>
                    </td>
                </tr>
                <tr>
                    <th>客户服务邮箱：</th>
                    <td>
                        <textarea style="width: 30%" name="email">{{$config->contactus->email}}</textarea>
                    </td>
                </tr>
                <tr>
                    <th>客服QQ：</th>
                    <td>
                        <textarea style="width: 30%" name="qq">{{$config->contactus->qq}}</textarea>
                    </td>
                </tr>
                <tr>
                    <th>邮递信息：</th>
                    <td>
                        <textarea style="width: 30%" name="postAddr">{{$config->contactus->postAddr}}</textarea>
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
