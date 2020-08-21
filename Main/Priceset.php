<?php

namespace YunTaIDC\Priceset;

class Priceset{
    
    private $Database;
    public $Priceset;
    
    public function __construct($Priceset, $Class){
        $this->Database = $Class->getSystem()->getDatabase();
        if($Priceset == 0){
            $this->Priceset = $this->Database->get_row("SELECT * FROM `ytidc_priceset` WHERE `default`='1'");
        }else{
            $this->Priceset = $this->Database->get_row("SELECT * FROM `ytidc_priceset` WHERE `id`='{$Priceset}'");
        }
    }
    
    public function isExisted(){
        if(empty($this->Priceset)){
            return false;
        }else{
            return true;
        }
    }
    
    public function getAll(){
        if(empty($this->Priceset)){
            return false;
        }else{
            return $this->Priceset;
        }
    }
    
    public function getId(){
        if(empty($this->Priceset)){
            return false;
        }else{
            return $this->Priceset['id'];
        }
    }
    
    public function getName(){
        if(empty($this->Priceset)){
            return false;
        }else{
            return $this->Priceset['name'];
        }
    }
    
    public function getDescrption(){
        if(empty($this->Priceset)){
            return false;
        }else{
            return $this->Priceset['description'];
        }
    }
    
    public function getWeight(){
        if(empty($this->Priceset)){
            return false;
        }else{
            return $this->Priceset['weight'];
        }
    }
    
    public function getMoney(){
        if(empty($this->Priceset)){
            return false;
        }else{
            return $this->Priceset['money'];
        }
    }
    
    public function getPrice(){
        if(empty($this->Priceset)){
            return false;
        }else{
            return json_decode($this->Priceset['price'], true);
        }
    }
    
    public function getProductPrice($product){
        if(empty($this->Priceset)){
            return false;
        }else{
            $price = json_decode($this->Priceset['price'], true);
            return $price[$product];
        }
    }
    
    public function isDefault(){
        if(empty($this->Priceset)){
            return false;
        }else{
            return $this->Priceset['default'];
        }
    }
    
    public function getStatus(){
        if(empty($this->Priceset)){
            return false;
        }else{
            return $this->Priceset['status'];
        }
    }
    
    public function setName($name){
        if(empty($this->Priceset)){
            return false;
        }else{
            return $this->Database->exec("UPDATE `ytidc_priceset` SET `name`='{$name}' WHERE `id`='{$this->Priceset['id']}'");
        }
    }
    
    public function setDescription($description){
        if(empty($this->Priceset)){
            return false;
        }else{
            return $this->Database->exec("UPDATE `ytidc_priceset` SET `description`='{$description}' WHERE `id`='{$this->Priceset['id']}'");
        }
    }
    
    public function setWeight($weight){
        if(empty($this->Priceset)){
            return false;
        }else{
            return $this->Database->exec("UPDATE `ytidc_priceset` SET `weight`='{$weight}' WHERE `id`='{$this->Priceset['id']}'");
        }
    }
    
    public function setMoney($money){
        if(empty($this->Priceset)){
            return false;
        }else{
            return $this->Database->exec("UPDATE `ytidc_priceset` SET `money`='{$money}' WHERE `id`='{$this->Priceset['id']}'");
        }
    }
    
    public function setPrice($price){
        if(empty($this->Priceset)){
            return false;
        }else{
            $price = json_encode($price);
            return $this->Database->exec("UPDATE `ytidc_priceset` SET `price`='{$price}' WHERE `id`='{$this->Priceset['id']}'");
        }
    }
    
    public function setStatus($status = true){
        if(empty($this->Priceset)){
            return false;
        }else{
            if($status == false){
                return $this->Database->exec("UPDATE `ytidc_priceset` SET `status`='0' WHERE `id`='{$this->Priceset['id']}'");
            }else{
                return $this->Database->exec("UPDATE `ytidc_priceset` SET `status`='1' WHERE `id`='{$this->Priceset['id']}'");
            }
        }
    }
    
    public function set($array){
        if(empty($this->Priceset)){
            return false;
        }else{
            foreach ($array as $k => $b){
                $this->Database->exec("UPDATE `ytidc_priceset` SET `{$k}`='{$v}' WHERE `id`='{$this->Priceset['id']}'");
            }
            if(empty($this->Database->error())){
                return true;
            }else{
                return false;
            }
        }
    }
    
}

?>