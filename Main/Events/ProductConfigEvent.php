<?php

namespace YunTaIDC\Events;

use YunTaIDC\Server\Server;

class ProductConfigEvent extends Events{
    
    public $Server;
    
    public function __construct(Server $Server){
        $this->Server = $Server;
    }
    
    public function getServer(){
        return $this->Server;
    }
    
}

?>