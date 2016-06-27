<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <link href="{{asset('resources/views/ionic/css/ionic.min.css')}}" rel="stylesheet">
    <link href="{{asset('resources/views/css/app.css')}}" rel="stylesheet">

    <script src="{{asset('resources/views/ionic/js/ionic.bundle.min.js')}}"></script>
    <script src="{{asset('resources/views/js/jquery-1.12.4.js')}}"></script>
    <script src="{{asset('resources/views/js/lazyload.js')}}"></script>
    <script src="{{asset('resources/views/js/layer.js')}}"></script>

    <script src="{{asset('resources/views/ionic/js/angular/angular-cookies.min.js')}}"></script>

    <script src="{{asset('resources/views/js/utile.js')}}"></script>
    <script src="{{asset('resources/views/js/app.js')}}"></script>
    <script src="{{asset('resources/views/js/filter.js')}}"></script>
    <script src="{{asset('resources/views/js/server.js')}}"></script>
    <script src="{{asset('resources/views/js/controller.js')}}"></script>

    <title></title>
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

        <ion-side-menu side="left" width= "[[(clientWidth/5) * 2]]">
            <ion-header-bar class="bar-calm">
                <!--<h1 class="title">Left Menu</h1>-->
                <div class="buttons pull-center" nav-clear menu-close style="width: 100%">
                    <img style="width: 100%" src="{{asset('resources/views/sysimg/logo.png')}}"/>
                </div>
            </ion-header-bar>
            <ion-content class = "has-header">
                <ion-list>
                    <ion-item ion-item nav-clear menu-close class="item-avatar"
                              ng-repeat="Category in Categorys"
                              ui-sref="menu.tabs.category({categoryID: [[Category.id]], categoryNam: '[[Category.title]]'})" ng-cloak>
                        <img class="lazy" ng-src="{{asset('uploads')}}/[[Category.img]]">
                        <br/>
                        <div style="margin-bottom: 0">[[Category.title]]</div>
                    </ion-item>
                </ion-list>
            </ion-content>
        </ion-side-menu>
    </ion-side-menus>
</script>

<script id="templates/tabs.html" type="text/ng-template">
    <ion-view view-title="首页">
        <ion-tabs class="tabs-icon-top tabs-positive">
            <ion-tab title="首页" icon="ion-ios-home" ui-sref="menu.tabs.home">
                <ion-nav-view name="home-tab"></ion-nav-view>
            </ion-tab>

            <ion-tab title="购物车" icon="ion-ios-cart" ui-sref="menu.tabs.car" badge="carItemNum" badge-style="badge-assertive">
                <ion-nav-view name="car-tab"></ion-nav-view>
            </ion-tab>

            <ion-tab title="我的" icon="ion-android-contact" ui-sref="menu.tabs.user">
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
    <ion-view view-title="首页" ng-controller="homeController">
        <ion-content  scroll="true" overflow-scroll="true">
            <!-- 轮播  至少要3个图...不然ion-slide-box 有 bug... -->
            <ion-slide-box auto-play="true" does-continue="true" slide-interval=2000 pager-click="go(index)"
                           delegate-handle="delegateHandler">
                <ion-slide ng-repeat="actItem in activityItem">
                    <img ng-src = "{{asset('uploads')}}/[[actItem.indeximg]]"
                         ui-sref="menu.tabs.iteminfo({itemID: [[actItem.id]]})"
                         style="width: 100%; height: [[imgHeight]]" class="lazy">
                </ion-slide>
            </ion-slide-box>

            <!--首页物品展示-->
            <div ng-bind-html="homeItemList | trustHtml"/>
        </ion-content>
    </ion-view>
</script>

<script id="templates/category.html" type="text/ng-template">
    <ion-view view-title="[[categoryNam]]" ng-controller="categoryController">
        <ion-content  scroll="true" overflow-scroll="true">
            <!--分类物品展示-->
            <div ng-bind-html="categoryItemList | trustHtml"/>
        </ion-content>
    </ion-view>
</script>

