'use strict';

var appModule = angular.module('ionicApp.controller', ['ionicApp.server']);

//主页
appModule.controller('homeController',['$scope', '$http', '$ionicSlideBoxDelegate', '$sce', '$timeout', '$window', function($scope, $http, $ionicSlideBoxDelegate, $sce, $timeout, $window){
    $scope.activityItem = [];
    $scope.itemList = [];
    $scope.Page = 0;
    $scope.moreData = true;
    $scope.innerWidth = $window.innerWidth > 850 ? 850: $window.innerWidth;

    $scope.doRefresh = function () {
        $http.get("indexItem")
            .success(
                function (data, status, header, config) {
                    $scope.Page = 0;
                    $scope.moreData = true;

                    $scope.activityItem = data.activityItem;
                    $scope.itemList = makeItemList(data.homeItem, $scope.innerWidth);
                    $scope.Notice = data.notice.notice;

                    //更新轮播
                    $ionicSlideBoxDelegate.$getByHandle('delegateHandler').update();
                    $ionicSlideBoxDelegate.$getByHandle('delegateHandler').loop(true);
                }
            ).error(
            function (data) {
                onError(data);
            }).finally(function () {
                // 停止广播ion-refresher
                $scope.$broadcast('scroll.refreshComplete');
            }
        );
    };

    $scope.doRefresh();

    $scope.loadMore = function () {
        $scope.Page++;
        $http.get("loadMoreIndexItem/" + $scope.Page)
            .success(
                function (data, status, header, config) {
                    if (0 == data.length){
                        $scope.moreData = false;
                    }else {
                        appendItemList($scope.itemList, data, $scope.innerWidth);
                    }
                }
            ).error(
            function (data) {
                onError(data);
                $scope.Page--;
            }).finally(function () {
                $scope.$broadcast('scroll.infiniteScrollComplete');
            }
        );
    };

    $scope.index = 0;
    $scope.go = function(index){
        $ionicSlideBoxDelegate.$getByHandle('delegateHandler').slide(index);
    };

    //页面切换后轮播可以继续
    $scope.$on('$ionicView.beforeEnter',function(){
        $ionicSlideBoxDelegate.$getByHandle('delegateHandler').start();
    })
}]);

//分类商品展示
appModule.controller('categoryController',['$scope','$stateParams', '$http', '$window', '$ionicHistory', function($scope, $stateParams, $http, $window, $ionicHistory){
    $scope.categoryID = $stateParams.categoryID;
    $scope.categoryNam = $stateParams.categoryNam;
    $scope.Page = 0;
    $scope.moreData = true;
    $scope.showBuild = false;
    $scope.innerWidth = $window.innerWidth > 850 ? 850: $window.innerWidth;

    $scope.doRefresh = function () {
        $http.get("categoryInfo/" + $scope.categoryID + '/'+ $scope.Page)
            .success(
                function(data, status, header, config){
                    $scope.Page = 0;
                    $scope.moreData = true;

                    $scope.itemList = makeItemList(data, $scope.innerWidth);
                    if (0 == $scope.itemList.length){
                        $scope.showBuild = true;
                    }
                    else{
                        $scope.showBuild = false;
                    }
                }
            ).error(
            function(data){
                onError(data);
            }).finally(function() {
                // 停止广播ion-refresher
                $scope.$broadcast('scroll.refreshComplete');
            }
        );
    };

    $scope.doRefresh();

    $scope.loadMore = function () {
        $scope.Page++;
        $http.get("categoryInfo/" + $scope.categoryID + '/'+ $scope.Page)
            .success(
                function (data, status, header, config) {
                    if (0 == data.length){
                        $scope.moreData = false;
                    }else {
                        appendItemList($scope.itemList, data, $scope.innerWidth);
                    }

                    if (0 == $scope.itemList.length){
                        $scope.showBuild = true;
                    }
                    else{
                        $scope.showBuild = false;
                    }
                }
            ).error(
            function (data) {
                onError(data);
                $scope.Page--;
            }).finally(function () {
                $scope.$broadcast('scroll.infiniteScrollComplete');
            }
        );
    };

    $scope.goBack = function () {
        $ionicHistory.goBack();
    };
}]);

