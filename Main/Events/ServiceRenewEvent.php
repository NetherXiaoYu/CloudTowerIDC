<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\User\User;
use CloudTowerIDC\Product\Product;
use CloudTowerIDC\Service\Service;

class ServiceRenewEvent extends Events{
    
    public function __construct(
        public Service $Service,
        public Product $Product,
        public array|null $Period,
        public User $User
    ){}
    
    public function getUser(){
        return $this->User;
    }
    
    public function getProduct(){
        return $this->Product;
    }
    
    public function getService(){
        return $this->Service;
    }
    
    public function getPeriod(){
        return $this->Period;
    }
    
}

?>