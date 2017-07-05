@include('admin.meta')
<title>角色管理</title>
</head>
<body>
<nav class="breadcrumb">
    <i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 系统管理 <span class="c-gray en">&gt;</span>角色管理
    <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="Hui-iconfont">&#xe68f;</i>
    </a>
</nav>
<div class="page-container">
    <div class="text-c">
        <input type="text" class="input-text" style="width:250px" placeholder="角色名" id="name" name="name">
        <button type="button" class="btn btn-success" id="search-group-by-key"><i class="Hui-iconfont">&#xe665;</i> 搜索
        </button>
    </div>
    <div class="cl pd-5 bg-1 bk-gray mt-20">
        <span class="l">
            <a href="javascript:;" onclick="admin_group_add()"
               class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加角色</a>
        </span>
    </div>
    <table class="table table-border table-bordered table-hover table-bg" id="table-sort">
        <thead>
        <tr class="text-c">
            <th width="10%">ID</th>
            <th width="20%">角色名</th>
            <th width="20%">创建时间</th>
            <th width="20%">更新时间</th>
            <th width="30%">操作</th>
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
        "bProcessing": true,//当表格在处理的时候（比如排序操作）是否显示“处理中...”
        "bServerSide": true,//服务器模式
        "autoWidth": false,
        "sort": "position",
        "ordering": false,//全局禁用排序
        "deferRender": true,//延迟渲染
        "bStateSave": false,//在第三页刷新自动到第一页
        "bLengthChange": false,//是否允许终端用户从一个选择列表中选择分页的页数，页数为10，25，50和100，需要分页组件bPaginate的支持
        "ajax": {
            type: 'GET',
            url: '{{url('admin/group/index')}}',
            data: function (result) {
                result.name = $("#name").val();
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
            {"data": "id"},
            {"data": "name"},
            {"data": "created_at"},
            {"data": "updated_at"},
            {"data": "operation"}
        ],
        "columnDefs": [
            {
                "targets": [4],
                "data": "operation",
                "render": function (data, type, full) {
                    return '<a title="编辑" href="javascript:;" onclick="admin_group_edit(' + full["id"] + ',\'' + full['name'] + '\')" class="ml-5"style="text-decoration:none">' +
                            ' <i class="Hui-iconfont">&#xe6df;</i>编辑' +
                            ' </a>' +
                            '<a title="删除" href="javascript:;" onclick="admin_group_del(' + full["id"] + ')" class="ml-5"style="text-decoration:none">' +
                            ' <i class="Hui-iconfont">&#xe6e2;</i>删除' +
                            ' </a>' +
                            '<a title="查看" href="javascript:;" onclick="admin_users_by_id(\'查看用户列表\',\'{{url("admin/group/user-list")}}\',' + full["id"] + ',\'400\',\'450\')" class="ml-5"style="text-decoration:none">' +
                            ' <i class="Hui-iconfont">&#xe62c;</i>查看关联用户' +
                            ' </a>' +
                            '<a title="编辑" href="javascript:;" onclick="admin_users_operation_by_id(\'编辑权限\',\'{{url("admin/operation/")}}\',' + full["id"] + ')" class="ml-5"style="text-decoration:none">' +
                            ' <i class="Hui-iconfont">&#xe6a8;</i>编辑权限' +
                            ' </a>';
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

    $(document).on('click', '#search-group-by-key', function () {
        table.draw();
    });

    //查看角色对应的用户列表
    function admin_users_by_id(title, url, id, w, h) {
        layer_show(title, url + "?id=" + id, w, h);
    }

    //编辑权限
    function admin_users_operation_by_id(title, url, id) {
        var index = layer.open({
            type: 2,
            title: title,
            content: url + '?id=' + id
        });
        layer.full(index);
    }

    //添加角色
    function admin_group_add() {
        layer.prompt({title: '请输入角色名称', formType: 0}, function (pass, index) {
            var index1 = layer.msg('正在提交,请稍后...', {icon: 16});
            $.ajax({
                type: "POST",
                url: "{{url('admin/group/')}}",
                data: {
                    "_token": "{{csrf_token()}}",
                    "name": pass
                },
                success: function (result) {
                    layer.close(index1);
                    if (result.errno == 0) {
                        layer.close(index);
                        table.ajax.reload();
                        layer.msg('添加成功！', {icon: 1, time: 1500});
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

    //更新角色名称
    function admin_group_edit(id, name) {
        layer.prompt({title: '更改' + name, value: name, formType: 0}, function (pass, index) {
            var index1 = layer.msg('正在提交,请稍后...', {icon: 16});
            $.ajax({
                type: "PUT",
                url: "{{url('admin/group/')}}",
                data: {
                    "_token": "{{csrf_token()}}",
                    "name": pass,
                    "id": id
                },
                success: function (result) {
                    layer.close(index1);
                    if (result.errno == 0) {
                        layer.close(index);
                        table.ajax.reload();
                        layer.msg('更改成功！', {icon: 1, time: 1500});
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

    //删除角色
    function admin_group_del(id) {
        var index = layer.msg('正在提交,请稍后...', {icon: 16});
        $.ajax({
            type: "DELETE",
            url: "{{url('admin/group/')}}",
            data: {
                "_token": "{{csrf_token()}}",
                "id": id
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
</script>
</body>
</html>