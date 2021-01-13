<?php
	require_once "Config.php";

	class Message extends Config {
		
		public function __construct(){
			$this->setPdo();
			$this->setCurrentLocalTime();
		}
		
		public function insert_message($from, $to, $corpo){
		 
			try{
				$now = $this->getCurrentLocalTime();

				$sql = $this->getPdo()->prepare("INSERT INTO chat_messages(fk_userFrom, fk_userTo, msgbody, register_date5) VALUES(:from, :to, :body, :now)");
				$sql->bindParam(":from", $from, PDO::PARAM_INT);
				$sql->bindParam(":to", $to, PDO::PARAM_INT);
				$sql->bindParam(":body", $corpo, PDO::PARAM_STR);
				$sql->bindParam(":now", $now, PDO::PARAM_STR);
				$sql->execute();

				if($sql->rowCount() > 0){
				 
					$retorno = array(
						"success" => true,
						"msg" => "Message registered successfully!"
					);
					
				}else{
					
					$retorno = array(
						"success" => false,
						"msg" => "The message could not be registered!"
					);
					
				}
				
				
			}catch(Exception $e){
			 
				$retorno = array(
					"success" => false,
					"msg" => "Fatal Error: the message could not be registered!",
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
						"msg" => "The messages were loaded successfully",
						"count" => $count,
						"data" => $data
					);
					
				}else{
					
					$retorno = array(
						"success" => true,
						"msg" => "No message found!",
						"count" => $count,
						"data" => NULL
					);
					
				}
					
			}catch(Exception $e){
			
				$retorno = array(
					"success" => false,
					"msg" => "Error when loading messages",
					"erro" => $e->getMessage()
				);
				
			}
			
			return $retorno;
		}

		public function update_online($bool, $id)
		{
			$now = $this->getCurrentLocalTime();
			$update = $this->getPdo()->prepare("UPDATE registered_users SET st_online = ?, last_activity_update = ? WHERE id = ?");
			$update->bindParam(1, $bool, PDO::PARAM_INT);
			$update->bindParam(2, $now, PDO::PARAM_STR);
			$update->bindParam(3, $id, PDO::PARAM_INT);
			$update->execute();
		}
		
	}

?>
