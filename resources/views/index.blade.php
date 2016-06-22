<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <link href="{{asset('resources/views/ionic/css/ionic.min.css')}}" rel="stylesheet">

    <script src="{{asset('resources/views/ionic/js/ionic.bundle.min.js')}}"></script>
    <script src="{{asset('resources/views/js/jquery-1.12.4.js')}}"></script>
    <script src="{{asset('resources/views/js/lazyload.js')}}"></script>
    <script src="{{asset('resources/views/js/layer.js')}}"></script>
    <script src="{{asset('resources/views/js/index.js')}}"></script>

    <title>{{$config->title}}</title>
</head>
<body ng-app="ionicApp">

<ion-nav-view></ion-nav-view>

<!--左侧菜单-->
<script id="templates/menu.html" type="text/ng-template">
    <ion-side-menus ng-controller="menuController">

        <ion-side-menu-content>
            <ion-nav-bar class="bar-calm">
                <ion-nav-buttons side="left">
                    <button class="button button-icon button-clear ion-navicon" menu-toggle="left">
                    </button>
                </ion-nav-buttons>
            </ion-nav-bar>
            <ion-nav-view name="menu"></ion-nav-view>
        </ion-side-menu-content>

        <ion-side-menu side="left">
            <ion-header-bar class="bar-calm">
                <!--<h1 class="title">Left Menu</h1>-->
                <div class="buttons pull-center" nav-clear menu-close style="width: 100%">
                    <img style="width: 100%" src="{{asset('resources/views/sysimg/logo.png')}}"/>
                </div>
            </ion-header-bar>
            <ion-content class = "has-header">
                <ion-list>
                    <ion-item nav-clear menu-close
                              ng-repeat="Category in Categorys"
                              ui-sref="menu.tabs.category({categoryID: [[Category.id]], categoryNam: '[[Category.title]]'})">
                        [[Category.title]]
                    </ion-item>
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
    <ion-view view-title="{{$config->title}}" ng-controller="homeController">
        <ion-content  scroll="true" overflow-scroll="true">
            <!-- 轮播  至少要3个图...不然ion-slide-box 有 bug... -->
            <ion-slide-box auto-play="true" does-continue="true" slide-interval=2000 pager-click="go(index)"
                           delegate-handle="delegateHandler">
                <ion-slide ng-repeat="actItem in homeData.activityItem">
                    <img ng-src = "{{asset('uploads')}}/[[actItem.indeximg]]"
                         ui-sref="menu.tabs.iteminfo({itemID: [[actItem.id]], itemNam: '[[actItem.name]]'})"
                         style="width: 100%; height: [[imgHeight]]">
                </ion-slide>
            </ion-slide-box>

            <!--首页物品展示-->
            <div class="row">
                <div class="col col-44">1111</div>
                <div class="col col-44">2222</div>
                <div class="col col-44">333</div>
                <div class="col col-44">44444</div>
            </div>
        </ion-content>
    </ion-view>
</script>

<script id="templates/category.html" type="text/ng-template">
    <ion-view view-title="[[categoryNam]]" ng-controller="categoryController">
        <ion-content  scroll="true" overflow-scroll="true">


        </ion-content>
    </ion-view>
</script>

<script id="templates/iteminfo.html" type="text/ng-template">
    <ion-view view-title="[[itemNam]]" ng-controller="iteminfoController">
        <ion-nav-bar class="bar-calm">
            <ion-nav-buttons side="left">
                <button class="button button-icon button-clear ion-chevron-left" ng-click="goBack()">
                </button>
            </ion-nav-buttons>
        </ion-nav-bar>

        <ion-content  scroll="true" overflow-scroll="true" >
            <ion-slide-box auto-play="true" does-continue="true" slide-interval=2000 pager-click="go(index)"
                           delegate-handle="delegateHandler">
                <ion-slide ng-repeat="actItem in homeData.activityItem">
                    <img ng-src = "{{asset('uploads')}}"
                         ui-sref="menu.tabs.iteminfo({itemID: [[actItem.id]], itemNam: '[[actItem.name]]'})"
                         style="width: 100%; height: [[imgHeight]]">
                </ion-slide>
            </ion-slide-box>

        </ion-content>
    </ion-view>
</script>

<script id="templates/user.html" type="text/ng-template">
    <ion-view view-title="我的{{$config->title}}" ng-controller="uerCenterController">
        <ion-content  scroll="true" overflow-scroll="true">

        </ion-content>
    </ion-view>
</script>

<script id="templates/car.html" type="text/ng-template">
    <ion-view view-title="购物车" ng-controller="carController">
        <ion-content  scroll="true" overflow-scroll="true">

        </ion-content>
    </ion-view>
</script>

</body>
</html>
