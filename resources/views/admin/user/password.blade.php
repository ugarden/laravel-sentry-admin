@include('admin.meta')
<title>添加用户</title>
</head>
<body>
<article class="page-container">
    <form class="form form-horizontal" id="form-admin-reset-password">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="id" id="id" value="{{$id}}">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>重置代码：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" autocomplete="off" id="reset_password_code" style="width: 60%"
                       name="reset_password_code">&nbsp;<span style="cursor: pointer;" onclick="resetPasswordCode()">点击获取</span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>新密码：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="password" class="input-text" autocomplete="off" placeholder="请输入新密码" id="password"
                       name="password">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>重复新密码：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="password" class="input-text" autocomplete="off" placeholder="再输一次新密码" id="password1"
                       name="password1">
            </div>
        </div>
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
            </div>
        </div>
    </form>
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
        $("#form-admin-reset-password").validate({
            rules: {
                reset_password_code: {
                    required: true
                },
                password: {
                    required: true,
                    minlength: 6
                },
                password1: {
                    required: true,
                    equalTo: "#password"
                }
            },
            onkeyup: false,
            focusCleanup: true,
            success: "valid",
            submitHandler: function (form) {
                var index1 = layer.msg('正在提交,请稍后...', {icon: 16});
                $(form).ajaxSubmit({
                    type: "POST",
                    url: "{{url('admin/user/password')}}",
                    success: function (result) {
                        layer.close(index1);
                        if (result.errno == 0) {
                            var index2 = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index2);
                            parent.layer.msg("重置密码成功！", {icon: 1, time: 1500});
                        } else {
                            if (result.errno == 402) {
                                layer.confirm('用户登录已失效或在其他地点登录，是否重新登录？', function (index) {
                                    window.top.location.href = "{{url('admin/login')}}";
                                });
                            } else {
                                layer.msg(result.errmsg, {icon: 2, time: 1500});
                            }
                        }
                    },
                    error: function (XmlHttpRequest, textStatus, errorThrown) {
                        layer.msg('error!', {icon: 2, time: 1500});
                    }
                });
            }
        });
    });
    function resetPasswordCode() {
        var index = layer.msg('正在获取，请稍后...', {icon: 16});
        $.ajax({
            type: 'GET',
            url: '{{ url('admin/util/reset-password-code')}}?id=' + $('#id').val(),
            success: function (result) {
                layer.close(index);
                if (result.errno == 0) {
                    $('#reset_password_code').val(result.data.code);
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
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>