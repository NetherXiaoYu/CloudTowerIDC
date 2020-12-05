<?php

namespace CloudTowerIDC\Events;

class CronEvent extends Events{
    
    public function __construct(private $System){
    }
    
    public function getSystem(){
        return $this->System;
    }
    
}

?>