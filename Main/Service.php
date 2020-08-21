<?php

namespace YunTaIDC\Service;

use YunTaIDC\Product\Product;
use YunTaIDC\User\User;

class Service{
    
    private $Database;
    private $Class;
    
    public $Service;
    
    public function __construct($Service, $Class){
        $this->Class = $Class;
        $this->Database = $Class->getSystem()->getDatabase();
        if(is_numeric($Service)){
            $this->Service = $this->Database->get_row("SELECT * FROM `ytidc_service` WHERE `id`='{$Service}'");
        }else{
            $this->Service = $this->Database->get_row("SELECT * FROM `ytidc_service` WHERE `username`='{$Service}'");
        }
    }
    
    public function isExisted(){
        if(empty($this->Service)){
            return false;
        }else{
            return true;
        }
    }
    
    public function getAll(){
        if(empty($this->Service)){
            return false;
        }else{
            return $this->Service;
        }
    }
    
    public function getId(){
        if(empty($this->Service)){
            return false;
        }else{
            return $this->Service['id'];
        }
    }
    
    public function getUsername(){
        if(empty($this->Service)){
            return false;
        }else{
            return $this->Service['username'];
        }
    }
    
    public function getPassword(){
        if(empty($this->Service)){
            return false;
        }else{
            return base64_decode($this->Service['password']);
        }
    }
    
    public function getBuyDate(){
        if(empty($this->Service)){
            return false;
        }else{
            return $this->Service['buydate'];
        }
    }
    
    public function getEndDate(){
        if(empty($this->Service)){
            return false;
        }else{
            return $this->Service['enddate'];
        }
    }
    
    public function getPeriod(){
        if(empty($this->Service)){
            return false;
        }else{
            return json_decode($this->Service['period'],true);
        }
    }
    
    public function getProduct(){
        if(empty($this->Service)){
            return false;
        }else{
            return new Product($this->Service['product'], $this->Class);
        }
    }
    
    public function getPromoCode(){
        if(empty($this->Service)){
            return false;
        }else{
            return $this->Service['PromoCode'];
        }
    }
    
    public function getCustomOption(){
        if(empty($this->Service)){
            return false;
        }else{
            return json_decode($this->Service['customoption'],true);
        }
    }
    
    public function getConfigOption(){
        if(empty($this->Service)){
            return false;
        }else{
            return json_decode($this->Service['username'],true);
        }
    }
    
    public function getUser(){
        if(empty($this->Service)){
            return false;
        }else{
            return new User($this->Service['user'],$this->Class);
        }
    }
    
    public function getStatus(){
        if(empty($this->Service)){
            return false;
        }else{
            return $this->Service['status'];
        }
    }
    
    public function set($array){
        if(empty($this->Service)){
            return false;
        }else{
            if(empty($array)){
                return true;
            }else{
                foreach($array as $k => $v){
                    $this->Database->exec("UPDATE `ytidc_service` SET `{$k}`='{$v}' WHERE `id`='{$this->Service['id']}'");
                }
                return true;
            }
        }
    }
    
    public function setConfigOption($array){
        if(empty($this->Service)){
            return false;
        }else{
            $configoption = json_encode($array);
            return $this->Database->exec("UPDATE `ytidc_service` WHERE `configoption`='{$configoption}' WHERE `id`='{$this->Service['id']}'");
        }
    }
    
    public function setEndDate($date){
        if(empty($this->Service)){
            return false;
        }else{
            $this->Service['enddate'] = $date;
            return $this->Database->exec("UPDATE `ytidc_service` SET `enddate`='{$date}' WHERE `id`='{$this->Service['id']}'");
        }
    }
    
    public function setStatus($status){
        if(empty($this->Service)){
            return false;
        }else{
            $this->Service['status'] = $status;
            return $this->Database->exec("UPDATE `ytidc_service` SET `status`='{$status}' WHERE `id`='{$this->Service['id']}'");
        }
    }
    
}

?>