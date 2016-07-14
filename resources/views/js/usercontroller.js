'use strict';

var appModule = angular.module('ionicApp.usercontroller', ['ionicApp.server']);

//用户中心
appModule.controller('uerCenterController', ['$scope', '$http', function($scope, $http){
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
            if (pwd.length < 6){
                layer.msg('密码长度最少6位!');
                return;
            }
            if (regPSW != pwd){
                layer.msg('两次输入密码不同!');
                return;
            }

            $http.get("register/"+phone+"/"+pwd)
                .success(
                    function (data, status, header, config) {
                        layer.msg(data.msg);
                        if (0 == data.status){
                            $scope.register();
                        }
                    }
                ).error(
                function (data) {
                    onError(data);
                });
        }else {
            //登录
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
appModule.controller('userInfoController', ['$scope', '$ionicHistory', '$http', function($scope, $ionicHistory, $http){
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
        if (psw1.length < 6){
            layer.msg('密码长度最少6位!');
            return;
        }
        if (psw1 != psw2){
            layer.msg('两次输入密码不同!');
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
