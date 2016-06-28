
var bDebug = true;

function dd(data) {
    if (bDebug) {
        console.log(data);
    }
};

function onError(data) {
    layer.msg('加载页面出错,请稍后再试...');
    dd(data);
};

function getSlideImgH() {
    var clientHeight=$(window).height();
    return (clientHeight / 5) * 2 + 'px';
};

function checkInt(strVal, bLayer) {
    if (0 == strVal.length){
        return false;
    }

    var strCheck = /^\+?[1-9][0-9]*$/;
    if (!strCheck.test(strVal)){
        if(bLayer){
            layer.msg('亲,请输入数字...');
        }
        return false;
    }

    var iNum = parseInt(strVal);
    if (0 >= iNum){
        if(bLayer){
            layer.msg('亲,数量必须大于0...');
        }
        return false;
    }

    return true;
}

function addInCarOrBuyPopup(itemNam, itemSpec, itemImg, strButtName, $ionicPopup, $scope) {

    $scope.checkInput = function (strVal) {
        checkInt(strVal, true);
    };

    $scope.inptItem = {};
    $scope.inptItem.itemSpec = itemSpec;
    $scope.inptItem.itemImg = itemImg;

    return $ionicPopup.show({
        templateUrl: 'resources/views/templates/buyitempopup.html',
        scope: $scope,
        title: itemNam,
        buttons: [
            {
                text: '取消',
                type: 'button-energized',
            },
            {
                text: '<b>'+strButtName+'</b>',
                type: 'button-balanced',
                onTap: function(e) {
                    if (!checkInt($scope.Data.itemNum, false)) {
                        //不允许用户关闭
                        e.preventDefault();
                    } else {
                        if (!$scope.Data.itemSpec){
                            $scope.Data.itemSpec = '';
                        }
                        return $scope.Data;
                    }
                }
            },
        ]
    });
}

function getCarItemNum(carInfo) {
    var iNum = 0;
    if (!carInfo){
        return iNum;
    }

    for (i = 0; i < carInfo.length; i++){
        iNum += carInfo[i].num;
    }

    return iNum;
}

function getCarPriceTotal(carInfo) {
    var iPrice = 0;
    if (!carInfo){
        return iPrice;
    }

    for (i = 0; i < carInfo.length; i++){
        iPrice += carInfo[i].num * carInfo[i].price;
    }

    return iPrice;
}
