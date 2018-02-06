@extends('layouts.app')

@section('content')

    <div>
        <div id="messageArea" class="row">
            <div class="col-md-4">
                <div class="alert alert-light">
                    <div>
                        <h3>{{ str_limit(Auth::user()->name, 50) }}</h3>
                    </div>
                </div>
                <h3 class="text-center">Online</h3>
                <ul class="listen-group" id="users"></ul>
            </div>
            <div class="col-md-8">
                <div class="chat mh-40" id="chat" style="resize: none;max-height: 500px;overflow: auto;">
                </div>
                <form id="messageForm">
                    <label>Message</label>
                    <div class="row">
                        <div class="col-md-10  form-group">
                            <textarea class="form-control" rows="3" id="message"></textarea>
                        </div>
                        <div class="col-md-2">
                            <input type="file" id="file">
                            <input type="button" id="btnFileSubmit">
                            <input type="submit" class="btn btm-primary btn-lg" style="width: 100%;height: 80px"
                                   value="Send">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            var socket = io.connect('http://127.0.0.1:3000/');
            var $messageForm = $('#messageForm');
            var $message = $('#message');
            var $chat = $('#chat');
            var $messageArea = $('#messageArea');
            var $users = $('#users');
            var $file = $('#file');
            var delivery = new Delivery(socket);


            socket.emit('new user', {{ Auth::user()->id }} , function (data) {
                if (data) {
                    for (i = 0; i < data.msg.length; i++) {
                        if(data.msg[i].id == data.last_message){
                            $chat.append('<hr id="last_message_hr">');
                            $chat.animate({scrollTo: $("#last_message_hr").offset().top}, 500);
                        }
                        $chat.append('<div class="well"><strong>' + data.msg[i].user_sender.name + ': </strong>' + data.msg[i].message + '</div>');
                    }
                }
            });

            $messageForm.submit(function (e) {
                e.preventDefault();
                socket.emit('send message', $message.val());
                $message.val('');
            });

            delivery.on('delivery.connect',function(delivery){
                $("#btnFileSubmit").click(function(evt){
                    var file = $file[0].files[0];
                    var extraParams = {foo: 'bar'};
                    delivery.send(file, extraParams);
                    evt.preventDefault();
                });
            });

            delivery.on('send.success',function(fileUID){
                console.log("file was successfully sent.");
            });

            socket.on('new message', function (data) {
                $chat.append('<div class="well"><strong>' + data.user + ': </strong>' + data.msg + '</div>');
                $chat.animate({scrollTop: $chat[0].scrollHeight}, 500);
            });

            socket.on('clear', function (data) {
                var html = '';
                $chat.html(html);
            });

            $messageArea.keydown(function (e) {

                if (e.ctrlKey && e.keyCode == 13) {
                    e.preventDefault();
                    socket.emit('send message', $message.val());
                    $message.val('');
                }
            });

            socket.on('get user', function (data) {
                var html = '';
                for (i = 0; i < data.length; i++) {
                    html += '<li class="list-group-item">' + data[i] + '</li>';
                }
                $users.html(html);
            });

        });
    </script>
@endsection
