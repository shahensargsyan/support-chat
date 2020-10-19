<?php
$session = mt_rand(1,999);
//dd(Auth::user()->id);
?>
    <!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <script src="{{ asset('js/jquery.js') }}" ></script>
    <style type="text/css">
        * {margin:0;padding:0;box-sizing:border-box;font-family:arial,sans-serif;resize:none;}
        html,body {width:100%;height:100%;}
        #wrapper {position:relative;margin:auto;max-width:1000px;height:100%;}
        #chat_output {position:absolute;top:0;left:0;padding:20px;width:100%;height:calc(100% - 100px);height: 300px;height: 470px;overflow-y: scroll;}
        #chat_input {position:absolute;bottom:0;left:0;padding:10px;width:100%;height:100px;border:1px solid #ccc;}
    </style>
</head>
<body>

<div id="wrapper">
    <div id="chat_output"></div>
    <textarea id="chat_input" placeholder="Deine Nachricht..."></textarea>
    <script type="text/javascript">
        let user = '{{json_encode(Auth::user()->toArray())}}'
        jQuery(function($){
            // Websocket
            var websocket_server = new WebSocket("ws://localhost:8090/");
            websocket_server.onopen = function(e) {
                websocket_server.send(
                    JSON.stringify({
                        'type': 'socket',
                        'user_id': {{ Auth::user()->id }},
                        'name': '{{ Auth::user()->name }}'
                    })
                );
                console.log(e);
                console.log('opened');
            };
            websocket_server.onerror = function(e) {
                // Errorhandling
            }
            websocket_server.onmessage = function(e)
            {
                var json = JSON.parse(e.data);
                console.log(json);
                //$('#chat_output').append(json.msg);
                switch(json.type) {
                    case 'chat':
                        $('#chat_output').append('<span style="color:#999"><b>'+json.name+':</b> '+json.msg+'</span><br />');
                        $('#chat_output').animate({scrollTop: $('#chat_output').prop("scrollHeight")});
                        break;
                }
            }
            // Events
                $('#chat_input').on('keyup',function(e){
                if(e.keyCode==13 && !e.shiftKey)
                {
                    var chat_msg = $('#chat_input').val();
                    if (!chat_msg.trim()) {
                        return false;
                    }

                    let name = '{{ Auth::user()->name }}';
                    websocket_server.send(
                        JSON.stringify({
                            'type': 'chat',
                            'to': 1,
                            'from': {{ Auth::user()->id }},
                            'content':chat_msg,
                            'name': name
                        })
                    );
                    $('#chat_output').append('<span style="color:#000"><b>'+name+':</b> '+chat_msg+'</span><br />');
                    $('#chat_output').animate({scrollTop: $('#chat_output').prop("scrollHeight")});
                    $(this).val('');

                }
            });
        });
    </script>
</div>
</body>
</html>
