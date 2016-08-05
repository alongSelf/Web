@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 提现申请
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->

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
                        <th class="tc" width="5%">用户ID</th>
                        <th>提现金额(元)</th>
                        <th>余额(分)</th>
                        <th>申请时间</th>
                        <th>状态</th>
                        <th>处理者</th>
                        <th>操作</th>
                    </tr>
                    @foreach($data as $v)
                        <tr>
                            <td class="tc">
                                <a href="javascript:;" onclick="showUser('{{url('admin/user/show')}}/{{$v->userid}}')">
                                    {{$v->userid}}
                                </a>
                            </td>
                            <td>{{$v->money}}</td>
                            <td>{{$v->balance}}</td>
                            <td>
                                <?php
                                echo date('Y-m-d H:i:s', $v->time)
                                ?>
                            </td>
                            <td>
                                <?php
                                if (0 == $v->status){
                                    echo '未处理';
                                }
                                if (1 == $v->status){
                                    echo '完成';
                                }
                                if (2 == $v->status){
                                    echo '取消';
                                }
                                ?>
                            </td>
                            <td>{{$v->operate}}</td>
                             <td>
                                 @if (0 == $v->status)
                                     <a href="javascript:;" onclick="Cash({{$v->id}})">发红包</a>
                                     <a href="javascript:;" onclick="Cancel({{$v->id}})">取消</a>
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
        function Cash(id) {
            $.post("{{url('admin/user/cashPay')}}",{'_token':"{{csrf_token()}}", 'id':id},function (data) {
                if(data.status==0){
                    location.href = location.href;
                    layer.msg(data.msg, {icon: 6});
                }else{
                    layer.msg(data.msg, {icon: 5});
                }
            });
        }

        function Cancel(id) {
            $.post("{{url('admin/user/cashCancel')}}",{'_token':"{{csrf_token()}}", 'id':id},function (data) {
                if(data.status==0){
                    location.href = location.href;
                    layer.msg(data.msg, {icon: 6});
                }else{
                    layer.msg(data.msg, {icon: 5});
                }
            });
        }
    </script>


@endsection