//物品详情
appModule.controller('iteminfoController', ['$scope','$stateParams', '$ionicHistory', '$ionicSlideBoxDelegate', '$http', '$sce', '$ionicPopover', '$cookieStore', 'carItemNumFactory', function($scope, $stateParams, $ionicHistory, $ionicSlideBoxDelegate, $http, $sce, $ionicPopover, $cookieStore, carItemNumFactory){
    $scope.itemID = $stateParams.itemID;
    $scope.itemInfo = [];
    $scope.slideImg = [];
    $scope.cur_price = '';
    $scope.buynum = '';
    $scope.isCancel = true;
    $scope.isBuy = true;
    $scope.showCon = false;
    $scope.showEv = false;
    $scope.PopData = {};

    //数据获取
    $scope.doRefresh = function () {
        $http.get("itemInfo/" + $stateParams.itemID)
            .success(
                function (data, status, header, config) {
                    if (data.showimg) {
                        $scope.slideImg = JSON.parse(data.showimg);
                      }
                    if (data.spec) {
                        $scope.itemSpec = JSON.parse(data.spec);
                    }

                    $scope.commentNum = data.commentNum;

                    $scope.itemInfo = data;

                    var f = parseFloat(data.cur_price);
                    $scope.cur_price = '惊爆价:￥' + f.toFixed(2);
                    $scope.buynum = data.buynum + '人付款';

                    $ionicSlideBoxDelegate.$getByHandle('delegateHandler').update();
                    $ionicSlideBoxDelegate.$getByHandle('delegateHandler').loop(true);
                }
            ).error(
            function (data) {
                onError(data);
            }).finally(function() {
                // 停止广播ion-refresher
                $scope.$broadcast('scroll.refreshComplete');
            }
        );
    };

    $scope.doRefresh();

    //数字验证
    $scope.checkInput = function (strVal) {
        checkInt(strVal, true);
    };
    //弹出选项
    $scope.PopData.itemNums = ['1','2','3','4','5','6','7','8','9','10'];
    $scope.popover = $ionicPopover.fromTemplateUrl('resources/views/templates/buyitempopup.html', {
        scope: $scope
    });
    $ionicPopover.fromTemplateUrl('resources/views/templates/buyitempopup.html', {
        scope: $scope
    }).then(function(popover) {
        $scope.popover = popover;
    });
    //取消
    $scope.cancel = function() {
        $scope.isCancel = true;
        $scope.popover.hide();
    };
    //确定
    $scope.confirm = function() {
        if (!checkInt($scope.PopData.chooseNum, true)) {
            return;
        }

        $scope.PopData.itemSpec = "";
        for(var specNam in $scope.itemSpec){
            var specVal = document.getElementById(specNam).value;
            $scope.PopData.itemSpec += (specNam + ":" + specVal + "  ");
        }

        if (0 != $scope.PopData.itemSpec.length){
            $scope.PopData.itemSpec = $scope.PopData.itemSpec.substring(0,
                $scope.PopData.itemSpec.length - 2);
        }

        $scope.isCancel = false;
        $scope.popover.hide();
    };
    // 在隐藏浮动框后执行
    $scope.$on('popover.hidden', function() {
        // 执行代码
        if ($scope.isCancel){
            return;
        }

        if ($scope.isBuy){
            alert('发起购买');
        }
        else {
            var car = $cookieStore.get('car');
            if(!car){
                car = [];
            }

            var info = {};
            info.carID = uuid();
            info.id = $scope.itemInfo.id;
            info.name = $scope.itemInfo.name;
            info.spec = $scope.PopData.itemSpec;
            info.num = $scope.PopData.chooseNum;//这个是字符串......
            info.price = $scope.itemInfo.cur_price;

            car.push(info);
            $cookieStore.put("car", car);

            carItemNumFactory.setCarItemNum(getCarItemNum(car));
        }
    });

    //购买
    $scope.buy = function($event){
        $scope.isBuy = true;
        $scope.popover.show($event);
        if($scope.itemSpec){
            $scope.PopData.itemSpec = $scope.itemSpec[0];
        }

        $scope.PopData.chooseNum = $scope.PopData.itemNums[0];
    };
    //加进购物车
    $scope.addInCar = function($event){
        $scope.isBuy = false;
        $scope.popover.show($event);
        if($scope.itemSpec){
            $scope.PopData.itemSpec = $scope.itemSpec[0];
        }

        $scope.PopData.chooseNum = $scope.PopData.itemNums[0];
    };

    $scope.index = 0;
    $scope.go = function(index){
        $ionicSlideBoxDelegate.$getByHandle('delegateHandler').slide(index);
    };
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };
    $scope.$on('$ionicView.beforeEnter',function(){
        $ionicSlideBoxDelegate.$getByHandle('delegateHandler').start();
    });

    $scope.showContent = function () {
        $scope.showCon = true;
        $scope.showEv = false;
    };
    $scope.showEvaluate = function () {
        $scope.showCon = false;
        $scope.showEv = true;
    };

    $scope.Page = 0;
    $scope.moreData = true;
    $scope.loadMore = function () {
        $http.get("itemEvaluate/" + $stateParams.itemID + '/'+ $scope.Page)
            .success(
                function (data, status, header, config) {
                    if (0 == data.length){
                        $scope.moreData = false;
                    }else {
                        if ($scope.Evaluates){
                            $scope.Evaluates = $scope.Evaluates.concat(data);
                        }else {
                            $scope.Evaluates = data;
                        }
                    }
                }
            ).error(
            function (data) {
                onError(data);
                $scope.Page--;
            }).finally(function () {
                $scope.$broadcast('scroll.infiniteScrollComplete');
            }
        );
        $scope.Page++;
    };

    $scope.loadMore();
}]);

