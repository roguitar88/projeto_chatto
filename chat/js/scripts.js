//NOTE: With the addition of the online/offline feature, it doesn't work completely in Mozilla
var locationHost = document.location.host;
var protocol = window.location.protocol;

if (locationHost == 'localhost' || locationHost == '127.0.0.1') {
    //Localhost
    var websocket_server = new WebSocket('ws://' + locationHost + ':8080/projeto_chatto');
    var load_users_path = 'chat/load_users.php';
    var load_messages_path = 'chat/load_messages.php';        
} else {
    //Remote Server
    if(protocol == 'http:'){
        var websocket_server = new WebSocket('ws://' + locationHost + ':8080');
    }else{
        var websocket_server = new WebSocket('wss://' + locationHost + ':8080');
    }
    var load_users_path = protocol + '//' + locationHost + '/chat/load_users.php';
    var load_messages_path = protocol + '//' + locationHost + '/chat/load_messages.php';    
}

jQuery(function($){    
    // Websocket
    websocket_server.onopen = function(e) {
        socket();
        online();
        console.log('Connection started!');
    };

    websocket_server.onerror = function(e) {
        // Errorhandling
    };

    websocket_server.onmessage = function(e)
    {
        //let html ='';
        var json = JSON.parse(e.data);
        switch(json.type) {
            case 'chat':
            
                if(json.from == chosen.username || json.from == username.value){
                
					clearInterval(clock_other);
                    
                    other_time_typing = 0;
                    
                    limit_other = 3;
                    
                    other_typing = false;
                    
                    chatHeader.innerHTML = chosen.username;
					
                    //$('#logs').append(json.msg);
                    $(json.msg).prependTo('#logs');
                    break;
                    
                }else{
                 
                    alert('Mensagem recebida de '+json.from);
                    
                }
                
            break;
			
            case 'typing':
            
                if(json.to == username.value){
                    
                   console.log(json.to+' = '+username.value);
               
                   am_typing();
                   
               }
           
            break;
            
            //Let's update the user's status as online
            case 'online':

                console.log(json.from2); //array

                for(let i = 0; i < json.from2.length; i++){
                    if(json.from2[i] == tabFrom.value){   //json.from2
                        $('#user-status').html('<img src="images/online-dot.png" style="width: 12px; height: auto;" /> Online agora');
                        console.log(json.from2[i]+" is online");
                    } 
                }

            break;

            //Let's update the user's status as offline
            case 'close':

                for(let i = 0; i < json.from2.length; i++){
                    if(json.from2[i] == tabFrom.value){
                        $('#user-status').html('<img src="images/offline-dot.png" style="width: 12px; height: auto;" /> Offline'); 
                        console.log(json.from2[i]+" is offline");
                    }
                }

            break;
		   
        }
    };

    websocket_server.onclose = function(e) {
        console.log('Connection closed!');
    };

    // EVENTS
    //Everytime the user logs in or navigates to another page or maximizes or makes the window visible in total or in part, the status is changed to online.
    document.addEventListener( 'visibilitychange' , function() {

        if (document.hidden) {
        } else {
            online();
        }
        
    }, false );

    //This is to capture the event when the user CLOSES the browser. Then the status is changed to offline.
    //Issues: When the user presses F5 or Enter to refresh the page or clicks on the back button of the browser, the status is changed to offline for 2.5 seconds more or less (unload event) then comes back to online (load event).
    //Another issue: The user has to wait for the page to load completely and the websocket connection to open so that he closes the browser thereafter. If he closes it immediately during the loading process, the other user (at the other end) will continue as online.
    //NOTE: 'beforeunload' may not work in 100% of the cases.
    var inFormOrLink;

    $('a.navigate').click(function() {
        inFormOrLink = true;
        console.log( 'Whoa! You\' just clicked on a link' ); 
    });
    $('form.logout').bind('submit', function() { inFormOrLink = false; }); //When the user logs out
    $( 'input[type=submit]' ).click( function () { inFormOrLink = true; }); 
    $( 'button[type=submit]' ).click( function () { inFormOrLink = true; });

    /*
    window.addEventListener( 'beforeunload' , function () {

        if(!inFormOrLink){
            offline();
        }
    
        inFormOrLink = false;

    });
    */

    window.onbeforeunload = closeWindow;
    function closeWindow()
    {
        if(!inFormOrLink){
            offline();
        }
    
        inFormOrLink = false;
    }
    
    $('#leftPart').keypress(function(e){ return e.which != 13; });  //This prevents the Enter key from producing <br> in the end everytime it's pressed.

    $('#leftPart').on('keyup',function(e){ //$('#message1')
        if(e.keyCode==13 && !e.shiftKey)
        {            
            var text = $('#leftPart').html(); //delete
            $('#message1').val(text); //delete
            
            var chat_msg = $('#message1').val(); //$(this).val();
            websocket_server.send(
                JSON.stringify({
                    'type':'chat',
                    'user_from': userFrom.value,
                    'username_from': username.value,
                    'username_to': chosen.username,
                    'usernameto': usernameto.value,
                    'usernameto_to': chosen.usernameto,
                    'user_to': userTo.value,
                    'chat_msg':chat_msg
                })
            );
            //$(this).val('');
            //$('#logs').scrollTop($('#logs').prop('scrollHeight'));
            //$('#logs').animate({scrollTop: $('#logs').height() - $('#logs').scrollTop()});  //This makes the div scroll to the bottom, whenever someone enters a new message
            $(this).html('');
        }else if(from_typing()){
		 
            websocket_server.send(
				JSON.stringify({
					'type':'typing',
					'user_from': userFrom.value,
					'username_from': username.value,
					'username_to': chosen.username,
					'user_to': userTo.value,
				})
			);
			
		}
		
    });
    
    loadUsers();
    
});

