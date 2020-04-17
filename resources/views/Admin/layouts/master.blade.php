<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="{{ asset('laYuiAdmin/layui/css/layui.css') }}"/>
    <link rel="stylesheet" href="{{ asset('laYuiAdmin/style/admin.css') }}"/>
    <script src="{{ asset('laYuiAdmin/layui/layui.js') }}"></script>
    <script src="{{ asset('admin/js/jquery-1.11.0.js') }}"></script>
    <title>安妮花App管理系统</title>
</head>
<script>
    layui.config({
        base: "../laYuiAdmin/" //静态资源所在路径
    }).extend({
        index: 'lib/index', //主入口模块
        selectN: 'layui/lay/modules/selectN',
        selectM: 'layui/lay/modules/selectM',
        dtree: 'layui/lay/modules/layui_ext/dtree/dtree',
    }).use('index');

    function getSign(params, kAppKey, kAppSecret) {
        if (typeof params == "string") {
            return paramsStrSort(params);
        } else if (typeof params == "object") {
            var arr = [];
            for (var i in params) {
                arr.push((i + "=" + params[i]));
            }
            //return arr.join(("&"));
            return paramsStrSort(arr.join(("&")));
        }
    }

    function paramsStrSort(paramsStr) {
        var urlStr = paramsStr.split("&").sort().join("&");
        return $.base64.encode($.md5(urlStr));
    }
</script>
