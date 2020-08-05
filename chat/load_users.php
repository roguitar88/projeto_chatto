<?php

	if(isset($_POST['usernameto'])){
		
		include("../app/classes/Message.php");
		
		$msg = new Message();
		
		$pdo = $msg->getPdo();
	
		$username = $_POST['usernameto'];
		
		$sql = $pdo->prepare("SELECT * FROM registered_users WHERE username = :username");
		$sql->bindParam(":username", $username, PDO::PARAM_STR);
		$sql->execute();
		
		$count = $sql->rowCount();
		
		if($count > 0){
		
			while($row = $sql->fetch()){
			
				$data[] = array(
					"id" => $row['id'],
					"username" => $row['username']
				);
				
			}
			
			$result = array(
				"success" => true,
				"msg" => "Usuarios carregados com sucesso!",
				"count" => $sql->rowCount(),
				"data" => $data
			);
			
		}else{

			$result = array(
					"success" => true,
					"msg" => "Nenhuma mensagem encontrada!",
					"count" => $count,
					"data" => NULL
				);
		
		}
	
	}else{
			
		$result = array(
			"success" => false,
			"msg" => "Não logado!"
		);
		
	}
	
	
	echo json_encode($result);
	
	

?>
