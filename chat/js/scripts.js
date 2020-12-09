var locationHost = document.location.host;
var protocol = window.location.protocol;
//console.log(protocol + '//' + locationHost);
if (locationHost == 'localhost' || locationHost == "127.0.0.1") {
    //Localhost
    var websocket_server = new WebSocket('ws://' + locationHost + '/projeto_chatto:8080');
    var load_users_path = 'chat/load_users.php';
    var load_messages_path = 'chat/load_messages.php';        
} else {
    //Remote Server
    if(protocol == 'http:'){
        var websocket_server = new WebSocket('ws://' + locationHost + ':8080');
    }else{
        var websocket_server = new WebSocket('wss://' + locationHost + ':8080/chat/server');
    }
    var load_users_path = protocol + '//' + locationHost + '/chat/load_users.php';
    var load_messages_path = protocol + '//' + locationHost + '/chat/load_messages.php';    
}

jQuery(function($){
    // Websocket
    websocket_server.onopen = function(e) {
        websocket_server.send(
            JSON.stringify({
                'type':'socket',
                'user_id': userFrom.value,
                'username': username.value,
            })
        );

    };
    websocket_server.onerror = function(e) {
        // Errorhandling
    }
    websocket_server.onmessage = function(e)
    {
        let html ="";
        var json = JSON.parse(e.data);
        switch(json.type) {
            case 'chat':
            
                if(json.from == escolhido.username || json.from == username.value){
                
					clearInterval(relogio_outro);
                    
                    outro_tempo_digitando = 0;
                    
                    limit_outro = 3;
                    
                    outro_digitando = false;
                    
                    chatHeader.innerHTML = escolhido.username;
					
                    //$('#logs').append(json.msg);
                    $(json.msg).prependTo('#logs');
                    break;
                    
                }else{
                 
                    alert("Chegou mensagem de "+json.from);
                    
                }
                
            break;
			
            case 'digitando':
            
                if(json.to == username.value){
                    
                   console.log(json.to+" = "+username.value);
               
                   to_digitando();
                   
               }
           
			break;
		   
        }
    }
    // Events
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
                    'username_to': escolhido.username,
                    'usernameto': usernameto.value,
                    'usernameto_to': escolhido.usernameto,
                    'user_to': userTo.value,
                    'chat_msg':chat_msg
                })
            );
            //$(this).val('');
            //$('#logs').scrollTop($('#logs').prop('scrollHeight'));
            //$('#logs').animate({scrollTop: $('#logs').height() - $('#logs').scrollTop()});  //This makes the div scroll to the bottom, whenever someone enters a new message
            $(this).html("");
        }else if(from_digitando()){
		 
            websocket_server.send(
				JSON.stringify({
					'type':'digitando',
					'user_from': userFrom.value,
					'username_from': username.value,
					'username_to': escolhido.username,
					'user_to': userTo.value,
				})
			);
			
		}
		
    });
    
    carregaUsuarios();
    
});

var escolhido = {id: 0, userTo: null};

const username = document.querySelector('#username');
const userFrom = document.querySelector('#user-from');
const usernameto = document.querySelector('#usernameto');
const userTo = document.querySelector('#user-to');
const chatHeader = document.querySelector('#header-name');
const logs = document.querySelector('#logs');

var eu_tempo_digitando = 0;
var estou_digitando = false;
var outro_tempo_digitando = 0;
var outro_digitando = false;
var limit_outro = 3;
var relogio_eu;
var relogio_outro;

function carregaUsuarios(){
    
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
             
                let html = "";
                
                let usuarios = data.data;
                
                let userDados = new Array();
                
                usuarios.forEach(function(usuario){
                
                    html += "Iniciar Chat<input type='hidden' class='user-id' value='"+usuario.id+"'>"; //"<div class='item-user'><p>"+usuario.username+"</p><input type='hidden' class='user-id' value='"+usuario.id+"'></div>";
                    
                    userDados.push({id: usuario.id, username: usuario.username});
                    
                });
                
                let users = document.querySelector('#users');

                users.innerHTML = html;
                
                let userItem = document.querySelectorAll('.item-user');
                
                for(let i = 0; i < userItem.length; i++){
                 
                    userItem[i].addEventListener('click', function(){
                    
                        escolhido = userDados[i];
                        
                        console.log("escolhido: "+escolhido.username);
                        
                        userTo.value = escolhido.id;
                        
                        chatHeader.innerHTML = escolhido.username;
                        
                        carregaMensagens(escolhido.username);
                        
                    }, {once : true});
                    
                }
                
            }
            
        }else{
         
            console.log("Erro");
            
        }
        
    });
    
}

