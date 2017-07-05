@include('admin.meta')
<title>用户管理</title>
</head>
<body>
<nav class="breadcrumb">
    <i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 系统管理 <span class="c-gray en">&gt;</span>用户管理
    <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="Hui-iconfont">&#xe68f;</i>
    </a>
</nav>
<div class="page-container">
    <div class="text-c">
        <input type="text" class="input-text" style="width:250px" placeholder="用户账号" id="email" name="email">
        <button type="button" class="btn btn-success" id="search-user-by-key"><i class="Hui-iconfont">&#xe665;</i> 搜索
        </button>
    </div>
    <div class="cl pd-5 bg-1 bk-gray mt-20">
        <span class="l">
            <a href="javascript:;" onclick="admin_user_del()" class="btn btn-danger radius"><i class="Hui-iconfont">
                    &#xe6e2;</i> 批量删除</a>
            <a href="javascript:;" onclick="admin_user_add('添加用户','{{url("admin/user/create")}}','800','450')"
               class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加用户</a>
        </span>
    </div>
    <table class="table table-border table-bordered table-hover table-bg" id="table-sort">
        <thead>
        <tr class="text-c">
            <th width="5%"><input type="checkbox" id="checkbtn"></th>
            <th width="10%">ID</th>
            <th width="20%">用户账号</th>
            <th width="10%">用户姓名</th>
            <th width="5%">状态</th>
            <th width="15%">激活时间</th>
            <th width="15%">最近登录时间</th>
            <th width="20%">操作</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@include('admin.footer')
