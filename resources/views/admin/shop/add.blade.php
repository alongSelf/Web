@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 宝贝管理
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 开始-->
    <div class="result_wrap">
        <div class="result_title">
            <h3>添加宝贝</h3>
            @if(count($errors)>0)
                <div class="mark">
                    @if(is_object($errors))
                        @foreach($errors->all() as $error)
                            <p>{{$error}}</p>
                        @endforeach
                    @else
                        <p>{{$errors}}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
    <!--结果集标题与导航组件 结束-->
    <script src="{{asset('resources/views/uploadify/jquery.uploadify.min.js')}}" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('resources/views/uploadify/uploadify.css')}}">
    <style>
        .uploadify{display:inline-block;}
        .uploadify-button{border:none; border-radius:5px; margin-top:8px;}
        table.add_tab tr td span.uploadify-button-text{color: #FFF; margin:0;}
    </style>

    <div class="result_wrap">
        <form action="{{url('admin/shop')}}" method="post">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                <tr>
                    <th width="70"><i class="require">*</i>分类：</th>
                    <td>
                        <select style="width: 20%" name="category">
                            @foreach($cate as $c)
                                <option value="{{$c->id}}"

                                >{{$c->title}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>名称：</th>
                    <td>
                        <input type="text" name="name" value="">
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>主图片：</th>
                    <td>
                        <input type="text" size="50" name="indeximg" hidden="true" value="">
                        <input id="indeximg_upload" name="indeximg_upload" type="file" multiple="true">
                        <img alt="" id="index_img" style="max-width: 350px; max-height:200px;" src="">
                    </td>
                </tr>

                <tr>
                    <th><i class="require">*</i>文字描述：</th>
                    <td>
                        <textarea style="width: 30%" name="describe"></textarea>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>原价：</th>
                    <td>
                        <input type="number" name="prime_price" value="0">
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>现价：</th>
                    <td>
                        <input type="number" name="cur_price" value="0">
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>库存(-1无限制)：</th>
                    <td>
                        <input type="number" name="stock" value="-1">
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>售出数量：</th>
                    <td>
                        <input type="number" name="buynum" value="0">
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>首页轮播(0否1是)：</th>
                    <td>
                        <input type="number" name="activity" value="0">
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>首页显示(0否1是)：</th>
                    <td>
                        <input type="number" name="showindex" value="1">
                    </td>
                </tr>
                <tr>
                    <th>规格：</th>
                    <td>
                        <table id="spec_table">
                            <tr><input type="text" id="spec" name="spec" value="" hidden="true"></tr>
                            <tr>
                                <th width="60">商品规格</th>
                                <td><span onclick="add_specattr()">添加规格组</span></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <th><i class="require">*</i>物品详情轮播图(至少3张)：</th>
                    <td id="showimgtd">
                        <input type="text" id="showimg" name="showimg" value="" hidden="true">
                        <input id="showimg_upload" name="showimg_upload" type="file" multiple="true">
                    </td>
                </tr>

                <tr>
                    <th><i class="require">*</i>宝贝详情(图片加上style="width: 100%"):</th>
                    <td>
                        <script type="text/javascript" charset="utf-8" src="{{asset('resources/views/ueditor/ueditor.config.js')}}"></script>
                        <script type="text/javascript" charset="utf-8" src="{{asset('resources/views/ueditor/ueditor.all.min.js')}}"> </script>
                        <script type="text/javascript" charset="utf-8" src="{{asset('resources/views/ueditor/lang/zh-cn/zh-cn.js')}}"></script>
                        <script id="editor" name="content" type="text/plain" style="width:860px;height:500px;"></script>
                        <script type="text/javascript">
                            var ue = UE.getEditor('editor');
                        </script>
                        <style>
                            .edui-default{line-height: 28px;}
                            div.edui-combox-body,div.edui-button-body,div.edui-splitbutton-body
                            {overflow: hidden; height:20px;}
                            div.edui-box{overflow: hidden; height:22px;}
                        </style>
                    </td>
                </tr>

                <tr>
                    <th></th>
                    <td>
                        <input type="submit" value="提交">
                        <input type="button" class="back" onclick="history.go(-1)" value="返回">
                    </td>
                </tr>

                </tbody>
            </table>
        </form>
    </div>

    <script type="text/javascript">
        <?php $timestamp = time();?>
        //页面显示图片
        $(function() {
            $('#indeximg_upload').uploadify({
                'buttonText' : '图片上传',
                'formData'     : {
                    'timestamp' : '<?php echo $timestamp;?>',
                    '_token'     : "{{csrf_token()}}"
                },
                'swf'      : "{{asset('resources/views/uploadify/uploadify.swf')}}",
                'uploader' : "{{url('admin/upload')}}",
                'onUploadSuccess' : function(file, data, response) {
                    $('input[name=indeximg]').val(data);
                    $('#index_img').attr('src','/uploads/'+data);
                }
            });
        });

        //物品详情轮播图片
        $(function() {
            $('#showimg_upload').uploadify({
                'buttonText' : '图片上传',
                'formData'     : {
                    'timestamp' : '<?php echo $timestamp;?>',
                    '_token'     : "{{csrf_token()}}"
                },
                'swf'      : "{{asset('resources/views/uploadify/uploadify.swf')}}",
                'uploader' : "{{url('admin/upload')}}",
                'onUploadSuccess' : function(file, data, response) {
                    var  html = '<dl>\
                     <input type="text" value="'+data+'" onchange="showimg_total(this)"><br>\
                     <img style="max-width: 200px; max-height:210px;" src="{{asset('uploads')}}/'+data+'">\
                     </dl>';
                    $(document.getElementById('showimgtd')).append(html);
                    setShowImg();
                }
            });
        });

        //物品详情轮播
        function setShowImg() {
            var showimg = document.getElementById('showimg');
            var showimgTd = document.getElementById('showimgtd');
            var allImg = []

            $(showimgTd).find('dl').each(function(i) {
                var img = $(this).find('input').val();
                if (0 != img.length){
                    allImg.push(img);
                }
            });

            showimg.value = JSON.stringify(allImg);
        }

        function showimg_total(obj) {
            var inputVal = obj.value;
            if (0 == inputVal.length){
                $(obj).parents('dl').hide();
            }
            else {
                $(obj).parents('dl').find('img').attr('src', '{{asset('uploads')}}/'+inputVal+'');
            }

            setShowImg();
        }

        //规格
        function add_specattr(){
            var attr = '<tr>\
                    <th></th>\
                    <td>\
                        <dl class="spec_attr">\
                            <dt>规格名：<input type="text" name="spec_name"> <span onclick="add_spec_value(this)"><i class="fa fa-plus-circle"></i></span></dt>\
                            <dd>规格值：<input type="text" placeholder="规格值@价格" name="spec_value" onchange="spec_total(this)"></dd>\
                        </dl>\
                    </td>\
                </tr>';

            var specTable = document.getElementById('spec_table');
            $(specTable).append(attr);
        }
        function add_spec_value(obj){
            var input = '<input type="text" placeholder="规格值@价格" name="spec_value" onchange="spec_total(this)">';
            $(obj).parents('dl').find('dd').append(input);
        }
        function spec_total(obj){
            var specAttr = {};
            $('.spec_attr').each(function(i) {
                var specNam = $(this).find("[name='spec_name']").val();
                if (0 != specNam.length){
                    if (specAttr[specNam]){
                        alert('规格名称重复!');
                        return;
                    }

                    var specValue = [];
                    //遍历读取所有属性值
                    $(this).find("[name='spec_value']").each(function(j) {
                        var specV = $(this).val();
                        if (0 != specV.length){
                            var valInfo = specV.split('@');
                            var info = {}
                            info.val = valInfo[0];
                            if (2 == valInfo.length){
                                info.price = parseInt(valInfo[1]);
                            }
                            specValue[j] = info;
                        }
                    });

                    specAttr[specNam] = specValue;
                }
            });

            var specJson = JSON.stringify(specAttr);
            var specInput = document.getElementById("spec");
            specInput.value = specJson;
        }
    </script>


@endsection
