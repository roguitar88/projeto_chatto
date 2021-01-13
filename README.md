# Projeto ChatTo
### Sistema de chat em PHP, Javascript e Websocket

- Adaptação do tutorial https://www.youtube.com/watch?v=OHy2zpmsWf8

- Na pasta /bd você vai encontrar o banco de dados (chatto). Para configurar o banco de dados remoto, só ir em classes/Config.php (função ***setPdo()***) e alterar as credenciais de acordo com a configuração de acesso do seu banco.

- DICA: Vá até o diretório /chat/server/websocket_server.php, abra o terminal ou cmd e execute a seguinte linha para habilitar o servidor.
```
php websocket_server.php
```

- No servidor remoto VPS, vá até o diretório pelo comando cd (Ex: cd /var/www/html/projeto_chatto/chat/server) execute via terminal SSH ou por qualquer outro meio a seguinte linha de comando para habilitar o servidor e deixar ele rodando direto, sem interrupções:
```
php -f websocket_server.php         # para testar uma vez
nohup php websocket_server.php &    # para rodar ininterruptamente
```

- IMPORTANTE: se estiver usando Linux, não esqueça de usar o comando ***sudo*** no início, a menos que você seja o usuário ***root***.

- Em /app/classes/Message.php, edite a porção que compreende as linhas 30 a 42, referente à configuração da conexão com o banco de dados no servidor remoto. Desabilite o comentário. Essa parte foi configurada com o objetivo de estabelecer uma ponte direta com o bd independente de ele estar ou no localhost ou no servidor remoto, sem que seja necessária realizar tal operação/alteração em outro(s) momento(s).

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
- A parte dos ticks de visualização das mensagens
- Corrigir alguns pequenos problemas da funcionalidade online/offline via Websocket:
    - Estamos usando o evento Javascript 'beforeunload'. No entanto, sabemos que ele não é assim tão efetivo para executar comandos na hora em que o evento 'beforeunload' ou 'onbeforeunload' é disparado. Até porque ele pode ser chamado quando o usuário recarrega a página com F5 ou ENTER, ou quando usa as setas de ir ou voltar do navegador, ou quando está navegando no site, por meio do clique de algum link.
    - Sabido isto, até criamos uma maneira de 'burlar' isso com uma pequena gambiarra que você encontrará em /chat/js/scripts.js. Tentamos desativar os eventos de cliques em links que permitem a navegação. Agora, só falta achar uma solução para impedir o 'beforeunload' quando o usuário dá um refresh com F5 ou ENTER, quando clica nas setas de ir e voltar e ainda quando durante a mudança de uma página para a outra, a conexão com o websocket fecha.
    - Colocando em miúdos, temos os seguintes problemas para sanar:
        - Anular o efeito de F5 ou ENTER (refresh);
        - Anular o clique nos botões de ir e voltar do navegador;
        - Criar algum mecanismo para anular ou mascarar o 'offline' momentâneo quando durante a navegação de uma página para a outra o Websocket ligeiramente desconecta e depois volta a conectar.
- Fazer a barra de rolagem rolar automaticamente até o final da div quando qualquer dos usuários entra com nova mensagem, independente de onde está posicionada a barra de rolagem (scroll). Em /chat/js/scripts.js, até tentei fazer algo nesse sentido... Lembrando também que inverti a div #logs de ponta a cabeça no CSS (Vide /css/index.css que você entenderá melhor)
- Quanto ao estilo, se você for bom em design poderá bolar um layout pro chat box do jeito que você quiser
- IMPORTANTE: Lembre-se que para haver compatibilidade com a versão HTTPS de seu site, é necessário usar o protocolo ***wss://*** ao invés do ***ws://***. Isto já está definido nas 18 primeiras linhas de /chat/js/scripts.js. Além disso, é preciso que, nas linhas 151 e 152, você configure o caminho correto tanto do seu certificado (.pem ou .crt), quanto da sua chave privada ou ***private key*** (.pem ou .key). Na versão HTTPS do seu site, as linhas abaixo, devem ser habilitadas, enquanto que as linhas correspondentes apenas à conexão HTTP normal devem ser comentadas.

