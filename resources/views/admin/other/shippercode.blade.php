@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 承运公司
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->

    <div class="result_wrap">
        <div class="result_title">
            <h3>承运公司</h3>
        </div>
        <!--快捷导航 开始-->
        <div class="result_content">
            <div class="short_wrap">
                <input type="text" id="search" placeholder="公司名" name="search" value="{{$name}}">
                <input type="button" onclick="searchbynam()" value="搜索">
            </div>
        </div>
        <!--快捷导航 结束-->
    </div>
    <form action="#" method="post">
        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc" width="15%">ID</th>
                        <th class="tc" width="15%">编码</th>
                        <th>名称</th>
                        <th>是否显示</th>
                        <th>操作</th>
                    </tr>

                    @foreach($data as $v)
                        <tr>
                            <td class="tc">{{$v->id}}</td>
                            <td class="tc">
                                {{$v->code}}
                            </td>
                            <td>{{$v->name}}</td>
                            <td>
                                @if($v->display==1)
                                    是
                                @else
                                    否
                                @endif
                            </td>
                            <td>
                                <a href="javascript:;" onclick="hiddenOrShow({{$v->id}})">
                                    @if($v->display==1)
                                        隐藏
                                    @else
                                        显示
                                    @endif
                                </a>
                                <a href="javascript:;" onclick="showLAccount({{$v->id}})">
                                    完善账号
                                </a>
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
    <!--搜索结果页面 列表 结束-->
    <script>
        function hiddenOrShow(id) {
            $.post("{{url('admin/other/setShippercode')}}",{'_token':"{{csrf_token()}}", 'id':id},function (data) {
                if(data.status==0){
                    location.href = location.href;
                    layer.msg(data.msg, {icon: 6});
                }else{
                    layer.msg(data.msg, {icon: 5});
                }
            });
        }

        function showLAccount(id) {
            window.location.href='{{url('admin/other/showLAccount')}}/' + id;
        }

        function searchbynam() {
            var inputVal = document.getElementById('search').value;
            if (0 == inputVal.length){
                window.location.href='{{url('admin/other/showShippercode')}}';
                return;
            }

            window.location.href='{{url('admin/other/showShippercode')}}/'+inputVal;
        }

    </script>
@endsection
