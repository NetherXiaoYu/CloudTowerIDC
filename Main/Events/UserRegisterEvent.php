<?php

namespace YunTaIDC\Events;

use YunTaIDC\Logger\Logger;
use YunTaIDC\Events\Events;

class UserRegisterEvent extends Events{
    
    public $Username;
    public $Password;
    
    public function __construct($Username, $Password){
        $this->Username = $Username;
        $this->Password = $Password;
    }
    
    public function getUsername(){
        return $this->Username;
    }
    
    public function getPassword(){
        return $this->Password;
    }
    
}

?>