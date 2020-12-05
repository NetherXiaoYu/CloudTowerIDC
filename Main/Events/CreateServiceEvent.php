<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\User\User;
use CloudTowerIDC\Product\Product;
use CloudTowerIDC\Service\Service;

class CreateServiceEvent extends Events{
    
    public function __construct(
        public Service $Service,
        public Product $Product,
        public $Period,
        public User $User
    ){}
    
    public function getPeriod(){
        return $this->Period;
    }
    
    public function getUser(){
        return $this->User;
    }
    
    public function getProduct(){
        return $this->Product;
    }
    
    public function getService(){
        return $this->Service;
    }
    
    
}

?>