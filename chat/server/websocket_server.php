<?php
set_time_limit(0);

require_once '../../app/classes/Message.php';
require_once '../vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server;
use React\Socket\SecureServer;

class Chat implements MessageComponentInterface {
	protected $clients;
	protected $users;
	protected $msg;

	public function __construct() {
		$this->clients = new \SplObjectStorage;
		$this->msg = new Message();
	}

	public function onOpen(ConnectionInterface $conn) {
		$this->clients->attach($conn);
		// $this->users[$conn->resourceId] = $conn;
	}

	public function onClose(ConnectionInterface $conn) {
		$this->clients->detach($conn);
		// unset($this->users[$conn->resourceId]);
	}

	public function onMessage(ConnectionInterface $from,  $data) {
		
		$data = json_decode($data);
		$type = $data->type;
		
		switch ($type) {
			
			case 'socket':
			
				$from_id = $data->user_id;
				$from->resourceId = intval($from_id);
				$username = $data->username;
				echo("\nConexao {$from->resourceId} aberta para {$username}");
			
			break;
			
			case 'chat':
			
				$user_from = $data->user_from;
				$user_to = $data->user_to;
				$chat_msg = $data->chat_msg;
				$username_from = $data->username_from;
				$username_to = $data->username_to;
				
				$result = $this->msg->insert_message($user_from, $user_to, $chat_msg);
				
				echo("\nMensagem de {$username_from} para {$username_to}: {$result['msg']}");

				//$response_from = "<div class='rightmsg'><b>".$username_from.":</b> ".$chat_msg."</div>"; //or $user_from
				$response_from = "<div class='rightmsg'>".$chat_msg."</div>"; //or $user_from
				//$response_to = "<div class='leftmsg'><b>".$username_to."</b>: ".$chat_msg."</div>";
				$response_to = "<div class='leftmsg'>".$chat_msg."</div>";
				// Output
				
				$from->send(json_encode(array("type"=>$type, "from" => $username_from, "msg"=>$response_from)));
				
				foreach($this->clients as $client)
				{
					if($from!=$client && $client->resourceId == $user_to)
					{
						$client->send(json_encode(array("type"=>$type, "from" => $username_from, "msg"=>$response_to)));
					}
				}
				
			break;
			
			case 'digitando':
			
				$user_from = $data->user_from;
				$user_to = $data->user_to;
				$username_from = $data->username_from;
				$username_to = $data->username_to;
				
				echo("\n{$username_from} está digitando uma mensagem para {$username_to}");
				
				// Output
				
				foreach($this->clients as $client)
				{
					if($from!=$client && $client->resourceId == $user_to)
					{
						$client->send(json_encode(array("type"=>$type, "from" => $username_from, "to" => $username_to)));
					}
				}
				
			break;

		}
	}

	/*
	public function onMessage(ConnectionInterface $from,  $data) {
		$from_id = $from->resourceId;
		$data = json_decode($data);
		$type = $data->type;
		switch ($type) {
			case 'chat':
				$user_id = $data->user_id;
				$chat_msg = $data->chat_msg;
				$response_from = "</div><div id='rightmsg'><b>".$user_id.":</b> ".$chat_msg."</div>";
				$response_to = "<div id='leftmsg'><b>".$user_id."</b>: ".$chat_msg."</div>";
				// Output
				$from->send(json_encode(array("type"=>$type,"msg"=>$response_from)));
				foreach($this->clients as $client)
				{
					if($from!=$client)
					{
						$client->send(json_encode(array("type"=>$type,"msg"=>$response_to)));
					}
				}
				break;
		}
	}
	*/

	public function onError(ConnectionInterface $conn, \Exception $e) {
		$conn->close();
	}
}

$server = IoServer::factory(
	new HttpServer(new WsServer(new Chat())),
	8080
);
$server->run();

//To use this server with https protocol disable the five lines above by commenting them and use the code snippet that follows:
/*
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
*/
