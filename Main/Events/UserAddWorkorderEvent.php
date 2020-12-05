<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\Events\Events;
use CloudTowerIDC\User\User;

class UserAddWorkorderEvent extends Events{
    
    public function __construct(
        public User $User,
        public string $Title,
        public string $Content
    ){}
    
    public function getUser(){
        return $this->User;
    }
    
    public function getTitle(){
        return $this->Title;
    }
    
    public function getContent(){
        return $this->Content;
    }
    
}

?>