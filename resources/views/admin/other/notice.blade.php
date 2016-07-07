@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 公告
    </div>
    <!--面包屑导航 结束-->
    <div class="result_wrap">

    </div>

    <!--搜索结果页面 列表 开始-->
    <div class="result_wrap">
        <table class="add_tab">
            <tbody>
            <tr>
                <th width="70">公告：</th>
                <td>
                    <input style="width: 90%" type="text" onchange="changeNotice(this, {{$notice->id}})" value="{{$notice->notice}}">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!--搜索结果页面 列表 结束-->

    <script>
        function changeNotice(obj,notice_id){
            var notice = $(obj).val();
            $.post("{{url('admin/other/changeNotice')}}",{'_token':'{{csrf_token()}}','id':notice_id,'notice':notice},function(data){
                console.log(data);
                if(data.status == 0){
                    layer.msg(data.msg, {icon: 6});
                }else{
                    layer.msg(data.msg, {icon: 5});
                }
            });
        }
    </script>

@endsection
