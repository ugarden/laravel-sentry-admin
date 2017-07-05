@include('admin.meta')
<title>权限管理</title>
</head>
<body>
<article class="page-container">
    <div class="formControls">
        @foreach($data as $k=>$v)
            <dl class="permission-list">
                <dt>
                    <label class="">
                        <input type="checkbox" value="" name="user-Character-0-0" id="user-Character-0-0">
                        {{ $k }}</label>
                </dt>
                <dd>
                    @foreach($v as $k1 => $v1)
                        <label class="">
                            <input type="checkbox" value="{{ implode(',', $v1) }}"
                                   name="user-Character-0-0-0" {{!array_diff($v1, $group_operation) ? 'checked' : ''}}>
                            {{ $k1 }}</label>
                    @endforeach
                </dd>
            </dl>
        @endforeach
    </div>
    <div class="row cl" style="margin-top: 10px;">
        <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
            <button type="button" class="btn btn-success radius" id="admin-role-save"><i
                        class="Hui-iconfont">&#xe6a7;</i> 保存
            </button>
            <button type="button" class="btn btn-primary radius" id="admin-role-refresh"><i
                        class="Hui-iconfont">&#xe68f;</i> 刷新
            </button>
        </div>
    </div>
</article>

@include('admin.footer')
        <!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript"
        src="{{asset('H-ui.admin_v3.0/lib/jquery.validation/1.14.0/jquery.validate.js')}}"></script>
<script type="text/javascript"
        src="{{asset('H-ui.admin_v3.0/lib/jquery.validation/1.14.0/validate-methods.js')}}"></script>
<script type="text/javascript" src="{{asset('H-ui.admin_v3.0/lib/jquery.validation/1.14.0/messages_zh.js')}}"></script>

<script type="text/javascript">
    $(function () {
        $(".permission-list dt input:checkbox").click(function () {
            $(this).closest("dl").find("dd input:checkbox").prop("checked", $(this).prop("checked"));
        });

        //保存
        $(document).on('click', '#admin-role-save', function () {
            var actions = [];
            $("input:checkbox").each(function (index, element) {
                var action_str = $(element).val();
                var splits = action_str.split(',');
                var is_checked = $(element).is(':checked');
                splits.forEach(function (v) {
                    if (is_checked == true)
                        actions.push({'key': v, 'value': 1});
                    else
                        actions.push({'key': v, 'value': 0})
                });
            });
            var index = layer.msg('正在提交,请稍后...', {icon: 16});
            $.ajax({
                type: 'POST',
                url: "{{ url('admin/operation/') }}",
                data: {
                    _token: '{{csrf_token()}}',
                    id: '{{$group_id}}',
                    operation: actions
                },
                success: function (result) {
                    layer.close(index);
                    if (result.errno == 0) {
                        var index2 = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index2);
                        parent.layer.msg('授权成功!', {icon: 1, time: 1500});
                    } else {
                        layer.msg(result.errmsg, {icon: 2, time: 1500});
                    }
                }
            })
        });

        //刷新
        $(document).on('click', '#admin-role-refresh', function () {
            var index = layer.msg('正在刷新,请稍后...', {icon: 16});
            $.ajax({
                type: 'PUT',
                url: "{{ url('admin/operation/') }}",
                data: {
                    _token: '{{csrf_token()}}'
                },
                success: function (result) {
                    layer.close(index);
                    if (result.errno == 0) {
                        location.href = "{{url("admin/operation/")}}?id={{$group_id}}";
                        parent.layer.msg('刷新成功!', {icon: 1, time: 1500});
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
            })
        });
    });
</script>
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>