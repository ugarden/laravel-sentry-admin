@include('admin.meta')
<title>我的桌面</title>
</head>
<body>
<div class="page-container">
    <p class="f-20 text-success">欢迎使用植物百科后台应用管理系统！</p>
    <table class="table table-border table-bordered table-bg mt-20">
        <thead>
        <tr>
            <th colspan="2" scope="col">服务器信息</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="30%">服务器域名</td>
            <td>{{$_SERVER['SERVER_NAME']}}</td>
        </tr>
        <tr>
            <td>服务器端口</td>
            <td>{{$_SERVER['SERVER_PORT']}}</td>
        </tr>
        <tr>
            <td>经验证的用户</td>
            <td></td>
        </tr>
        <tr>
            <td>本文件所在文件夹</td>
            <td></td>
        </tr>
        <tr>
            <td>服务器操作系统</td>
            <td></td>
        </tr>
        <tr>
            <td>系统所在文件夹</td>
            <td></td>
        </tr>
        </tbody>
    </table>
</div>
<footer class="footer mt-20">
    <div class="container">
        <p>
            感谢H-ui、jQuery、layer、laypage、Validform、UEditor、My97DatePicker、iconfont、Datatables、WebUploaded、icheck、bootstrap-Switch<br>
            Copyright@ 2013-2017 Vbuy. All Rights Reserved.
            本系统由广州微百信息技术有限公司提供技术支持</p>
    </div>
</footer>
</body>
</html>