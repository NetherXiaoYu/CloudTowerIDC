<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\Server\Server;
use CloudTowerIDC\Product\Product;
use CloudTowerIDC\Service\Service;

class LoginServiceEvent extends Events{
    
    public function __construct(
        public Service $Service,
        public Product $Product,
        public Server $Server
    ){}
    
    public function getService(){
        return $this->Service;
    }
    
    public function getServer(){
        return $this->Server;
    }
    
    public function getProduct(){
        return $this->Product;
    }
    
}

?>