<?php

namespace CloudTowerIDC\Product;

use CloudTowerIDC\ProductGroup\ProductGroup;
use CloudTowerIDC\Server\Server;

class Product{
    
    private $Database;
    
    public $Product;
    
    public function __construct($Product,private $Class){
        $this->Database = $Class->getSystem()->getDatabase();
        $this->Product = $this->Database->get_row("SELECT * FROM `ytidc_product` WHERE `id`='{$Product}'");
    }
    
    public function isExisted(){
        if(empty($this->Product)){
            return false;
        }else{
            return true;
        }
    }
    
    public function getAll(){
        if(empty($this->Product)){
            return false;
        }else{
            return $this->Product;
        }
    }
    
    public function getId(){
        if(empty($this->Product)){
            return false;
        }else{
            return $this->Product['id'];
        }
    }
    
    public function getName(){
        if(empty($this->Product)){
            return false;
        }else{
            return $this->Product['name'];
        }
    }
    
    public function getDescription(){
        if(empty($this->Product)){
            return false;
        }else{
            return $this->Product['description'];
        }
    }
    
    public function getWeight(){
        if(empty($this->Product)){
            return false;
        }else{
            return $this->Product['weight'];
        }
    }
    
    public function getPeriod(){
        if(empty($this->Product)){
            return false;
        }else{
            return json_decode($this->Product['period'],true);
        }
    }
    
    public function getProductGroup(){
        if(empty($this->Product)){
            return false;
        }else{
            return new ProductGroup($this->Product['group'], $this->Class);
        }
    }
    
    public function getConfigOption(){
        if(empty($this->Product)){
            return false;
        }else{
            return json_decode($this->Product['configoption'],true);
        }
    }
    
    public function getCustomOption(){
        if(empty($this->Product)){
            return false;
        }else{
            return json_decode($this->Product['customoption'],true);
        }
    }
    
    public function getServer(){
        if(empty($this->Product)){
            return false;
        }else{
            return new Server($this->Product['server'], $this->Class);
        }
    }
    
    public function isHidden(){
        if(empty($this->Product)){
            return false;
        }else{
            if($this->Product['hidden'] == 1){
                return true;
            }else{
                return false;
            }
        }
    }
    
    public function getStatus(){
        if(empty($this->Product)){
            return false;
        }else{
            return $this->Product['status'];
        }
    }
    
    public function set($array){
        if(empty($this->Product)){
            return false;
        }else{
            if(empty($array)){
                return true;
            }else{
                foreach($array as $k => $v){
                    $this->Database->exec("UPDATE `ytidc_product` SET `{$k}`='{$v}' WHERE `id`='{$this->Product['id']}'");
                }
                return true;
            }
        }
    }
    
    public function setPeriod($array){
        if(empty($this->Product)){
            return false;
        }else{
            $array = json_encode($array);
            return $this->Database->exec("UPDATE `ytidc_product` SET `period`='{$array}' WHERE `id`='{$this->Product['id']}'");
        }
    }
    
    public function setConfigOption($array){
        if(empty($this->Product)){
            return false;
        }else{
            $array = json_encode($array, JSON_UNESCAPED_UNICODE);
            return $this->Database->exec("UPDATE `ytidc_product` SET `configoption`='{$array}' WHERE `id`='{$this->Product['id']}'");
        }
    }
    
    public function setCustomOption($array){
        if(empty($this->Product)){
            return false;
        }else{
            $array = json_encode($array, JSON_UNESCAPED_UNICODE);
            return $this->Database->exec("UPDATE `ytidc_product` SET `customoption`='{$array}' WHERE `id`='{$this->Product['id']}'");
        }
    }
    
    public function setStatus($Status = true){
        if(empty($this->Product)){
            return false;
        }else{
            if($status){
                return $this->Database->exec("UPDATE `ytidc_product` SET `status`='1' WHERE `id`='{$this->Product['id']}'");
            }else{
                return $this->Database->exec("UPDATE `ytidc_product` SET `status`='0' WHERE `id`='{$this->Product['id']}'");
            }
        }
    }
    
}

?>