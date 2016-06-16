<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet"  media="screen" href="{{asset('resources/views/css/comm.css')}}">
        <link rel="stylesheet"  media="screen" href="{{asset('resources/views/css/bootstrap.min.css')}}">

        <script type="text/javascript" src="{{asset('resources/views/js/jquery.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('resources/views/js/layer.js')}}"></script>
        <script type="text/javascript" src="{{asset('resources/views/js/lazyload.js')}}"></script>
        <script type="text/javascript" src="{{asset('resources/views/js/bootstrap.min.js')}}"></script>

        @yield('head')
    </head>

    <body class="body">
        @yield('content')
    </body>

    <!--底部菜单-->
    <div class="footer">
        @include('footer')
    </div>

    <script type="text/javascript">
        /*引用懒加载*/
        $(document).ready(function (e) {
            $("img.lazy").lazyload(
             {
                effect: "fadeIn"
             });
        });

        $(function(){
            /*启动轮播*/
            $("#homeCarousel").carousel({
                interval:"3000"
            });
            /*轮播根据设备*/
            var screen_width=document.documentElement.clientWidth;
            var screen_height=document.documentElement.clientHeight;
            $(".carousel-inner img").css({"width":screen_width});
            $(".carousel-inner img").css({"height":screen_height * 0.3});
        });
    </script>

    @yield('script')
</html>
