<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>登录</title>
    <link rel="Bookmark" href="{{asset('images/admin/favicon.ico')}}">
    <link rel="Shortcut Icon" href="{{asset('images/admin/favicon.ico')}}"/>
    <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}" media="all"/>
    <link rel="stylesheet" href="{{asset('css/admin/login.css')}}"/>
</head>

<body class="beg-login-bg">
<div class="beg-login-box">
    <header>
        <h1>后台登录</h1>
    </header>
    <div class="beg-login-main">
        <form class="layui-form">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <div class="layui-form-item">
                <label class="beg-login-icon">
                    <i class="layui-icon">&#xe612;</i>
                </label>
                <input type="text" name="email" lay-verify="required" autocomplete="off" placeholder="这里输入登录名"
                       class="layui-input" value="{{!empty($_COOKIE["email"]) ? $_COOKIE["email"] : ''}}">
            </div>
            <div class="layui-form-item">
                <label class="beg-login-icon">
                    <i class="layui-icon">&#xe642;</i>
                </label>
                <input type="password" name="password" lay-verify="required" autocomplete="off" placeholder="这里输入密码"
                       class="layui-input" value="{{!empty($_COOKIE["password"]) ? $_COOKIE["password"] : ''}}">
            </div>
            <div class="layui-form-item">
                <label class="beg-login-icon">
                    <i class="layui-icon">&#xe609;</i>
                </label>
                <input type="text" name="verify_img" lay-verify="required" autocomplete="off" placeholder="请输入验证码"
                       class="layui-input" style="width: 50%;float: left;">&nbsp;
                <img src="{{captcha_src()}}" id="verify_img" onclick="javascript:refreshVerify()">
            </div>
            <div class="layui-form-item">
                <div class="beg-pull-left beg-login-remember">
                    <label>记住帐号？</label>
                    <input type="checkbox" name="remember_me" value="true"
                           {{!empty($_COOKIE["email"]) && !empty($_COOKIE["password"]) ? 'checked' : ''}}  lay-skin="switch"
                           title="记住帐号">
                </div>
                <div class="beg-pull-right">
                    <button class="layui-btn layui-btn-primary" lay-submit lay-filter="login">
                        <i class="layui-icon">&#xe650;</i> 登录
                    </button>
                </div>
                <div class="beg-clear"></div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
<script>
    layui.use(['layer', 'form'], function () {
        var layer = layui.layer,
                $ = layui.jquery,
                form = layui.form();

        //判断ie浏览器以及ie版本
        var DEFAULT_VERSION = "8.0";
        var ua = navigator.userAgent.toLowerCase();
        var isIE = ua.indexOf("msie") > -1;
        var safariVersion;
        if (isIE) {
            safariVersion = ua.match(/msie ([\d.]+)/)[1];
            if (safariVersion <= DEFAULT_VERSION) {
                layer.msg("请安装IE8以上的版本或者其他浏览器打开！", {shift: 6});
                return false;
            }
        }

        //判断浏览器是否禁用了cookie
        if (navigator.cookieEnabled == false) {
            layer.msg("您的浏览器禁用了cookie，请开启cookie！", {shift: 6});
            return false;
        }

        //判断浏览器是否安装flash插件
        if (has_flash() == false) {
            layer.msg("您的浏览器没有安装或者禁用了flash插件！", {shift: 6});
            return false;
        }

        //监听提交
        form.on('submit(login)', function (data) {
            var index = layer.msg('正在登录,请稍后...', {icon: 16});
            $.ajax({
                type: 'POST',
                data: data.field,
                url: '{{ url('admin/login')}}',
                success: function (result) {
                    layer.close(index);
                    if (result.errno == 0) {
                        location.href = "{{ url('admin/index') }}";
                    } else {
                        layer.msg(result.errmsg, {icon: 5, time: 3000, shift: 6});
                    }
                }
            });
            return false;
        });
    });

    function has_flash() {
        var isIE = !-[1,];
        if (isIE) {
            try {
                return !!new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
            }
            catch (e) {
            }
        }
        else {
            try {
                return !!navigator.plugins['Shockwave Flash'];
            }
            catch (e) {
            }
        }
        return false;
    }

    //切换验证码
    function refreshVerify() {
        var ts = Date.parse(new Date()) / 1000;
        var img = document.getElementById('verify_img');
        img.src = "{{url('/captcha')}}?id=" + ts;
    }
</script>
</body>
</html>