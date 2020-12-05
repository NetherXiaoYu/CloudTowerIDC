<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\Order\Order;

class OrderChangeEvent extends Events{
    
    public function __construct(
        public Order $Order, 
        public string|null|bool $Status
    ){}
    
    public function getOrder(){
        return $this->Order;
    }
    
    public function getStatus(){
        return $this->Status;
    }
    
}

?>