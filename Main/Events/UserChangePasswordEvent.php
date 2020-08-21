<?php

namespace YunTaIDC\Events;

use YunTaIDC\Events\Events;
use YunTaIDC\User\User;

class UserChangePasswordEvent extends Events{
    
    public $User;
    public $Password;
    
    public function __construct(User $User, String $Password){
        $this->User = $User;
        $this->Password = $Password;
    }
    
    public function getUser(){
        return $this->User;
    }
    
    public function getPassword(){
        return $this->Password;
    }
    
}

?>