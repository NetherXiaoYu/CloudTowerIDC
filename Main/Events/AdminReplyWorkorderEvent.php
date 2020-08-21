<?php

namespace YunTaIDC\Events;

use YunTaIDC\Admin\Admin;
use YunTaIDC\Workorder\Workorder;

class AdminReplyWorkorderEvent extends Events{
    
    public $Workorder;
    public $Content;
    public $Admin;
    
    public function __construct($Content, Workorder $Workorder, Admin $Admin){
        $this->Content = $Content;
        $this->Workorder = $Workorder;
        $this->Admin = $Admin;
    }
    
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