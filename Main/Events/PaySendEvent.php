<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\Events\Events;
use CloudTowerIDC\User\User;
use CloudTowerIDC\Order\Order;
use CloudTowerIDC\Gateway\Gateway;

class PaySendEvent extends Events{
    
    public function __construct(
        public User $User,
        public Order $Order,
        public Gateway $Gateway
    ){}
    
    public function getUser(){
        return $this->User;
    }
    
    public function getOrder(){
        return $this->Order;
    }
    
    public function getGateway(){
        return $this->Gateway;
    }
    
    
}

?>