<?php

namespace YunTaIDC\Events;

use YunTaIDC\Order\Order;

class OrderChangeEvent extends Events{
    
    public $Order;
    public $Status;
    
    public function __construct(Order $Order, $Status){
        $this->Status = $Status;
        $this->Order = $Order;
    }
    
    public function getOrder(){
        return $this->Order;
    }
    
    public function getStatus(){
        return $this->Status;
    }
    
}

?>