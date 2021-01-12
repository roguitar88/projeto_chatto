<?php
require_once "User.php";

class Clients extends User{
    //Attributes
    private $clientId;
    private $title;
    private $chatuser;
    private $chatuserId;
    private $fetchClient;

    //Methods
    public function viewClients(){
        if(isset($_GET['v'])){
            #require 'time.inc.php';

            $adCode = $_GET['v'];
            $selectclient = $this->getPdo()->prepare("SELECT clients.*, registered_users.* FROM clients INNER JOIN registered_users ON clients.created_by = registered_users.id WHERE clients.ad_id = ?");
            $selectclient->execute(array($adCode));
            $countad = $selectclient->rowCount();

            if($countad > 0){
                $this->fetchClient = $selectclient->fetch(PDO::FETCH_ASSOC);

                $this->clientId = $this->fetchClient['id2'];

                $this->title = $this->fetchClient['title'];

                $this->chatuser = $this->fetchClient['username'];

                $this->chatuserId = $this->fetchClient['id'];

            }else{
                //If any code couldn't be found in the table, the page won't be accessed.
                header('Location: '.$this->getUrlHost());
                exit;
            }
        }else{
            header('Location: '.$this->getUrlHost());
            exit;
        }        
    }

    //Special Methods
    public function __construct(){
        parent::__construct();        
    }

    function getTitle(){
        return $this->title;
    }

    function setTitle($title){
        $this->title = $title;
    }

    public function getFetchClient(){
        return $this->fetchClient;
    }

    public function setFetchClient($fetchClient){
        $this->fetchClient = $fetchClient;
    }

    public function getClientId(){
        return $this->clientId;
    }

    public function setClientId($clientId){
        $this->clientId = $clientId;
    }

    public function getChatuser(){
        return $this->chatuser;
    }

    public function setChatuser($chatuser){
        $this->chatuser = $chatuser;
    }

    public function getChatuserId(){
        return $this->chatuserId;
    }

    public function setChatuserId($chatuserId){
        $this->chatuserId = $chatuserId;
    }
}
?>