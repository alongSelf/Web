@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 微信客服管理
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->
    <div class="result_wrap">
        <div class="result_title">
            <h3>微信客服管理</h3>
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

    <div class="result_wrap">
        <!--快捷导航 开始-->
        <div class="result_content">
            <div class="short_wrap">
            </div>
        </div>
        <!--快捷导航 结束-->
    </div>
    <form action="#" method="post">
        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc" width="5%">ID</th>
                        <th>账号</th>
                        <th>昵称</th>
                        <th>头像</th>
                        <th>操作</th>
                    </tr>
                    @foreach($csv as $v)
                        <tr>
                            <td>{{$v->kf_id}}</td>
                            <td>{{$v->kf_account}}</td>
                            <td>{{$v->kf_nick}}</td>
                            <td>
                                <img src="{{$v->kf_headimgurl}}">
                            </td>
                            <td>
                                <a href="javascript:;" onclick="disPlayEvaluates({{$v->id}})">
                                    删除
                                </a>
                                <a href="javascript:;" onclick="disPlayEvaluates({{$v->id}})">
                                    修改
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->

@endsection
