<?php

namespace YunTaIDC\Events;

use YunTaIDC\User\User;
use YunTaIDC\Product\Product;

class ServiceCreateEvent extends Events{
    
    public $User;
    public $Product;
    public $Username;
    public $Password;
    public $Period;
    public $CustomOption;
    
    public function __construct($Username, $Password, $Period, $CustomOption, Product $Product, User $User){
        $this->Username = $Username;
        $this->Product = $Product;
        $this->Password = $Password;
        $this->Period = $Period;
        $this->CustomOption = $CustomOption;
        $this->User = $User;
    }
    
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