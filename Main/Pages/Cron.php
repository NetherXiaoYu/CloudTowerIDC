<?php

namespace YunTaIDC\Page;

use YunTaIDC\Service\Service;

use YunTaIDC\Events\CronEvent;
use YunTaIDC\Events\ServiceDeleteEvent;
use YunTaIDC\Events\DeleteServiceEvent;

class Cron{
    
    private $System;
    
    public function __construct($System){
        $this->System = $System;
    }
    
    public function getSystem(){
        return $this->System;
    }
    
    public function Index(){
        exit('CloudTowerIDC Cron Page');
    }
    
    public function Service(){
        $config = $this->getSystem()->getConfigAll();
        $stopdate = date('Y-m-d', strtotime("+{$config['cron_stopday']} days", time()));
        $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_service` SET `status`='暂停' WHERE `enddate`<='$stopdate'");
        $deletedate = date('Y-m-d', strtotime("-{$config['cron_stopday']} days", time()));
        $Services = $this->getSystem()->getDatabase()->exec("SELECT * FROM `ytidc_service` WHERE `enddate`<='{$deletedate}'");
        foreach ($Services as $k => $v){
            $Service = new Service($v['username'], $this);
            $Product = $Service->getProduct();
            if($Product->isExisted() !== false){
                $Server = $Product->getServer();
                if($Server->isExisted() !== false){
                    $PluginManager = $this->getSystem()->getPluginManager();
                    $Event = new ServiceDeleteEvent($Service, $Server);
                    $PluginManager->loadEvent('onServiceDelete', $Event);
                    $DeleteEvent = new DeleteServiceEvent($Service, $Server);
                    $PluginManager->loadEventByPlugin('DeleteService', $DeleteEvent, $Server->getServerPluginName());
                }
            }
        }
        $this->getSystem()->getDatabase()->exec("DELETE FROM `ytidc_service` WHERE `enddate`<='{$deletedate}'");
        $date = date('Y-m-d');
        $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_config` SET `value`='{$date}' WHERE `key`='cron_date'");
        exit('success');
    }
    
    public function Plugin(){
        $PluginManager = $this->getSystem()->getPluginManager();
        $Event = new CronEvent($this->getSystem());
        if(!empty($this->getSystem()->getGetParams()['plugin'])){
            $PluginManager->loadEventByPlugin('onCron', $Event, $this->getSystem()->getGetParams()['plugin']);
        }
        exit('success');
    }
    
}

?>