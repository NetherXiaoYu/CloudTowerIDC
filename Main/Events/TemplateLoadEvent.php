<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\Events\Events;
use CloudTowerIDC\Template\Template;

class TemplateLoadEvent extends Events{
    
    public function __construct(public Template $template){
    }
    
    public function getTemplate(){
        return $this->template;
    }
    
}

?>