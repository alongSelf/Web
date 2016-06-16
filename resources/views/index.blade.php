@extends('homelayouts')

@section('head')
    <title>首页</title>
    <meta name="keywords" content="key" />
    <meta name="description" content="description" />
@endsection

@section('content')
    <!--图片轮播-->
    <div class="carousel slide" id="homeCarousel">
        <ol class="carousel-indicators">
            <li class='active' data-slide-to="1" data-target="#homeCarousel"></li>
            <li data-slide-to="2" data-target="#homeCarousel"></li>
        </ol>
        <div class="carousel-inner">
                <div class='item active'>
                    <img class="lazy" src="{{asset('uploads/homeSlide-01.jpg')}}" alt="First slide" />
                </div>
                <div class='item'>
                    <img class="lazy" src="{{asset('uploads/homeSlide-02.jpg')}}" alt="Second slide" />
                </div>
        </div>
        <!-- 轮播（Carousel）导航 -->
        <a class="carousel-control left" href="#homeCarousel"
           data-slide="prev">&lsaquo;</a>
        <a class="carousel-control right" href="#homeCarousel"
           data-slide="next">&rsaquo;</a>
    </div>

    <!--导航-->
    <div style="height: 15%; width: 100%">
        @include('menu')
    </div>

    <!--正文-->
    <div style="width: 100%">
        正文
    </div>
@endsection

@section('script')
@endsection
