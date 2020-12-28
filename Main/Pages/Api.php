<?php

namespace CloudTowerIDC\Page;

use CloudTowerIDC\User\User;
use CloudTowerIDC\Service\Service;
use CloudTowerIDC\ProductGroup\ProductGroup as PG;
use CloudTowerIDC\Product\Product as P;
use CloudTowerIDC\Server\Server;

use CloudTowerIDC\Events\OrderCreateEvent;
use CloudTowerIDC\Events\ServiceCreateEvent;
use CloudTowerIDC\Events\CreateServiceEvent;
use CloudTowerIDC\Events\ServiceRenewEvent;
use CloudTowerIDC\Events\RenewServiceEvent;
use CloudTowerIDC\Events\LoginServiceEvent;

class Api{
    
    private $User;
    
    public function __construct(private $System){
    }
    
    private function LoginUser($username, $password){
        $this->User = new User($username, $this);
        if($this->User->isExisted() === false){
            return false;
        }else{
            if($this->User->getPassword() == md5(md5($password))){
                if($this->User->getStatus() == 0){
                    return false;
                }else{
                    return true;
                }
            }else{
                return false;
            }
        }
    }
    
    public function getSystem(){
        return $this->System;
    }
    
    public function Index(){
        exit('CloudTowerIDC API Page');
    }
    