<script id="templates/iteminfo.html" type="text/ng-template">
    <ion-view view-title="[[itemInfo.name]]" ng-controller="iteminfoController">
        <!--返回-->
        <ion-nav-bar class="bar-calm">
            <ion-nav-buttons side="left">
                <button class="button button-icon button-clear ion-chevron-left" ng-click="goBack()">
                </button>
            </ion-nav-buttons>
        </ion-nav-bar>

        <ion-content  scroll="true" overflow-scroll="true" >
            <ion-slide-box auto-play="true" does-continue="true" slide-interval=2000 pager-click="go(index)"
                           delegate-handle="delegateHandler">
                <ion-slide ng-repeat="strImg in slideImg">
                    <img ng-src = "{{asset('uploads')}}/[[strImg]]"
                         style="width: 100%; height: [[imgHeight]]" class="lazy">
                </ion-slide>
            </ion-slide-box>

            <!--物品信息-->
            <div style="padding-left:15px; padding-right: 15px;">
                <div>
                    <span style="font-weight:bold" ng-cloak>[[itemInfo.describe]]</span>
                </div>
                <div>
                    <span>
                        <em style="color: red" ng-cloak>[[cur_price]]&nbsp&nbsp</em>
                        <em style="text-decoration:line-through; color: #adadad" ng-cloak>[[itemInfo.prime_price | currency:"￥"]]</em>
                        <em ng-cloak>&nbsp&nbsp&nbsp&nbsp[[buynum]]</em>
                    </span>
                </div>
                <br/>

                <div ng-bind-html="itemInfo.content | trustHtml"/>
            </div>

        </ion-content>

        <ion-footer-bar align-title="left">
            <button class="button button-balanced" style="width: 49%;" ng-click="buy(itemInfo.id, itemInfo.name, itemInfo.cur_price, itemInfo.indeximg, itemSpec)">
                立即购买
            </button>
            <button class="button button-calm" style="width: 49%;" ng-click="addInCar(itemInfo.id, itemInfo.name, itemInfo.cur_price, itemInfo.indeximg, itemSpec)">
                加入购物车
            </button>
        </ion-footer-bar>

    </ion-view>
</script>

<script id="templates/user.html" type="text/ng-template">
    <ion-view view-title="我的" ng-controller="uerCenterController">
        <ion-content  scroll="true" overflow-scroll="true">
            <div class="visible-print text-center">
                {!! QrCode::size(100)->generate('http://192.168.0.234/#/menu/tabs/home')!!}
                <p>{{Request::url('')}}</p>
            </div>

            <ion-list>
                <div ng-repeat="group in groups">
                    <ion-item class="item-stable"
                              ng-click="toggleGroup(group)"
                              ng-class="{active: isGroupShown(group)}">
                        <i class="icon" ng-class="isGroupShown(group) ? 'ion-minus' : 'ion-plus'"></i>
                        &nbsp;
                        Group [[group.name]]
                    </ion-item>
                    <ion-item class="item-accordion"
                              ng-repeat="item in group.items"
                              ng-show="isGroupShown(group)">
                        [[item]]
                    </ion-item>
                </div>
            </ion-list>

        </ion-content>
    </ion-view>
</script>

<script id="templates/car.html" type="text/ng-template">
    <ion-view view-title="购物车" ng-controller="carController">
        <ion-content  scroll="true" overflow-scroll="true">
            <div class="carbox" ng-if="showCarInfo">
                <table id="cartTable">
                    <thead>
                        <tr>
                            <th>商品</th>
                            <th>单价</th>
                            <th>数量</th>
                            <th>小计</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="item in itemInCar">
                            <td class="goods">
                                <span ui-sref="menu.tabs.iteminfo({itemID: [[item.id]]})">[[item.name]]</span>
                                <span style="color: #adadad">[[item.spec]]</span>
                            </td>
                            <td class="price">
                                [[item.price | currency:'￥']]
                            </td>
                            <td class="count">
                                <input type="text" style="width: 100%;" ng-model="item.num" ng-change="numChange(item.id, item.num)"/>
                            <td class="subtotal">[[item.num * item.price | currency:'￥']]</td>
                            <td class="operation">
                                 <span ng-click="delete(item.id)" style="color:red">删除</span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="foot" id="foot">
                    <div class="fr total">合计：<span id="priceTotal">[[priceTotal | currency:'￥']]</span></div>
                </div>
            </div>

            <div class="fc" ng-if="!showCarInfo">
                <img src="{{asset('resources/views/sysimg/emptycar.png')}}" />
            </div>

        </ion-content>

        <ion-footer-bar align-title="left">
            <button class="button button-calm" style="width: 49%;" ng-click="checkout()">
                结算
            </button>
            <button class="button button-energized" style="width: 49%;" ng-click="clear()">
                清空
            </button>
        </ion-footer-bar>

    </ion-view>
</script>

<script type="text/javascript">
    /*引用懒加载*/
    $("img.lazy").lazyload({
        threshold : 100,
        effect : "fadeIn"
    });
</script>

</body>
</html>
