
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

function checkMobile(str) {
    var re = /^0?1[3|4|5|8][0-9]\d{8}$/;
    if (re.test(str)) {
        return true;
    } else {
        return false;
    }
}

function checkEmail(str){
    var re = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
    if(re.test(str)){
        return true;
    }else{
        return false;
    }
}

function checkStr(str) {
    var re = /[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|"|\|/;
    if(re.test(str)){
        return true;
    }else{
        return false;
    }
}

function getItemListImgH() {
    return 170;
}

function getMaxW() {
    return 850;
}

function getSlideImgH() {
    return 465;
}

function getColStyle(per) {
    var col = new Array(25, 33, 34, 40, 50, 60, 66, 67, 75, 80, 90);
    for (var i = 0; i < col.length; i++){
        if (per >= col[i]){
            continue;
        }

        return col[i];
    }

    return 90;
}

function getCateInfo($cateID, $Categorys) {
    for (var i = 0; i < $Categorys.length; i++){
        if ($Categorys[i].id == $cateID){
            return $Categorys[i];
        }
    }

    return null;
}

function getLineNum(clientWidth) {
    var lineNum = parseInt(clientWidth / getItemListImgH());//每列多少个
    return lineNum > 4 ? 4 : lineNum;
}

function makeItemList(itemData, $Categorys, clientWidth) {
    var itemList = new Array;
    var itemCount = itemData.length;
    if (!itemData || 0 == itemCount){
        return itemList;
    }

    var lineNum = getLineNum(clientWidth);//每列多少个
    var iIndex = 0;
    var curCate = -1;
    var itemRow = new Array;

    while (iIndex < itemCount){
        for (var i = 0; i < lineNum; i++){
            itemData[iIndex].type = 1;
            var item = itemData[iIndex];
            if (item['category'] != curCate){
                if (0 != itemRow.length){
                    itemList.push(itemRow);
                    itemRow = new Array;
                }

                curCate = item['category'];
                var cateInfo = getCateInfo(curCate, $Categorys);
                var cateImg = new Array;
                cateImg.type = 0;
                cateImg.image = cateInfo.backimg;
                cateImg.id = cateInfo.id;
                cateImg.title = cateInfo.title;

                var cateRow = new Array;
                cateRow.push(cateImg);
                itemList.push(cateRow);
            }

            if (lineNum == itemRow.length){
                itemList.push(itemRow);
                itemRow = new Array;
            }

            itemRow.push(item);

            iIndex++;
            if (iIndex >= itemCount){
                break;
            }
        }
    }

    if (0 != itemRow.length){
        itemList.push(itemRow);
    }

    return itemList;
}

function reMakeList(itemList, $Categorys, clientWidth) {
    var newItemList = new Array;
    if (!itemList){
        return newItemList;
    }

    var lineNum = getLineNum(clientWidth);//每列多少个
    var itemRow = new Array;

    for (var i = 0; i < itemList.length; i++){
        for (var j = 0; j < itemList[i].length; j++){
            var item = itemList[i][j];
            if (item.type == 0){
                if (0 != itemRow.length){
                    newItemList.push(itemRow);
                    itemRow = new Array;
                }

                var cateRow = new Array;
                cateRow.push(item);
                newItemList.push(cateRow);
            }else{
                if (itemRow.length == lineNum){
                    newItemList.push(itemRow);
                    itemRow = new Array;
                }

                itemRow.push(item);
            }
        }
    }

    if (0 != itemRow.length){
        newItemList.push(itemRow);
    }

    return newItemList;
}

function getLastCate(itemOldeData) {
    var lastCate = -1;
    for (var i = itemOldeData.length - 1; i >= 0; i--){
        var item = itemOldeData[i][0];
        if(item.type == 0){
            lastCate = item.id;
            break;
        }
    }

    return lastCate;
}

function deepcopy(obj) {
    var out = [],i = 0,len = obj.length;
    for (; i < len; i++) {
        if (obj[i] instanceof Array){
            out[i] = deepcopy(obj[i]);
        }
        else out[i] = obj[i];
    }
    return out;
}

function appendItemList(itemOldeData, itemNewData, $Categorys, clientWidth) {
    if (0 == itemNewData.length){
        return itemOldeData;
    }

    var lineNum = getLineNum(clientWidth);//每列多少个
    var curCate = getLastCate(itemOldeData);
    var iIndex = 0;
    var itemRow = new Array;
    if (0 != itemOldeData.length){
        var lastItems = itemOldeData[itemOldeData.length - 1];
        if (lastItems[0].type == 1 && lastItems.length != lineNum){
            itemRow = deepcopy(lastItems);
            itemOldeData.splice(itemOldeData.length - 1, 1);
        }
    }

    while (iIndex < itemNewData.length){
        for (var i = 0; i < lineNum; i++){
            itemNewData[iIndex].type = 1;
            var item = itemNewData[iIndex];
            if (item['category'] != curCate){
                if (0 != itemRow.length){
                    itemOldeData.push(itemRow);
                    itemRow = new Array;
                }

                curCate = item['category'];
                var cateInfo = getCateInfo(curCate, $Categorys);
                var cateImg = new Array;
                cateImg.type = 0;
                cateImg.image = cateInfo.backimg;
                cateImg.id = cateInfo.id;
                cateImg.title = cateInfo.title;

                var cateRow = new Array;
                cateRow.push(cateImg);
                itemOldeData.push(cateRow);
            }

            if (lineNum == itemRow.length){
                itemOldeData.push(itemRow);
                itemRow = new Array;
            }

            itemRow.push(item);

            iIndex++;
            if (iIndex >= itemNewData.length){
                break;
            }
        }
    }

    if (0 != itemRow.length){
        itemOldeData.push(itemRow);
    }

    return itemOldeData;
}

function getCarItemNum(carInfo) {
    var iNum = 0;
    if (!carInfo){
        return iNum;
    }

    for (i = 0; i < carInfo.length; i++){
        iNum += parseInt(carInfo[i].num);
    }

    return iNum;
}

function getCarPriceTotal(carInfo) {
    var iPrice = 0;
    if (!carInfo){
        return iPrice;
    }

    for (i = 0; i < carInfo.length; i++){
        iPrice += parseInt(carInfo[i].num) * parseInt(carInfo[i].price);
    }

    return iPrice;
}

function uuid() {
    var s = [];
    var hexDigits = "0123456789abcdef";
    for (var i = 0; i < 36; i++) {
        s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
    }
    s[14] = "4"; // bits 12-15 of the time_hi_and_version field to 0010
    s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1); // bits 6-7 of the clock_seq_hi_and_reserved to 01
    s[8] = s[13] = s[18] = s[23] = "-";

    var uuid = s.join("");
    
    return uuid;
}

