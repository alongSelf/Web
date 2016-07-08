@extends('admin.layouts')
@section('content')
        <!--面包屑导航 开始-->
<div class="crumb_warp">
    <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 分类
</div>
<!--面包屑导航 结束-->

<!--搜索结果页面 列表 开始-->

<div class="result_wrap">
    <div class="result_title">
        <h3>分类列表</h3>
    </div>
    <!--快捷导航 开始-->
    <div class="result_content">
        <div class="short_wrap">
            <a href="{{url('admin/category/create')}}"><i class="fa fa-plus"></i>添加分类</a>
        </div>
    </div>
    <!--快捷导航 结束-->
</div>
<form action="#" method="post">
    <table class="list_tab">
        <tr>
            <th class="tc" width="5%">ID</th>
            <th class="tc" width="5%">排序</th>
            <th>名称</th>
            <th>简介</th>
            <th>图标</th>
            <th>操作</th>
        </tr>

        @foreach($data as $v)
            <tr>
                <td class="tc">{{$v->id}}</td>
                <td class="tc">
                    <input type="text" onchange="changeOrder(this, {{$v->id}})" value="{{$v->sort}}">
                </td>
                <td>{{$v->title}}</td>
                <td>{{$v->describe}}</td>
                <td>
                    <img src="{{asset('uploads/'.$v->img)}}" style="width: 50px; height: 50px; border-radius:50%"/>
                </td>
                <td>
                    <a href="{{url('admin/category/'.$v->id.'/edit')}}">修改</a>
                    <a href="javascript:;" onclick="delCate({{$v->id}})">删除</a>
                </td>
            </tr>
        @endforeach
    </table>
</form>
<!--搜索结果页面 列表 结束-->

<script>
    function changeOrder(obj,cate_id){
        var cate_order = $(obj).val();
        $.post("{{url('admin/category/changeOrder')}}",{'_token':'{{csrf_token()}}','id':cate_id,'sort':cate_order},function(data){
            if(data.status == 0){
                layer.msg(data.msg, {icon: 6});
            }else{
                layer.msg(data.msg, {icon: 5});
            }
        });
    }
    //删除分类
    function delCate(cate_id) {
        layer.confirm('您确定要删除这个分类吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.post("{{url('admin/category/')}}/"+cate_id,{'_method':'delete','_token':"{{csrf_token()}}"},function (data) {
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

</script>

@endsection
