<?php

namespace YunTaIDC\Events;

use YunTaIDC\Events\Events;
use YunTaIDC\User\User;

class OrderCreateEvent extends Events{
    
    public $User;
    public $OrderId;
    public $Description;
    public $Money;
    public $Action;
    
    public function __construct(User $User, $OrderId, $Description, $Money, $Action){
        $this->User = $User;
        $this->OrderId = $OrderId;
        $this->Description = $Description;
        $this->Money = $Money;
        $this->Action = $Action;
    }
    
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