@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 配置
    </div>
    <!--面包屑导航 结束-->
    <div class="result_wrap">
        <div class="result_title">
            <h3>配置</h3>
        </div>
    </div>

    <!--搜索结果页面 列表 开始-->
    <div class="result_wrap">
        <table class="add_tab">
            <tbody>
                <tr>
                    <th width="70"><i class="require">*</i>软件名：</th>
                    <td>
                        <input type="text" onchange="changeTitle(this, {{$config->id}})" value="{{$config->title}}">
                    </td>
                </tr>
                <tr>
                    <th width="70"><i class="require">*</i>代理介绍：</th>
                    <td>
                        <textarea onchange="changeAgent(this, {{$config->id}})">{{$config->agent}}</textarea>
                    </td>
                </tr>
                <tr>
                    <th width="70"><i class="require">*</i>推广介绍：</th>
                    <td>
                        <textarea onchange="changeSpread(this, {{$config->id}})">{{$config->spread}}</textarea>
                    </td>
                </tr>
                <tr>
                    <th width="70"><i class="require">*</i>推广条件消费满(元)：</th>
                    <td>
                        <input type="number" onchange="changeOpenSpread(this, {{$config->id}})" value="{{$config->openspread}}">
                    </td>
                </tr>
                <tr>
                    <th width="70"><i class="require">*</i>推广提成(每100元提成多少分)：</th>
                    <td>
                        <input type="number" onchange="changeCommission(this, {{$config->id}})" value="{{$config->commission}}">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!--搜索结果页面 列表 结束-->

    <script>
        function changeTitle(obj,config_id){
            var title = $(obj).val();
            $.post("{{url('admin/other/changeTitle')}}",{'_token':'{{csrf_token()}}','id':config_id,'title':title},function(data){
                if(data.status == 0){
                    layer.msg(data.msg, {icon: 6});
                }else{
                    layer.msg(data.msg, {icon: 5});
                }
            });
        }
        
        function changeAgent(obj,config_id) {
            var agent = $(obj).val();
            $.post("{{url('admin/other/changeAgent')}}",{'_token':'{{csrf_token()}}','id':config_id,'agent':agent},function(data){
                if(data.status == 0){
                    layer.msg(data.msg, {icon: 6});
                }else{
                    layer.msg(data.msg, {icon: 5});
                }
            });
        }

        function changeSpread(obj,config_id) {
            var spread = $(obj).val();
            $.post("{{url('admin/other/changeSpread')}}",{'_token':'{{csrf_token()}}','id':config_id,'spread':spread},function(data){
                if(data.status == 0){
                    layer.msg(data.msg, {icon: 6});
                }else{
                    layer.msg(data.msg, {icon: 5});
                }
            });
        }

        function changeOpenSpread(obj,config_id) {
            var openspread = $(obj).val();
            $.post("{{url('admin/other/changeOpenSpread')}}",{'_token':'{{csrf_token()}}','id':config_id,'openspread':openspread},function(data){
                if(data.status == 0){
                    layer.msg(data.msg, {icon: 6});
                }else{
                    layer.msg(data.msg, {icon: 5});
                }
            });
        }

        function changeCommission(obj,config_id) {
            var commission = $(obj).val();
            $.post("{{url('admin/other/changeCommission')}}",{'_token':'{{csrf_token()}}','id':config_id,'commission':commission},function(data){
                if(data.status == 0){
                    layer.msg(data.msg, {icon: 6});
                }else{
                    layer.msg(data.msg, {icon: 5});
                }
            });
        }
    </script>

@endsection
