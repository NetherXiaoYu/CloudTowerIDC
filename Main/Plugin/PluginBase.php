<?php

namespace YunTaIDC\Plugin;

use YunTaIDC\Plugin\PluginConfig;

class PluginBase{
    
    public $System;
    public $dataFolder;
    public $resourceFolder;
    public $PluginName;
    
    public function __construct($Main, $dataFolder, $resourceFolder, $Name){
        $this->System = $Main;
        $this->dataFolder = $dataFolder.'/';
        $this->resourceFolder = $resourceFolder.'/resources/';
        $this->PluginName = $Name;
    }
    
    public function onLoad(){
        
    }
    
    public function getSystem(){
        return $this->System;
    }
    
    public function getResourceFolder(){
        return $this->resourceFolder;
    }
    
    public function saveResource($file){
        if(file_exists($this->getDataFolder() . '/' . $file)){
            return true;
        }else{
            $content = file_get_contents($this->getResourceFolder() . $file);
            return file_put_contents($this->getDataFolder() . '/' . $file, $content);
        }
    }
    
    public function getPluginName(){
        return $this->PluginName;
    }
    
    public function getdataFolder(){
        return $this->dataFolder;
    }
    
}

?>