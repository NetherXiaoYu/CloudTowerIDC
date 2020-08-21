<?php

namespace YunTaIDC\Events;

use YunTaIDC\User\User;
use YunTaIDC\Product\Product;
use YunTaIDC\Service\Service;

class ServiceRenewEvent extends Events{
    
    public $User;
    public $Product;
    public $Service;
    public $Period;
    
    public function __construct(Service $Service, Product $Product, $Period, User $User){
        $this->Service = $Service;
        $this->Product = $Product;
        $this->Period = $Period;
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
    
    public function getPeriod(){
        return $this->Period;
    }
    
}

?>