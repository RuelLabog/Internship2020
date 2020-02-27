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
	if(users.includes(socket.username)){
		console.log('exists');
	}else{
		users.push(socket.username);
	}






    connection.query("UPDATE users SET user_status='active' WHERE id="+socket.id+"", function(err){
		if(err){
			console.log('Error: ' + err.message);
		}else{
			console.log(socket.username+' is Online.\n');
			updateUsernames();
		}
	});

});

connection.query("SELECT username FROM users WHERE user_status='active'", function(err, rows, fields){
	if(err){
		console.log('Error: ' + err.message);
	}else{
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


function updateUsernames(){
	io.sockets.emit('get users', users);
}










//Disconnect
  	socket.on('disconnect', function(data){
  		users.splice(users.indexOf(socket.username), 1);
		connected_users.splice(connected_users.indexOf(socket), 1);
		updateUsernames();
		console.log('Disconnected: %s sockets connected', connected_users.length);

		updateUsernames();
	});


//admin pairing show online subs
connection.query("SELECT username,subscriber_id, service_id,subscriber_name FROM subscribers WHERE subscriber_status='active'", function(err, rows, fields){
	if(err){
		console.log('Error: ' + err.message);
	}else{
      	socket.emit('showSubscribers', rows);
	}
});

//admin pairing show online ops
connection.query("SELECT username,id, service_id,username FROM users WHERE user_status='active'", function(err, rows, fields){
	if(err){
		console.log('Error: ' + err.message);
	}else{
      	socket.emit('showOnOperators', rows);
	}
});

//admin pairing show online operators w/ same service
socket.on('selectOperators', (service_id)=>{
    connection.query('SELECT username, id, username FROM users WHERE service_id="'+service_id+'" AND user_status="active"', (err, rows, fields)=>{
        if(err){
            console.log('Error: ' + err.message);
        }else{
            socket.emit('showOperators', rows);
        }
    });
});



	//Send Message
	socket.on('send message', function(data){
		io.sockets.emit('new message', {msg: data, user:socket.username});
	});



});
