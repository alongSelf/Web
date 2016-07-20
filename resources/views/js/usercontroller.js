'use strict';

var appModule = angular.module('ionicApp.usercontroller', ['ionicApp.server']);

//用户中心
appModule.controller('uerCenterController', ['$scope', '$http', '$ionicLoading', function($scope, $http, $ionicLoading){
    $scope.needLogIn = true;
    $scope.logInOrReg = '登录';
    $scope.clickLoginOrReg = '点击注册';
    $scope.isRegister = false;
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

    $scope.register = function () {
        $scope.isRegister = !$scope.isRegister;
        if ($scope.isRegister){
            $scope.logInOrReg = '注册';
            $scope.clickLoginOrReg = '取消注册';
        }else{
            $scope.logInOrReg = '登录';
            $scope.clickLoginOrReg = '点击注册';
        }
    };

    $scope.logIn = function () {
        var phone = document.getElementById('phone').value;
        var pwd = document.getElementById('password').value;
        if (!checkMobile(phone)){
            layer.msg('亲，请输入正确的电话号码!');
            return;
        }

        var regPSW = '';
        //注册
        if ($scope.isRegister){
            regPSW = document.getElementById('regpassword').value;            
            if (regPSW != pwd){
                layer.msg('两次输入密码不同!');
                return;
            }
            if (pwd.length < pswMin() || pwd.length > pswMax()){
                layer.msg('密码长度最少'+pswMin()+'位最多'+pswMax()+'位!');
                return;
            }

            $ionicLoading.show({
                template: 'Working...'
            });
            $.post("register",{'_token':$('meta[name="_token"]').attr('content'),'phone':phone, 'psw':pwd},function(data){
                layer.msg(data.msg);
                if (0 == data.status){
                    $scope.register();
                }
                $ionicLoading.hide();
            });
        }else {
            $ionicLoading.show({
                template: 'Working...'
            });
            $.post("logIn",{'_token':$('meta[name="_token"]').attr('content'),'phone':phone, 'psw':pwd},function(data){
                if (0 != data.status){
                    $scope.needLogIn = true;
                    layer.msg(data.msg);
                }else {
                    $scope.needLogIn = false;
                    $scope.uerBase = data.msg;
                }
                $ionicLoading.hide();
            });
        }
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
appModule.controller('userInfoController', ['$scope', '$ionicHistory', '$http', '$ionicLoading', function($scope, $ionicHistory, $http, $ionicLoading){
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };

    $scope.showBind = false;
    $scope.showChange = false;
    $scope.doRefresh = function () {
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
            }).finally(function() {
            // 停止广播ion-refresher
            $scope.$broadcast('scroll.refreshComplete');
        });
    };
    $scope.doRefresh();

    //绑定账号
    $scope.bindAccount = function () {
        var phone = document.getElementById('bind_phone').value;
        var psw1 = document.getElementById('bind_psw1').value;
        var psw2 = document.getElementById('bind_psw2').value;
        if (!checkMobile(phone)){
            layer.msg('亲，请输入正确的电话号码!');
            return;
        }
        if (psw1.length < pswMin() || psw1.length > pswMax()){
            layer.msg('密码长度最少'+pswMin()+'位最多'+pswMax()+'位!');
            return;
        }
        if (psw1 != psw2){
            layer.msg('两次输入密码不同!');
            return;
        }
        $ionicLoading.show({
            template: 'Working...'
        });
        $.post("bindAccount",{'_token':$('meta[name="_token"]').attr('content'),'phone':phone, 'psw':psw1},function(data){
            if (0 != data.status){
                $scope.showBind = true;
                layer.msg(data.msg);
            }else {
                $scope.userInfo.phone = phone;
                $scope.showBind = false;
                layer.msg(data.msg);
            }
            $ionicLoading.hide();
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
            ||checkStr($scope.userInfo.nickname)
            ||checkStr($scope.userInfo.qq)
            ||checkStr($scope.userInfo.weixnumber)){
            layer.msg('请输入特殊字符!');
            return;
        }

        if ($scope.userInfo.name.length < 2 || $scope.userInfo.name.length > 64){
            layer.msg('姓名最少2位最多64位!');
            return;
        }
        if ($scope.userInfo.nickname.length < 2 || $scope.userInfo.nickname.length > 64){
            layer.msg('昵称最少2位最多64位!');
            return;
        }
        if ($scope.userInfo.email.length < 5 || $scope.userInfo.email.length > 64){
            layer.msg('邮箱最少5位最多64位!');
            return;
        }
        if (isNaN($scope.userInfo.qq)){
            layer.msg('请输入有效的QQ号码!');
            return;
        }
        if ($scope.userInfo.qq.length < 4 || $scope.userInfo.qq.length > 15){
            layer.msg('QQ号码最少4位最多15位!');
            return;
        }
        if ($scope.userInfo.weixnumber.length < 2 || $scope.userInfo.weixnumber.length > 64){
            layer.msg('微信号最少2位最多64位!');
            return;
        }

        var info = {};
        info.name = $scope.userInfo.name;
        info.nickname = $scope.userInfo.nickname;
        info.email = $scope.userInfo.email;
        info.qq = $scope.userInfo.qq;
        info.weixnumber = $scope.userInfo.weixnumber;

        $ionicLoading.show({
            template: 'Working...'
        });
        $.post("changeUserInfo",{'_token':$('meta[name="_token"]').attr('content'),'data':JSON.stringify(info)},function(data){
            if (0 != data.status){
                $scope.showChange = true;
                layer.msg(data.msg);
            }else {
                $scope.showChange = false;
                layer.msg(data.msg);
            }
            $ionicLoading.hide();
        });
    };
}]);

//地址
appModule.controller('addrController', ['$scope', '$ionicHistory', '$http', '$ionicLoading', function($scope, $ionicHistory, $http, $ionicLoading){
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
        $http.get("getArea1")
            .success(
                function (data, status, header, config) {
                    $scope.Area1 = data;
                }
            ).error(
            function (data) {
                onError(data);
            });

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
            }).finally(function() {
            // 停止广播ion-refresher
            $scope.$broadcast('scroll.refreshComplete');
        });
    };
    $scope.loadAddr();

    $scope.addAddr = function () {
        $scope.showAddAddr = true;
    };

    $scope.cancelAddAddr = function () {
        $scope.showAddAddr = false;
    };

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
        if ($scope.addr.name.length < 2 || $scope.addr.name.length > 64){
            layer.msg('收货人姓名最少2位最多64位！');
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

        $ionicLoading.show({
            template: 'Working...'
        });
        $.post("saveAddr",{'_token':$('meta[name="_token"]').attr('content'),'data':JSON.stringify(addrs)},function(data){
            if (0 != data.status){
                layer.msg(data.msg);
            }else {
                layer.msg(data.msg);
                $scope.showAddAddr = false;
                $scope.loadAddr();
            }
            $ionicLoading.hide();
        });
    };

    $scope.delAddr = function (addrID) {
        $ionicLoading.show({
            template: 'Working...'
        });
        $.post("delAddr",{'_token':$('meta[name="_token"]').attr('content'),'id':addrID},function(data){
            if (0 != data.status){
                layer.msg(data.msg);
            }else {
                $scope.AddrList = data.msg;
            }
            $ionicLoading.hide();
        });
    };

}]);

//密码修改
appModule.controller('changePSWController', ['$scope', '$ionicHistory', '$http', '$ionicLoading', function($scope, $ionicHistory, $http, $ionicLoading){
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
        if (psw1.length < pswMin() || psw1.length > pswMax()){
            layer.msg('密码长度最少'+pswMin()+'位最多'+pswMax()+'位!');
            return;
        }

        $ionicLoading.show({
            template: 'Working...'
        });
        $.post("changePsw",{'_token':$('meta[name="_token"]').attr('content'),'old':oldpsw, 'new':psw1},function(data){
            if (0 != data.status){
                layer.msg(data.msg);
            }else {
                layer.msg(data.msg);
                document.getElementById('change_oldpsw').value = "";
                document.getElementById('change_psw1').value = "";
                document.getElementById('change_psw2').value = "";
            }
            $ionicLoading.hide();
        });
    };
}]);

//联系我们
appModule.controller('contactusController', ['$scope', '$ionicHistory', function($scope, $ionicHistory){
    $scope.goBack = function () {
        $ionicHistory.goBack();
    };
}]);
