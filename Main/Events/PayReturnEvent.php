<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\Events\Events;
use CloudTowerIDC\User\User;
use CloudTowerIDC\Gateway\Gateway;

class PayReturnEvent extends Events{
    
    public function __construct(
        public User $User,
        public Gateway $Gateway,
        public array|null|string $Gets,
        public array|null|string $Posts
    ){}
    
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