<script type="text/javascript"
        src="{{asset('H-ui.admin_v3.0/lib/datatables/1.10.0/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript">
    var table = $('#table-sort').DataTable({
        "aLengthMenu": [10, 20, 40, 60],
        "searching": true,//禁用搜索
        "length": 10,
        "start": 0,
        "paging": true,//显示每页显示数选择器
        "bProcessing": true,//翻页显示加载状态
        "bServerSide": true,//服务器模式
        "bAutoWidth": false,
        "sort": "position",
        "ordering": false,//全局禁用排序
        "deferRender": true,//延迟渲染
        "bStateSave": false,//在第三页刷新自动到第一页
        "bLengthChange": false,   //去掉每页显示多少条数据方法
        "ajax": {
            type: 'GET',
            url: '{{url('admin/user/index')}}',
            data: function (result) {
                result.email = $("#email").val();
            },
            dataSrc: function (result) {
                if (result.errno == 402) {
                    layer.confirm('用户登录已失效或在其他地点登录，是否重新登录？', function (index) {
                        window.top.location.href = "{{url('admin/login')}}";
                    });
                }
                return result.data;
            }
        },
        "dom": '<"top">t<"bottom"ip><"#pageprocessing"r><"clear">',
        "columns": [
            {"data": "check"},
            {"data": "id"},
            {"data": "email"},
            {"data": "first_name"},
            {data: "activated"},
            {data: "activated_at"},
            {"data": "last_login"},
            {"data": "operation"},
        ],
        "columnDefs": [
            {
                "targets": [0],
                "data": "check",
                "render": function (data, type, full) {
                    return "<input type='checkbox' name='user_check' value='" + full['id'] + "'>";
                }
            },
            {
                "targets": [3],
                "data": "first_name",
                "render": function (data, type, full) {
                    return full['first_name'] + full['last_name'];
                }
            },
            {
                "targets": [4],
                "data": "activated",
                "render": function (data, type, full) {
                    if (data == 1)
                        return '<span class="label label-success radius">已激活</span>';
                    else
                        return '<span class="label label-default radius">禁用</span>';
                }
            },
            {
                "targets": [7],
                "data": "operation",
                "render": function (data, type, full) {
                    var str = '<a title="编辑" href="javascript:;" onclick="admin_user_edit(\'编辑用户\',\'{{url("admin/user/edit")}}\',' + full["id"] + ',\'800\',\'450\')" class="ml-5"style="text-decoration:none">' +
                            ' <i class="Hui-iconfont">&#xe6df;</i>编辑' +
                            ' </a>' +
                            '<a title="重置密码" href="javascript:;" onclick="admin_password_reset(\'重置密码\',\'{{url("admin/user/password")}}\',' + full["id"] + ',\'800\',\'450\')" class="ml-5"style="text-decoration:none">' +
                            ' <i class="Hui-iconfont">&#xe66c;</i>重置密码' +
                            ' </a>';
                    if (full['activated'] == 1) {
                        str = str + '<a style="text-decoration:none" onClick="admin_update_status(' + full["activated"] + ',this,' + full["id"] + ')" href="javascript:;"title="激活">' +
                                '<i class="Hui-iconfont">&#xe631;</i>禁用' +
                                '</a>';
                    } else {
                        str = str + '<a style="text-decoration:none" onClick="admin_update_status(' + full["activated"] + ',this,' + full["id"] + ')" href="javascript:;"title="禁用">' +
                                '<i class="Hui-iconfont">&#xe615;</i>激活' +
                                '</a>';
                    }
                    return str;
                }
            }
        ],
        "createdRow": function (row, data, dataIndex) {
            $(row).addClass('text-c');
        },
        "drawCallback": function () {//当每次表格重绘的时候触发一个操作，比如更新数据后或者创建新的元素
            $(this).find('thead input[type=checkbox]').removeAttr('checked');
        }
    });

    $(document).on('click', '#search-user-by-key', function () {
        table.draw();
    });

    //添加用户
    function admin_user_add(title, url, w, h) {
        layer_show(title, url, w, h);
    }

    //编辑用户
    function admin_user_edit(title, url, id, w, h) {
        layer_show(title, url + "?id=" + id, w, h);
    }

    //重置密码
    function admin_password_reset(title, url, id, w, h) {
        layer_show(title, url + "?id=" + id, w, h);
    }

    //删除用户
    function admin_user_del() {
        if ($("input[name=user_check]:checked").size() == 0) {
            layer.msg('请最少选择一个进行删除操作！', {icon: 5, time: 1500});
        } else {
            var ids = [];
            $("input[name='user_check']:checked").each(function () {
                ids.push($(this).val());
            });
            var index = layer.msg('正在提交,请稍后...', {icon: 16});
            $.ajax({
                type: "delete",
                url: "{{url('admin/user/')}}",
                data: {
                    "_token": "{{csrf_token()}}",
                    "ids": ids
                },
                success: function (result) {
                    layer.close(index);
                    if (result.errno == 0) {
                        table.ajax.reload();
                        layer.msg('删除成功!', {icon: 1, time: 1500});
                    } else {
                        if (result.errno == 402) {
                            layer.confirm('用户登录已失效或在其他地点登录，是否重新登录？', function (index) {
                                window.top.location.href = "{{url('admin/login')}}";
                            });
                        } else {
                            layer.msg(result.errmsg, {icon: 2, time: 1500});
                        }
                    }
                }
            });
        }
    }

    //用户状态操作
    function admin_update_status(activated, f, id) {
        layer.confirm('确认要操作吗？', function (index) {
            var index = layer.msg('正在提交,请稍后...', {icon: 16});
            $.ajax({
                type: "GET",
                url: "{{url('admin/user/status')}}",
                data: {
                    "id": id,
                    "activated": activated
                },
                success: function (result) {
                    layer.close(index);
                    if (result.errno == 0) {
                        table.ajax.reload();
                        layer.msg('操作成功!', {icon: 1, time: 1500});
                    } else {
                        if (result.errno == 402) {
                            layer.confirm('用户登录已失效或在其他地点登录，是否重新登录？', function (index) {
                                window.top.location.href = "{{url('admin/login')}}";
                            });
                        } else {
                            layer.msg(result.errmsg, {icon: 2, time: 1500});
                        }
                    }
                }
            });
        });
    }
</script>
</body>
</html>