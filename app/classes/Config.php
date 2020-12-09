<?php
//Here you will set the PDO connection, email configuration, session, standard support email and standard host or url
abstract class Config{
    //Attributes
    public $urlHost;   //This is the standard base url (ex.: /projeto_chatto/ according to localhost)
    public $pdo;   //Configuration and connection to database via pdo
    public $row;   //$row is the row of the table selected according to data stored in $_SESSION...
    public $currentLocalTime;

    //Special Methods
    function setUrlHost(){
        if($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "127.0.0.1"){
            $this->urlHost = "/projeto_chatto/";
        }else{
            $this->urlHost = $_SERVER['REQUEST_SCHEME']. '://'. "{$_SERVER['HTTP_HOST']}/";
        }
    }

    function getUrlHost(){
        return $this->urlHost;
    }

    function setPdo(){
        $db1 = "banana_nanica";
        
        try {
            //Local Host
            $dsn = 'mysql:host=localhost;dbname='.$db1;
            $user = 'root';
            $pw = "";
            $sessionpath = 'C:/laragon/tmp';
            $this->pdo = new PDO($dsn, $user, $pw, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8, NAMES utf8"));
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {  //$e
            echo 'Error: '.$e->getMessage();
            /*
            if(null === $this->pdo || $this->pdo == false){
                try{
                    //Remote Host
                    //roguitar@localhost
                    $dsn = 'mysql:host=ricky.heliohost.org;dbname='.$db1;
                    $user = 'mariamole';
                    $pw = '123456';
                    $sessionpath = '/home/orange77/tmp';
                    $this->pdo = new PDO($dsn, $user, $pw, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8, NAMES utf8"));
                    $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                }catch(PDOException $e) {
                    echo 'Error: '.$e->getMessage();
                }
            }
            */
        }
    }

    function getPdo(){
        return $this->pdo;
    }

    function setRow(){
        if(isset($_SESSION['username'])){
            $username = $_SESSION['username'];
            $result = $this->getPdo()->prepare("SELECT * FROM registered_users WHERE username = :username");
            $result->execute(array(":username" => $username));
            $this->row = $result->fetch(PDO::FETCH_ASSOC);    
        }        
    }

    function getRow(){
        return $this->row;
    }

    public function getCurrentLocalTime(){
        return $this->currentLocalTime;
    }

    public function setCurrentLocalTime(){
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
            
        $tz = 'America/Sao_Paulo';
        $timestamp = time();
        $dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
        $dt->setTimestamp($timestamp); //adjust the object to correct timestamp
        $this->currentLocalTime = $dt->format("Y-m-d H:i:s");                
    }
}
?>
