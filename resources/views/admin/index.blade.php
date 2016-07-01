@extends('admin.layouts')
@section('content')
		<!--头部 开始-->
<div class="top_box">
	<div class="top_left">
		<div class="logo">微商城后台管理</div>
		<ul>
			<li><a href="{{url('/')}}" target="_blank" class="active">首页</a></li>
			<li><a href="{{url('admin/info')}}" target="main">管理页</a></li>
		</ul>
	</div>
	<div class="top_right">
		<ul>
			<li>管理员：{{$uerNam}}</li>
			<li><a href="{{url('admin/pass')}}" target="main">修改密码</a></li>
			<li><a href="{{url('admin/quit')}}">退出</a></li>
		</ul>
	</div>
</div>
<!--头部 结束-->

<!--左侧导航 开始-->
<div class="menu_box">
	<ul>
		<li>
			<h3><i class="fa fa-fw fa-shopping-cart"></i>宝贝管理</h3>
			<ul class="sub_menu">
				<li><a href="{{url('admin/category/create')}}" target="main"><i class="fa fa-fw fa-plus-square"></i>添加分类</a></li>
				<li><a href="{{url('admin/category')}}" target="main"><i class="fa fa-fw fa-list-ul"></i>分类列表</a></li>
				<li><a href="{{url('admin/article/create')}}" target="main"><i class="fa fa-fw fa-plus-square"></i>添加宝贝</a></li>
				<li><a href="{{url('admin/article')}}" target="main"><i class="fa fa-fw fa-list-ul"></i>宝贝列表</a></li>
			</ul>
		</li>
		<li>
			<h3><i class="fa fa-fw fa-user"></i>用户管理</h3>
			<ul class="sub_menu" style="display: block;">
				<li><a href="{{url('admin/links')}}" target="main"><i class="fa fa-fw fa-list-ul"></i>用户列表</a></li>
			</ul>
		</li>

		<li>
			<h3><i class="fa fa-fw fa-credit-card"></i>订单管理</h3>
			<ul class="sub_menu" style="display: block;">
				<li><a href="{{url('admin/links')}}" target="main"><i class="fa fa-fw fa-list-ul"></i>订单列表</a></li>
			</ul>
		</li>
	</ul>
</div>
<!--左侧导航 结束-->

<!--主体部分 开始-->
<div class="main_box">
	<iframe src="{{url('admin/info')}}" frameborder="0" width="100%" height="100%" name="main"></iframe>
</div>
<!--主体部分 结束-->

<!--底部 开始-->
<div class="bottom_box">
	CopyRight © 2016. Powered By lqf
</div>
<!--底部 结束-->

@endsection

