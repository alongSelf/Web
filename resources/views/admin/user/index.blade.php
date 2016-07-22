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
                <a href="{{url('admin/user/index')}}"><i class="fa"></i>全部用户</a>
                <input type="text" id="search" name="search" value="">
                <select id="searchCondition">
                    <option value="phone" selected>电话</option>
                    <option value="name">姓名</option>
                    <option value="nickname">昵称</option>
                    <option value="wx">微信号</option>
                    <option value="qq">QQ</option>
                </select>
                <input type="button" onclick="Search()" value="搜索">
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
                        <th>姓名</th>
                        <th>头像</th>
                        <th>昵称</th>
                        <th>EMail</th>
                        <th>电话</th>
                        <th>微信号</th>
                        <th>QQ</th>
                        <th>总消费</th>
                        <th>收入(分)</th>
                        <th>操作</th>
                    </tr>
                    @foreach($data as $v)
                        <tr>
                            <td class="tc">{{$v->id}}</td>
                            <td>{{$v->name}}</td>
                            <td><img src="{{asset('uploads/'.$v->icon)}}" style="width: 50px; height: 50px"/></td>
                            <td>{{$v->nickname}}</td>
                            <td>{{$v->email}}</td>
                            <td>{{$v->phone}}</td>
                            <td>{{$v->weixnumber}}</td>
                            <td>{{$v->qq}}</td>
                            <td>{{$v->consume}}</td>
                            <td>{{$v->income}}</td>
                             <td>
                                 <a href="javascript:;" onclick="resetPSW({{$v->id}})">重置密码</a>
                            </td>
                        </tr>
                    @endforeach
                </table>

                <div class="page_list">
                    {{$data->links()}}
                </div>
            </div>
        </div>
    </form>
    <script>
        function resetPSW(userid) {
            $.post("{{url('admin/user/resetPSW')}}",{'_token':"{{csrf_token()}}", 'id':userid},function (data) {
                console.log(data);
                if(data.status==0){
                    layer.msg(data.msg, {icon: 6});
                }else{
                    layer.msg(data.msg, {icon: 5});
                }
            });
        }

        function Search() {
            var inputVal = document.getElementById('search').value;
            if (0 == inputVal.length){
                window.location.href='{{url('admin/user/index')}}';
                return;
            }
            var inputType = document.getElementById('searchCondition').value;

            window.location.href='{{url('admin/user/search')}}/'+inputVal+'/'+inputType;
        }
    </script>


@endsection
