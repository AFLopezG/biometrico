const app = require('express')();
const httpServer = require("http").createServer();
require('dotenv').config();
const io = require("socket.io")(httpServer, {
  cors: {
    origin: process.env.URL,
  },
});
const port = process.env.PORT || 3000;

// app.get('/', (req, res) => {
//   res.sendFile(__dirname + '/index.html');
// });

io.on('connection', (socket) => {
  socket.on('chat message', msg => {
    console.log(msg);
    io.emit('chat message', msg);
  });
});

httpServer.listen(port, () => {
  console.log(`Socket.IO server running at http://localhost:${port}/`);
});
