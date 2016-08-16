@extends('admin.layouts')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 提现申请
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->

    <div class="result_wrap">
        <!--快捷导航 开始-->
        <div class="result_content">
            <div class="short_wrap">
                <input type="text" placeholder="用户ID" id="search" name="search" value="{{$userID}}">
                <select id="searchCondition">
                    <option value="junior" @if ($condition == 'junior') selected @endif>下3级粉丝</option>
                    <option value="superior" @if ($condition == 'superior') selected @endif>上3级粉丝</option>
                </select>
                <input type="button" onclick="Search()" value="搜索">
            </div>
        </div>
        <!--快捷导航 结束-->
    </div>

    <form action="#" method="post">
        @if(!$userID)
            <div class="result_wrap">
                <div class="result_content">
                    <table class="list_tab">
                        <tr>
                            <th>
                                顶级用户ID
                            </th>
                            <th>组ID</th>
                            <th>级别</th>
                            <th>总人数</th>
                        </tr>
                        @foreach($data as $v)
                            <tr>
                                <td>
                                    <a href="javascript:;" onclick="showUser('{{url('admin/user/show')}}/{{$v->userid}}')">
                                        {{$v->userid}}
                                    </a>
                                </td>
                                <td>{{$v->groupid}}</td>
                                <td>{{$v->layer}}</td>
                                <td>{{$v->count}}</td>
                            </tr>
                        @endforeach
                    </table>

                    <div class="page_list">
                        {{$data->links()}}
                    </div>
                </div>
            </div>
        @else
            <div class="result_wrap">
                <div class="result_content">
                    <table class="list_tab">
                        <tr>
                            <th>
                                @if($condition == 'junior')
                                    粉丝ID
                                @else
                                    上级ID
                                @endif
                            </th>
                            <th>
                                @if($condition == 'junior')
                                    粉丝等级
                                @else
                                    上级等级
                                @endif
                            </th>
                        </tr>
                        @foreach($data as $v)
                            <tr>
                                <td class="tc">
                                    <a href="javascript:;" onclick="showUser('{{url('admin/user/show')}}/{{$v->userid}}')">
                                        {{$v->userid}}
                                    </a>
                                </td>
                                <td>
                                    @if($condition == 'junior')
                                        {{$v->layer - $myLayer->layer}}
                                    @else
                                        {{$myLayer->layer - $v->layer}}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>

                    <div class="page_list">
                        {{$data->links()}}
                    </div>
                </div>
            </div>
        @endif
    </form>
    <script>
        function Search() {
            var inputVal = document.getElementById('search').value;
            var inputType = document.getElementById('searchCondition').value;
            if (0 == inputVal.length){
                window.location.href='{{url('admin/user/follower')}}/'+inputType;
                return;
            }

            window.location.href='{{url('admin/user/follower')}}/'+inputType+'/' + inputVal;
        }
    </script>


@endsection
