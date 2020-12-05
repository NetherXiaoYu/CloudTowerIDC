<?php

namespace CloudTowerIDC\Events;

use CloudTowerIDC\Admin\Admin;
use CloudTowerIDC\Workorder\Workorder;

class AdminReplyWorkorderEvent extends Events{
    
    public function __construct(
        public string $Content,
        public Workorder $Workorder,
        public Admin $Admin
    ){}
    
    public function getContent(){
        return $this->Content;
    }
    
    public function getWorkorder(){
        return $this->Workorder;
    }
    
    public function getAdmin(){
        return $this->Admin;
    }
    
}

?>