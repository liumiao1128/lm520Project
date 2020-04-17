<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>后台管理</title>
    <link href="{{ asset('admin/css/login.css') }}" rel="stylesheet" type="text/css"/>
    <script src="{{ asset('admin/js/jquery-1.11.0.js') }}"></script>
</head>
<body>
<div class="login_box">
    <div class="login_l_img"><img src="{{ asset('admin/images/login-img.png') }}"/></div>
    <div class="login">
        <div class="login_logo"><a href="#"><img src="{{ asset('admin/images/login_logo.png') }}"/></a></div>
        <div class="login_name">
            <p>后台管理系统</p>
        </div>
        <form id="login_form" name="login_form" method="post" action="{{ url('admincp/login') }}">
            <input name="username" id="username" type="text" value="" placeholder="用户名">
            <input name="password" id="password" type="password" value="" placeholder="密码">
            <input maxlength="10" name="yam" type="text" id="yam" placeholder="验证码"
                   style="width: 190px;float: left;margin-right: 2px"/>
            <img src="{!! captcha_src() !!}" alt="验证码" title="点击换一张" id="captcha_img" width="100" height="50"
                 border="0">
            <input value="登录" style="width:100%;" type="button" id="btn_login">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <p class="text-muted text-center" style="color:red;" id="msg">
                @if (count($errors) > 0)
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                @endif
            </p>
        </form>
    </div>
    <div class="copyright">某某有限公司 版权所有©2016-2018 技术支持电话：000-00000000</div>
</div>
</body>
<script src="{{ asset('admin/js/bootstrap.min.js?v=3.3.6') }}"></script>
<script>
    function form_check() {
        var username = $('#username').val();
        var password = $('#password').val();
        var yanzhengma = $('#yam').val();

        if (username.length == 0) {
            $('#msg').html('您还没有输入用户名!');
            return false;
        }
        if (password.length == 0) {
            $('#msg').html('您还没有输入密码!');
            return false;
        }
        if (yanzhengma.length == 0) {
            $('#msg').html('您还没有输入验证码!');
            return false;
        }
        return true;
    }

    function get_captcha() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: "{{ url('admincp/getCaptcha') }}",
            data: '',
            dataType: 'json',
            success: function (data) {
                if (data.code == "1") {
                    $("#captcha_img").attr("src", data.data);
                }
            },
            error: function () {
            }
        });
    }

    $(function () {
        $('#btn_login').click(function () {
            if (form_check()) {
                $("#login_form").submit();
            }
        });
        $("#captcha_img").click(function () {
            get_captcha();
        })
    });
</script>
</html>
