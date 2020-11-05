<?php
require_once "User.php";

class Ads extends User{
    //Attributes
    private $adId;
    private $title;
    private $advertiser;
    private $advertiserId;
    private $fetchAd;

    //Methods
    public function viewAd(){
        if(isset($_GET['v'])){
            #require 'time.inc.php';

            $adCode = $_GET['v'];
            /*
            $selectad = $this->getPdo()->prepare("SELECT classified_ads.*, registered_users.*, picstable.* FROM classified_ads INNER JOIN registered_users ON classified_ads.created_by = registered_users.id INNER JOIN picstable ON classified_ads.id2 = picstable.fk_ads WHERE classified_ads.ad_id = ?");
            */

            $selectad = $this->getPdo()->prepare("SELECT classified_ads.*, registered_users.* FROM classified_ads INNER JOIN registered_users ON classified_ads.created_by = registered_users.id WHERE classified_ads.ad_id = ?");
            $selectad->execute(array($adCode));
            $countad = $selectad->rowCount();

            if($countad > 0){
                $this->fetchAd = $selectad->fetch(PDO::FETCH_ASSOC);

                $this->adId = $this->fetchAd['id2'];

                $this->title = $this->fetchAd['title'];

                $this->advertiser = $this->fetchAd['username'];

                $this->advertiserId = $this->fetchAd['id'];

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

    public function getFetchAd(){
        return $this->fetchAd;
    }

    public function setFetchAd($fetchAd){
        $this->fetchAd = $fetchAd;
    }

    public function getAdId(){
        return $this->adId;
    }

    public function setAdId($adId){
        $this->adId = $adId;
    }

    public function getAdvertiser(){
        return $this->advertiser;
    }

    public function setAdvertiser($advertiser){
        $this->advertiser = $advertiser;
    }

    public function getAdvertiserId(){
        return $this->advertiserId;
    }

    public function setAdvertiserId($advertiserId){
        $this->advertiserId = $advertiserId;
    }
}
?>