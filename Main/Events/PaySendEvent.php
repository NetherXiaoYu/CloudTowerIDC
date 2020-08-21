<?php

namespace YunTaIDC\Events;

use YunTaIDC\Events\Events;
use YunTaIDC\User\User;
use YunTaIDC\Order\Order;
use YUnTaIDC\Gateway\Gateway;

class PaySendEvent extends Events{
    
    public $User;
    public $Order;
    public $Gateway;
    
    public function __construct(User $User, Order $Order, Gateway $Gateway){
        $this->User = $User;
        $this->Order = $Order;
        $this->Gateway = $Gateway;
    }
    
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