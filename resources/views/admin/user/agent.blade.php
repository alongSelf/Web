@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 代理申请
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->

    <div class="result_wrap">
        <!--快捷导航 开始-->
        <div class="result_content">
            <div class="short_wrap">
                <input type="text" placeholder="电话号码" id="search" name="search" value="{{$phone}}">
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
                        <th class="tc" width="5%">用户ID</th>
                        <th>姓名</th>
                        <th>电话</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    @foreach($data as $v)
                        <tr>
                            <td class="tc">
                                <a href="javascript:;" onclick="showUser('{{url('admin/user/show')}}/{{$v->userid}}')">
                                    {{$v->userid}}
                                </a>
                            </td>
                            <td>{{$v->name}}</td>
                            <td>{{$v->phone}}</td>

                            <td>
                                <?php
                                if (0 == $v->state){
                                    echo '未处理';
                                }
                                if (1 == $v->state){
                                    echo '已处理';
                                }
                                ?>
                            </td>
                             <td>
                                 @if (0 == $v->state)
                                     <a href="javascript:;" onclick="setStatues({{$v->userid}})">标记为已处理</a>
                                 @endif
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
        function setStatues(userid) {
            $.post("{{url('admin/user/changeAgent')}}",{'_token':"{{csrf_token()}}", 'userid':userid},function (data) {
                if(data.status==0){
                    location.href = location.href;
                    layer.msg(data.msg, {icon: 6});
                }else{
                    layer.msg(data.msg, {icon: 5});
                }
            });
        }

        function Search() {
            var inputVal = document.getElementById('search').value;
            if (0 == inputVal.length){
                window.location.href='{{url('admin/user/agent')}}';
                return;
            }

            window.location.href='{{url('admin/user/agent')}}/'+inputVal;
        }
    </script>


@endsection
