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
                <input type="text" id="search" placeholder="用户ID" name="search" value="{{$userID}}">
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
                        <th>粉丝ID</th>
                        <th>粉丝昵称</th>
                        <th>订单号</th>
                        <th>消费(元)</th>
                        <th>提成(分)</th>
                        <th>余额(分)</th>
                        <th>时间</th>
                    </tr>
                    @foreach($data as $v)
                        <tr>
                            <td class="tc">
                                <a href="javascript:;" onclick="showUser('{{url('admin/user/show')}}/{{$v->userid}}')">
                                    {{$v->userid}}
                                </a>
                            </td>
                            <td>{{$v->followerid}}</td>
                            <td>{{$v->followernam}}</td>
                            <td>{{$v->orderid}}</td>
                            <td>{{$v->consume}}</td>
                            <td>{{$v->income}}</td>
                            <td>{{$v->balance}}</td>
                            <td>
                                <?php
                                echo date('Y-m-d H:i:s', $v->time)
                                ?>
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
        function Search() {
            var inputVal = document.getElementById('search').value;
            if (0 == inputVal.length){
                window.location.href='{{url('admin/user/income')}}';
                return;
            }

            window.location.href='{{url('admin/user/income')}}/'+inputVal;
        }
    </script>


@endsection
