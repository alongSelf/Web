@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 订单详情
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 开始-->
    <div class="result_wrap">
        <div class="result_title">
            <input type="button" onclick="self.location=document.referrer;" value="返回">
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
        <table class="list_tab">
            <tbody>
            <tr>
                <th>订单号：</th>
                <td>
                    {{$data->id}}
                    <input type="hidden" id="orderID" value="{{$data->id}}">
                </td>
                <th>状态：</th>
                <td>
                    <?php
                    if (0 == $data->status){
                        echo '待付款';
                    }
                    if (1 == $data->status){
                        echo '<em style="color: red">待发货</em>';
                    }
                    if (2 == $data->status){
                        echo '待评价';
                    }
                    if (3 == $data->status){
                        echo '完成';
                    }
                    if (4 == $data->status){
                        echo '取消';
                    }
                    ?>
                </td>
                <th>金额：</th>
                <td style="color: red">
                    {{$data->price}}
                </td>
                <th>支付渠道：</th>
                <td>
                    <?php
                    if (0 == $data->paychannel){
                        echo '尚未支付';
                    }
                    if (1 == $data->paychannel){
                        echo '微信';
                    }
                    if (2 == $data->paychannel){
                        echo '支付宝';
                    }
                    ?>
                </td>
                <th>日期：</th>
                <td>
                    <?php
                    echo date('Y-m-d H:i:s', $data->createtime)
                    ?>
                </td>
            </tr>
            <tr>
                <th>购买者ID：</th>
                <td>
                    {{$data->userinfo->id}}
                </td>
                <th>姓名：</th>
                <td>
                    {{$data->userinfo->name}}
                </td>
                <th>昵称：</th>
                <td>
                    {{$data->userinfo->nickname}}
                </td>
                <th>电话：</th>
                <td>
                    {{$data->userinfo->phone}}
                </td>
            </tr>
            <tr>
                <th>物品信息：</th>
                <td>
                    {!! $data->iteminfo !!}
                </td>
            </tr>
            <tr>
                <th>收货人姓名：</th>
                <td>
                    {{$data->addr->name}}
                </td>
                <th>收货人电话：</th>
                <td>
                    {{$data->addr->phone}}
                </td>
                <th>收货人地址：</th>
                <td>
                    <?php
                    $addr = json_decode($data->addr->addr);
                    echo $addr->province . ' ' . $addr->city . ' ' . $addr->county. ' ' .$addr->address;
                    ?>
                </td>
            </tr>
            <!--发货-->
            @if($data->status == 1)
                <tr>
                    <th>承运公司：</th>
                    <td>
                        <select id="ShipperCode">
                            @foreach($shippercode as $sh)
                                <option value="{{$sh->code}}">
                                    {{$sh->name}}
                                </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>运单号：</th>
                    <td>
                        <input type="text" style="width: 200px" id="LogisticCode" placeholder="运单号" value="">
                        <br>
                        <input type="button" onclick="delivery()" value="发货">
                    </td>
                </tr>
            @endif
            @if($data->status == 2 || $data->status == 3 )
                <tr>
                    <th>物流：</th>
                    <td>
                        {!! $data->logistics !!}
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <script>
        function delivery() {
            var orderID = document.getElementById('orderID').value;
            var ShipperCodeDoc = document.getElementById('ShipperCode');
            var index = ShipperCodeDoc.selectedIndex;
            var ShipperName = ShipperCodeDoc.options[index].text;
            var ShipperCode = ShipperCodeDoc.options[index].value;
            var LogisticCode = document.getElementById('LogisticCode').value;
            if (!LogisticCode || 0 == LogisticCode.length){
                layer.msg('请输入运单号', {icon: 5});
                return;
            }

            var msg = '承运公司：' + ShipperName + '  运单号：' +LogisticCode + '?';
            layer.confirm(msg, {
                title: '线下发货',
                btn: ['确定','取消'] //按钮
            }, function(){
                $.post("{{url('admin/order/delivery')}}",
                        {'_token':"{{csrf_token()}}", 'ShipperCode':ShipperCode, 'LogisticCode':LogisticCode,'orderID':orderID},function (data) {
                    if(data.status==0){
                        location.href = location.href;
                        layer.msg(data.msg, {icon: 6});
                    }else{
                        layer.msg(data.msg, {icon: 5});
                    }
                });
            }, function(){

            });
        }
        
        function deliveryOnLine() {
            var orderID = document.getElementById('orderID').value;
            var ShipperCodeDoc = document.getElementById('ShipperCode');
            var index = ShipperCodeDoc.selectedIndex;
            var ShipperName = ShipperCodeDoc.options[index].text;
            var ShipperCode = ShipperCodeDoc.options[index].value;

            var msg = '向 ' + ShipperName + ' 下单发货?';
            layer.confirm(msg, {
                title: '在线发货',
                btn: ['确定','取消'] //按钮
            }, function(){
                $.post("{{url('admin/order/deliveryOnLine')}}",
                        {'_token':"{{csrf_token()}}", 'ShipperCode':ShipperCode, 'orderID':orderID},function (data) {
                            if(data.status==0){
                                layer.msg(data.msg, {icon: 6});
                            }else{
                                layer.msg(data.msg, {icon: 5});
                            }
                        });
            }, function(){

            });
        }

    </script>
@endsection
