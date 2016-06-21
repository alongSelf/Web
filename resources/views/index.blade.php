<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <link href="{{asset('resources/views/ionic/css/ionic.min.css')}}" rel="stylesheet">
    <script src="{{asset('resources/views/ionic/js/ionic.bundle.min.js')}}"></script>
    <script src="{{asset('resources/views/js/index.js')}}"></script>

    <title>{{$config->title}}</title>
</head>
<body ng-app="ionicApp">

<ion-nav-view></ion-nav-view>

<!--左侧菜单-->
<script id="templates/menu.html" type="text/ng-template">
    <ion-side-menus>

        <ion-side-menu-content>
            <ion-nav-bar class="bar-calm">
                <ion-nav-back-button>
                </ion-nav-back-button>

                <ion-nav-buttons side="left">
                    <button class="button button-icon button-clear ion-navicon" menu-toggle="left">
                    </button>
                </ion-nav-buttons>
            </ion-nav-bar>
            <ion-nav-view name="menu"></ion-nav-view>
        </ion-side-menu-content>

        <ion-side-menu side="left">
            <ion-header-bar class="bar-dark">
                <!--<h1 class="title">Left Menu</h1>-->
                <div class="buttons pull-center" nav-clear menu-close style="width: 100%">
                    <img style="width: 100%" src="{{asset('resources/views/sysimg/logo.png')}}"/>
                </div>
            </ion-header-bar>
            <ion-content class = "has-header">
                <ion-list>
                    @foreach($category as $val)
                        <ion-item nav-clear menu-close ui-sref="menu.tabs.category({categoryID: {{$val->id}}, categoryNam: '{{$val->title}}'})">
                            {{$val->title}}
                        </ion-item>
                    @endforeach
                </ion-list>
            </ion-content>
        </ion-side-menu>
    </ion-side-menus>
</script>

<script id="templates/tabs.html" type="text/ng-template">
    <ion-view view-title="{{$config->title}}">
        <ion-tabs class="tabs-icon-top tabs-positive">
            <ion-tab title="{{$config->title}}" icon="ion-ios-home" ui-sref="menu.tabs.home">
                <ion-nav-view name="home-tab"></ion-nav-view>
            </ion-tab>

            <ion-tab title="购物车" icon="ion-ios-cart" ui-sref="menu.tabs.car">
                <ion-nav-view name="car-tab"></ion-nav-view>
            </ion-tab>

            <ion-tab title="我的{{$config->title}}" icon="ion-android-contact" ui-sref="menu.tabs.user">
                <ion-nav-view name="user-tab"></ion-nav-view>
            </ion-tab>

            <ion-tab title="分类" ui-sref="menu.tabs.category" hidden="true">
                <ion-nav-view title="分类" name="category-tab"></ion-nav-view>
            </ion-tab>

            <ion-tab title="物品详情" ui-sref="menu.tabs.iteminfo" hidden="true">
                <ion-nav-view title="物品详情" name="iteminfo-tab"></ion-nav-view>
            </ion-tab>
        </ion-tabs>
    </ion-view>
</script>

<script id="templates/home.html" type="text/ng-template">
    <ion-view view-title="{{$config->title}}">
        <ion-content  scroll="true" overflow-scroll="true">
            @include('home')
        </ion-content>
    </ion-view>
</script>

<script id="templates/category.html" type="text/ng-template">
    <ion-view view-title="[[categoryNam]]" ng-controller="categoryController">
        <ion-content  scroll="true" overflow-scroll="true">
            <div nav-clear menu-close ui-sref="menu.tabs.iteminfo({itemID: [[categoryID]], itemNam: '商品[[categoryNam]]'})">商品分类 [[categoryID]]</div>
        </ion-content>
    </ion-view>
</script>

<script id="templates/iteminfo.html" type="text/ng-template">
    <ion-view view-title="[[itemNam]]" ng-controller="iteminfoController">
        <ion-content  scroll="true" overflow-scroll="true" >
            <div nav-clear menu-close >商品详情 [[itemID]]  [[itemNam]]</div>
        </ion-content>
    </ion-view>
</script>

<script id="templates/user.html" type="text/ng-template">
    <ion-view view-title="我的{{$config->title}}">
        <ion-content  scroll="true" overflow-scroll="true">
            @include('user')
        </ion-content>
    </ion-view>
</script>

<script id="templates/car.html" type="text/ng-template">
    <ion-view view-title="购物车">
        <ion-content  scroll="true" overflow-scroll="true">
            @include('car')
        </ion-content>
    </ion-view>
</script>

</body>
</html>
