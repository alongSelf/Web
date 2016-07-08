@extends('admin.layouts')
@section('content')
        <!--面包屑导航 开始-->
<div class="crumb_warp">
    <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 宝贝管理
</div>
<!--面包屑导航 结束-->
<div class="result_wrap">
    <div class="result_title">
        <h3>商品列表</h3>
    </div>
</div>

<!--搜索结果页面 列表 开始-->

<div class="result_wrap">
    <!--快捷导航 开始-->
    <div class="result_content">
        <div class="short_wrap">
            <a href="{{url('admin/shop')}}"><i class="fa"></i>全部宝贝</a>

            <select name="category" onchange="searchbycate(this)">
                @foreach($cate as $c)
                    <option value="{{$c->id}}"
                            @if($c->id==$cate_id) selected @endif
                    >{{$c->title}}</option>
                @endforeach
            </select>

            <input type="text" id="search" name="search" value=""><input type="button" onclick="searchbynam()" value="搜索(物品名)">
        </div>

        <br>
        <div class="short_wrap">
            <a href="{{url('admin/shop/create')}}"><i class="fa fa-plus"></i>添加宝贝</a>
        </div>
    </div>
    <!--快捷导航 结束-->
</div>
<form action="#" method="post">
    <div class="result_wrap">
        <div class="result_content">
            <table class="list_tab">
                <tr>
                    <th class="tc" width="5%">ID</th>
                    <th class="tc" width="5%">活动物品</th>
                    <th class="tc" width="5%">主页显示</th>
                    <th>主图片</th>
                    <th>名称</th>
                    <th>类别</th>
                    <th>原价</th>
                    <th>现价</th>
                    <th>库存</th>
                    <th>购买人数</th>
                    <th>操作</th>
                </tr>
                @foreach($data as $v)
                    <tr>
                        <td class="tc">{{$v->id}}</td>
                        <td>{{$v->activity}}</td>
                        <td>{{$v->showindex}}</td>
                        <td><img src="{{asset('uploads/'.$v->indeximg)}}" style="width: 50px; height: 50px"/></td>
                        <td><a target=_blank" href="{{url('/').'#/tabs/iteminfo/?itemID='.$v->id}}">{{$v->name}}</a></td>
                        <td>{{$v->category}}</td>
                        <td>{{$v->prime_price}}</td>
                        <td>{{$v->cur_price}}</td>
                        <td>{{$v->stock}}</td>
                        <td>{{$v->buynum}}</td>
                        <td>
                            <a href="{{url('admin/shop/'.$v->id.'/edit')}}">修改</a>
                            <a href="javascript:;" onclick="delShop({{$v->id}})">删除</a>
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
    //删除分类
    function delShop(item_id) {
        layer.confirm('您确定要删除该商品吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.post("{{url('admin/shop/')}}/"+item_id,{'_method':'delete','_token':"{{csrf_token()}}"},function (data) {
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
    
    function searchbycate(obj) {
        window.location.href='{{url('admin/shop/searchbycate')}}/'+obj.value+'';
    }
    
    function searchbynam() {
        var inputVal = document.getElementById('search').value;
        if (0 == inputVal.length){
            window.location.href='{{url('admin/shop')}}';
            return;
        }

        window.location.href='{{url('admin/shop/searchbyname')}}/'+inputVal+'';
    }

</script>

@endsection
