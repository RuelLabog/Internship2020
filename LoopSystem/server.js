var app = require('express')();
var http = require('http').createServer(app);
var io = require('socket.io')(http);
const mysql = require('mysql');

connected_users = [];
users = [];


const port = 8000;
http.listen(8000, function(){
  console.log('listening on *: ' + port);
});

/*
app.get('/', function(req, res){
  res.sendFile(__dirname + '/resources/views/pages/home.blade.php');
});
*/


const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'loopsystem'
});

connection.connect((err) => {
  if(err){
    console.log('Error connecting to Db');
    return;
  }
  console.log('Connection established');
});

/*
app.get('/', function(req, res) {
    // Do normal req and res here
    // Forward to realtime route
    res.sendFile(__dirname + '/index.html');
});
*/


//Connection
io.on('connection', function(socket){
  if(connected_users){
  	connected_users.push(socket);
    console.log('Connected %s sockets connected', connected_users.length);
  }

//User Login
socket.on('login user', function(data, callback){
	callback(true);
	socket.id = data[0];
	socket.username = data[1];



    connection.query("UPDATE users SET user_status='active' WHERE id="+socket.id+"", function(err){
		if(err){
			console.log('Error: ' + err.message);
		}else{
			console.log(socket.username+' is Online.\n');
		}
	});

});
connection.query("SELECT username FROM users WHERE user_status='active'", function(err, rows, fields){
	if(err){
		console.log('Error: ' + err.message);
	}else{
		//console.log('Data received from Db:\n');
      	//console.log(rows);
      	socket.emit('showrows', rows);
	}
});


//User Logout
socket.on('logout user', function(data, callback){
	callback(true);
	socket.id = data[0];
	socket.username = data[1];

    connection.query("UPDATE users SET user_status='inactive' WHERE id="+socket.id+"", function(err){
		if(err){
			console.log('Error: ' + err.message);
		}else{
			console.log(socket.username+' is now Offline.\n');
		}
	});

});











  

//Disconnect
  	socket.on('disconnect', function(data){
		connected_users.splice(connected_users.indexOf(socket), 1);
		console.log('Disconnected: %s sockets connected', connected_users.length);


	});



});