//购物车
appModule.controller('carController', ['$scope', '$cookieStore', '$ionicPopup', 'carItemNumFactory', function($scope, $cookieStore, $ionicPopup, carItemNumFactory){
    var carInfo = $cookieStore.get('car');
    $scope.itemInCar = carInfo;
    $scope.priceTotal = getCarPriceTotal(carInfo);
    $scope.showCarInfo = false;
    $scope.itemNums = ['1','2','3','4','5','6','7','8','9','10'];
    var bPopuped = false;//解决ie会弹出2次

    if (carInfo && carInfo.length > 0){
        $scope.showCarInfo = true;
    }

    $scope.checkout = function(){
        carInfo = $cookieStore.get('car');
        if (!carInfo || 0 == carInfo.length){
            return;
        }

        if (bPopuped){
            return;
        }

        bPopuped = true;

        alert('发起购买');

        bPopuped = false;
    };

    //清空购物车
    $scope.clear = function(){
        carInfo = $cookieStore.get('car');
        if (!carInfo || 0 == carInfo.length){
            return;
        }

        if (bPopuped){
            return;
        }

        bPopuped = true;
        var confirmPopup = $ionicPopup.confirm({
            title: '',
            template: '确定清空购物车?'
        });
        confirmPopup.then(function(res) {
            bPopuped = false;
            if(res) {
                $cookieStore.remove('car');
                $scope.itemInCar = [];
                carItemNumFactory.setCarItemNum(0);
                $scope.priceTotal = 0;
                $scope.showCarInfo = false;
            }
        });
    };

    //修改数量
    $scope.numChange = function (carID, itemNum) {
        carInfo = $cookieStore.get('car');
        if (!carInfo || 0 == carInfo.length){
            return;
        }

        if (!checkInt(itemNum, true)){
            return;
        }

        for (var i = 0; i < carInfo.length; i++){
            if (carInfo[i].carID == carID){
                carInfo[i].num = itemNum;
                $cookieStore.put("car", carInfo);
                carItemNumFactory.setCarItemNum(getCarItemNum(carInfo));
                $scope.priceTotal = getCarPriceTotal(carInfo);

                return;
            }
        }
    };

    //删除物品
    $scope.delete = function (carID) {
        carInfo = $cookieStore.get('car');
        if (!carInfo || 0 == carInfo.length){
            return;
        }

        for (var i = 0; i < carInfo.length; i++){
            if (carInfo[i].carID == carID){
                carInfo.splice(i, 1);
                $cookieStore.put("car", carInfo);
                $scope.itemInCar = carInfo;
                carItemNumFactory.setCarItemNum(getCarItemNum(carInfo));
                $scope.priceTotal = getCarPriceTotal(carInfo);
                if (carInfo.length == 0){
                    $scope.showCarInfo = false;
                }

                return;
            }
        }
    }
}]);