```
$app = new HttpServer(new WsServer(new Chat()));

$loop = Factory::create();

#By configuring the stuff through the steps below, I guess you don't need to set PROXY PASS in the Web server (Apache, Nginx, etc.)
$secure_websockets = new Server('0.0.0.0:8080', $loop);
$secure_websockets = new SecureServer($secure_websockets, $loop, [
	'local_cert' => '/etc/pathto/your-site/fullchain.pem',
	'local_pk' => '/etc/pathto/your-site/privkey.pem',
	'verify_peer' => false,
	'allow_self_signed' => false   //When tested locally: true
]);

$secure_websockets_server = new IoServer($app, $secure_websockets, $loop);
$secure_websockets_server->run();

```

É preciso que haja esse casamento para que o websocket seguro funcione com propriedade no HTTPS. Agora, a configuração padrão que eu costumo fazer para servidores Nginx é a seguinte e você pode se basear por ela também:

```
  map $http_upgrade $connection_upgrade {
      default upgrade;
      ''      close;
  }

  upstream ws-backend {
      #enable sticky session based on IP
      ip_hash;
             
      server your-site.com:8080;        
      server 90.138.151.13:8080;             
      server localhost:8080;            
      server 127.0.0.1:8080;
  }
  
  #If you want to force HTTPS, use the server block below:
  server {
      listen 80 default_server;
      listen [::]:80 default_server;

      return 301 https://$host$request_uri;
  }

  server{
      listen 443 ssl http2;
      listen [::]:443 ssl http2;

      root /var/www/html/your-site;
      index index.php index.html index.htm;

      server_name your-site.com www.your-site.com;

      #Start the SSL configurations
      #ssl on;
      ssl_certificate /etc/pathto/your-site.com/fullchain.pem; # managed by Certbot
      ssl_certificate_key /etc/pathto/your-site.com/privkey.pem; # managed by Certbot
      ssl_session_timeout 1d;
      ssl_session_cache shared:MozSSL:10m;  # about 40000 sessions
      ssl_session_tickets off;

      #curl https://ssl-config.mozilla.org/ffdhe2048.txt > /path/to/dhparam
      ssl_dhparam dhparams.pem;
      
      ssl_ciphers ECDHE+AESGCM:DHE+AESGCM:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES256-SHA384:ECDHE-ECDSA-AES128-SHA256;
      #ssl_prefer_server_ciphers off;

      #Diffie Hellmann performance improvements
      ssl_ecdh_curve              secp384r1;

      #HSTS (ngx_http_headers_module is required) (63072000 seconds)
      add_header Strict-Transport-Security "max-age=63072000" always;

      #OCSP stapling
      ssl_stapling on;
      ssl_stapling_verify on;

      location / {
          try_files $uri $uri/ =404;
      }

      location ~ \.php$ {
          try_files $uri /index.php =404;
          fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
          fastcgi_index index.php;
          fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
          include fastcgi_params;
      }

      location /chat/server {
          proxy_pass http://ws-backend;
          proxy_set_header Host               $host;
          proxy_set_header X-Real-IP          $remote_addr;

          proxy_set_header X-Forwarded-For    $proxy_add_x_forwarded_for;
          proxy_set_header X-Forwarded-Proto  https;
          proxy_set_header X-VerifiedViaNginx yes;
          proxy_read_timeout                  86400;
          proxy_connect_timeout               60;
          proxy_redirect                      off;

          #proxy_ssl_certificate /etc/pathto/your-site.com/fullchain.pem;
          #proxy_ssl_certificate_key /etc/pathto/your-site.com/privkey.pem; 

          #Specific for websockets: force the use of HTTP/1.1 and set the Upgrade header
          proxy_http_version 1.1;
          proxy_set_header Upgrade $http_upgrade;
          proxy_set_header Connection $connection_upgrade;
          proxy_set_header Host $host;
          proxy_cache_bypass $http_upgrade;
      }
  }
```

### ***Implementado por [@roguitar88](https://github.com/roguitar88), [@RafaelCapo](https://github.com/RafaelCapo) e [@rodriguesrenato61](https://github.com/rodriguesrenato61)***

### Qualquer bronca, enviar email para rogeriobsoares5@gmail.com ou uma mensagem direta para o Whatsapp [(62)982570993](http://wa.me/5562982570993)
