<?php

namespace YunTaIDC\Events;

use YunTaIDC\Service\Service;
use YunTaIDC\Server\Server;

class ServiceDeleteEvent extends Events{
    
    public $Service;
    public $Server;
    
    public function __construct(Service $Service, Server $Server){
        $this->Service = $Service;
        $this->Server = $Server;
    }
    
    public function getServer(){
        return $this->Server;
    }
    
    public function getService(){
        return $this->Service;
    }
    
}

?>