
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

//物品展示
function createItemList(itemData) {
    var iCount = itemData.length;
    var clientWidth = $(window).width();

    //每列多少个
    var iLine = parseInt(clientWidth/150);
    iLine = (0 == iLine ? 1 : iLine);

    //共有多少行
    var iRow = Math.ceil(iCount / iLine);
    var html = '';
    var iIndex = 0;
    for (i = 0; i < iRow; i++)
    {
        html += '<div class="row">';
        for (j = 0; j < iLine; j++)
        {
            if (iIndex >= iCount)
            {
                for (k = j; k < iLine; k++)
                {
                    html += '<div class="col col-'+iLine+''+iLine+'"/>';
                }

                break;
            }

            var url = '#/menu/tabs/iteminfo/?itemID='+itemData[iIndex].id+'';
            html += '<div class="col col-'+iLine+''+iLine+'">';
            html += '<span>';
            html += '<a href="'+url+'"><img class="lazy" href="'+url+'" class="lazy" style="height: 100%; width: 100%" src="uploads/'+itemData[iIndex].indeximg+'"></a>';
            html += '</span>';
            html += '<span>';
            html += '<a href="'+url+'" style="text-decoration:none; color: #000000;"><div>'+itemData[iIndex].name+'</div></a>';
            html += '</span>';
            html += '<div></div>';
            html += '<span>';
            var f = parseFloat(itemData[iIndex].cur_price);
            html += '<em style="color: red">￥'+f.toFixed(2)+'</em>';
            html += '<em style="padding-left:10px; font-size:10px; color:#999">已售：'+itemData[iIndex].buynum+'笔</em>';
            html += '</span>';

            html += '</div>';

            iIndex++;
        }
        html += '</div>';
    }

    return html;
};
