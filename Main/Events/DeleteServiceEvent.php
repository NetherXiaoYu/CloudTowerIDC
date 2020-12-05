<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\Service\Service;
use CloudTowerIDC\Server\Server;

class DeleteServiceEvent extends Events{
    
    public function __construct(
        public Service $Service,
        public Server $Server
    ){}
    
    public function getServer(){
        return $this->Server;
    }
    
    public function getService(){
        return $this->Service;
    }
    
}

?>