    public function createService(){
        @header("Content-type: text/json");
        $params = $this->getSystem()->getPostParams();
        if(!$this->LoginUser($params['ctuser'], $params['ctpass'])){
            exit(json_encode(array(
                'status' => 'fail',
                'code' => '1000',
            )));
        }else{
            $P = new P($params['product'], $this);
            if($P->isExisted() === false){
                exit(json_encode(array(
                    'status' => 'fail',
                    'code' => '2001'
                )));
            }else{
                $Period = $P->getPeriod();
                foreach($Period as $k => $v){
                    if($v['name'] == $params['period']){
                        $Period = $v;
                    }
                }
                if(empty($Period)){
                    exit(json_encode(array(
                        'status' => 'fail',
                        'code' => '2002'
                    )));
                }else{
                    $Service = new Service($params['username'], $this);
                    if($Service->isExisted() === true){
                        exit(json_encode(array(
                            'status' => 'fail',
                            'code' => '2003'
                        )));
                    }
                    if(is_numeric($params['username'])){
                        exit(json_encode(array(
                            'status' => 'fail',
                            'code' => '2003'
                        )));
                    }
                    $Priceset = $this->User->getPriceset();
                    if($Priceset !== false){
                        $Discount = $this->User->getPriceset()->getPrice()[$Post['product']];
                        if(empty($Discount)){
                            $Discount = $this->User->getPriceset()->getPrice()['*'];
                            if(empty($Discount)){
                                $Discount = 100;
                            }
                        }
                    }else{
                        $Discount = 100;
                    }
                    $Price = $Period['price'] * $Discount / 100;
                    $Orderid = date('YmdHis').random_int(100000,999999);
                    $OrderEvent = new OrderCreateEvent($this->User, $Orderid, '开通服务#'.$params['username'], $Price, '扣款');
                    $this->getSystem()->getPluginManager()->loadEvent('onOrderCreate', $OrderEvent);
                    if($OrderEvent->isCancelled() === false){
                        if(($this->User->getMoney() - $OrderEvent->getMoney()) < 0){
                            exit(json_encode(array(
                                'status' => 'fail',
                                'code' => '2004'
                            )));
                        }else{
                            $this->User->setMoney(($this->User->getMoney() - $OrderEvent->getMoney()));
                            $this->getSystem()->addOrder($Orderid, '开通服务#'.$Posts['username'], $OrderEvent->getMoney(), '扣款', $this->User->getId(), '已完成');
                        }
                    }
                    $Server = $P->getServer();
                    if($Server->isExisted() === false){
                        exit(json_encode(array(
                            'status' => 'fail',
                            'code' => '2005'
                        )));
                    }else{
                        if($this->getSystem()->getPluginManager()->PluginLoaded($Server->getServerPluginName()) === false){
                            exit(json_encode(array(
                                'status' => 'fail',
                                'code' => '2006'
                            )));
                        }else{
                            $params['customoption'] = json_decode($params['customoption'], true);
                            $Event = new ServiceCreateEvent($params['username'], $params['password'], $Period, $params['customoption'], $P, $this->User);
                            $this->getSystem()->getPluginManager()->loadEvent('onServiceCreate', $Event);
                            if($Event->isCancelled() === false){
                                $Buydate = date('Y-m-d');
                                $Enddate = date('Y-m-d', strtotime("+{$Event->getPeriod()['day']} days", time()));
                                if(!$this->getSystem()->addService($this->User->getId(), $Event->getUsername(), $Event->getPassword(), $Buydate, $Enddate, $Event->getPeriod(), $P->getId(), $params['customoption'])){
                                    exit(json_encode(array(
                                        'status' => 'fail',
                                        'code' => '2007'
                                    )));
                                }else{
                                    $Service = new Service($Event->getUsername(), $this);
                                    if($Service->isExisted() === false){
                                        exit(json_encode(array(
                                            'status' => 'fail',
                                            'code' => '2007'
                                        )));
                                    }
                                }
                                $CreateEvent = new CreateServiceEvent($Service, $P, $Event->getPeriod(), $this->User);
                                $result = $this->getSystem()->getPluginManager()->loadEventByPlugin('CreateService', $CreateEvent, $Server->getServerPluginName());
                                if($result === true){
                                    $CreateEvent->getService()->setStatus('激活');
                                    $OutService = $CreateEvent->getService()->getAll();
                                    unset($OutService['configoption']);
                                    unset($OutService['period']);
                                    unset($OutService['customoption']);
                                    $OutService['password'] = base64_decode($OutService['password']);
                                    exit(json_encode(array(
                                        'status' => 'success',
                                        'code' => '200',
                                        'result' => $OutService
                                    )));
                                }else{
                                    exit(json_encode(array(
                                        'status' => 'fail',
                                        'code' => '2010'
                                    )));
                                }
                            }else{
                                exit(json_encode(array(
                                    'status' => 'fail',
                                    'code' => '2008'
                                )));
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function renewService(){
        @header("Content-type: text/json");
        $params = $this->getSystem()->getPostParams();
        if(!$this->LoginUser($params['ctuser'], $params['ctpass'])){
            exit(json_encode(array(
                'status' => 'fail',
                'code' => '1000'
            )));
        }else{
            $Service = new Service($params['service'], $this);
            if($Service->isExisted() === false){
                exit(json_encode(array(
                    'status' => 'fail',
                    'code' => '2011'
                )));
            }else{
                if($Service->getUser()->getId() != $this->User->getId()){
                    exit(json_encode(array(
                        'status' => 'fail',
                        'code' => '2012'
                    )));
                }else{
                    $Product = $Service->getProduct();
                    if($Product->isExisted() === false){
                        exit(json_encode(array(
                            'status' => 'fail',
                            'code' => '2013'
                        )));
                    }else{
                        $Server = $Product->getServer();
                        if($Server->isExisted() === false){
                            exit(json_encode(array(
                                'status' => 'fail',
                                'code' => '2014'
                            )));
                        }else{
                            $Period = $Product->getPeriod();
                            foreach($Period as $k => $v){
                                if($params['period'] == $v['name']){
                                    $Period = $v;
                                }
                            }
                        }
                        if(empty($Period) || $Period['renew'] != 1){
                            exit(json_encode(array(
                                'status' => 'fail',
                                'code' => '2015'
                            )));
                        }else{
                            $Priceset = $this->User->getPriceset();
                            if($Priceset !== false){
                                $Discount = $this->User->getPriceset()->getPrice()[$Post['product']];
                                if(empty($Discount)){
                                    $Discount = $this->User->getPriceset()->getPrice()['*'];
                                    if(empty($Discount)){
                                        $Discount = 100;
                                    }
                                }
                            }else{
                                $Discount = 100;
                            }
                            $Price = $Period['price'] * $Discount / 100;
                            $Orderid = date('YmdHis').random_int(100000,999999);
                            $OrderEvent = new OrderCreateEvent($this->User, $Orderid, '续费服务#'.$Service->getUsername(), $Price, '扣款');
                            $this->getSystem()->getPluginManager()->loadEvent('onOrderCreate', $OrderEvent);
                            if($OrderEvent->isCancelled() === false){
                                if(($this->User->getMoney() - $OrderEvent->getMoney()) < 0){
                                    exit(json_encode(array(
                                        'status' => 'fail',
                                        'code' => '2016'
                                    )));
                                }else{
                                    $this->User->setMoney(($this->User->getMoney() - $OrderEvent->getMoney()));
                                    $this->getSystem()->addOrder($Orderid, '续费服务#'.$Service->getUsername(), $OrderEvent->getMoney(), '扣款', $this->User->getId(), '已完成');
                                }
                            }
                            if($this->getSystem()->getPluginManager()->PluginLoaded($Server->getServerPluginName()) === false){
                                exit(json_encode(array(
                                    'status' => 'fail',
                                    'code' => '2017'
                                )));
                            }else{
                                $Event = new ServiceRenewEvent($Service, $Product, $Period, $this->User);
                                $this->getSystem()->getPluginManager()->loadEvent('onServiceRenew', $Event);
                                if($Event->isCancelled() === false){
                                    $Enddate = date('Y-m-d',strtotime("+{$Period['day']} days", strtotime($Service->getEndDate())));
                                    $Service->setEndDate($Enddate);
                                    $RenewEvent = new RenewServiceEvent($Service, $Product, $Period, $this->User);
                                    $result = $this->getSystem()->getPluginManager()->loadEventByPlugin('RenewService', $RenewEvent, $Server->getServerPluginName());
                                    if($result === true){
                                        exit(json_encode(array(
                                            'status' => 'success',
                                            'code' => '200'
                                        )));
                                    }else{
                                        exit(json_encode(array(
                                            'status' => 'fail',
                                            'code' => '2018'
                                        )));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function loginService(){
        $params = $this->getSystem()->getPostParams();
        if(empty($params['username']) || empty($params['password'])){
            exit('请提交账户密码');
        }else{
            $Service = new Service($params['username'], $this);
            if($params['password'] != $Service->getPassword()){
                exit('账户密码不正确');
            }else{
                $Product = $Service->getProduct();
                $PluginManager = $this->getSystem()->getPluginManager();
                if($Product->isExisted() === false){
                    exit('产品不存在，无法登陆');
                }else{
                    $Server = $Product->getServer();
                    if($Server->isExisted() === false){
                        exit('服务器不存在，无法登陆');
                    }else{
                        $Plugin = $PluginManager->getPlugin($Server->getServerPluginName());
                        if(method_exists($Plugin,'LoginService')){
                            $Event = new LoginServiceEvent($Service, $Product, $Server);
                            $Plugin->LoginService($Event);
                        }else{
                            exit('该服务器无法登陆');
                        }
                    }
                }
            }
        }
    }
    
}

?>