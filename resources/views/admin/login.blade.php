<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="{{asset('resources/views/admin/style/css/ch-ui.admin.css')}}">
	<link rel="stylesheet" href="{{asset('resources/views/admin/style/font/css/font-awesome.min.css')}}">
	<title>微商城后台管理</title>
</head>
<body style="background:#F3F3F4;">
	<div class="login_box">
		<h1>微商城</h1>
		<h2>欢迎使用微商城管理平台</h2>
		<div class="form">
			<!--
			web中间件从5.2.27版本以后默认全局加载，不需要自己手动载入，如果自己手动重复载入，会导致session无法加载的情况
			php.ini 中设置 session.auto_start = 1
			-->
			@if(session('msg'))
			<p style="color:red">{{session('msg')}}</p>
			@endif

			<form action="" method="post">
				{{csrf_field()}}
				<ul>
					<li>
					<input type="text" name="user_name" class="text"/>
						<span><i class="fa fa-user"></i></span>
					</li>
					<li>
						<input type="password" name="user_pass" class="text"/>
						<span><i class="fa fa-lock"></i></span>
					</li>
					<li>
						<input type="text" class="code" name="code"/>
						<span><i class="fa fa-check-square-o"></i></span>
						<img src="{{url('admin/verificationCode')}}" alt="" onclick="this.src='{{url('admin/verificationCode')}}?'+Math.random()">
					</li>
					<li>
						<input type="submit" value="立即登陆"/>
					</li>
				</ul>
			</form>
			<p>&copy; 2016 Powered by lqf</p>
		</div>
	</div>
</body>
</html>