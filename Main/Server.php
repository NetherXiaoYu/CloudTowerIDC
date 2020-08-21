<?php

namespace YunTaIDC\Server;

class Server{
    
    private $Class;
    private $Database;
    
    public $Server;
    
    public function __construct($Server, $Class){
        $this->Class = $Class;
        $this->Database = $Class->getSystem()->getDatabase();
        $this->Server = $this->Database->get_row("SELECT * FROM `ytidc_server` WHERE `id`='{$Server}'");
    }
    
    public function isExisted(){
        if(empty($this->Server)){
            return false;
        }else{
            return true;
        }
    }
    
    public function getAll(){
        if(empty($this->Server)){
            return false;
        }else{
            return $this->Server;
        }
    }
    
    public function getId(){
        if(empty($this->Server)){
            return false;
        }else{
            return $this->Server['id'];
        }
    }
    
    public function getName(){
        if(empty($this->Server)){
            return false;
        }else{
            return $this->Server['name'];
        }
    }
    
    public function getServerIp(){
        if(empty($this->Server)){
            return false;
        }else{
            return $this->Server['serverip'];
        }
    }
    
    public function getServerDomain(){
        if(empty($this->Server)){
            return false;
        }else{
            return $this->Server['serverdomain'];
        }
    }
    
    public function getServerDns1(){
        if(empty($this->Server)){
            return false;
        }else{
            return $this->Server['serverdns1'];
        }
    }
    
    public function getServerDns2(){
        if(empty($this->Server)){
            return false;
        }else{
            return $this->Server['serverdns2'];
        }
    }
    
    public function getServerUsername(){
        if(empty($this->Server)){
            return false;
        }else{
            return $this->Server['serverusername'];
        }
    }
    
    public function getServerPassword(){
        if(empty($this->Server)){
            return false;
        }else{
            return base64_decode($this->Server['serverusername']);
        }
    }
    
    public function getServerAccessHash(){
        if(empty($this->Server)){
            return false;
        }else{
            return base64_decode($this->Server['serveraccesshash']);
        }
    }
    
    public function getServerCpanel(){
        if(empty($this->Server)){
            return false;
        }else{
            return $this->Server['servercpanel'];
        }
    }
    
    public function getServerPort(){
        if(empty($this->Server)){
            return false;
        }else{
            return $this->Server['serverport'];
        }
    }
    
    public function getServerPluginName(){
        if(empty($this->Server)){
            return false;
        }else{
            return $this->Server['plugin'];
        }
    }
    
    public function getServerStatus(){
        if(empty($this->Server)){
            return false;
        }else{
            return $this->Server['status'];
        }
    }
    
    public function set($array){
        if(empty($this->Server)){
            return false;
        }else{
            if(empty($array)){
                return true;
            }else{
                foreach($array as $k => $v){
                    $this->Database->exec("UPDATE `ytidc_server` SET `{$k}`='{$v}' WHERE `id`='{$this->Server['id']}'");
                }
                return true;
            }
        }
    }
    
    public function setServerPassword($password){
        if(empty($this->Server)){
            return false;
        }else{
            $password = base64_encode($password);
            return $this->Database->exec("UPDATE `ytidc_server` SET `serverpassword`='{$password}' WHERE `id`='{$this->Server['id']}'");
        }
    }
    
    public function setServerAccessHash($accesshash){
        if(empty($this->Server)){
            return false;
        }else{
            $accesshash = base64_encode($accesshash);
            return $this->Database->exec("UPDATE `ytidc_server` SET `serveraccesshash`='{$accesshash}' WHERE `id`='{$this->Server['id']}'");
        }
    }
    
    public function setStatus($status = true){
        if(empty($this->Server)){
            return false;
        }else{
            if($status){
                return $this->Database->exec("UPDATE `ytidc_server` SET `status`='1' WHERE `id`='{$this->Server['id']}'");
            }else{
                return $this->Database->exec("UPDATE `ytidc_server` SET `status`='0' WHERE `id`='{$this->Server['id']}'");
            }
        }
    }
    
}

?>