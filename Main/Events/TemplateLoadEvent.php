<?php

namespace YunTaIDC\Events;

use YunTaIDC\Events\Events;
use YunTaIDC\Template\Template;

class TemplateLoadEvent extends Events{
    
    public $template;
    
    public function __construct(Template $template){
        $this->template = $template;
    }
    
    public function getTemplate(){
        return $this->template;
    }
    
}

?>