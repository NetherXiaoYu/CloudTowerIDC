<?php

namespace CloudTowerIDC\Page;

use CloudTowerIDC\Service\Service;

use CloudTowerIDC\Events\CronEvent;
use CloudTowerIDC\Events\ServiceDeleteEvent;
use CloudTowerIDC\Events\DeleteServiceEvent;

class Cron{
    
    private $Logger;

    public function __construct(
        private $System
    ){
        $this->Logger = $this->System->getLogger();
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
        $deletedate = date('Y-m-d', strtotime("-{$config['cron_deleteday']} days", time()));
        $Services = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_service` WHERE `enddate`<='{$deletedate}'");
        if(is_array($Services)){
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
        }
        $this->getSystem()->getDatabase()->exec("DELETE FROM `ytidc_service` WHERE `enddate`<='{$deletedate}'");
        $this->UpdateCron();
        $this->Logger->addSystemLog('云塔Service Cron执行成功');
        exit('success');
    }

    private function UpdateCron(){
        $date = date('Y-m-d');
        $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_config` SET `value`='{$date}' WHERE `key`='cron_date'");
        $this->Logger->addSystemLog('云塔Cron日期已更新');
    }

    public function Orders(){
        $this->getSystem()->getDatabase()->exec("DELETE FROM `ytidc_order` WHERE `status`='待支付'");
        $this->UpdateCron();
        $this->Logger->addSystemLog('云塔Orders Cron执行成功');
        exit('success');
    }

    
    public function Plugin(){
        $PluginManager = $this->getSystem()->getPluginManager();
        $Event = new CronEvent($this->getSystem());
        if(!empty($this->getSystem()->getGetParams()['plugin'])){
            $PluginManager->loadEventByPlugin('onCron', $Event, $this->getSystem()->getGetParams()['plugin']);
        }
        $this->Logger->addSystemLog('云塔Plugin Cron执行成功');
        exit('success');
    }
    
}

?>