<?php

namespace CloudTowerIDC\Page;

use CloudTowerIDC\Service\Service as S;
use CloudTowerIDC\USer\User;
use CloudTowerIDC\Product\Product;
use CloudTowerIDC\Priceset\Priceset;
use CloudTowerIDC\Server\Server;

use CloudTowerIDC\Plugin\PluginManager;

use CloudTowerIDC\Events\OrderCreateEvent;
use CloudTowerIDC\Events\ServiceCreateEvent;
use CloudTowerIDC\Events\CreateServiceEvent;
use CloudTowerIDC\Events\ServiceRenewEvent;
use CloudTowerIDC\Events\LoginServiceEvent;
use CloudTowerIDC\Events\RenewServiceEvent;

class Service extends Page{
    
    private $User;
    
    public function getUser(){
        return $this->User;
    }
    
    public function checkLogin(){
        if($this->getSystem()->checkUserLogin() === true){
            $this->User = new User($_SESSION['ytidc_user'], $this);
            return true;
        }else{
            return false;
        }
    }
    
    public function goMsg($msg){
        @header("Location: ./index.php?p=Clientarea&a=Msg&msg={$msg}");
        exit;
    }
    
    public function CreateService(){
        if($this->checkLogin() === false){
            $this->goMsg('请先登陆');
        }else{
            $Posts = $this->getSystem()->getPostParams();
            $Product = new Product($Posts['product'], $this);
            if($Product->isExisted() === false){
                $this->goMsg('产品不存在');
            }else{
                $Periods = $Product->getPeriod();
                foreach($Periods as $k => $v){
                    if($v['name'] == $Posts['period']){
                        $Period = $v;
                    }
                }
                if(empty($Period)){
                    $this->goMsg('所选周期不存在');
                }else{
                    $Service = new S($Posts['username'], $this);
                    if($Service->isExisted() === true){
                        $this->goMsg('服务账号已被使用');
                    }
                    if(is_numeric($Posts['username'])){
                        $this->goMsg('服务账号不能为纯数字！');
                    }
                    $Priceset = $this->getUser()->getPriceset();
                    if($Priceset !== false){
                        $Discount = $this->getUser()->getPriceset()->getPrice()[$Post['product']];
                        if(empty($Discount)){
                            $Discount = $this->getUser()->getPriceset()->getPrice()['*'];
                            if(empty($Discount)){
                                $Discount = 100;
                            }
                        }
                    }else{
                        $Discount = 100;
                    }
                    $Price = $Period['price'] * $Discount / 100;
                    $Orderid = date('YmdHis').random_int(100000,999999);
                    $OrderEvent = new OrderCreateEvent($this->getUser(), $Orderid, '开通服务#'.$Posts['username'], $Price, '扣款');
                    $this->getPluginManager()->loadEvent('onOrderCreate', $OrderEvent);
                    if($OrderEvent->isCancelled() === false){
                        if(($this->getUser()->getMoney() - $OrderEvent->getMoney()) < 0){
                            $this->goMsg('用户余额不足');
                        }else{
                            $this->getUser()->setMoney(($this->getUser()->getMoney() - $OrderEvent->getMoney()));
                            $this->getSystem()->addOrder($Orderid, '开通服务#'.$Posts['username'], $OrderEvent->getMoney(), '扣款', $this->getUser()->getId(), '已完成');
                        }
                    }
                    $Server = $Product->getServer();
                    if($Server->isExisted() === false){
                        $this->goMsg('产品所属服务器不存在');
                    }else{
                        if($this->getPluginManager()->PluginLoaded($Server->getServerPluginName()) === false){
                            $this->goMsg('服务器插件未被成功加载');
                        }else{
                            $Event = new ServiceCreateEvent($Posts['username'], $Posts['password'], $Period, $Posts['customoption'], $Product, $this->getUser());
                            $this->getPluginManager()->loadEvent('onServiceCreate', $Event);
                            if($Event->isCancelled() === false){
                                $Buydate = date('Y-m-d');
                                $Enddate = date('Y-m-d', strtotime("+{$Event->getPeriod()['day']} days", time()));
                                if(!$this->getSystem()->addService($this->getUser()->getId(), $Event->getUsername(), $Event->getPassword(), $Buydate, $Enddate, $Event->getPeriod(), $Product->getId(), $Posts['customoption'])){
                                    $this->goMsg('服务插入数据库失败');
                                }else{
                                    $Service = new S($Event->getUsername(), $this);
                                    if($Service->isExisted() === false){
                                        $this->goMsg('服务插入数据库失败');
                                    }
                                }
                                $CreateEvent = new CreateServiceEvent($Service, $Product, $Event->getPeriod(), $this->getUser());
                                $result = $this->getPluginManager()->loadEventByPlugin('CreateService', $CreateEvent, $Server->getServerPluginName());
                                if($result === true){
                                    $CreateEvent->getService()->setStatus('激活');
                                    $this->goMsg('服务开通成功');
                                }else{
                                    $this->goMsg('服务开通失败，但已扣除费用，请联系管理员处理');
                                }
                            }else{
                                $this->goMsg('插件取消了服务创建，但已扣除费用，请联系管理员处理');
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function RenewService(){
        if($this->checkLogin() === false){
            $this->goMsg('请先登陆');
        }else{
            $Posts = $this->getSystem()->getPostParams();
            $Service = new S($Posts['service'], $this);
            if($Service->isExisted() === false){
                $this->goMsg('服务不存在');
            }else{
                if($Service->getUser()->getId() != $this->getUser()->getId()){
                    $this->goMsg('该服务不属于您');
                }
                $Product = $Service->getProduct();
                if($Product->isExisted() === false){
                    $this->goMsg('服务所属产品已被删除，不能续费！');
                }else{
                    $Server = $Product->getServer();
                    if($Server->isExisted() === false){
                        $this->goMsg('产品所属服务器已被删除，不能续费！');
                    }else{
                        $Periods = $Product->getPeriod();
                        foreach($Periods as $k => $v){
                            if($Posts['period'] == $v['name']){
                                $Period = $v;
                            }
                        }
                        if(empty($Period) || $Period['renew'] != 1){
                            exit(print_r($Period));
                            $this->goMsg('所选周期不存在或不允许续费');
                        }else{
                            $Priceset = $this->getUser()->getPriceset();
                            if($Priceset !== false){
                                $Discount = $this->getUser()->getPriceset()->getPrice()[$Post['product']];
                                if(empty($Discount)){
                                    $Discount = $this->getUser()->getPriceset()->getPrice()['*'];
                                    if(empty($Discount)){
                                        $Discount = 100;
                                    }
                                }
                            }else{
                                $Discount = 100;
                            }
                            $Price = $Period['price'] * $Discount / 100;
                            $Orderid = date('YmdHis').random_int(100000,999999);
                            $OrderEvent = new OrderCreateEvent($this->getUser(), $Orderid, '续费服务#'.$Service->getUsername(), $Price, '扣款');
                            $this->getPluginManager()->loadEvent('onOrderCreate', $OrderEvent);
                            if($OrderEvent->isCancelled() === false){
                                if(($this->getUser()->getMoney() - $OrderEvent->getMoney()) < 0){
                                    $this->goMsg('用户余额不足');
                                }else{
                                    $this->getUser()->setMoney(($this->getUser()->getMoney() - $OrderEvent->getMoney()));
                                    $this->getSystem()->addOrder($Orderid, '续费服务#'.$Service->getUsername(), $OrderEvent->getMoney(), '扣款', $this->getUser()->getId(), '已完成');
                                }
                            }
                            if($this->getPluginManager()->PluginLoaded($Server->getServerPluginName()) === false){
                                $this->goMsg('服务器插件未被成功加载');
                            }else{
                                $Event = new ServiceRenewEvent($Service, $Product, $Period, $this->getUser());
                                $this->getPluginManager()->loadEvent('onServiceRenew', $Event);
                                if($Event->isCancelled() === false){
                                    $Enddate = date('Y-m-d',strtotime("+{$Period['day']} days", strtotime($Service->getEndDate())));
                                    $Service->setEndDate($Enddate);
                                    $RenewEvent = new RenewServiceEvent($Service, $Product, $Period, $this->getUser());
                                    $result = $this->getPluginManager()->loadEventByPlugin('RenewService', $RenewEvent, $Server->getServerPluginName());
                                    if($result === true){
                                        $this->goMsg('服务续费成功');
                                    }else{
                                        $this->goMsg('服务续费失败，但已扣除费用，请联系管理员处理');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function LoginService(){
        if($this->checkLogin() === false){
            $this->goMsg('请先登陆');
        }else{
            $params = $this->getSystem()->getGetParams();
            if(empty($params['service'])){
                $this->goMsg('请提交服务ID');
            }else{
                $Service = new S($params['service'], $this);
                if($Service->getUser()->getId() != $this->getUser()->getId()){
                    $this->goMsg('该服务不属于你');
                }else{
                    $Product = $Service->getProduct();
                    $PluginManager = $this->getSystem()->getPluginManager();
                    if($Product->isExisted() === false){
                        $this->goMsg('产品不存在，无法登陆');
                    }else{
                        $Server = $Product->getServer();
                        if($Server->isExisted() === false){
                            $this->goMsg('服务器不存在，无法登陆');
                        }else{
                            $Plugin = $PluginManager->getPlugin($Server->getServerPluginName());
                            if(method_exists($Plugin,'LoginService')){
                                $Event = new LoginServiceEvent($Service, $Product, $Server);
                                $Plugin->LoginService($Event);
                            }else{
                                $this->goMsg('该服务器无法登陆');
                            }
                        }
                    }
                }
            }
        }
    }
    
}

?>