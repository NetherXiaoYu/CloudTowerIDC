<?php

namespace CloudTowerIDC\Events;

class Events{
    
    public $isCancelled = false;
    
    public function isCancelled(){
        return $this->isCancelled;
    }
    
    public function setCancelled(bool $value = true){
        $this->isCancelled = $value;
    }
    
}

?>