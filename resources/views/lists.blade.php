<!doctype html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport" />
	<meta content="yes" name="apple-mobile-web-app-capable" />
	<meta content="black" name="apple-mobile-web-app-status-bar-style" />
	<meta content="telephone=no" name="format-detection" />
	<meta content="email=no" name="format-detection" />
    <title>沟通中</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/themes.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/h5app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/iconfont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/chat_list.css') }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/dist/flexible/flexible_css.debug.js') }}"></script>
    <script src="{{ asset('js/dist/flexible/flexible.debug.js') }}"></script>
</head>
<body>
<div class='fui-page-group'>
    <div class="fui-statusbar"></div>
<div class='fui-page chat-page'>
	<div class="fui-header">
	    <div class="title">消息列表</div>
	    <div class="fui-header-right"></div>
	</div>

	<div class="fui-content navbar chat-fui-content" style="padding-bottom: 2rem;"></div>
</div>
</div>
</body>
<script>
    const API_URL = 'http://local.chat.com/api/';
    let fromid = parseInt('{{ $req['fromid'] }}');

    let ws = new WebSocket('ws://127.0.0.1:8282');
    ws.onmessage = function(e){
        let data;
        if (isJsonString(e.data)) {
            data = JSON.parse(e.data);

            switch (data.type) {
                case 'init':
                    let bind = {
                        'type':"bind",
                        'fromid':fromid
                    };
                    ws.send(JSON.stringify(bind));
                    lists();
                    break;
                case 'text':
                    $('.chat-fui-content').html('');
                    lists();
                    break;
                case 'say_img':
                    $('.chat-fui-content').html('');
                    lists();
                    break;
            }
        } else {
            data = e.data;
        }
        console.log(data);
    }


    function lists(){
        $.get(API_URL+"get_list", {id:fromid}, function(data){
            let html = '';
            $(data).each(function(k, v){
                var no_read = '';
                if (v.count_not_read) {
                    no_read = '<span class="badge" style="top: -0.1rem;left: 80%;">'+v.count_not_read+'</span>';
                }
                html += '\
				<div class="chat-list flex" onclick="clickTo(\''+v.chat_page+'\');" >\
					<div class="chat-img"  style="background-image: url('+v.head_url+')">\
						'+no_read+'\
					</div>\
					<div class="chat-info">\
						<p class="chat-merch flex">\
							<span class="title t-28">'+v.username+'</span>\
							<span class="time">'+mydate(v.last_message.time)+'</span>\
						</p>\
						<p class="chat-text singleflow-ellipsis">'+v.last_message.content+'</p>\
					</div>\
				</div>';
            });
            $('.chat-fui-content').append(html);
        });
    }

    function isJsonString(str) {
        try {
            if (typeof JSON.parse(str) == "object") {
                return true;
            }
        } catch(e) {
        }
        return false;
    }

    function clickTo(url){
        window.location.href = url;
    }

    /**
     *根据时间戳格式化为日期形式
     */
    function mydate(nS){
        return new Date(parseInt(nS) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
    }
</script>

</html>
