<?php

namespace YunTaIDC\Page;

use YunTaIDC\Plugin\PluginManager;

class Plugin{
    
    private $System;
    private $PluginManager;
    
    public function __construct($main){
        $this->System = $main;
        $this->PluginManager = $main->getPluginManager();
    }
    
    private function goUserIndex(){
        @header("Location: ./index.php?p=Clientarea&a=Index");
        exit;
    }
    
    private function goMsg($msg){
        exit($msg);
    }
    
    public function Index(){
        $Gets = $this->System->getGetParams();
        if(empty($Gets['plugin'])){
            $this->goUserIndex();
        }else{
            if($this->PluginManager->PluginLoaded($Gets['plugin']) === false){
                $this->goMsg('插件未被正常加载');
            }else{
                if($this->PluginManager->PageRegistered($Gets['plugin']) === false){
                    $this->goMsg('该插件没有配置插件页面');
                }else{
                    $this->PluginManager->loadPluginPage($Gets['plugin'], $this->System);
                }
            }
        }
    }
    
}

?>