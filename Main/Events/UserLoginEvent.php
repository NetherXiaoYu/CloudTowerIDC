<?php

namespace YunTaIDC\Events;

use YunTaIDC\User\User;
use YunTaIDC\Logger\Logger;
use YunTaIDC\Events\Events;

class UserLoginEvent extends Events{
    
    public $User;
    public $LoginIp;
    
    public function __construct(User $User, $ip){
        $this->User = $User;
        $this->LoginIp = $ip;
        $this->LoginStatus = $LoginStatus;
    }
    
    public function getUser(){
        return $this->User;
    }
    
    public function getLoginIp(){
        return $this->LoginIp;
    }
    
}

?>