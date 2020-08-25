<?php

namespace YunTaIDC\ProductGroup;

class ProductGroup{
    
    private $Class;
    private $Database;
    
    public $Group;
    
    public function __construct($Group, $Class){
        $this->Class = $Class;
        $this->Database = $Class->getSystem()->getDatabase();
        $this->Group = $this->Database->get_row("SELECT * FROM `ytidc_group` WHERE `id`='{$Group}'");
    }
    
    public function isExisted(){
        if(empty($this->Group)){
            return false;
        }else{
            return true;
        }
    }
    
    public function getAll(){
        if(empty($this->Group)){
            return false;
        }else{
            return $this->Group;
        }
    }
    
    public function getId(){
        if(empty($this->Group)){
            return false;
        }else{
            return $this->Group['id'];
        }
    }
    
    public function getName(){
        if(empty($this->Group)){
            return false;
        }else{
            return $this->Group['name'];
        }
    }
    
    public function getDescription(){
        if(empty($this->Group)){
            return false;
        }else{
            return $this->Group['description'];
        }
    }
    
    public function getWeight(){
        if(empty($this->Group)){
            return false;
        }else{
            return $this->Group['weight'];
        }
    }
    
    public function getStatus(){
        if(empty($this->Group)){
            return false;
        }else{
            return $this->Group['status'];
        }
    }
    
    public function getProducts(){
        if(empty($this->Group)){
            return false;
        }else{
            return $this->Database->get_rows("SELECT * FROM `ytidc_product` WHERE `group`='{$this->Group['id']}' AND `status`='1'");
        }
    }
    
    public function set($array){
        if(empty($this->Group)){
            return false;
        }else{
           if(empty($array)){
               return true;
           }else{
               foreach($array as $k => $v){
                   $this->Database->exec("UPDATE `ytidc_group` SET `{$k}`='{$v}' WHERE `id`='{$this->Group['id']}'");
               }
               return true;
           }
        }
    }
    
    public function setStatus($status = true){
        if($status){
            return $this->Database->exec("UPDATE `ytidc_group` SET `status`='1' WHERE `id`='{$this->Group['id']}'");
        }else{
            return $this->Database->exec("UPDATE `ytidc_group` SET `status`='0' WHERE `id`='{$this->Group['id']}'");
        }
    }
    
}

?>