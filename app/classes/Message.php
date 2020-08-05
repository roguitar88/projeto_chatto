<?php
	//require_once "User.php";
	
	class Message{
		protected $pdo;
		private $currentLocalTime;
		
		public function __construct(){
			//parent::__construct();
			
			$tz = 'America/Sao_Paulo';
			$timestamp = time();
			$dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
			$dt->setTimestamp($timestamp); //adjust the object to correct timestamp
			$this->currentLocalTime = $dt->format("Y-m-d H:i:s");	

			$host = "localhost";
			$dbname = "banana_nanica";
			$user = "root";
			$password = "";
			
			try{
			
				$this->pdo = new PDO("mysql:host={$host};dbname={$dbname}", $user, $password);
				
			}catch(Exception $e){
				
				echo("Erro: {$e->getMessage()}");
				
			}
		}
		
		public function insert_message($from, $to, $corpo){
		 
			try{
				$now = $this->currentLocalTime;

				$sql = $this->getPdo()->prepare("INSERT INTO chat_messages(fk_userFrom, fk_userTo, msgbody, register_date5) VALUES(:from, :to, :body, :now)");
				$sql->bindParam(":from", $from, PDO::PARAM_INT);
				$sql->bindParam(":to", $to, PDO::PARAM_INT);
				$sql->bindParam(":body", $corpo, PDO::PARAM_STR);
				$sql->bindParam(":now", $now, PDO::PARAM_STR);
				$sql->execute();
				/*
				$sql = $this->getPdo()->prepare("INSERT INTO chat_messages(fk_userFrom, fk_userTo, msgbody, register_date5) VALUES(?, ?, ?, ?)");
				$sql->execute(array($from, $to, $corpo, $now));
				*/
				if($sql->rowCount() > 0){
				 
					$retorno = array(
						"success" => true,
						"msg" => "Mensagem registrada com sucesso!"
					);
					
				}else{
					
					$retorno = array(
						"success" => false,
						"msg" => "Mensagem não registrada!"
					);
					
				}
				
				
			}catch(Exception $e){
			 
				$retorno = array(
					"success" => false,
					"msg" => "Não foi possível registrar a mensagem!",
					"erro" => $e->getMessage()
				);
				
			}
			
			return $retorno;
			
		}
		
		public function getMessages($from, $to){
		
			try{
					
				$sql = $this->getPdo()->prepare("SELECT * FROM vw_messages WHERE (userFrom = :from AND userTo = :to) OR (userFrom = :to AND userTo = :from) ORDER BY id DESC");
				$sql->bindParam(":from", $from, PDO::PARAM_STR);
				$sql->bindParam(":to", $to, PDO::PARAM_STR);
				$sql->execute();
				
				$count = $sql->rowCount();
				
				if($count > 0){
				
					while($row = $sql->fetch()){
					
						$data[] = array(
							"from" => $row['userFrom'],
							"to" => $row['userTo'],
							"msg" => $row['msgbody']
						);
						
					}
				
					$retorno = array(
						"success" => true,
						"msg" => "Mensagens carregadas com sucesso!",
						"count" => $count,
						"data" => $data
					);
					
				}else{
					
					$retorno = array(
						"success" => true,
						"msg" => "Nenhuma mensagem encontrada!",
						"count" => $count,
						"data" => NULL
					);
					
				}
					
			}catch(Exception $e){
			
				$retorno = array(
					"success" => false,
					"msg" => "Erro ao carregar mensagens!",
					"erro" => $e->getMessage()
				);
				
			}
			
			return $retorno;
		}

		public function getPdo(){
		
			return $this->pdo;
		}

		public function setPdo($pdo){
		
			$this->pdo = $pdo;
		}

		public function getCurrentLocalTime(){
		
			return $this->currentLocalTime;
		}

		public function setCurrentLocalTime($currentLocalTime){
		
			$this->currentLocalTime = $currentLocalTime;
		}
		
	}

?>
