var server = require('http').Server();
var io = require('socket.io')(server);

var Redis = require('ioredis');
var redis = new Redis();

io.set('transports', ['websocket']);
redis.subscribe('whatcanido-channel');

redis.on('message', function (channel, message) {
    var msg = JSON.parse(message);
    console.log(msg, channel + ":" + msg.event);
    io.emit(channel + ":" + msg.event, msg.data);
});


server.listen(3000);