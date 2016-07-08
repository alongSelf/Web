@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 分类管理
    </div>
    <!--面包屑导航 结束-->

    <script src="{{asset('resources/views/uploadify/jquery.uploadify.min.js')}}" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('resources/views/uploadify/uploadify.css')}}">
    <style>
        .uploadify{display:inline-block;}
        .uploadify-button{border:none; border-radius:5px; margin-top:8px;}
        table.add_tab tr td span.uploadify-button-text{color: #FFF; margin:0;}
    </style>

    <!--结果集标题与导航组件 开始-->
    <div class="result_wrap">
        <div class="result_title">
            <h3>修改分类</h3>
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
        <form action="{{url('admin/category/'.$data->id)}}" method="post">
            <input type="hidden" name="_method" value="put">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                <tr>
                    <th><i class="require">*</i>分类名称：</th>
                    <td>
                        <input type="text" name="title" value="{{$data->title}}">
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>简介：</th>
                    <td>
                        <input type="text" style="width: 50%" name="describe" value="{{$data->describe}}">
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>图标：</th>
                    <td>
                        <input type="text" size="50" name="img" value="{{$data->img}}">
                        <input id="file_upload" name="file_upload" type="file" multiple="true">
                        <img alt="" id="category_img" style="width: 100px; height:100px; border-radius:50%;" src="{{asset('uploads/'.$data->img)}}">
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>排序：</th>
                    <td>
                        <input type="number" class="sm" name="sort" value="{{$data->sort}}">
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
                    $('input[name=img]').val(data);
                    $('#category_img').attr('src','/uploads/'+data);
                }
            });
        });
    </script>

@endsection
