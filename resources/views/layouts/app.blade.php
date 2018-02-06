<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>

    <script>
        (function(global){

            /*
            Channels
            delivery.connect
            file.load
            send.start
            send.success
            send.error
            receive.start
            receive.success
            receive.error
            */
            var imageFilter = /^(image\/gif|image\/jpeg|image\/png|image\/svg\+xml|image\/tiff)/i,
                pubSub;

            /********************************/
            /****        PUBSUB     *********/
            /********************************/
            function PubSub(){
                this.channels = {};
            };

            PubSub.prototype.subscribe = function(channel, fn){
                if (this.channels[channel] === undefined) {
                    this.channels[channel] = [fn];
                }else{
                    this.channels[channel].push(fn);
                };
            };

            PubSub.prototype.publish = function(channel,obj){
                var cnl = this.channels[channel];
                var numChannels = (cnl === undefined) ? 0 : cnl.length;
                for (var i = 0; i < numChannels; i++) {
                    cnl[i](obj);
                };
            };

            /********************************/
            /****        FilePackage    *****/
            /********************************/
            function FilePackage(file,receiving,params){
                _this = this;
                this.name = file.name;
                this.size = file.size;

                if(receiving){
                    this.uid = file.uid;
                    this.isText = file.isText;
                    this.mimeType = file.mimeType;
                    this.data = file.data;
                    this.dataURLPrefix = file.prefix;
                    this.params = file.params;
                    pubSub.publish('receive.success',this);
                }else{
                    //Sending a file.
                    this.params = params;
                    this.uid = this.getUID();
                    this.reader = new FileReader();

                    this.reader.onerror = function(obj){};

                    this.reader.onload = function(){
                        _this.base64Data = _this.reader.result;
                        _this.prepBatch();
                    };

                    this.reader.readAsDataURL(file);
                };
            };


            FilePackage.prototype.getUID = function(){
                //fix this
                return this.name + this.size + (new Date()).getTime();
            };

            FilePackage.prototype.prepBatch = function(){
                //replace 'data:image/gif;base64,' with ''
                this.data = this.base64Data.replace(/^[^,]*,/,'');
                this.batch = {
                    uid: this.uid,
                    name: this.name,
                    size: this.size,
                    data: this.data,
                    params: this.params
                };
                pubSub.publish('file.load',this);
            };

            FilePackage.prototype.isImage = function(){
                return imageFilter.test(this.mimeType);
            };

            FilePackage.prototype.isText = function(){
                return this.isText;
            }

            FilePackage.prototype.text = function(){
                return this.data;
            }

            FilePackage.prototype.dataURL = function(){
                return this.dataURLPrefix + this.data;
            };

            /********************************/
            /****        DELIVERY     *******/
            /********************************/
            function Delivery(socket){
                this.socket = socket;
                this.sending = {};
                this.receiving = {};
                this.connected = false;
                this.subscribe();
                this.connect();
            };

            Delivery.prototype.subscribe = function(){
                var _this = this;
                pubSub.subscribe('file.load',function(filePackage){
                    _this.socket.emit('send.start',filePackage.batch);
                });

                pubSub.subscribe('receive.success',function(filePackage){
                    _this.socket.emit('send.success',filePackage.uid);

                });

                //Socket Subscriptions
                this.socket.on('send.success',function(uid){
                    pubSub.publish('send.success',_this.sending[uid]);
                    delete _this.sending[uid];
                });

                this.socket.on('receive.start',function(file){
                    pubSub.publish('receive.start',file.uid);
                    var filePackage = new FilePackage(file,true);
                    _this.receiving[file.uid] = filePackage;

                });


            };

            Delivery.prototype.connect = function(){
                var _this = this;
                this.socket.on('delivery.connect',function(){
                    _this.connected = true;
                    pubSub.publish('delivery.connect', _this);
                });
                this.socket.emit('delivery.connecting','');
            };

            Delivery.prototype.on = function(evt,fn){
                if (evt === 'delivery.connect' && this.connected) {
                    return fn(this);
                };
                pubSub.subscribe(evt,fn);
            };

            Delivery.prototype.off = function(evt){
                throw "Delivery.off() has not yet been implemented.";
            };

            Delivery.prototype.send = function(file, params){
                var filePackage = new FilePackage(file, false, params);
                this.sending[filePackage.uid] = filePackage;
                return filePackage.uid;
            };



            pubSub = new PubSub();

            window.Delivery = Delivery;

        })(window);


        /*
        todo: server
        Receive batch & send batch
        batch should send DataURL prefix
        */
    </script>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
