<?php

namespace CloudTowerIDC\Notice;

class Notice{
    
    private $Database;
    public $Notice;
    
    public function __construct($Notice,private $Class){
        $this->Database = $this->Class->getSystem()->getDatabase();
        $this->Notice = $this->Database->get_row("SELECT * FROM `ytidc_notice` WHERE `id`='{$Notice}'");
    }
    
    public function isExisted(){
        if(empty($this->Notice)){
            return false;
        }else{
            return true;
        }
    }
    
    public function getAll(){
        if(empty($this->Notice)){
            return false;
        }else{
            return $this->Notice;
        }
    }
    
    public function getId(){
        if(empty($this->Notice)){
            return false;
        }else{
            return $this->Notice['id'];
        }
    }
    
    public function getTitle(){
        if(empty($this->Notice)){
            return false;
        }else{
            return $this->Notice['title'];
        }
    }
    
    public function getContent(){
        if(empty($this->Notice)){
            return false;
        }else{
            return $this->Notice['content'];
        }
    }
    
    public function getDate(){
        if(empty($this->Notice)){
            return false;
        }else{
            return $this->Notice['date'];
        }
    }
    
    public function getStatus(){
        if(empty($this->Notice)){
            return false;
        }else{
            return $this->Notice['status'];
        }
    }
    
    public function setStatus($status = true){
        if(empty($this->Notice)){
            return false;
        }else{
            if($status = true){
                return $this->Database->exec("UPDATE `ytidc_notice` SET `status`='1' WHERE `id`='{$this->Notice['id']}'");
            }else{
                return $this->Database->exec("UPDATE `ytidc_notice` SET `status`='0' WHERE `id`='{$this->Notice['id']}'");   
            }
        }
    }
    
    public function set($array){
        if(empty($this->Notice)){
            return false;
        }else{
            foreach($array as $k => $v){
                $this->Database->exec("UPDATE `ytidc_notice` SET `{$k}`='{$v}' WHERE `id`='{$this->Notice['id']}'");
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