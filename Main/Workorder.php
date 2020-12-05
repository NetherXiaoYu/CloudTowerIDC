<?php

namespace CloudTowerIDC\Workorder;

use CloudTowerIDC\User\User;

class Workorder{
    
    private $Database;
    public $Workorder;
    
    public function __construct($Workorder,private $Class){
        $this->Database = $this->Class->getSystem()->getDatabase();
        $this->Workorder = $this->Database->get_row("SELECT * FROM `ytidc_workorder` WHERE `id`='{$Workorder}'");
    }
    
    public function isExisted(){
        if(empty($this->Workorder)){
            return false;
        }else{
            return true;
        }
    }
    
    public function getAll(){
        if(empty($this->Workorder)){
            return false;
        }else{
            return $this->Workorder;
        }
    }
    
    public function getId(){
        if(empty($this->Workorder)){
            return false;
        }else{
            return $this->Workorder['id'];
        }
    }
    
    public function getTitle(){
        if(empty($this->Workorder)){
            return false;
        }else{
            return $this->Workorder['title'];
        }
    }
    
    public function getContent(){
        if(empty($this->Workorder)){
            return false;
        }else{
            return $this->Workorder['content'];
        }
    }
    
    public function getService(){
        if(empty($this->Workorder)){
            return false;
        }else{
            return $this->Workorder['service'];
        }
    }
    
    public function getUser(){
        if(empty($this->Workorder)){
            return false;
        }else{
            return new User($this->Workorder['user'], $this->Class);
        }
    }
    
    public function getStatus(){
        if(empty($this->Workorder)){
            return false;
        }else{
            return $this->Workorder['status'];
        }
    }
    
    public function getReplys(){
        if(empty($this->Workorder)){
            return false;
        }else{
            return $this->Database->get_rows("SELECT * FROM `ytidc_workorder_reply` WHERE `workorder`='{$this->Workorder['id']}'");
        }
    }
    
    public function addReply($person, $content){
        if(empty($this->Workorder)){
            return false;
        }else{
            $time = date('Y-m-d H:i:s');
            return $this->Database->exec("INSERT INTO `ytidc_workorder_reply`(`person`, `content`, `workorder`, `time`) VALUES ('{$person}', '{$content}', '{$this->Workorder['id']}', '{$time}')");
        }
    }
    
    public function setStatus($status){
        if(empty($this->Workorder)){
            return false;
        }else{
            return $this->Database->exec("UPDATE `ytidc_workorder` SET `status`='{$status}' WHERE `id`='{$this->Workorder['id']}'");
        }
    }
    
}

?>