//发现
appModule.controller('searchController', ['$scope', '$http', function ($scope, $http) {
    $scope.search = function (strVal) {
        if (!strVal){
            $scope.searchData = {};
            return;
        }
        if (0 == strVal.length){
            $scope.searchData = {};
            return;
        }
        if (checkStr(strVal)){
            $scope.searchData = {};
            return;
        }

        $http.get("search/" + strVal)
            .success(
                function(data, status, header, config){
                    $scope.searchData = data;
                }
            ).error(
            function(data){
                onError(data);
                $scope.searchData = {};
            }
        );
    }

    $scope.Categorys = [];
    $scope.doRefresh = function () {
        $http.get("categorys")
            .success(
                function(data, status, header, config){
                    $scope.Categorys = data;
                }
            ).error(
            function(data){
                onError(data);
            }).finally(function() {
                // 停止广播ion-refresher
                $scope.$broadcast('scroll.refreshComplete');
            }
        );
    };

    $scope.doRefresh();
}]);

//用户中心
appModule.controller('uerCenterController', ['$scope', '$http', function($scope, $http){
    $scope.needLogIn = true;
    $scope.doRefresh = function () {
        $http.get("getUserBase")
            .success(
                function (data, status, header, config) {
                    if (0 != data.status){
                        $scope.needLogIn = true;
                    }else {
                        $scope.needLogIn = false;
                        $scope.uerBase = data.msg;
                    }
                }
            ).error(
            function (data) {
                $scope.needLogIn = true;
                onError(data);
            }).finally(function() {
            // 停止广播ion-refresher
            $scope.$broadcast('scroll.refreshComplete');
        });
    };

    $scope.doRefresh();

    $scope.logIn = function () {
        var phone = document.getElementById('phone').value;
        var pwd = document.getElementById('password').value;
        if (!checkMobile(phone)){
            layer.msg('亲，请输入正确的电话号码!');
            return;
        }

        $http.get("logIn/"+phone+"/"+pwd)
            .success(
                function (data, status, header, config) {
                    if (0 != data.status){
                        $scope.needLogIn = true;
                        layer.msg(data.msg);
                    }else {
                        $scope.needLogIn = false;
                        $scope.uerBase = data.msg;
                    }
                }
            ).error(
            function (data) {
                $scope.needLogIn = true;
                onError(data);
            });
    };

    $scope.logOut = function () {
        $http.get("logOut")
            .success(
                function (data, status, header, config) {
                    $scope.needLogIn = true;
                }
            ).error(
            function (data) {
                onError(data);
            });
    };
}]);


//个人资料
appModule.controller('userInfoController', ['$scope', '$ionicHistory', '$http', function($scope, $ionicHistory, $http){
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    $scope.showBind = false;
    $scope.showChange = false;
    //获取用户信息
    $http.get("getUserInfo")
        .success(
            function (data, status, header, config) {
                if (0 == data.status){
                    $scope.userInfo = data.msg;
                    var phone = $scope.userInfo.phone;
                    if(phone && 0 != phone.length){
                        $scope.showBind = false;
                    }else {
                        $scope.showBind = true;
                    }
                }else {

                }
            }
        ).error(
        function (data) {
            onError(data);
        });

    //绑定账号
    $scope.bindAccount = function () {
        var phone = document.getElementById('bind_phone').value;
        var psw1 = document.getElementById('bind_psw1').value;
        var psw2 = document.getElementById('bind_psw2').value;
        if (!checkMobile(phone)){
            layer.msg('亲，请输入正确的电话号码!');
            return;
        }
        if (0 == psw1.length){
            layer.msg('密码不能为空!');
            return;
        }
        if (psw1 != psw2){
            layer.msg('两次输入密码不同!');
            return;
        }
        if (psw1.length < 6){
            layer.msg('密码长度最少6位!');
            return;
        }
        $http.get("bindAccount/"+phone+"/"+psw1)
            .success(
                function (data, status, header, config) {
                    if (0 != data.status){
                        $scope.showBind = true;
                        layer.msg(data.msg);
                    }else {
                        $scope.userInfo.phone = phone;
                        $scope.showBind = false;
                        layer.msg(data.msg);
                    }
                }
            ).error(
            function (data) {
                $scope.showBind = true;
                onError(data);
            });
    };

    //是否修改
    $scope.fieldChanged = function () {
        $scope.showChange = true;
    };

    //修改用户资料
    $scope.changeInfo = function () {
        if (!$scope.userInfo.email){
            layer.msg('请输入正确的邮箱地址!');
            return;
        }
        if (checkStr($scope.userInfo.name)
            ||checkStr($scope.userInfo.qq)
            ||checkStr($scope.userInfo.weixnumber)){
            layer.msg('请输入特殊字符!');
            return;
        }

        var info = {};
        info.name = $scope.userInfo.name;
        info.email = $scope.userInfo.email;
        info.qq = $scope.userInfo.qq;
        info.weixnumber = $scope.userInfo.weixnumber;

        $http.get("changeUserInfo/"+JSON.stringify(info))
            .success(
                function (data, status, header, config) {
                    if (0 != data.status){
                        $scope.showChange = true;
                        layer.msg(data.msg);
                    }else {
                        $scope.showChange = false;
                        layer.msg(data.msg);
                    }
                }
            ).error(
            function (data) {
                $scope.showChange = true;
                onError(data);
            });
    };
}]);

