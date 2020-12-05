<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\Events\Events;
use CloudTowerIDC\Gateway\Gateway;

class PayNotifyEvent extends Events{
    
    public function __construct(
        public Gateway $Gateway,
        public array|null|string $Gets,
        public array|null|string $Posts
    ){}
    
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