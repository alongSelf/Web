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
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>

                </tbody>
            </table>
        </form>
    </div>

@endsection
