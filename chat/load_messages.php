<?php

	include("../app/classes/Message.php");
	
	$msg = new Message();
	
	if(isset($_POST['from']) && isset($_POST['to'])){		
			
		$from = $_POST['from'];
		$to = $_POST['to'];
		
		$result = $msg->getMessages($from, $to);
	
	}else{
		
		$result = array(
			"success" => false,
			"msg" => "Não logado!"
		);
	
	
	}
	
	echo json_encode($result);
	
	

?>

