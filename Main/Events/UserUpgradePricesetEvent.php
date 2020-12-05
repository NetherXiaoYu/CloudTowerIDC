<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\Events\Events;
use CloudTowerIDC\User\User;
use CloudTowerIDC\Priceset\Priceset;

class UserUpgradePricesetEvent extends Events{
    
    public function __construct(
        public User $User,
        public Priceset $Priceset
    ){}
    
    public function getUser(){
        return $this->User;
    }
    
    public function getPriceset(){
        return $this->Priceset;
    }
    
}

?>