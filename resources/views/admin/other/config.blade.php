@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 配置
    </div>
    <!--面包屑导航 结束-->
    <div class="result_wrap">

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
    </script>

@endsection
