<?php

namespace YunTaIDC\Events;

use YunTaIDC\User\User;
use YunTaIDC\Product\Product;
use YunTaIDC\Service\Service;

class CreateServiceEvent extends Events{
    
    public $User;
    public $Product;
    public $Service;
    
    public function __construct(Service $Service, Product $Product, User $User){
        $this->Service = $Service;
        $this->Product = $Product;
        $this->User = $User;
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