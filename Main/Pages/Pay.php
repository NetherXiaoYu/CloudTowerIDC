<?php

namespace YunTaIDC\Page;

use YunTaIDC\User\User;
use YunTaIDC\Order\Order;
use YunTaIDC\Gateway\Gateway;

use YunTaIDC\Plugin\PluginManager;

use YunTaIDC\Events\OrderCreateEvent;
use YunTaIDC\Events\OrderChangeEvent;
use YunTaIDC\Events\PaySendEvent;
use YunTaIDC\Events\PayReturnEvent;
use YunTaIDC\Events\PayNotifyEvent;

class Pay extends Page{
    
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
    
    public function Send(){
        if($this->checkLogin() === false){
            $this->goMsg('请先登陆');
        }else{
            $Posts = $this->getSystem()->getPostParams();
            if(empty($Posts['gateway']) || empty($Posts['money']) || $Posts['money'] <= 0){
                $this->goMsg('请先选择支付渠道或填写金额！');
            }else{
                $Gateway = new Gateway($Posts['gateway'], $this);
                if($Gateway->isExisted() === false){
                    $this->goMsg('支付渠道不存在');
                }else{
                    if($Gateway->getPluginName() === false){
                        $this->goMsg('支付渠道没有配置插件');
                    }else{
                        $Plugin = $this->getPluginManager()->getPlugin($Gateway->getPluginName());
                        if($Plugin === false){
                            $this->goMsg('支付渠道配置插件不存在');
                        }else{
                            $Orderid = date('YmdHis').rand(100000,999999);
                            $OrderEvent = new OrderCreateEvent($this->getUser(), $Orderid, '用户充值#'.$Orderid, $Posts['money'], '加款');
                            $this->getPluginManager()->loadEvent('onOrderCreate', $OrderEvent);
                            if($OrderEvent->isCancelled() === false){
                                if($this->getSystem()->addOrder($Orderid, '用户充值#'.$Orderid,  $Posts['money'], '加款', $this->getUser()->getId(), '待支付')){
                                    $Order = new Order($Orderid, $this);
                                    $event = new PaySendEvent($this->getUser(), $Order, $Gateway);
                                    $this->getPluginManager()->loadEventByPlugin('onPaySend', $event, $Gateway->getPluginName());
                                }else{
                                    exit('系统提示：创建订单失败');
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function Return(){
        if($this->checkLogin() === false){
            $this->goMsg('请先登陆');
        }else{
            $Gets = $this->getSystem()->getGetParams();
            $Posts = $this->getSystem()->getPostParams();
            if(empty($Gets['Gateway'])){
                $this->goMsg('返回格式不正确');
            }else{
                $Gateway = new Gateway($Gets['Gateway'],$this);
                unset($Gets['Gateway']);
                if($Gateway->isExisted() === false){
                    $this->goMsg('支付渠道不存在');
                }else{
                    // 
                    if($this->getPluginManager()->getPlugin($Gateway->getPluginName()) === false){
                        $this->goMsg('支付渠道配置插件不存在');
                    }else{
                        $event = new PayReturnEvent($this->getUser(), $Gateway, $Gets, $Posts);
                        $result = $this->getPluginManager()->loadEventByPlugin('onPayReturn', $event, $Gateway->getPluginName());
                        if($result['status'] == 'success'){
                            $Order = new Order($result['Orderid'], $this);
                            if($Order->isExisted() === false){
                                $this->goMsg('订单不存在或插件无返回订单信息');
                            }else{
                                $event = new OrderChangeEvent($Order, '已完成');
                                $this->getPluginManager()->loadEvent('onOrderChange', $event);
                                if($event->isCancelled() === false){
                                    if($Order->getStatus() != '待支付'){
                                        $this->goMsg('该订单已完成');
                                    }else{
                                        $Money = $Order->getMoney();
                                        $Order->setStatus('已完成');
                                        if($Order->getUser()->setMoney(($Order->getUser()->getMoney() + $Money)) === false){
                                            $this->goMsg('加款失败！请联系管理员');
                                        }else{
                                            $this->goMsg('加款成功！');
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function Notify(){
        $Gets = $this->getSystem()->getGetParams();
        $Posts = $this->getSystem()->getPostParams();
        if(empty($Gets['Gateway'])){
            exit('fail');
        }else{
            $Gateway = new Gateway($Gets['Gateway'],$this);
            unset($Gets['Gateway']);
            if($Gateway->isExisted() === false){
                exit('fail');
            }else{
                // 
                if($this->getPluginManager()->getPlugin($Gateway->getPluginName()) === false){
                    exit('fail');
                }else{
                    $event = new PayNotifyEvent($Gateway, $Gets, $Posts);
                    $result = $this->getPluginManager()->loadEventByPlugin('onPayNotify', $event, $Gateway->getPluginName());
                    if($result['status'] == 'success'){
                        $Order = new Order($result['Orderid'], $this);
                        if($Order->isExisted() === false){
                            exit('fail');
                        }else{
                            $event = new OrderChangeEvent($Order, '已完成');
                            $this->getPluginManager()->loadEvent('onOrderChange', $event);
                            if($event->isCancelled() === false){
                                if($Order->getStatus() != '待支付'){
                                    exit('success');
                                }else{
                                    $Money = $Order->getMoney();
                                    $Order->setStatus('已完成');
                                    if($Order->getUser()->setMoney(($Order->getUser()->getMoney() + $Money)) === false){
                                        exit('fail');
                                    }else{
                                        exit('success');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
}

?>