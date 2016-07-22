@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 宝贝管理
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->
    <div class="result_wrap">
        <div class="result_title">
            <h3>点评</h3>
        </div>
    </div>

    <div class="result_wrap">
        <!--快捷导航 开始-->
        <div class="result_content">
            <div class="short_wrap">
                <a href="{{url('admin/other/evaluates')}}"><i class="fa"></i>全部点评</a>
                <input type="text" id="search" name="search" value=""><input type="button" onclick="searchbyID()" value="搜索(物品ID)">
            </div>

            <br>
        </div>
        <!--快捷导航 结束-->
    </div>
    <form action="#" method="post">
        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc" width="5%">ID</th>
                        <th class="tc" width="5%">物品ID</th>
                        <th class="tc" width="5%">用户ID</th>
                        <th class="tc" width="5%">订单ID</th>
                        <th class="tc" width="5%">评分</th>
                        <th class="tc" width="5%">是否显示</th>
                        <th>评价</th>
                        <th>时间</th>
                        <th>操作</th>
                    </tr>
                    @foreach($evaluates as $ev)
                        <tr>
                            <td>{{$ev->id}}</td>
                            <td>{{$ev->itemid}}</td>
                            <td>{{$ev->userid}}</td>
                            <td>{{$ev->orderid}}</td>
                            <td>{{$ev->star}}</td>
                            <td>{{$ev->display}}</td>
                            <td>{{$ev->evaluate}}</td>
                            <td>
                                <?php
                                    echo date('Y-m-d H:i:s', $ev->createtime)
                                ?>
                            </td>
                            <td>
                                <!--<a href="javascript:;" onclick="delEvaluates({{$ev->id}})">删除</a>-->
                                <a href="javascript:;" onclick="disPlayEvaluates({{$ev->id}})">显示/隐藏</a>
                            </td>
                        </tr>
                    @endforeach
                </table>

                <div class="page_list">
                    {{$evaluates->links()}}
                </div>
            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->

    <script>
        //删除分类
        function delEvaluates(id) {
            layer.confirm('您确定要删除该评论吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.post("{{url('admin/other/delEvaluates')}}", {'id':id,'_token':"{{csrf_token()}}"},function (data) {
                    if(data.status==0){
                        location.href = location.href;
                        layer.msg(data.msg, {icon: 6});
                    }else{
                        layer.msg(data.msg, {icon: 5});
                    }
                });
//            layer.msg('的确很重要', {icon: 1});
            }, function(){

            });
        }

        function disPlayEvaluates(id) {
            $.post("{{url('admin/other/disPlayEvaluates')}}", {'id':id,'_token':"{{csrf_token()}}"},function (data) {
                if(data.status==0){
                    location.href = location.href;
                    layer.msg(data.msg, {icon: 6});
                }else{
                    layer.msg(data.msg, {icon: 5});
                }
            });
        }

        function searchbyID() {
            var inputVal = document.getElementById('search').value;
            if (0 == inputVal.length){
                window.location.href='{{url('admin/other/evaluates')}}';
                return;
            }

            window.location.href='{{url('admin/other/searchEvaluates')}}/'+inputVal+'';
        }

    </script>

@endsection