function socket(){
    websocket_server.send(
        JSON.stringify({
            'type':'socket',
            'user_from': userFrom.value,
            'username_from': username.value
        })
    );
}

function online(){
    websocket_server.send(
        JSON.stringify({
            'type':'online',
            'user_from': userFrom.value,
        })
    );     
}

function offline(){
    websocket_server.send(
        JSON.stringify({
            'type':'close',
            'user_from': userFrom.value,
            'username_from': username.value,
        })
    );
}

var chosen = {id: 0, userTo: null};

const tabFrom = document.querySelector('#sellerid');
const username = document.querySelector('#username');
const userFrom = document.querySelector('#user-from');
const usernameto = document.querySelector('#usernameto');
const userTo = document.querySelector('#user-to');
const chatHeader = document.querySelector('#header-name');
const logs = document.querySelector('#logs');

var me_time_typing = 0;
var iam_typing = false;
var other_time_typing = 0;
var other_typing = false;
var limit_other = 3;
var clock_me;
var clock_other;

function loadUsers(){
    
    let form = new FormData();
    
    form.append('usernameto', usernameto.value);
 
   fetch(load_users_path, {
      
      method: 'POST',
      body: form
       
    }).then(function(response){
       
        return response.json();
    }).then(function(data){
        
        if(data.success){
            
            if(data.data != null){
             
                let html = '';
                
                let usuarios = data.data;
                
                let userDados = new Array();
                
                usuarios.forEach(function(usuario){
                
                    html += 'Iniciar Chat<input type="hidden" class="user-id" value="'+usuario.id+'">'; 
                    //usuario.username, usuario.id
                    
                    userDados.push({id: usuario.id, username: usuario.username});
                    
                });
                
                let users = document.querySelector('#users');

                users.innerHTML = html;
                
                let userItem = document.querySelectorAll('.item-user');
                
                for(let i = 0; i < userItem.length; i++){
                 
                    userItem[i].addEventListener('click', function(){
                    
                        chosen = userDados[i];
                        
                        console.log('chosen: '+chosen.username);
                        
                        userTo.value = chosen.id;
                        
                        chatHeader.innerHTML = chosen.username;
                        
                        loadMessages(chosen.username);
                        
                    }, {once : true});
                    
                }
                
            }
            
        }else{
         
            console.log('Error');
            
        }
        
    });
    
}

function loadMessages(user){
 
    let form = new FormData();
    
    form.append('from', username.value);
    form.append('to', user);
 
    fetch(load_messages_path, {
    
        method: 'POST',
        body: form
        
    })
    .then(function(response){
        
        return response.json();
    }).then(function(data){
    
        if(data.success){
            
            if(data.count > 0){
             
                let html = '';
                
                messages = data.data;
                
                messages.forEach(function(message){
                    
                    if(message.from == username.value){
                        //message.from, message.msg
                        html += '<div class="rightmsg">'+message.msg+'</div>';

                        
                    }else{

                        html += '<div class="leftmsg">'+message.msg+'</div>';

                    }
                    
                });
                
                //logs.innerHTML = html;
                //$('#logs').append(json.msg);
                $(html).prependTo('#logs');
                //$(message.msg).prependTo('#logs');
                //break;
                
                
            }else{
             
                console.log('No message found!');
                
            }
            
        }else{
         
            console.log(data.msg);
            
        }
        
    });
    
}

/* checking if I am typing */
function from_typing(){
	
	const limit = 3; //time limit in seconds between sends of "I'm typing"
	
	if(!iam_typing){
		
		iam_typing = true;
		
		console.log('I\'ve just sent the "I\'m typing"');
		
		clock_me = setInterval(function(){
		
			me_time_typing++;
			
			if(me_time_typing >= limit){
			
				iam_typing = false;
				
				me_time_typing = 0;
				
				clearInterval(clock_me);
				
			}else{
			
				console.log('"I\'m typing" waiting: '+me_time_typing);
				
			}
			
		}, 1000);
		
	}
	
	return iam_typing;
}

function am_typing(){
	
	if(!other_typing){
		
		other_typing = true;
		
		console.log('The other client is typing');
		
		let points = 0;
		
		clock_other = setInterval(function(){
		
			other_time_typing++;
			
			if(other_time_typing >= limit_other){
				
				chatHeader.innerHTML = chosen.username;
			
				other_typing = false;
				
				other_time_typing = 0;
				
				limit_other = 3;
				
				clearInterval(clock_other);
				
			}else{
			
				console.log('The other client is waiting: '+other_time_typing);
	
				if(other_time_typing == 0){
		
					chatHeader.innerHTML = chosen.username+' está digitando';
	
				}else if(other_time_typing <= limit_other){
		
					if(points >= 3){
						
						chatHeader.innerHTML = chosen.username+' está digitando';
						
						points = 0;
						
					}else{
						
						let html = chosen.username+' está digitando';
						
						for(let i = 0; i <= points; i++){
			
							html += '.';
						
						}
						
						chatHeader.innerHTML = html;
						
						points++;
						
					}
				
				}
		
			}
			
		}, 1000);
		
	}else{
	
		limit_other++;
		
	}
	
}
