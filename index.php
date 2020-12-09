<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once "app/classes/User.php";

$u = new User();
$u->makeLogin();
$u->skipPageIfLogged();
?>
<!--the html goes here-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <link rel="stylesheet" type="text/css" href="css/index.css"/>
    <link rel="shortcut icon" href="images/favicon_io/favicon.ico" type="image/x-icon">
    <link rel="icon" href="images/favicon_io/favicon.ico" type="image/x-icon">
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
    <div class="section2">
        <h1 class="text-center">Projeto ChatTo</h1>
        <br/>
        <h1>Login</h1>
        <?php
        ?>
        <form name="login2" method="post" action="" enctype="multipart/form-data">
            <!--<h2 class="titulo">FAZER LOGIN NO PORTAL</h2>-->
            <div class="form-group">
                <input placeholder="Usuário"  class="form-control" name="user" type="text" maxlength="75">
            </div>
            <div class="form-group">
                <input placeholder="Senha" class="form-control" name="password"  type="password" maxlength="50">
            </div>
            <input name="signin" class="btn btn-primary" value="Login" type="submit"><br/><br/>
            
        </form>
        <br/><br/><br/><br/>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>-->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>


