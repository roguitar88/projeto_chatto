jQuery(function($){
    // Websocket
    var websocket_server = new WebSocket("ws://localhost:8080/");
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
                
                    //$('#logs').append(json.msg);
                    $(json.msg).prependTo('#logs');
                    break;
                    
                }else{
                 
                    alert("Chegou mensagem de "+json.from);
                    
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
            $('#logs').animate({scrollTop: $('#logs').height() - $('#logs').scrollTop()});  //This makes the div scroll to the bottom, whenever someone enters a new message
            $(this).html("");
        }
    });
    
    carregaUsuarios();
    
});

var escolhido = {id: 0, userTo: null};

const username = document.querySelector('#username');
const userFrom = document.querySelector('#user-from');
const usernameto = document.querySelector('#usernameto');
const userTo = document.querySelector('#user-to');
const chatHeader = document.querySelector('#chat-header');
const logs = document.querySelector('#logs');


function carregaUsuarios(){
    
    let form = new FormData();
    
    form.append('usernameto', usernameto.value);
 
   fetch('http://localhost/projeto_chatto/chat/load_users.php', {
      
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
                        
                        //chatHeader.innerHTML = escolhido.username+"<span id='chat-close'>&times;</span>";
                        
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
 
    fetch('http://localhost/projeto_chatto/chat/load_messages.php', {
    
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
