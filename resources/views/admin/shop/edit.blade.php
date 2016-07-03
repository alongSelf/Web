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
        <h3>编辑宝贝</h3>
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

<div class="result_wrap">
    <form action="#" method="post">
        <input type="hidden" name="_method" value="put">
        <input type="text" name="id" hidden="true" value="{{$item->id}}">
        {{csrf_field()}}
        <table class="add_tab">
            <tbody>
            <tr>
                <th width="120"><i class="require">*</i>分类：</th>
                <td>
                    <select name="category">
                        @foreach($cate as $c)
                            <option value="{{$c->id}}"
                                    @if($c->id==$item->category) selected @endif
                            >{{$c->title}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <tr>
                <th><i class="require">*</i>名称：</th>
                <td>
                    <input type="text" name="name" value="{{$item->name}}">
                </td>
            </tr>

            <tr>
                <th><i class="require">*</i>主图片：</th>
                <td>
                    <input type="text" size="50" name="indeximg" value="{{$item->indeximg}}">
                    <input id="file_upload" name="file_upload" type="file" multiple="true">
                    <img alt="" id="index_img" style="max-width: 350px; max-height:200px;" src="{{asset('uploads/'.$item->indeximg)}}">
                    <script src="{{asset('resources/views/uploadify/jquery.uploadify.min.js')}}" type="text/javascript"></script>
                    <link rel="stylesheet" type="text/css" href="{{asset('resources/views/uploadify/uploadify.css')}}">
                    <script type="text/javascript">
                        <?php $timestamp = time();?>
                        $(function() {
                            $('#file_upload').uploadify({
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
                    </script>
                    <style>
                        .uploadify{display:inline-block;}
                        .uploadify-button{border:none; border-radius:5px; margin-top:8px;}
                        table.add_tab tr td span.uploadify-button-text{color: #FFF; margin:0;}
                    </style>
                </td>
            </tr>

            <tr>
                <th><i class="require">*</i>文字描述：</th>
                <td>
                    <input type="text" name="describe" value="{{$item->describe}}">
                </td>
            </tr>

            <tr>
                <th><i class="require">*</i>原价：</th>
                <td>
                    <input type="number" name="prime_price" value="{{$item->prime_price}}">
                </td>
                <th><i class="require">*</i>现价：</th>
                <td>
                    <input type="number" name="cur_price" value="{{$item->cur_price}}">
                </td>
                <th><i class="require">*</i>库存(-1无限制)：</th>
                <td>
                    <input type="number" name="stock" value="{{$item->stock}}">
                </td>
                <th>售出数量：</th>
                <td>
                    <input type="number" name="buynum" value="{{$item->buynum}}">
                </td>
            </tr>
            <tr>
                <th>规格：</th>
                <td>
                    <table id="spec_table">
                        <tbody>
                        <tr><input type="text" id="spec" value="{{$item->spec}}" hidden="true"></tr>
                        <tr>
                            <th>商品规格</th>
                            <td><span onclick="add_specattr(this)">添加规格组</span></td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>图片描述：</th>
                <td>
                    <input type="text" name="content" value="{{$item->content}}">
                </td>
            </tr>

            <tr>
                <th><i class="require">*</i>物品详情轮播：</th>
                <td>
                    <input type="text" name="showimg" value="{{$item->showimg}}">
                </td>
            </tr>

            <tr>
                <th>首页轮播：</th>
                <td>
                    <input type="text" name="activity" value="{{$item->activity}}">
                </td>

                <th>首页显示：</th>
                <td>
                    <input type="text" name="showindex" value="{{$item->showindex}}">
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
    $(document).ready(function(){
        var spec = document.getElementById('spec').value;
        if (0 == spec.length){
            return;
        }

        var specJson = JSON.parse(spec);
        var specTable = document.getElementById('spec_table');
        var html = '';
        for(var specNam in specJson){
            var ispecLens = specJson[specNam].length;
            var attr = '<tr>\
                        <th></th>\
                        <td>\
                        <dl class="spec_attr">\
                        <dt>规格名：<input type="text" name="spec_name[]" value="'+specNam+'"> <span onclick="add_spec_value(this)"><i class="fa fa-plus-circle"></i></span></dt>\
                        <dd>规格值：';

            for (var i = 0; i < ispecLens; i++){
                var specVal = specJson[specNam][i];
                attr += '<input type="text" name="spec_value[]" onchange="spec_total(this)" value="'+specVal+'">';
            }

            attr += '</dd>\
                    </dl>\
                    </td>\
                    </tr>';

            html += attr;
        }

        if (0 != html.length){
            $(specTable).find('tbody').append(html);
        }
    });

    //点击添加属性框
    function add_specattr(obj){
        var attr = '<tr>\
                    <th></th>\
                    <td>\
                        <dl class="spec_attr">\
                            <dt>规格名：<input type="text" name="spec_name[]"> <span onclick="add_spec_value(this)"><i class="fa fa-plus-circle"></i></span></dt>\
                            <dd>规格值：<input type="text" name="spec_value[]" onchange="spec_total(this)"></dd>\
                        </dl>\
                    </td>\
                </tr>';
        $(obj).parents('tbody').append(attr);
    }
    function add_spec_value(obj){
        var input = '<input type="text" name="spec_value[]" onchange="spec_total(this)">';
        $(obj).parents('dl').find('dd').append(input);
    }
    function spec_total(obj){
        var specAttr = {};
        $('.spec_attr').each(function(i) {
            var specNam = $(this).find('[name*=spec_name]').val();
            if (specAttr[specNam]){
                alert('规格名称重复!');
                return;
            }

            var specValue = [];
            //遍历读取所有属性值
            $(this).find('[name*=spec_value]').each(function(j) {
                var specV = $(this).val();
                if (0 != specV.length){
                    specValue[j] = specV;
                }
            });

            specAttr[specNam] = specValue;
        });

        var specJson = JSON.stringify(specAttr);
        var specInput = document.getElementById("spec");
        specInput.value = specJson;
    }
</script>


@endsection
