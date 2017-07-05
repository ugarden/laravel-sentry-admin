<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>api文档</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('easyui/themes/default/easyui.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('easyui/themes/icon.css') }}">
    <script type="text/javascript" src="{{ asset('easyui/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('easyui/jquery.easyui.min.js') }}"></script>
    <!-- The fav icon -->
    <link rel="shortcut icon" href="{{asset('images/admin/favicon.ico')}}">
    <script type="text/javascript">

    </script>
    <style type="text/css">
        body {
            font-family: Consolas;
        }

        pre {
            font-family: Consolas;
            line-height: 30px;
        }

        .module {
            font-weight: bold;
        }

        .panel-body {
            overflow-x: hidden;
        }

        .tree-icon {
            display: none;
        }

        dd {
            margin-left: 20px;
            line-height: 25px
        }

        .tree-title {
            font-size: 14px;
            width: calc(100% - 32px);
            width: -moz-calc(100% - 32px);
            width: -webkit-calc(100% - 32px);
            display: inline-block;
            position: relative;
        }

        .tree-title a {
            text-decoration: none;
        }

        p td {
            font-size: 16px;
        }

        .tree-node {
            height: 30px;
        }

        .tree-node > span {
            height: 30px;
            line-height: 30px;
        }

        .tree-expanded {
            background: none;
        }

        .tree-hit {
            display: none
        }

        .tree-expanded ~ .tree-title:after {
            content: '-';
            font-weight: bold;
            font-size: 16px;
            position: absolute;
            right: 20px;
        }

        .tree-collapsed {
            background: none;
        }

        .tree-collapsed ~ .tree-title:after {
            content: '+';
            font-weight: bold;
            font-size: 16px;
            position: absolute;
            right: 20px;
        }

        .page {
            border: 1px solid white;
            box-shadow: 0 0 5px #111;
            width: 900px;
            margin: 20px auto 20px auto;
            background-color: white;
            min-height: calc(100% - 40px);
            min-height: -moz-calc(100% - 40px);
            min-height: -webkit-calc(100% - 40px);
        }

        .page > h2 {
            text-align: center;
        }

        #tab .panel-body {
            background-color: #c0c2cc;
        }

        .block {
            margin: 50px 40px 0 40px;
            border-bottom: 1px solid #0072c6;
        }

        .block:last-child {
            border: none;
            margin-bottom: 30px;
        }

        .title {
            height: 30px;
            line-height: 30px;
            font-size: 16px;
            font-weight: bold;
            color: #0072c6;
        }

        .content {
            font-size: 15px;
            text-indent: 40px;
            padding-bottom: 10px;
        }

        .content table {
            margin-left: 40px;
            width: calc(100% - 40px);
            width: -moz-calc(100% - 40px);
            width: -webkit-calc(100% - 40px);
        }

        .content table tbody tr:hover {
            background-color: #006dcc;
            color: white;
            cursor: pointer;
        }

        .content ol {
            margin-left: 20px;
        }

        .content th {
            font-weight: normal;
            color: grey;
        }

        .content th, td {
            padding: 5px 0;
            text-indent: 3px;
        }

        .content ol li {
            height: 26px;
            line-height: 26px;
        }

        #home li {
            margin: 20px 0;
            font-size: 15px;
        }

        #home pre {
            background-color: #f5f5f5;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 5px;
        }

        .remark {
            text-indent: 5px
        }

        #rebuild {
            position: fixed;
            left: 0;
            bottom: 0;
            z-index: 100;
            padding: 10px;
        }

        #rebuild a {
            font-size: 13px;
            text-decoration: none;
            color: black;
            width: 16px;
            height: 16px;
            display: block;
        }

    </style>
</head>
<body class="easyui-layout">
<div id="rebuild"><a class="icon-reload" href="{{ url('/doc?rebuild=1') }}" title="重新生成文档"></a></div>
{{--<div id="tools">
    <a href="{{ url('/doc?rebuild=1') }}" class="icon-reload" title="重新生成"></a>
</div>--}}