//地址
appModule.controller('addrController', ['$scope', '$ionicHistory', '$http', function($scope, $ionicHistory, $http){
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    $scope.addr=[];
    $scope.selectAddrStr = '--请选择--';
    $scope.showArea3 = true;
    $scope.showAddAddr = false;
    $scope.addr.Area1 = $scope.selectAddrStr;
    $scope.addr.Area2 = $scope.selectAddrStr;
    $scope.addr.Area3 = $scope.selectAddrStr;

    $scope.loadAddr = function () {
        $http.get("getAddr")
            .success(
                function (data, status, header, config) {
                    if (0 != data.status){
                        layer.msg(data.msg);
                    }else {
                        $scope.AddrList = data.msg;
                    }
                }
            ).error(
            function (data) {
                onError(data);
            });
    };
    $scope.loadAddr();

    $scope.addAddr = function () {
        $scope.showAddAddr = true;
    };

    $scope.cancelAddAddr = function () {
        $scope.showAddAddr = false;
    };

    $http.get("getArea1")
        .success(
            function (data, status, header, config) {
                $scope.Area1 = data;
            }
        ).error(
        function (data) {
            onError(data);
        });
    
    $scope.selectArea1 = function () {
        $scope.Area3 = [];
        $scope.addr.Area3 = $scope.selectAddrStr;

        if ($scope.addr.Area1 == $scope.selectAddrStr){
            $scope.Area2 = [];
            $scope.addr.Area2 = $scope.selectAddrStr;
            return;
        }

        var cityID = getCityID($scope.Area1, $scope.addr.Area1);
        $http.get("getChildArea/" + cityID)
            .success(
                function (data, status, header, config) {
                    $scope.Area2 = data;
                    $scope.addr.Area2 = $scope.selectAddrStr;
                    $scope.showArea3 = true;
                }
            ).error(
            function (data) {
                onError(data);
            });
    };
    $scope.selectArea2 = function () {
        if ($scope.addr.Area2 == $scope.selectAddrStr){
            $scope.Area3 = [];
            $scope.addr.Area3 = $scope.selectAddrStr;
            return;
        }

        var cityID = getCityID($scope.Area2, $scope.addr.Area2);
        $http.get("getChildArea/" + cityID)
            .success(
                function (data, status, header, config) {
                    if (0 == data.length){
                        $scope.showArea3 = false;
                        $scope.Area3 = [];
                    }else {
                        $scope.showArea3 = true;
                        $scope.Area3 = data;
                        $scope.addr.Area3 = $scope.selectAddrStr;
                    }
                }
            ).error(
            function (data) {
                onError(data);
            });
    };

    $scope.saveAddr = function () {
        var addrs = {};
        if (!$scope.addr.name || 0 == $scope.addr.name.length | checkStr($scope.addr.name)){
            layer.msg('请输入有效的收货人姓名！');
            return;
        }
        addrs.name = $scope.addr.name;

        if (!$scope.addr.tel || 0 == $scope.addr.tel.length || !checkMobile($scope.addr.tel)){
            layer.msg('请输入收货人联系电话！');
            return;
        }
        addrs.phone = $scope.addr.tel;

        if (!$scope.addr.Area1 ||  $scope.selectAddrStr == $scope.addr.Area1){
            layer.msg('请选择收货人所在地址！');
            return;
        }
        var addrInfo = $scope.addr.Area1;
        if (!$scope.addr.Area2 ||  $scope.selectAddrStr == $scope.addr.Area2){
            layer.msg('请选择收货人所在地址！');
            return;
        }
        addrInfo = addrInfo + ' ' + $scope.addr.Area2;
        if ($scope.showArea3){
            if (!$scope.addr.Area3 ||  $scope.selectAddrStr == $scope.addr.Area3){
                layer.msg('请选择收货人所在地址！');
                return;
            }

            addrInfo = addrInfo + ' ' + $scope.addr.Area3;
        }
        if (!$scope.addr.street || 0 == $scope.addr.street.length){
            layer.msg('请选择收货人详细地址！');
            return;
        }
        addrInfo = addrInfo + ' ' + $scope.addr.street;

        addrs.addr = addrInfo;

        $http.get("saveAddr/"+JSON.stringify(addrs))
            .success(
                function (data, status, header, config) {
                    if (0 != data.status){
                        layer.msg(data.msg);
                    }else {
                        layer.msg(data.msg);
                        $scope.showAddAddr = false;
                        $scope.loadAddr();
                    }
                }
            ).error(
            function (data) {
                onError(data);
            });
    };

    $scope.delAddr = function (addrID) {
        $http.get("delAddr/"+addrID)
            .success(
                function (data, status, header, config) {
                    if (0 != data.status){
                        layer.msg(data.msg);
                    }else {
                        $scope.AddrList = data.msg;
                    }
                }
            ).error(
            function (data) {
                onError(data);
            });
    };

}]);

