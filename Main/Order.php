<?php

namespace YunTaIDC\Order;

use YunTaIDC\User\User;

class Order{
    
    private $Database;
    private $Class;
    
    public $Order;
    
    public function __construct($Order, $Class){
        $this->Class = $Class;
        $this->Database = $this->Class->getSystem()->getDatabase();
        $this->Order = $this->Database->get_row("SELECT * FROM `ytidc_order` WHERE `orderid`='{$Order}'");
    }
    
    public function isExisted(){
        if(empty($this->Order)){
            return false;
        }else{
            return true;
        }
    }
    
    public function getAll(){
        if(!empty($this->Order)){
            return $this->Order;
        }else{
            return false;
        }
    }
    
    public function getOrderId(){
        if(!empty($this->Order)){
            return $this->Order['orderid'];
        }else{
            return false;
        }
    }
    
    public function getDescription(){
        if(!empty($this->Order)){
            return $this->Order['description'];
        }else{
            return false;
        }
    }
    
    public function getMoney(){
        if(!empty($this->Order)){
            return $this->Order['money'];
        }else{
            return false;
        }
    }
    
    public function getAction(){
        if(!empty($this->Order)){
            return $this->Order['action'];
        }else{
            return false;
        }
    }
    
    public function getUser(){
        if(!empty($this->Order)){
            return new User($this->Order['user'], $this->Class);
        }else{
            return false;
        }
    }
    
    public function getStatus(){
        if(!empty($this->Order)){
            return $this->Order['status'];
        }else{
            return false;
        }
    }
    
    public function setDescrption($description){
        if(!empty($this->Order)){
            return $this->Database->exec("UPDATE `ytidc_order` SET `description`='{$description}' WHERE `orderid`='{$this->Order['orderid']}'");
        }else{
            return false;
        }
    }
    
    public function setMoney($money){
        if(!empty($this->Order)){
            return $this->Database->exec("UPDATE `ytidc_order` SET `money`='{$money}' WHERE `orderid`='{$this->Order['orderid']}'");
        }else{
            return false;
        }
    }
    
    public function setStatus($status){
        if(!empty($this->Order)){
            return $this->Database->exec("UPDATE `ytidc_order` SET `status`='{$status}' WHERE `orderid`='{$this->Order['orderid']}'");
        }else{
            return false;
        }
    }
    
}

?>