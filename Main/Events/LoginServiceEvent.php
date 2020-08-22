<?php

namespace YunTaIDC\Events;

use YunTaIDC\Server\Server;
use YunTaIDC\Product\Product;
use YunTaIDC\Service\Service;

class LoginServiceEvent extends Events{
    
    public $Server;
    public $Service;
    public $Product;
    
    public function __construct(Service $Service, Product $Product, Server $Server){
        $this->Service = $Service;
        $this->Server = $Server;
        $this->Product = $Product;
    }
    
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