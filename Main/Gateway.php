<?php

namespace YunTaIDC\Gateway;

class Gateway{
    
    private $Database;
    private $Class;
    
    public $Gateway;
    
    public function __construct($Gateway, $Class){
        $this->Class = $Class;
        $this->Database = $this->Class->getSystem()->getDatabase();
        $this->Gateway = $this->Database->get_row("SELECT * FROM `ytidc_gateway` WHERE `id`='{$Gateway}'");
    }
    
    public function isExisted(){
        if(empty($this->Gateway)){
            return false;
        }else{
            return true;
        }
    }
    
    public function getAll(){
        if(!empty($this->Gateway)){
            return $this->Gateway;
        }else{
            return false;
        }
    }
    
    public function getId(){
        if(!empty($this->Gateway)){
            return $this->Gateway['id'];
        }else{
            return false;
        }
    }
    
    public function getName(){
        if(!empty($this->Gateway)){
            return $this->Gateway['name'];
        }else{
            return false;
        }
    }
    
    public function getRate(){
        if(!empty($this->Gateway)){
            return $this->Gateway['rate'];
        }else{
            return false;
        }
    }
    
    public function getConfigOption(){
        if(!empty($this->Gateway)){
            return json_decode($this->Gateway['configoption'], true);
        }else{
            return false;
        }
    }
    
    public function getPluginName(){
        if(!empty($this->Gateway)){
            return $this->Gateway['plugin'];
        }else{
            return false;
        }
    }
    
    public function getStatus(){
        if(!empty($this->Gateway)){
            return $this->Gateway['status'];
        }else{
            return false;
        }
    }
    
    public function setName($name){
        if(!empty($this->Gateway)){
            return $this->Database->exec("UPDATE `ytidc_gateway` SET `name`='{$name}' WHERE `id`='{$this->Gateway['id']}'");
        }else{
            return false;
        }
    }
    
    public function setRate($rate){
        if(!empty($this->Gateway)){
            return $this->Database->exec("UPDATE `ytidc_gateway` SET `rate`='{$rate}' WHERE `id`='{$this->Gateway['id']}'");
        }else{
            return false;
        }
    }
    
    public function setPluginName($plugin){
        if(!empty($this->Gateway)){
            return $this->Database->exec("UPDATE `ytidc_gateway` SET `plugin`='{$plugin}' WHERE `id`='{$this->Gateway['id']}'");
        }else{
            return false;
        }
    }
    
    public function setConfigOption($configoption){
        if(!empty($this->Gateway)){
            $configoption = json_encode($configoption);
            return $this->Database->exec("UPDATE `ytidc_gateway` SET `configoption`='{$configoption}' WHERE `id`='{$this->Gateway['id']}'");
        }else{
            return false;
        }
    }
    
    public function setStatus($status = true){
        if(!empty($this->Gateway)){
            if($status){
                return $this->Database->exec("UPDATE `ytidc_gateway` SET `status`='1' WHERE `id`='{$this->Gateway['id']}'");
            }else{
                return $this->Database->exec("UPDATE `ytidc_gateway` SET `status`='0' WHERE `id`='{$this->Gateway['id']}'");
            }
        }else{
            return false;
        }
    }
}

?>