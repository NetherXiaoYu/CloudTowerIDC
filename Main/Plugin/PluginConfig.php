<?php

namespace YunTaIDC\Plugin;

class PluginConfig{

    private $File;
    private $Config;

    public function __construct(String $file, Array $default = array()){
        $this->File = $file;
        if(!file_exists($file)){
            file_put_contents($file, json_encode($default, JSON_UNESCAPED_UNICODE));
            $this->Config = $default;
        }else{
            $this->Config = json_decode(file_get_contents($file), true);
            if(!is_array($this->Config)){
                $this->Config = array();
            }
        }
    }

    public function set($key, $value){
        $this->Config[$key] = $value;
    }

    public function get($key){
        return $this->Config[$key];
    }

    public function isset($key){
        return isset($this->Config[$key]);
    }

    public function unset($key){
        unset($this->Config[$key]);
    }

    public function setAll(Array $array){
        $this->Config = $array;
    }

    public function getAll(){
        return $this->Config;
    }

    public function save(){
        if(file_put_contents($this->File, json_encode($this->Config, JSON_UNESCAPED_UNICODE))){
            return true;
        }else{
            return false;
        }
    }

}

?>