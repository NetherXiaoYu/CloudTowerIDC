<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\Events\Events;
use CloudTowerIDC\User\User;
use CloudTowerIDC\Workorder\Workorder;

class UserReplyWorkorderEvent extends Events{
    
    public function __construct(
        public User $User,
        public Workorder $Workorder,
        public String $Reply
    ){}
    
    public function getUser(){
        return $this->User;
    }
    
    public function getWorkorder(){
        return $this->Workorder;
    }
    
    public function getReply(){
        return $this->Reply;
    }
    
}

?>