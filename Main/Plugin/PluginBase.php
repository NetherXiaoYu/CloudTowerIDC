<?php

namespace CloudTowerIDC\Plugin;

use CloudTowerIDC\Plugin\PluginConfig;

class PluginBase{
    
    public $System;
    public $dataFolder;
    public $resourceFolder;
    public $PluginName;
    public $pageFolder;
    
    public function __construct($Main, $dataFolder, $pageFolder, $resourceFolder, $Name){
        $this->System = $Main;
        $this->dataFolder = $dataFolder.'/';
        $this->pageFolder = $pageFolder .'/';
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
    
    public function getDataFolder(){
        return $this->dataFolder;
    }

    public function getPageFolder($html){
        if ($html === true) {
            return str_replace(BASE_ROOT, '', $this->PageFolder);
        }else{
            return $this->pageFolder;
        }
    }
    
}

?>