//订单
appModule.controller('orderController', ['$scope', '$ionicHistory', '$http', function($scope, $ionicHistory, $http){
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    $scope.allOrder = function () {
        console.log('allOrder');
    }
}]);

//推广
appModule.controller('spreadController', ['$scope', '$ionicHistory', '$http', function($scope, $ionicHistory, $http){
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    $scope.showQRC = false;
    $scope.showMemo = true;
    $scope.showIncome = false;
    $scope.canShowQRC = false;
    $scope.doRefresh = function () {
        $http.get("showQRC")
            .success(
                function (data, status, header, config) {
                    $scope.canShowQRC = data.msg;
                }
            ).error(
            function (data) {
                onError(data);
            }).finally(function() {
            // 停止广播ion-refresher
            $scope.$broadcast('scroll.refreshComplete');
        });
    };

    $scope.doRefresh();
    
    $scope.showSpreadMemo = function () {
        $scope.showMemo = true;
        $scope.showQRC = false;
        $scope.showIncome = false;
    };
    $scope.showIncomeFc = function () {
        $scope.showMemo = false;
        $scope.showQRC = false;
        $scope.showIncome = true;
    };
    $scope.showQRCFc = function () {
        $scope.showMemo = false;
        $scope.showQRC = true;
        $scope.showIncome = false;
    };
}]);

//代理
appModule.controller('agentController', ['$scope', '$ionicHistory', '$http', function($scope, $ionicHistory, $http){
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    $scope.agent = [];
    $scope.myAgent = function () {
        if (!$scope.agent.name || 0 == $scope.agent.name.length || checkStr($scope.agent.name)){
            layer.msg('请输入有效的名字！');
            return;
        }
        if (!$scope.agent.phone || 0 == $scope.agent.phone.length || !checkMobile($scope.agent.phone)){
            layer.msg('请输入有效联系电话！');
            return;
        }

        $http.get("agent/"+$scope.agent.name + "/" + $scope.agent.phone)
            .success(
                function (data, status, header, config) {
                    if (0 != data.status){
                        layer.msg(data.msg);
                    }else {
                        layer.msg(data.msg);
                    }
                }
            ).error(
            function (data) {
                onError(data);
            });
    }
}]);

//密码修改
appModule.controller('changePSWController', ['$scope', '$ionicHistory', '$http', function($scope, $ionicHistory, $http){
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    //修改密码
    $scope.changePassWord = function () {
        var oldpsw = document.getElementById('change_oldpsw').value;
        var psw1 = document.getElementById('change_psw1').value;
        var psw2 = document.getElementById('change_psw2').value;
        if (0 == psw1.length){
            layer.msg('密码不能为空!');
            return;
        }
        if (psw1 != psw2){
            layer.msg('两次输入密码不同!');
            return;
        }
        $http.get("changePsw/"+oldpsw+"/"+psw1)
            .success(
                function (data, status, header, config) {
                    if (0 != data.status){
                        layer.msg(data.msg);
                    }else {
                        layer.msg(data.msg);
                        document.getElementById('change_oldpsw').value = "";
                        document.getElementById('change_psw1').value = "";
                        document.getElementById('change_psw2').value = "";
                    }
                }
            ).error(
            function (data) {
                onError(data);
            });
    };
}]);
