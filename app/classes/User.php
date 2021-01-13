<?php
require_once "Config.php";
class User extends Config{
    //Attributes
    public $id;
    private $password;
    public $username;

    //Methods
    public function makeLogin(){
        if(isset($_POST['signin'])){
            $this->username = $_POST['user'];
            $this->password = $_POST['password'];
            $stmt = $this->getPdo()->prepare("SELECT * FROM registered_users WHERE username = ? AND password2 = ?");
            $stmt->execute(array($this->username, $this->password));
        
            $this->row = $stmt->fetch(PDO::FETCH_ASSOC);

            #Validação de inputs
            $err = array();
        
            if (empty($this->getUserName())) {$err[] = "*Entre com o seu usuário";}
        
            if (empty($this->getPassword())) {$err[] = "*Entre com a senha";}
        
            if ($stmt->rowCount() == 0) {$err[] = "*Ou o usuário ou a senha está incorreto/a. Por favor, tente novamente.";}
            // Se a linha (row) existir, mostre ao usuário que o mesmo se encontra logado e redirecione-o para outro lugar.
            if(!$err){
                if ($stmt->rowCount() == 1) {
                    //Update the user's status in bd to 'online'
                    $updatestatus = $this->getPdo()->prepare("UPDATE registered_users SET st_online = ?, last_activity_update = ? WHERE id = ?");
                    $updatestatus->execute(array(1, $this->getCurrentLocalTime(), $this->row['id']));

                    $_SESSION['username'] = $this->row['username'];
                    $_SESSION['user_id'] = $this->row['id'];
                    header('Location: '.$this->getUrlHost().'clients.php');
                    exit;
                }
            }else{
                echo '<script>alert("';
                foreach($err as $value){
                    echo $value.'\n';
                }
                echo '");</script>';
            }
        }
    }
    
    public function checkLogin(){
        if(!isset($_SESSION['username'])){
            header('Location: '.$this->getUrlHost());
        }
    }

    public function skipPageIfLogged(){
        if(isset($_SESSION['username'])){
            header('Location: '.$this->getUrlHost().'clients.php');
        }
    }

    public function logOut(){
        if(isset($_POST['logout'])){
            if(isset($_SESSION['username'])){
                //Update the user's status in bd to 'offline'
                $updatestatus = $this->getPdo()->prepare("UPDATE registered_users SET st_online = ?, last_activity_update = ? WHERE id = ?");
                $updatestatus->execute(array(0, $this->getCurrentLocalTime(), $this->row['id']));
                session_destroy();
                header('Location: '.$this->getUrlHost());
                exit;
            }else{
                header('Location: '.$this->getUrlHost());
                exit;
            }
        }
    }
    
    //Special Methods
    public function __construct(){
        $this->setPdo();
        $this->setUrlHost();
        $this->setCurrentLocalTime();
        $this->setRow();

        if(NULL !== $this->row){
            $this->username = $this->row['username'];
            $this->id = $this->row['id'];
        }
    }
    

    function getId(){
        return $this->id;
    }

    function setId($id){
        $this->id = $id;
    }

    function getPassword(){
        return $this->password;
    }

    function setPassword($password){
        $this->password = $password;
    }

    function getUserName(){
        return $this->username;
    }

    function setUserName($username){
        $this->username = $username;
    }
}
?>
