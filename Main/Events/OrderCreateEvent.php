<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\Events\Events;
use CloudTowerIDC\User\User;

class OrderCreateEvent extends Events{
    
    public function __construct(
        public User $User,
        public $OrderId,
        public string $Description,
        public float $Money,
        public string $Action
    ){}
    
    public function getUser(){
        return $this->User;
    }
    
    public function getOrderId(){
        return $this->OrderId;
    }
    
    public function getDescrption(){
        return $this->Description;
    }
    
    public function getMoney(){
        return $this->Money;
    }
    
    public function getAction(){
        return $this->Action;
    }
    
    public function setMoney($Money){
        $this->Money = $Money;
    }
    
}

?>