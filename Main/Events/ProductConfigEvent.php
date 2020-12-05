<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\Server\Server;

class ProductConfigEvent extends Events{
    
    public function __construct(public Server $Server){
    }
    
    public function getServer(){
        return $this->Server;
    }
    
}

?>