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
    <script src="{{ asset('js/jquery1.8.2.min.js') }}"></script>
    <script src="{{ asset('js/dist/flexible/flexible_css.debug.js') }}"></script>
    <script src="{{ asset('js/dist/flexible/flexible.debug.js') }}"></script>
    <script src="{{ asset('js/ajaxfileupload.js') }}"></script>
    <script src="{{ asset('qqFace/js/jquery.qqFace.js') }}"></script>
    <style>
        .qqFace { margin-top: -180px; background: #fff; padding: 2px; border: 1px #dfe6f6 solid; }
        .qqFace table td { padding: 0px; }
        .qqFace table td img { cursor: pointer; border: 1px #fff solid; }
        .qqFace table td img:hover { border: 1px #0066cc solid; }
    </style>
</head>
<body>
<div class='fui-page-group'>
<div class='fui-page chatDetail-page'>
    <div class="chat-header flex">
        <i class="icon icon-toleft t-48"></i>
        <span class="shop-titlte t-30"></span>
        <span class="shop-online t-26"></span>
        <span class="into-shop">进店</span>
    </div>
    <div class="fui-content navbar" style="padding:1.2rem 0 1.35rem 0;">
        <div class="chat-content">
            <p style="display: none;text-align: center;padding-top: 0.5rem" id="more"><a>加载更多</a></p>
            <p class="chat-time"><span class="time">2017-11-12</span></p>
        </div>
    </div>
    <div class="fix-send flex footer-bar">
        <i class="icon icon-emoji1 t-50"></i>
        <input class="send-input t-28" maxlength="200" id="saytext">
        <input type="file" name="pic" id="file" style="display:none;" onchange="images_upload(this);" />
        <i class="icon icon-add image-up t-50" style="color: #888;" onclick="$('#file').trigger('click');"></i>
        <span class="send-btn" onclick="send_message();">发送</span>
    </div>
</div>
</div>

<script>
    const API_URL = 'http://local.chat.com/api/';
    const UPLOAD_URL = 'http://local.chat.com/uploads/';

    let sending = 0;
    let fromid = parseInt('{{ $req['fromid'] ?? 0 }}');
    let toid = parseInt('{{ $req['toid'] ?? 0 }}');

    let from_info = {};
    let to_info = {};

    let ws = new WebSocket('ws://127.0.0.1:8282');
    let online = 0;

    $(function(){
        $('.icon-emoji1').qqFace({
            assign:'saytext',
            path:'{{ asset('qqFace/arclist') }}/'	//表情存放的路径
        });

        scrollToBottom();
    });

    ws.onmessage = function(e){
        let data;

        if (isJsonString(e.data)) {
            data = JSON.parse(e.data);

            switch (data.type) {
                case 'init':
                    let bind = {
                        'type': 'bind',
                        'fromid': fromid
                    };
                    ws.send(JSON.stringify(bind));

                    get_info();
                    setTimeout(()=>{
                        load_message();
                    }, 500);

                    let online_data = {
                        'type': 'online',
                        'fromid': fromid,
                        'toid': toid
                    };
                    ws.send(JSON.stringify(online_data));
                    changeNoRead();
                    break;

                case 'text':
                    if (toid === parseInt(data.fromid)) {
                        let html = '<div class="chat-text section-left flex"><span class="char-img" style="background-image: url('+to_info.headimgurl+')"></span><span class="text"><i class="icon icon-sanjiao4 t-32"></i>'+replace_em(data.data)+'</span></div>';
                        $('.chat-content').append(html);
                        changeNoRead();
                        scrollToBottom();
                    }
                    break;

                case 'say_img':
                    let html = '<div class="chat-text section-left flex"><span class="char-img" style="background-image: url('+to_info.headimgurl+')"></span><span class="text"><i class="icon icon-sanjiao4 t-32"></i><img src="'+data.url+'" alt="" style="width:160px;" /></span></div>';
                    $('.chat-content').append(html);
                    changeNoRead();
                    scrollToBottom();
                    break;

                case 'save':
                    save_message(data);

                    if (data.isread === 1) {
                        online = 1;
                        $('.shop-online').html('在线');
                    } else {
                        online = 0;
                        $('.shop-online').html('离线');
                    }

                    break;

                case 'online':
                    if (data.state === 1) {
                        online = 1;
                        $('.shop-online').html('在线');
                    } else {
                        online = 2;
                        $('.shop-online').html('离线');
                    }
                    break;
            }
        } else {
            data = e.data;
        }

        console.log(data);
    }

    function get_info() {
        $.get(API_URL+'get_info', {'fromid':fromid, 'toid':toid}, function(data){
            from_info = data.from_info;
            to_info = data.to_info;

            $('.shop-titlte').html('与'+to_info.nickname+'聊天中...');
        });
    }

    function load_message() {
        let html = '';
        $.get(API_URL+'load_message', {'fromid':fromid, 'toid':toid}, function(data){
            $(data).each(function(k, v){
                let content = '';
                if (v.type === 2) {
                    content = '<img src="'+v.content+'" alt="" style="width:160px;" />';
                } else {
                    content = replace_em(v.content);
                }

                if (fromid === v.fromid) {
                    html += '<div class="chat-text section-right flex"><span class="text"><i class="icon icon-sanjiao3 t-32"></i>'+content+'</span><span class="char-img" style="background-image: url('+from_info.headimgurl+')"></span></div>';
                } else {
                    html += '<div class="chat-text section-left flex"><span class="char-img" style="background-image: url('+to_info.headimgurl+')"></span><span class="text"><i class="icon icon-sanjiao4 t-32"></i>'+content+'</span></div>';
                }
            });

            $('.chat-content').append(html);
            scrollToBottom(0);
        });
    }

    function changeNoRead(){
        $.post(API_URL+'change_no_read', {'fromid':fromid, 'toid':toid}, function(data){});
    }

    function send_message(){
        let txt = $('.send-input').val();
        if (txt !== '') {
            let message = {
                'data': txt,
                'type': 'say',
                'fromid': fromid,
                'toid': toid
            };

            ws.send(JSON.stringify(message));

            let html = '<div class="chat-text section-right flex"><span class="text"><i class="icon icon-sanjiao3 t-32"></i>'+replace_em(txt)+'</span><span class="char-img" style="background-image: url('+from_info.headimgurl+')"></span></div>';
            $('.chat-content').append(html);

            $('.send-input').val('');

            scrollToBottom();
        }
    }

    function save_message(data) {
        $.post(API_URL+'save_message', data, function(res){});
    }

    function images_upload(obj){
        if(sending === 1) return false;

        sending = 1;

        var val = $(obj).val();

        if(!val.match(/.jpg|.jpeg|.png/i)){
            console.log('이미지 확장자 오류');
            sending = 0;
            return false;
        }

        var path = obj.id;

        $.ajaxFileUpload({
            url:API_URL+'upload?fromid='+fromid+'&toid='+toid+'&online='+online,
            secureuri:false,
            dataType:'JSON',
            fileElementId:path,
            success:function(data){
                data = JSON.parse(data);
                if (data.state === 0) {
                    let html = '<div class="chat-text section-right flex"><span class="text"><i class="icon icon-sanjiao3 t-32"></i><img src="'+data.url+'" alt="" style="width:160px;" /></span><span class="char-img" style="background-image: url('+from_info.headimgurl+')"></span></div>';
                    $('.chat-content').append(html);

                    let message = {
                        'data':data.url,
                        'fromid':fromid,
                        'toid':toid,
                        'type':"say_img"
                    };
                    ws.send(JSON.stringify(message));

                    $('#file').val('');
                    scrollToBottom();
                } else {
                    console.log(data);
                }
            },
            error:function(data,status,_exception){
                sending = 0;
                console.log(_exception);
            }
        });
    }

    /**
     * 判断字符串是否能转为json
     * @param str
     * @returns {boolean}
     */
    function isJsonString(str) {
        try {
            if (typeof JSON.parse(str) == "object") {
                return true;
            }
        } catch(e) {}

        return false;
    }

    function scrollToBottom(time=500){
        $('.chat-content').animate({scrollTop: $('.chat-content').get(0).scrollHeight},time);
    }

    function replace_em(str){
        str = str.replace(/\</g,'&lt;');
        str = str.replace(/\>/g,'&gt;');
        str = str.replace(/\n/g,'<br/>');
        str = str.replace(/\[em_([0-9]*)\]/g,'<img src="{{ asset('qqFace/arclist') }}/$1.gif" border="0" />');
        return str;
    }
</script>
</body>
</html>
