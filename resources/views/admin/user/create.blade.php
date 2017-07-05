@include('admin.meta')
<title>添加用户</title>
</head>
<body>
<article class="page-container">
    <form class="form form-horizontal" id="form-admin-add">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        @if(isset($model))
            <input type="hidden" name="id" value="{{$model->id}}">
        @endif
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>邮箱：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" autocomplete="off" placeholder="请输入邮箱" id="email" name="email"
                       value="{{isset($model) ? $model->email : ''}}">
            </div>
        </div>
        @if(!isset($model))
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>密码：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="password" class="input-text" autocomplete="off" placeholder="请输入密码" id="password"
                           name="password">
                </div>
            </div>
        @endif
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>姓：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" autocomplete="off" placeholder="请输入姓" id="first_name"
                       name="first_name" value="{{isset($model) ? $model->first_name : ''}}">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>名：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" autocomplete="off" placeholder="请输入名" id="last_name"
                       name="last_name" value="{{isset($model) ? $model->last_name : ''}}">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3">角色：</label>
            <div class="formControls col-xs-8 col-sm-9"> <span class="select-box" style="width:150px;">
			<select class="select" name="group_id">
                @if(isset($model))
                    @foreach($groups as $vo)
                        <option value="{{$vo->id}}" {{$group==$vo->id ? 'selected' : ''}}>{{$vo->name}}</option>
                    @endforeach
                @else
                    @foreach($groups as $vo)
                        <option value="{{$vo->id}}">{{$vo->name}}</option>
                    @endforeach
                @endif
            </select>
			</span></div>
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
        $("#form-admin-add").validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 6,
                },
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
            },
            onkeyup: false,
            focusCleanup: true,
            success: "valid",
            submitHandler: function (form) {
                var index1 = layer.msg('正在提交,请稍后...', {icon: 16});
                var type = "POST", tip = "添加成功!", url = "{{url('admin/user/create')}}";
                @if(isset($model))
                        type = "PUT";
                tip = "修改成功!";
                url = "{{url('admin/user/edit')}}";
                @endif
                $(form).ajaxSubmit({
                    type: type,
                    url: url,
                    success: function (result) {
                        layer.close(index1);
                        if (result.errno == 0) {
                            var index2 = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index2);
                            window.parent.table.ajax.reload();
                            parent.layer.msg(tip, {icon: 1, time: 1500});
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
</script>
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>