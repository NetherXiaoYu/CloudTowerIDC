<?php

namespace YunTaIDC\Events;

class CronEvent extends Events{
    
    public $System;
    
    public function __construct($system){
        $this->System = $system;
    }
    
    public function getSystem(){
        return $this->System;
    }
    
}

?>