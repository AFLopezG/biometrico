var cors = require('cors')
var express=require('express');
// var Pedido=require('./models/Pedido');
var app = express();
var http = require('http').createServer(app);
var io = require('socket.io')(http);
var path=require('path');
// require('./database');
app.set('port', process.env.PORT || 3000);
app.use(cors());
app.use(express.urlencoded({extended:false}));
app.use(express.json());
app.use(express.static(__dirname + '/public'));

app.get('/', function(req, res){
    res.sendFile(__dirname + '/public.html');
});
app.get('/adminpro', function(req, res){
    res.sendFile(__dirname + '/admin.html');
});
app.get('/chat', function(req, res){
    res.sendFile(__dirname + '/chat.html');
});
io.on('connection',(socket)=>{
    // console.log('newuser')
    socket.on('chat message', (msg) => {
        console.log(msg)
        io.emit('chat message', msg);
    });
})

http.listen(app.get('port'), function(){
    console.log('listening on *:'+app.get('port'));
});
