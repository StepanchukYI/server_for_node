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
                            <input type="submit" class="btn btm-primary btn-lg" style="width: 100%;height: 80px"
                                   value="Send">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>

    </style>
    <script>
        $(function () {
            var socket = io.connect('http://37.57.92.40:3000/');
            var $messageForm = $('#messageForm');
            var $message = $('#message');
            var $chat = $('#chat');
            var $messageArea = $('#messageArea');
            var $user = $('#name');
            var $users = $('#users');
            var $token = $('#_token');

            socket.emit('new user', {{ Auth::user()->id }} , function (data) {
                if (data) {
                    console.log($user);
                    for (i = 0; i < data.msg.length; i++) {
                        $chat.append('<div class="well"><strong>' + data.msg[i].user + ': </strong>' + data.msg[i].text + '</div>');
                    }
                }
            });

            $messageForm.submit(function (e) {
                e.preventDefault();
                socket.emit('send message', $message.val());
                $message.val('');
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
                    console.log(data[i]);
                    html += '<li class="list-group-item">' + data[i] + '</li>';
                }
                $users.html(html);
            });

        });
    </script>
@endsection
