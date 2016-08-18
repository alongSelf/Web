<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <link rel="stylesheet" href="{{asset('resources/views/css/app.css')}}" type="text/css" charset="utf-8">
    <title>{{$sharetitle}}</title>
</head>
<body class="st_body">
    <div style="text-align: center">
        <h1>{{$sharetitle}}</h1>
        <h4>{{$sharememo}}</h4>
        <img src="{{asset($QRC)}}" style="width: 200px; height: 200px">
        <div>长按二维码,微信扫码关注</div>
        <div>更多精彩等你来发现。</div>
    </div>
</body>
</html>