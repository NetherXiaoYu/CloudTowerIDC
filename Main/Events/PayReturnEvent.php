<?php

namespace YunTaIDC\Events;

use YunTaIDC\Events\Events;
use YunTaIDC\User\User;
use YunTaIDC\Gateway\Gateway;

class PayReturnEvent extends Events{
    
    public $User;
    public $Order;
    public $Gateway;
    public $Gets;
    public $Posts;
    
    public function __construct(User $User, Gateway $Gateway, $Gets, $Posts){
        $this->User = $User;
        $this->Gateway = $Gateway;
        $this->Gets = $Gets;
        $this->Posts = $Posts;
    }
    
    public function getUser(){
        return $this->User;
    }
    
    public function getGateway(){
        return $this->Gateway;
    }
    
    public function getGetParams(){
        return $this->Gets;
    }
    
    public function getPostParams(){
        return $this->Posts;
    }
    
}

?>