<html ng-app="ionicApp">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <link href="{{asset('resources/views/ionic/css/ionic.min.css')}}" rel="stylesheet">
    <script src="{{asset('resources/views/ionic/js/ionic.bundle.min.js')}}"></script>
    <script src="{{asset('resources/views/js/index.js')}}"></script>

    <title>首页</title>
</head>
<body>

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
                <div class="buttons pull-center" nav-clear menu-close>
                    <img src="{{asset('resources/views/sysimg/Tool_Animation_Icon_LANShare.png')}}"/>
                </div>
            </ion-header-bar>
            <ion-content class = "has-header">
                <ion-list>
                    <img ui-sref="menu.tabs.home" src="{{asset('resources/views/sysimg/Tool_Animation_Icon_LANShare.png')}}"/>
                    <ion-item nav-clear menu-close ui-sref="menu.tabs.search">Search</ion-item>
                    <ion-item nav-clear menu-close ui-sref="menu.tabs.about">About</ion-item>
                </ion-list>
            </ion-content>
        </ion-side-menu>
    </ion-side-menus>
</script>

<script id="templates/tabs.html" type="text/ng-template">
    <ion-view view-title="Welcome">
        <ion-tabs class="tabs-icon-top tabs-positive">

            <ion-tab title="Search" icon="ion-search" ui-sref="menu.tabs.search">
                <ion-nav-view name="search-tab"></ion-nav-view>
            </ion-tab>

            <ion-tab title="About" icon="ion-information-circled" ui-sref="menu.tabs.about">
                <ion-nav-view name="about-tab"></ion-nav-view>
            </ion-tab>
            <ion-tab title="Home" icon="ion-information-circled" ui-sref="menu.tabs.home" hidden = "true">
                <ion-nav-view name="home-tab"></ion-nav-view>
            </ion-tab>
        </ion-tabs>
    </ion-view>
</script>

<script id="templates/home.html" type="text/ng-template">
    <ion-view view-title="home Page">
        <ion-content>
            <div>
                Main Content goes here
            </div>
        </ion-content>
    </ion-view>
</script>

<script id="templates/search.html" type="text/ng-template">
    <ion-view view-title="search">
        <ion-content class="padding">
            search content
        </ion-content>
    </ion-view>
</script>

<script id="templates/about.html" type="text/ng-template">
    <ion-view view-title="about">
        <ion-content class="padding">
            about content
        </ion-content>
    </ion-view>
</script>


</body>
</html>
