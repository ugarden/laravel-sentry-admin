@include('admin.meta')
<title>{{env('PROJECT_TITLE')}}{{env('PROJECT_VERSION')}}</title>
</head>
<body>
@include('admin.header')
<aside class="Hui-aside">
    <input runat="server" id="divScrollValue" type="hidden" value=""/>
    <div class="menu_dropdown bk_2">
        @foreach($menus as $menu)
            <dl>
                <dt><i class="Hui-iconfont">{{$menu['icon']}}</i> {{$menu['text']}}<i
                            class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i>
                </dt>
                <dd>
                    <ul>
                        @foreach($menu['children'] as $vo)
                            <li><a data-href="{{$vo['url']}}" data-title="{{$vo['text']}}"
                                   href="javascript:void(0)">{{$vo['text']}}</a>
                            </li>
                        @endforeach
                    </ul>
                </dd>
            </dl>
        @endforeach
    </div>
</aside>
<div class="dislpayArrow hidden-xs">
    <a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a>
</div>
<section class="Hui-article-box">
    <div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
        <div class="Hui-tabNav-wp">
            <ul id="min_title_list" class="acrossTab cl">
                <li class="active"><span title="我的桌面" data-href="{{url('admin/welcome')}}">我的桌面</span><em></em></li>
            </ul>
        </div>
        <div class="Hui-tabNav-more btn-group">
            <a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">
                    &#xe6d4;</i></a>
            <a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">
                    &#xe6d7;</i></a>
        </div>
    </div>
    <div id="iframe_box" class="Hui-article">
        <div class="show_iframe">
            <div style="display:none" class="loading"></div>
            <iframe scrolling="yes" frameborder="0" src="{{url('admin/welcome')}}"></iframe>
        </div>
    </div>
</section>

<div class="contextMenu" id="Huiadminmenu">
    <ul>
        <li id="closethis">关闭当前</li>
        <li id="closeall">关闭全部</li>
    </ul>
</div>
@include('admin.footer')
<script type="text/javascript"
        src="{{asset('H-ui.admin_v3.0/lib/jquery.contextmenu/jquery.contextmenu.r2.js')}}"></script>
<script type="text/javascript">
    $(function () {
        var $me = $('dl dd ul li');
        $me.click(function () {
            $.each($("dl"), function () {
                $("dd ul li").removeClass("current");
            });
            $(this).addClass("current")
        });

        $("#min_title_list li").contextMenu('Huiadminmenu', {
            bindings: {
                'closethis': function (t) {
                    if (t.find("i")) {
                        t.find("i").trigger("click");
                    }
                },
                'closeall': function (t) {
                    alert('Trigger was ' + t.id + '\nAction was Email');
                },
            }
        });
    });
</script>
</body>
</html>