@include('admin.meta')
<title>用户列表</title>
</head>
<body>
<table class="table table-border table-bg table-bordered">
    <thead>
    <tr class="text-c">
        <th>用户名</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr class="active text-c">
            <td>{{$user->first_name}}{{$user->last_name}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>