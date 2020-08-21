<?php

namespace YunTaIDC\Events;

use YunTaIDC\Events\Events;
use YunTaIDC\User\User;
use YunTaIDC\Workorder\Workorder;

class UserReplyWorkorderEvent extends Events{
    
    public $User;
    public $Workorder;
    public $Reply;
    
    public function __construct(User $User, Workorder $Workorder, String $Reply){
        $this->User = $User;
        $this->Workorder = $Workorder;
        $this->Reply = $Reply;
    }
    
    public function getUser(){
        return $this->User;
    }
    
    public function getWorkorder(){
        return $this->Workorder;
    }
    
    public function getReply(){
        return $this->Reply;
    }
    
}

?>