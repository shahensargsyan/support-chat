<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->


<html>
<head>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/chat.css') }}" type="text/css" rel="stylesheet">

</head>
<body>
<div class="container">
    <h3 class=" text-center">Messaging</h3>
    <div class="messaging">
        <div class="inbox_msg">
            <div class="inbox_people">
                <div class="headind_srch">
                    <div class="recent_heading">
                        <h4>Recent</h4>
                    </div>
                    <div class="srch_bar">

                    </div>
                </div>
                <div class="inbox_chat">


                </div>
            </div>
            <div class="mesgs">
                <div class="msg_history">


                </div>
                <div class="type_msg">
                    <div class="input_msg_write">
                        <input type="text" class="write_msg" placeholder="Type a message" />
{{--                        <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>--}}
                    </div>
                </div>
            </div>
        </div>



    </div></div>
    <script type="text/javascript">
        jQuery(function($){
            let all_conversations = {};
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
            };
            websocket_server.onerror = function(e) {
                // Errorhandling
            }
            websocket_server.onmessage = function(e)
            {
                var json = JSON.parse(e.data);

                switch(json.type) {
                    case 'chat':
                        all_conversations[json.from_user_id].push({'type':'incoming_msg','msg':json.msg})
                        let id =   $('.active_chat').attr('id');
                        $('#' + json.from_user_id).addClass('new_message');
                        if(id == json.from_user_id) {
                            receiveMessage(json.msg);
                            $('.msg_history').animate({scrollTop: $('.msg_history').prop("scrollHeight")});
                        }
                        break;
                    case 'user':
                        let conversations = '';
                        $.each(json.users,function(k,v){
                            all_conversations[k] =  [];
                            conversations+= '<div id="'+k+'" class="chat_list">'+
                                '<div class="chat_people">'+
                                '<div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>'+
                                '<div class="chat_ib">'+
                                '<h5>'+ v.user.name +' </h5>'+
                            '</div>'+
                            '</div>'+
                            '</div>';
                        });
                        $(".inbox_chat").html(conversations);
                        break;
                }
            }
            // Events
            $('.write_msg').on('keyup',function(e){
                if(e.keyCode==13 && !e.shiftKey)
                {
                    var chat_msg = $(this).val();
                    if (chat_msg == '')
                        return false;

                    let id =   $('.active_chat').attr('id');
                    if (typeof id == 'undefined'){
                        alert('please select user');
                        return false;
                    }


                    let active_chat = parseInt($('.active_chat').attr('id'));
                    websocket_server.send(
                        JSON.stringify({
                            'type': 'chat',
                            'to': active_chat,
                            'from': {{ Auth::user()->id }},
                            'content':chat_msg,
                            'name': '{{ Auth::user()->name }}'
                        })
                    );
                    sendMessage(chat_msg);
                    all_conversations[active_chat].push({'type':'outgoing_msg','msg':chat_msg})
                    $(this).val('');
                    $('#'+id).removeClass('new_message');
                    $('.msg_history').animate({scrollTop: $('.msg_history').prop("scrollHeight")});
                }
            });



            $('#chat_input').on('click',function(e){
                let id = $(this).attr('id');
                $('msg_history').html();
            })
            $(document).on('click','.chat_list',function(e){
                let id = $(this).attr('id');
                $(this).removeClass('new_message');
                $('.active_chat').removeClass('active_chat');
                $(this).addClass('active_chat')
                $('.msg_history').html('');
                $.each(all_conversations[id],function(k,v){
                    let new_message = '';
                    if(v.type == 'incoming_msg'){
                        receiveMessage(v.msg);
                    } else {
                        sendMessage(v.msg);
                    }
                });
                $('.msg_history').animate({scrollTop: $('.msg_history').prop("scrollHeight")});
            });

            function receiveMessage(msg) {
                let new_message = '<div class=incoming_msg">'+
                    '<div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>'+
                    '<div class="received_msg">'+
                    '<div class="received_withd_msg">'+
                    '<p>'+msg+'</p>'+
                    '</div>'+
                    '</div>';
                $('.msg_history').append(new_message);
            }

            function sendMessage(msg) {
                let new_message = '<div class="outgoing_msg">'+
                    '<div class="sent_msg">'+
                    '<p>'+msg+'</p>'+
                    ' </div>';
                $('.msg_history').append(new_message);
            }
        });
    </script>
</body>
</html>
