<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\User\User;
use CloudTowerIDC\Product\Product;

class ServiceCreateEvent extends Events{
    
    public function __construct(
        public string|int|float $Username,
        public string|int|float $Password,
        public array|null $Period,
        public array|null $CustomOption,
        public Product $Product,
        public User $User
    ){}
    
    public function getUser(){
        return $this->User;
    }
    
    public function getProduct(){
        return $this->Product;
    }
    
    public function getUsername(){
        return $this->Username;
    }
    
    public function getPassword(){
        return $this->Password;
    }
    
    public function getPeriod(){
        return $this->Period;
    }
    
    public function getCustomOption(){
        return $this->CustomOption;
    }
    
    public function setUsername($Username){
        $this->Username = $Username;
    }
    
    public function setPassword($Password){
        $this->Password = $Password;
    }
    
}

?>