<?php

namespace CloudTowerIDC\Page;

use CloudTowerIDC\Template\Template;
use CloudTowerIDC\Logger\Logger;
use CloudTowerIDC\Plugin\PluginManager;

use CloudTowerIDC\Events\TemplateLoadEvent;

class Index extends Page{

    public function Index(){
        if($this->getTemplate()->setTemplateFile('Index') === false){
            exit('YunTaIDC:无法获取模板');
        }
        $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
        //echo $event->getTemplate()->setTemplateContent('Index', array('config' => $this->getSystem()->getConfigAll()));
        if(!$this->getTemplateEvent()->isCancelled()){
            $this->getTemplate()->setTemplateCode(array('config' => $this->getSystem()->getConfigAll(),'template' => $this->getSystem()->getTemplateCustom()));
            echo $this->getTemplate()->outputTemplate();
        }
    }

}

?>