<div data-options="region:'west',split:true,title:'',tools:'#tools'" style="width:300px;">
    <ul id="tree" class="easyui-tree">
        @foreach($data as $k => $v)
            <li>
                <span> <span class="module">{{ $k }}</span> </span>
                <ul>

                    @foreach($v as $v1)
                        <li data-options="id:'{{ $v1->hash() }}'">
                                <span>{{ $v1->api }} - {{ $v1->url }}

                                    <script type="text/html" id="{{ $v1->hash() }}">
                                        <div class="page">
                                            <h2 style="margin: 40px auto 40px auto">{{ $v1->api }}</h2>

                                            @if($v1->desc)
                                                <div class="block">
                                                    <p class="title">说明</p>
                                                    <div class="content">{{ $v1->getDesc() }}</div>
                                                </div>
                                            @endif

                                            <div class="block">
                                                <p class="title">请求</p>
                                                <div class="content" style="font-style: italic;">
                                                    {{ $v1->method }} &nbsp;{{ $v1->url }}
                                                    @if ($v1->contentType)
                                                        <p>Content-Type: {{ $v1->contentType }}</p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="block">
                                                <p class="title">输入参数 {{ $v1->inRepeat }}</p>
                                                <div class="content">

                                                    @if ($v1->in)
                                                        <table>
                                                            <thead><tr><th>参数名</th><th>参数类型</th><th>是否必须</th><th>参数解释</th></tr></thead>
                                                            <tbody>
                                                            @foreach($v1->in as $in)
                                                                <tr>
                                                                    <td>{{ $in->name }}</td>
                                                                    <td>{{ $in->type }}</td>
                                                                    <td>{{ $in->require ? '是' : '否' }}</td>
                                                                    <td>{{ $in->comment }}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    @else
                                                        无
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="block">
                                                <p class="title">输出参数 {{ $v1->outRepeat }}</p>
                                                <div class="content">
                                                    @if ($v1->out)
                                                        <table>
                                                            <thead><tr><th>参数名</th><th>参数类型</th><th>参数解释</th></tr></thead>
                                                            <tbody>
                                                            @foreach($v1->out as $in)
                                                                <tr>
                                                                    <td>{{ $in->name }}</td>
                                                                    <td>{{ $in->type }}</td>
                                                                    <td>{{ $in->comment }}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    @else
                                                        无
                                                    @endif

                                                </div>
                                            </div>

                                            @if ($v1->remark)
                                                <div class="block">
                                                    <p class="title">备注</p>
                                                    <div class="content remark">
                                                        <ol>
                                                            @foreach($v1->remark as $v2)
                                                                <li>{{ $v2 }}</li>
                                                            @endforeach
                                                        </ol>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </script>
                                </span>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>

<div data-options="region:'center',border:false" style="background-color: #eee">
    <div id="tab">
        <div title="主页">
            <div class="page" id="home">
                <h2 style="margin: 40px auto 40px auto">接口说明</h2>
                <div class="block">
                    <ol>

                        <li>接口返回数据格式为:
                            <pre>{
    "errno": "错误号",
    "errmsg": "错误信息",
    "data": "数据"
}</pre>
                            <dl>
                                <dt></dt>
                                <dd>errno为0表示成功，非0表示失败。</dd>
                                <dd>3001: 账号被封禁</dd>
                                <dd>4001: 参数错误</dd>
                                <dd>4002: 数据库错误</dd>
                                <dd>4003: 数据不存在</dd>
                                <dd>4004: token错误</dd>
                            </dl>
                        </li>

                        <li>
                            测试url: http://202.105.84.2:9999
                        </li>
                        <li>
                            所有接口必须通过header传递token参数授权才可访问， "token":"9ef5a4cf5d69fadd6be6d16c8670316abd37d01dMTE4ODE="
                        </li>

                    </ol>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    var $tab = $('#tab')
    var $tree = $('#tree')

    $tab.tabs({
        border: false,
        fit: true
    })

    $tree.tree({
        onClick: function (node) {
            if (node.id)
                tab(node.text, $('#' + node.id).html())
            else
                $tree.tree('toggle', node.target)
        },
        onLoadSuccess: function () {
            $('.module').parent().css({'background-color': '#E0ECFF', width: '100%', 'padding-left': 10})
        }
    })


    function tab(title, content) {
        if ($tab.tabs('exists', title))
            $tab.tabs('select', title)
        else
            $tab.tabs('add', {
                title: title,
                content: content,
                closable: true
            })
    }

</script>
</body>
</html>


