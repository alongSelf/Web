<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <base href="/" />
        <meta name="_token" content="{{ csrf_token()}}"/>
        <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
        <link rel="stylesheet" href="resources/views/ionic/css/ionic.min.css" type="text/css" charset="utf-8">
        <link rel="stylesheet" href="resources/views/css/app.css" type="text/css" charset="utf-8">

        <script src="resources/views/ionic/js/ionic.bundle.min.js"></script>
        <script src="resources/views/ionic/js/angular/angular-cookies.min.js"></script>
        <script src="resources/views/ionic/js/ionic-image-lazy-load.js"></script>
        <script src="http://libs.baidu.com/jquery/1.10.2/jquery.min.js"></script>
        <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>

        <script src="resources/views/js/utile.js"></script>
        <script src="resources/views/js/app.js"></script>
        <script src="resources/views/js/filter.js"></script>
        <script src="resources/views/js/server.js"></script>
        <script src="resources/views/js/shopcontroller.js"></script>
        <script src="resources/views/js/usercontroller.js"></script>
        <script src="resources/views/js/spreadcontroller.js"></script>
        <script src="resources/views/js/paycontroller.js"></script>

        <script src="resources/views/js/layer.js"></script>

        <title></title>
    </head>

    <body ng-app="ionicApp" class="st_body">
        <?php
            $info = [
                'state'=>$state
            ];
            setcookie('state', json_encode($info));
        ?>
        <ion-nav-view class="st_mainview">
        </ion-nav-view>
    </body>
</html>
