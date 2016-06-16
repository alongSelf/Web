<html ng-app="ionicApp">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">

    <title>ionic Nested Tabs with Side Menus</title>

    <link href="http://code.ionicframework.com/nightly/css/ionic.min.css" rel="stylesheet">
    <script src="http://code.ionicframework.com/nightly/js/ionic.bundle.min.js"></script>
    <script src="{{asset('resources/views/js/index.js')}}"></script>

</head>
<body>

<ion-nav-view></ion-nav-view>

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
                <h1 class="title">Left Menu</h1>
            </ion-header-bar>
            <ion-content class = "has-header">
                <ion-list>
                    <ion-item nav-clear menu-close ui-sref="menu.tabs.home">Home</ion-item>
                    <ion-item nav-clear menu-close ui-sref="menu.tabs.search">Search</ion-item>
                    <ion-item nav-clear menu-close ui-sref="menu.tabs.about.first">About</ion-item>
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

            <ion-tab title="About" icon="ion-information-circled" ui-sref="menu.tabs.about.first">
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

            <div class = "card">
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
    <ion-view view-title="About">
        <ion-content class="" scroll="false">
            <div class = "">
                <div class="item tabs tabs-secondary tabs-icon-top">
                    <a class="tab-item" ui-sref="menu.tabs.about.first">
                        <i class="icon ion-star"></i>
                        First
                    </a>
                    <a class="tab-item" ui-sref="menu.tabs.about.second">
                        <i class="icon ion-star"></i>
                        second
                    </a>
                    <a class="tab-item" ui-sref="menu.tabs.about.third">
                        <i class="icon ion-star"></i>
                        Third
                    </a>

                </div>
            </div>

        </ion-content>
        <ui-view name = "about-sub"></ui-view>

    </ion-view>
</script>

<!-- <ion-nav-view name = "first"></ion-nav-view> -->
<script id="templates/first.html" type="text/ng-template">
    <ion-view title="First" >
        <ion-content class="padding has-tabs-top">
            First content
        </ion-content>
    </ion-view>
</script>

<script id="templates/second.html" type="text/ng-template">
    <ion-view view-title="Second">
        <ion-content class="padding has-tabs-top">
            Second content
        </ion-content>
    </ion-view>
</script>
<script id="templates/third.html" type="text/ng-template">
    <ion-view view-title="Third">
        <ion-content class="padding has-tabs-top">
            Third content
        </ion-content>
    </ion-view>
</script>

</body>
</html>
