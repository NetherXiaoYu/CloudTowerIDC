<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\User\User;
use CloudTowerIDC\Logger\Logger;
use CloudTowerIDC\Events\Events;

class UserLoginEvent extends Events{
    
    public function __construct(
        public User $User,
        public string $ip
    ){}
    
    public function getUser(){
        return $this->User;
    }
    
    public function getLoginIp(){
        return $this->LoginIp;
    }
    
}

?>