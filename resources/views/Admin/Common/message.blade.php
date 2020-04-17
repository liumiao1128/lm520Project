@extends('admin.layouts.master')

@section('title', $page_title)

@section('content')
@if($wait_time >= 0)
    <script>setTimeout("window.location.href ='{{ $url }}';", "{{ $wait_time }}");</script>
@endif
<body>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12 message_div">
            <div class="ibox-content p-xl no_border_message">
                <div class="well m-t"><strong>提示信息：</strong>{{ $msg }}</div>
                <div class="text-left">
                    <a href="{{ $url }}"><button type="button" class="btn btn-primary">确定</button></a>
                    <button type="button" class="btn btn-primary" onclick="javascript:history.go(-1);">返回上一页</button>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
@endsection