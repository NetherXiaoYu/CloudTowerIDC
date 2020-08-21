<?php

namespace YunTaIDC\Events;

use YunTaIDC\Events\Events;
use YunTaIDC\User\User;

class UserAddWorkorderEvent extends Events{
    
    public $User;
    public $Title;
    public $Content;
    
    public function __construct(User $User, string $Title, string $Content){
        $this->User = $User;
    }
    
    public function getUser(){
        return $this->User;
    }
    
    public function getTitle(){
        return $this->Title;
    }
    
    public function getContent(){
        return $this->Content;
    }
    
}

?>