function carregaMensagens(user){
 
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
             
                let html = "";
                
                mensagens = data.data;
                
                mensagens.forEach(function(mensagem){
                    
                    if(mensagem.from == username.value){
                        //html += "<div class='rightmsg'><b>"+mensagem.from+"</b> : "+mensagem.msg+"</div>";
                        html += "<div class='rightmsg'>"+mensagem.msg+"</div>";

                        
                    }else{
                        //html += "<div class='leftmsg'><b>"+mensagem.from+"</b>: "+mensagem.msg+"</div>";
                        html += "<div class='leftmsg'>"+mensagem.msg+"</div>";

                    }
                    
                });
                
                //logs.innerHTML = html;
                //$('#logs').append(json.msg);
                $(html).prependTo('#logs');
                //$(mensagem.msg).prependTo('#logs');
                //break;
                
                
            }else{
             
                console.log("Nenhuma mensagem encontrada!");
                
            }
            
        }else{
         
            console.log(data.msg);
            
        }
        
    });
    
}

function carregaMensagens(user){
 
    let form = new FormData();
    
    form.append('from', username.value);
    form.append('to', user);
 
    //fetch('../chat/load_messages.php', {
    fetch(load_messages_path, {

        method: 'POST',
        body: form
        
    })
    .then(function(response){
        
        return response.json();
    }).then(function(data){
    
        if(data.success){
            
            if(data.count > 0){
             
                let html = "";
                
                mensagens = data.data;
                
                mensagens.forEach(function(mensagem){
                    
                    if(mensagem.from == username.value){
                        //html += "<div class='rightmsg'><b>"+mensagem.from+"</b> : "+mensagem.msg+"</div>";
                        html += "<div class='rightmsg'>"+mensagem.msg+"</div>";

                        
                    }else{
                        //html += "<div class='leftmsg'><b>"+mensagem.from+"</b>: "+mensagem.msg+"</div>";
                        html += "<div class='leftmsg'>"+mensagem.msg+"</div>";

                    }
                    
                });
                
                //logs.innerHTML = html;
                //$('#logs').append(json.msg);
                $(html).prependTo('#logs');
                //$(mensagem.msg).prependTo('#logs');
                //break;
                
                
            }else{
             
                console.log("Nenhuma mensagem encontrada!");
                
            }
            
        }else{
         
            console.log(data.msg);
            
        }
        
    });
    
}


/*verifica se eu estou digitando*/
function from_digitando(){
	
	const limit = 3;//limite de segundos entre um envio e outro de "estou digitando"
	
	if(!estou_digitando){
		
		estou_digitando = true;
		
		console.log("mandei o estou digitando");
		
		relogio_eu = setInterval(function(){
		
			eu_tempo_digitando++;
			
			if(eu_tempo_digitando >= limit){
			
				estou_digitando = false;
				
				eu_tempo_digitando = 0;
				
				clearInterval(relogio_eu);
				
			}else{
			
				console.log("estou digitando esperando: "+eu_tempo_digitando);
				
			}
			
		}, 1000);
		
	}
	
	return estou_digitando;
}

function to_digitando(){
	
	if(!outro_digitando){
		
		outro_digitando = true;
		
		console.log("outro digitando");
		
		let pontos = 0;
		
		relogio_outro = setInterval(function(){
		
			outro_tempo_digitando++;
			
			if(outro_tempo_digitando >= limit_outro){
				
				chatHeader.innerHTML = escolhido.username;
			
				outro_digitando = false;
				
				outro_tempo_digitando = 0;
				
				limit_outro = 3;
				
				clearInterval(relogio_outro);
				
			}else{
			
				console.log("outro esperando: "+outro_tempo_digitando);
	
				if(outro_tempo_digitando == 0){
		
					chatHeader.innerHTML = escolhido.username+" está digitando";
	
				}else if(outro_tempo_digitando <= limit_outro){
		
					if(pontos >= 3){
						
						chatHeader.innerHTML = escolhido.username+" está digitando";
						
						pontos = 0;
						
					}else{
						
						let html = escolhido.username+" está digitando";
						
						for(let i = 0; i <= pontos; i++){
			
							html += '.';
						
						}
						
						chatHeader.innerHTML = html;
						
						pontos++;
						
					}
				
				}
		
			}
			
		}, 1000);
		
	}else{
	
		limit_outro++;
		
	}
	
}
