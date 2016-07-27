@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 订单管理
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->

    <div class="result_wrap">
        <!--快捷导航 开始-->
        <div class="result_content">
            <div class="short_wrap">
                <select id="searchCondition" onchange="selectOrder()">
                    <option value="all" @if($type=='all') selected @endif>全部订单</option>
                    <option value="pay" @if($type=='pay') selected @endif>待付款</option>
                    <option value="delivery" @if($type=='delivery') selected @endif>待发货</option>
                    <option value="evaluate" @if($type=='evaluate') selected @endif>待评价</option>
                    <option value="complete" @if($type=='complete') selected @endif>完成</option>
                    <option value="cancel" @if($type=='cancel') selected @endif>取消</option>
                </select>
            </div>
            <div style="padding-left: 100px">
                <input type="text" placeholder="订单ID" onchange="searchByOrderID(this)" value="{{$orderID}}">
                <input type="text" placeholder="用户ID" onchange="searchByUserID(this)" value="{{$userID}}">
                <input type="text" placeholder="运单号" onchange="searchByLOrder(this)" value="{{$lOrder}}">
            </div>
        </div>
        <!--快捷导航 结束-->
    </div>

    <form action="#" method="post">
        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc" width="5%">订单ID</th>
                        <th>用户ID</th>
                        <th>支付渠道</th>
                        <th>总金额(元)</th>
                        <th>物品</th>
                        <th>物流</th>
                        <th>状态</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                    @foreach($data as $v)
                        <tr>
                            <td class="tc">{{$v->id}}</td>
                            <td class="tc">{{$v->userid}}</td>
                            <td>
                                <?php
                                if (0 == $v->paychannel){
                                    echo '尚未支付';
                                }
                                if (1 == $v->paychannel){
                                    echo '微信';
                                }
                                if (2 == $v->paychannel){
                                    echo '支付宝';
                                }
                                ?>
                            </td>
                            <td>{{$v->price}}</td>
                            <td>{!!$v->iteminfo!!}</td>
                            <td>{!!$v->logistics!!}</td>
                            <td>
                                <?php
                                if (0 == $v->status){
                                    echo '待付款';
                                }
                                if (1 == $v->status){
                                    echo '<em style="color: red">待发货</em>';
                                }
                                if (2 == $v->status){
                                    echo '待评价';
                                }
                                if (3 == $v->status){
                                    echo '完成';
                                }
                                if (4 == $v->status){
                                    echo '取消';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                echo date('Y-m-d H:i:s', $v->createtime)
                                ?>
                            </td>
                            <td>
                                <a href="{{url('admin/order/show')}}/{{$v->id}}">查看</a>
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
        function selectOrder() {
            var inputType = document.getElementById('searchCondition').value;
            if ('all' == inputType){
                window.location.href='{{url('admin/order/index')}}';
                return;
            }

            window.location.href='{{url('admin/order/searchByStatues')}}/'+inputType;
        }
        function searchByOrderID(obj) {
            var orderID = obj.value;
            if (!orderID || 0 == orderID.length){
                window.location.href='{{url('admin/order/index')}}';
                return;
            }
            window.location.href='{{url('admin/order/searchByOrderID')}}/'+orderID;
        }
        function searchByUserID(obj) {
            var userID = obj.value;
            if (!userID || 0 == userID.length){
                window.location.href='{{url('admin/order/index')}}';
                return;
            }
            window.location.href='{{url('admin/order/searchByUserID')}}/'+userID;
        }
        function searchByLOrder(obj) {
            var lOrder = obj.value;
            if (!lOrder || 0 == lOrder.length){
                window.location.href='{{url('admin/order/index')}}';
                return;
            }
            window.location.href='{{url('admin/order/searchByLOrder')}}/'+lOrder;
        }
    </script>

@endsection
