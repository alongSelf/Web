<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{asset('resources/views/admin/style/css/ch-ui.admin.css')}}">
    <link rel="stylesheet" href="{{asset('resources/views/admin/style/font/css/font-awesome.min.css')}}">
    <script type="text/javascript" src="{{asset('resources/views/js/jquery-1.12.4.js')}}"></script>
    <script type="text/javascript" src="{{asset('resources/views/admin/style/js/ch-ui.admin.js')}}"></script>
    <script type="text/javascript" src="{{asset('resources/views/js/layer.js')}}"></script>
</head>
<body>
<div class="result_wrap">
    <table class="add_tab">
        <tbody>
        <tr>
            <th>用户ID</th>
            <td>
                {{$data->id}}
            </td>
            <th>第三方ID</th>
            <td>
                {{$data->unionid}}
            </td>
        </tr>
        <tr>
            <th>昵称</th>
            <td>
                {{$data->nickname}}
            </td>
            <th>头像</th>
            <td>
                <img src="{{asset('uploads/'.$data->icon)}}" style="width: 50px; height: 50px;border-radius:50%">
            </td>
        </tr>
        <tr>
            <th>姓名</th>
            <td>
                {{$data->name}}
            </td>
            <th>电话</th>
            <td>
                {{$data->phone}}
            </td>
        </tr>
        <tr>
            <th>Email</th>
            <td>
                {{$data->email}}
            </td>
            <th>微信号</th>
            <td>
                {{$data->weixnumber}}
            </td>
        </tr>
        <tr>
            <th>QQ号</th>
            <td>
                {{$data->qq}}
            </td>
            <th>总消费(元)</th>
            <td>
                {{$data->consume}}
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>