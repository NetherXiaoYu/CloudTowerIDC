<?php

namespace YunTaIDC\User;

use YunTaIDC\Priceset\Priceset;

class User{
    
    private $Database;
    private $Class;
    public $User;
    
    // User => UserId|Username
    public function __construct($User, $Class){
        $this->Class = $Class;
        $this->Database = $this->Class->getSystem()->getDatabase();
        if(is_numeric($User)){
            $this->User = $this->Database->get_row("SELECT * FROM `ytidc_user` WHERE `id`='{$User}'"); 
        }else{
            $this->User = $this->Database->get_row("SELECT * FROM `ytidc_user` WHERE `username`='{$User}'");
        }
    }
    
    public function isExisted(){
        if(empty($this->User)){
            return false;
        }else{
            return true;
        }
    }
    
    public function getAll(){
        if(empty($this->User)){
            return false;
        }else{
            return $this->User;
        }
    }
    
    public function getId(){
        if(empty($this->User)){
            return false;
        }else{
            return $this->User['id'];
        }
    }
    
    public function getUsername(){
        if(empty($this->User)){
            return false;
        }else{
            return $this->User['username'];
        }
    }
    
    public function getPassword(){
        if(empty($this->User)){
            return false;
        }else{
            return $this->User['password'];
        }
    }
    
    public function getMoney(){
        if(empty($this->User)){
            return false;
        }else{
            return $this->User['money'];
        }
    }
    
    public function getLastIp(){
        if(empty($this->User)){
            return false;
        }else{
            return $this->User['lastip'];
        }
    }
    
    public function getPriceset(){
        if(empty($this->User)){
            return false;
        }else{
            $Priceset = new Priceset($this->User['priceset'], $this->Class);
            if($Priceset->isExisted() === false){
                $Priceset = new Priceset($this->Class->getSystem()->getDefaultPriceset()['id'], $this->Class);
                if($Priceset->isExisted() === false){
                    return false;
                }else{
                    return $Priceset;
                }
            }else{
                return $Priceset;
            }
        }
    }
    
    public function getStatus(){
        if(empty($this->User)){
            return false;
        }else{
            return $this->User['status'];
        }
    }
    
    public function set($array){
        if(empty($this->User)){
            return false;
        }else{
            foreach($array as $k => $v){
                $this->Database->exec("UPDATE `ytidc_user` SET `{$K}`='{$v}' WHERE `id`='{$this->User['id']}'");
            }
            if(empty($this->Database->error())){
                return true;
            }else{
                return false;
            }
        }
    }
    
    public function setMoney($money){
        if(empty($this->User)){
            return false;
        }else{
            if($this->Database->exec("UPDATE `ytidc_user` SET `money`='{$money}' WHERE `id`='{$this->User['id']}'")){
                return true;
            }else{
                return false;
            }
        }
    }
    
    public function setPriceset($priceset){
        if(empty($this->User)){
            return false;
        }else{
            if($this->Database->exec("UPDATE `ytidc_user` SET `priceset`='{$priceset}' WHERE `id`='{$this->User['id']}'")){
                return true;
            }else{
                return false;
            }
        }
    }
    
    public function setLastIp($ip){
        if(empty($this->User)){
            return false;
        }else{
            if($this->Database->exec("UPDATE `ytidc_user` SET `lastip`='{$ip}' WHERE `id`='{$this->User['id']}'")){
                return true;
            }else{
                return false;
            }
        }
    }
    
    public function setPassword($password){
        if(empty($this->User)){
            return false;
        }else{
            $password = md5(md5($password));
            if($this->Database->exec("UPDATE `ytidc_user` SET `password`='{$password}' WHERE `id`='{$this->User['id']}'")){
                return true;
            }else{
                return false;
            }
        }
    }
    
    public function setStatus($status = true){
        if(empty($this->User)){
            return false;
        }else{
            if($status){
                return $this->Database->exec("UPDATE `ytidc_group` SET `status`='1' WHERE `id`='{$this->User['id']}'");
            }else{
                return $this->Database->exec("UPDATE `ytidc_group` SET `status`='0' WHERE `id`='{$this->User['id']}'");
            }
        }
    }
    
    public function getServiceCount(){
        if(empty($this->User)){
            return false;
        }else{
            return $this->Database->num_rows("SELECT COUNT(*) FROM `ytidc_service` WHERE `user`='{$this->User['id']}'");
        }
    }
    
    public function getOrderCount(){
        if(empty($this->User)){
            return false;
        }else{
            return $this->Database->num_rows("SELECT COUNT(*) FROM `ytidc_order` WHERE `user`='{$this->User['id']}'");
        }
    }
    
    public function getWorkorderCount(){
        if(empty($this->User)){
            return false;
        }else{
            return $this->Database->num_rows("SELECT COUNT(*) FROM `ytidc_workorder` WHERE `user`='{$this->User['id']}'");
        }
    }
    
    public function getWorkorders(){
        if(empty($this->User)){
            return false;
        }else{
            return $this->Database->get_rows("SELECT * FROM `ytidc_workorder` WHERE `user`='{$this->User['id']}'");
        }
    }
    
    public function getServices(){
        if(empty($this->User)){
            return false;
        }else{
            return $this->Database->get_rows("SELECT * FROM `ytidc_service` WHERE `user`='{$this->User['id']}'");
        }
    }
    
    public function getOrders(){
        if(empty($this->User)){
            return false;
        }else{
            return $this->Database->get_rows("SELECT * FROM `ytidc_order` WHERE `user`='{$this->User['id']}' ORDER BY `orderid` DESC");
        }
    }
    
}

?>