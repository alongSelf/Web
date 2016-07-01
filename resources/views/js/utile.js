
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

function checkStr(str) {
    var containSpecial = RegExp(/[(\ )(\~)(\!)(\@)(\#)(\$)(\%)(\^)(\&)(\*)(\()(\))(\-)(\_)(\+)(\=)(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\,)(\.)(\/)(\<)(\>)(\?)(\)]+/);

    return (containSpecial.test(str));
}

function makeItemList(itemData, clientWidth) {
    var itemList = new Array;
    if (!itemData){
        return itemList;
    }

    var itemCount = itemData.length;
    var lineNum = parseInt(clientWidth / 150);//每列多少个
    var rowNum = Math.ceil(itemCount / lineNum);//多少行
    var iIndex = 0;

    for (var i = 0; i < rowNum; i++){
        var itemTmp = new Array;
        for (var j = 0; j < lineNum; j++){
            if (iIndex >= itemCount){
                itemList.push(itemTmp);
                return itemList;
            }

            itemTmp.push(itemData[iIndex]);
            iIndex++;
        }

        itemList.push(itemTmp);
    }

    return itemList;
}

function appendItemList(itemOldeData, itemNewData, clientWidth) {
    var lineNum = parseInt(clientWidth / 150);//每列多少个
    var oldItemNum = itemOldeData.length;
    var iAdded = 0;
    if (0 != oldItemNum){
        //补齐
        if (itemOldeData[oldItemNum - 1].length != lineNum){
            iAdded = lineNum - itemOldeData[oldItemNum - 1].length;
            for (var i = 0; i < iAdded; i++){
                itemOldeData[oldItemNum - 1].push(itemNewData[i]);
            }
        }
    }

    if (iAdded >= itemNewData.length){
        return itemOldeData;
    }

    while (true){
        var itemTmp = new Array;
        for (var j = 0; j < lineNum; j++){
            if (iAdded >= itemNewData.length){
                itemOldeData.push(itemTmp);
                return itemOldeData;
            }

            itemTmp.push(itemNewData[iAdded]);
            iAdded++;
        }

        itemOldeData.push(itemTmp);
        if (iAdded >= itemNewData.length) {
            break;
        }
    }

    return itemOldeData;
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
