<?php

namespace YunTaIDC\Events;

use YunTaIDC\Events\Events;
use YunTaIDC\Gateway\Gateway;

class PayNotifyEvent extends Events{
    
    public $User;
    public $Order;
    public $Gateway;
    public $Gets;
    public $Posts;
    
    public function __construct(Gateway $Gateway, $Gets, $Posts){
        $this->Gateway = $Gateway;
        $this->Gets = $Gets;
        $this->Posts = $Posts;
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