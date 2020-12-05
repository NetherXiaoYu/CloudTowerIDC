<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\Logger\Logger;
use CloudTowerIDC\Events\Events;

class UserRegisterEvent extends Events{
    
    public function __construct(
        public string|int|float $Username,
        public string|int|float $Password
    ){}
    
    public function getUsername(){
        return $this->Username;
    }
    
    public function getPassword(){
        return $this->Password;
    }
    
}

?>