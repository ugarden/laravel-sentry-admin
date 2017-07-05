<header class="navbar-wrapper">
    <div class="navbar navbar-fixed-top">
        <div class="container-fluid cl">
            <a class="logo navbar-logo f-l mr-10 hidden-xs" href="{{url('admin/index')}}">{{env('PROJECT_TITLE')}}</a>
            <a class="logo navbar-logo-m f-l mr-10 visible-xs"
               href="{{url('admin/index')}}">{{env('PROJECT_MIN_TITLE')}}</a>
            <span class="logo navbar-slogan f-l mr-10 hidden-xs">{{env('PROJECT_VERSION')}}</span>
            <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
            <nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
                <ul class="cl">
                    {{--<li>{{$user->first_name}}{{$user->last_name}}</li>--}}
                    <li class="dropDown dropDown_hover">
                        <a href="#" class="dropDown_A">{{$user->first_name}}{{$user->last_name}} <i
                                    class="Hui-iconfont">&#xe6d5;</i></a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            <li><a href="{{url('admin/logout')}}">退出</a></li>
                        </ul>
                    </li>
                    <li id="Hui-skin" class="dropDown right dropDown_hover">
                        <a href="javascript:;" class="dropDown_A" title="换肤"><i class="Hui-iconfont"
                                                                                style="font-size:18px">&#xe62a;</i></a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            <li><a href="javascript:;" data-val="default" title="默认（黑色）">默认（黑色）</a></li>
                            <li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
                            <li><a href="javascript:;" data-val="green" title="绿色">绿色</a></li>
                            <li><a href="javascript:;" data-val="red" title="红色">红色</a></li>
                            <li><a href="javascript:;" data-val="yellow" title="黄色">黄色</a></li>
                            <li><a href="javascript:;" data-val="orange" title="绿色">橙色</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>