function getCityID(Citys, cityNam) {
    for (i = 0; i < Citys.length; i++){
        if (Citys[i].areaname == cityNam){
            return Citys[i].areano;
        }
    }
}

function parseEvaluates(Evaluates) {
    for (i = 0; i < Evaluates.length; i++){
        var iStar = Evaluates[i].star;
        var starArray = [];
        for (j = 0; j < iStar; j++){
            starArray.push(j);
        }
        
        Evaluates[i].star = starArray;
    }

    return Evaluates;
}

function pswMin() {
    return 6;
}

function pswMax() {
    return 12;
}

function errLogin()
{
    return 10000;
}

function removeOrder(orderID, orderList) {
    for (var i = 0; i < orderList.length; i++){
        if (orderList[i].id == orderID){
            orderList.splice(i, 1);
            break;
        }
    }

    return orderList;
}

function arrangeLogistics(logistics) {
    var lastDate = '';
    for (var i = 0; i < logistics.length; i++){
        var dateList = logistics[i].AcceptTime.split(" ");
        if (lastDate != dateList[0]){
            lastDate = dateList[0]
        }
        else {
            logistics[i].AcceptTime = dateList[1];
        }
    }

    return logistics;
}

function isWX(){
    var ua = navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i)=="micromessenger") {
        return true;
    } else {
        return false;
    }
}

function getLoading() {
    return 'Loading...';
}
