<?php

namespace YunTaIDC\Events;

use YunTaIDC\Events\Events;
use YunTaIDC\User\User;
use YunTaIDC\Priceset\Priceset;

class UserUpgradePricesetEvent extends Events{
    
    public $User;
    public $Priceset;
    public $Status;
    
    public function __construct(User $User, Priceset $Priceset){
        $this->User = $User;
        $this->Priceset = $Priceset;
        $this->Status = $Status;
    }
    
    public function getUser(){
        return $this->User;
    }
    
    public function getPriceset(){
        return $this->Priceset;
    }
    
}

?>