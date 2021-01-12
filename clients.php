<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once "app/classes/User.php";
require_once "app/classes/Clients.php";

$u = new User();
$u->checkLogin();

$a = new Clients();

//$a->viewClients();

$u->logOut();

//$u->skipPageIfLogged();
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
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <script src="https://kit.fontawesome.com/dd21f273f4.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/jquery.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script src="scripts/html5shiv-printshiv"></script>
    <![endif]-->
    <title>Login do Usuário</title>
</head>

<body>

<div class="mainwrapper clearfix">
    <div class="section1">
        <h1 class="text-center"><img src="images/chatto.png" ></h1>
        <h1 class="text-center">Projeto ChatTo</h1>
        <br/>
        <p>logado como <?php echo $u->getUserName(); ?></p>
        <form enctype="multipart/form-data" action="" method="post">
            <input class="cust-btn" type="submit" name="logout" value="Sair" />
        </form>
    </div>
    <div class="section2">
        <br/><br/>
        <h1>Usuários disponíveis para conexão:</h1>
        <?php
        ?>
        <p><a class="navigate" href="view.php?v=1209843">Conectar com joilson</a> <?php if($u->getUserName() == "joilson"){ echo "(Eu)"; } ?></p>
        <p><a class="navigate" href="view.php?v=7845632">Conectar com roguitar <?php if($u->getUserName() == "roguitar"){ echo "(Eu)"; } ?></a></p>
        <input type="hidden" name="user-from" id="user-from" value="<?php echo($u->getId()); ?>">
        <input type="hidden" name="username" id="username" value="<?php echo($u->getUserName()); ?>">
        <input type="hidden" name="usernameto" id="usernameto" value="<?php if(NULL !== $a->getChatuser()){ echo($a->getChatuser()); } ?>">
        <input type="hidden" name="sellerid" id="sellerid" value="<?php if(NULL !== $a->getChatuserId()){ echo $a->getChatuserId(); } ?>">
        <br/><br/><br/><br/>
    </div>
</div>

<script type="text/javascript" src="chat/js/scripts.js"></script>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>-->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>


