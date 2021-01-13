<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once "app/classes/User.php";
require_once "app/classes/Clients.php";

$u = new User();
$u->checkLogin();

$a = new Clients();

$a->viewClients();

$u->logOut();

//if (isset($_SESSION['email'])) {
    $session = mt_rand(1,999);
//}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <link rel="stylesheet" type="text/css" href="css/index.css"/>
    <!--<link rel="stylesheet" type="text/css" href="css/styles.css"/>-->
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <script src="https://kit.fontawesome.com/dd21f273f4.js" crossorigin="anonymous"></script>
    <!--<script type="text/javascript" src="js/jquery.js"></script>-->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script src="scripts/html5shiv-printshiv"></script>
    <![endif]-->
    <title>Chat com PHP, JS e Websocket</title>
</head>

<body>

<div class="mainwrapper clearfix">
    <div class="section1">
        <h1 class="text-center"><img src="images/chatto.png" ></h1>        
        <h1 class="text-center">Projeto ChatTo</h1>
        <br/>
        <p>logado como <?php echo $u->getUserName(); ?></p>
        <p><a class="navigate" href="clients.php">Voltar para usuários</a></p>
        <form class="logout" method="post">
            <input class="cust-btn" type="submit" name="logout" value="Sair" />
        </form>
    </div>
    <div class="col-sm-3">

		<div class="row">
			<div class="col-lg-8">
                <!--
				<div class="pricetag">
					<button class="pricetag-main"><?php //echo "R$ ".$a->getPrice(); ?></button>
				</div>
                -->
				<div class="seller-details">
					<p style="font-size:120%;">
						<strong>
						<?php
						echo $a->getChatuser();
						?>
						</strong>
					</p>
					<button <?php if(!isset($_SESSION['username'])){ ?>onClick="alert('Ops! Você precisa estar logado para se conectar')"<?php }else{ if($_SESSION['username'] == $a->getChatuser()){ ?>onClick="alert('Ops! Você não pode mandar mensagens pra si mesmo')"<?php }else{ ?>id="users" class="chatbutton item-user"<?php } } ?> ></button>
					<br/>
                    <p style="font-size:65%;" id="user-status">
                        <!-- <img src="images/offline-dot.png" style="width: 12px; height: auto;" /> Offline -->
                        <?php
                        if ($a->getClientStatus() == 1) {
                        ?>
                        <img src="images/online-dot.png" style="width: 12px; height: auto;" /> Online agora
                        <?php    
                        } else {
                        ?>
                        <img src="images/offline-dot.png" style="width: 12px; height: auto;" /> Offline
                        <?php
                        }
                        ?>
                    </p>
				</div>
			</div>
			<div class="col-lg-4">

			</div>
		</div>
		
		<div id="chat">
            <div id="chat-header">
                <div id="header-name"><?php //echo $a->getAdvertiser(); ?></div>
                <span id="chat-close">&times;</span>
            </div>
            <input type="hidden" name="user-from" id="user-from" value="<?php echo($u->getId()); ?>">
            <!--<input type="hidden" name="user-from" id="user-from" value="<?php //echo($u->getId()); ?>">-->
            <input type="hidden" name="username" id="username" value="<?php echo($u->getUserName()); ?>">
            <!--<input type="hidden" name="username" id="username" value="<?php //echo($u->getUsername()); ?>">-->
            <input type="hidden" name="user-to" id="user-to">
            <input type="hidden" name="usernameto" id="usernameto" value="<?php if(NULL !== $a->getChatuser()){ echo($a->getChatuser()); } ?>">
            <input type="hidden" name="sellerid" id="sellerid" value="<?php if(NULL !== $a->getChatuserId()){ echo $a->getChatuserId(); } ?>">

            <div id="chat-window">
                <!-- https://codepen.io/muratcorlu/pen/KzmQEP -->
                <div id="logs">
                </div>
            </div>
            <!--<img src="images/emoji.svg" style="width:30px; height:auto; float:left;margin-left:20px; margin-top:2px;" />-->
            <!--
            <div id="message1" class="input" contenteditable="true" placeholder="Enter text here..."></div>-->
            <div class="emotion">
                <div id="leftPart" class="input" contenteditable="true" placeholder="Digite a mensagem aqui...">Ainda está disponível?</div>
                <textarea style="display:none;" id="message1" class="input" placeholder="Digite a mensagem aqui..."></textarea>
                <span class="emotion-Icon">
                    <i class="fa fa-smile-o" aria-hidden="true"></i>
                    <div class="emotion-area"></div>
                </span>
            </div>
            <!--
            <button type="submit" id="send" class="btn"><img src="images/send.svg" style="width:30px;height:auto;bottom:20px; position:absolute; right:30px;" /></button>
            -->
        </div>
        
		<!--
        <div id="usuarios">
            <div id="chat-header">
                usuários
                
            </div>
            <div id="chat-window">
                <div id="users">
                </div>
            </div>
            <hr>
        </div>
		-->
        
    </div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!--<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>-->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<?php
if(isset($_SESSION['username'])){
?>
<!--<script src="https://code.jquery.com/jquery-3.5.0.js"></script>-->
<script>
$( ".chatbutton" ).click(function() {
  $( "#chat" ).show( "slow" );
});
$( "#chat-close" ).click(function() {
  $( "#chat" ).hide( "slow" );
});
</script>
<?php
}
?>

<script type="text/javascript" src="chat/js/scripts.js"></script>

<script src="js/plugins.js"></script>

</body>
</html>


