# Projeto ChatTo
### Sistema de chat em PHP, Javascript e Websocket

- Adaptação do tutorial https://www.youtube.com/watch?v=OHy2zpmsWf8

- Na pasta /bd você vai encontrar o banco de dados (banana_nanica)

- DICA: Vá até o diretório /chat/server/websocket_server.php, abra o terminal ou cmd e execute a seguinte linha para habilitar o servidor.
```
php.exe websocket_server.php
```

- No servidor remoto VPS, vá até o diretório pelo comando cd (Ex: cd /var/www/html/chat/server) execute via terminal SSH ou por qualquer outro meio a seguinte linha de comando para habilitar o servidor e deixar ele rodando direto, sem interrupções:
```
php -f websocket_server.php    # para testar uma vez
nohup php websocket_server.php &    # para rodar eternamente
```

- Em /app/classes/Message.php, edite a porção que compreende as linhas 30 a 42, referente a configuração da conexão com o banco de dados no servidor remoto. Desabilite o comentário. Essa parte foi configurada com o objetivo de estabelecer uma ponte direta com o bd independente de ele estar ou no localhost ou no servidor remoto, sem que seja necessária realizar tal operação/alteração em outro(s) momento(s).

- Para matar o processo no servidor remoto, basta procurar primeiro pelo processo:
```
ps aux | grep php
```

- e localizado o id ou número do processo, digitar como comando:
```
kill -9 id_do_processo
```

- Viu como é simples?


## E agora? O que falta em termos de funcionalidade?
- A parte dos tickets de visualização das mensagens
- Fazer a barra de rolagem rolar automaticamente até o final da div quando qualquer dos usuários entram com nova mensagem, independente de onde está posicionada a barra de rolagem (scroll). Em /chat/js/scripts.js, na linha 98, até tentei fazer algo nesse sentido... Lembrando também que inverti a div #logs de ponta a cabeça no CSS (Vide /css/index.css, a partir da linha 804 que você entenderá melhor)
- Quanto ao estilo, se você for um bom design poderá bolar um layout pro chat box do jeito que você quiser
- IMPORTANTE: Quanto à configuração da conexão por https, ainda estamos trabalhando para resolver, mas se puder contribuir seremos gratos. Lembrando que para haver compatibilidade com a versão HTTPS de seu site, é necessário substituir o ws por wss em /chat/js/scripts.js, na linha 9. Mesmo assim, a conexão cairá após alguns segundos ou quando se entra com alguma mensagem no chat box, pois é necessário fazer a configuração do SSL do Websocket de acordo com o Certificado SSL do seu site. Trocando em miúdos: Os certificados tanto do Websocket quanto do seu servidor web devem ser os mesmos. Para isso, é necessário alterar alguma coisa dentro de /chat/vendor/cboden/ratchet/. Não tenho certeza.


### ***Implementado por [@roguitar88](https://github.com/roguitar88) e [@rodriguesrenato61](https://github.com/rodriguesrenato61)***

### Qualquer bronca, enviar email para rogeriobsoares5@gmail.com ou uma mensagem direta para o Whatsapp [(62)982570993](http://wa.me/5562982570993)
