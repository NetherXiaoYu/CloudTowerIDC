<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\Events\Events;
use CloudTowerIDC\User\User;

class UserChangePasswordEvent extends Events{
    
    public function __construct(
        public User $User,
        public String $Password
    ){}
    
    public function getUser(){
        return $this->User;
    }
    
    public function getPassword(){
        return $this->Password;
    }
    
}

?>