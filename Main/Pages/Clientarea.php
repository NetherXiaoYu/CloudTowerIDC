<?php

namespace YunTaIDC\Page;

use YunTaIDC\Template\Template;
use YunTaIDC\Logger\Logger;
use YunTaIDC\User\User;
use YuntaIDC\Priceset\Priceset;
use YunTaIDC\Workorder\Workorder;
use YunTaIDC\Service\Service;
use YunTaIDC\Plugin\PluginManager;
use YunTaIDC\Notice\Notice;

use YunTaIDC\Events\UserLoginEvent;
use YunTaIDC\Events\UserRegisterEvent;
use YunTaIDC\Events\UserUpgradePricesetEvent;
use YunTaIDC\Events\UserAddWorkorderEvent;
use YunTaIDC\Events\UserReplyWorkorderEvent;
use YunTaIDC\Events\UserChangePasswordEvent;

class Clientarea extends Page{
    
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
    
    public function Index(){ //用户首页
        if(!$this->checkLogin()){
            $this->goLogin();
        }else{
            $this->getTemplate()->setTemplateFile('UserIndex');
            $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
            if(!$this->getTemplateEvent()->isCancelled()){
                $template_code = array(
                    'data' => array(
                        'servicecount' => $this->getUser()->getServiceCount(),
                        'workordercount' => $this->getUser()->getWorkorderCount(),
                    ),
                    'config' => $this->getSystem()->getConfigAll(),
                    'template' => $this->getSystem()->getTemplateCustom(),
                    'user' => $this->getUser()->getAll(),
                );
                $this->getTemplate()->setTemplateCode($template_code);
                echo $this->getTemplate()->outputTemplate();
            }
        }
    }

    public function Logout(){
        unset($_SESSION['ytidc_user']);
        @header("Location: ./index.php?p=Clientarea&a=Login");
        exit;
    }
    
    public function Login(){ //用户登陆
        if($this->checkLogin()){
            $this->goIndex();
        }else{
            if(!empty($this->getSystem()->getPostParams())){
                $params = $this->getSystem()->getPostParams();
                if(!empty($params['ytidc_username']) && !empty($params['ytidc_password'])){
                    $user = new User($params['ytidc_username'], $this);
                    if(!$user){
                        $this->goLogin('用户不存在');
                    }else{
                        if(md5(md5($params['ytidc_password'])) == $user->getPassword()){
                            $event = new UserLoginEvent($user, $this->getSystem()->getClientIp());
                            $this->getPluginManager()->loadEvent('onUserLogin', $event);
                            if($event->isCancelled() === true){
                                $this->goLogin('插件取消登陆事件');
                            }else{
                                $_SESSION['ytidc_user'] = $user->getId();
                                $_SESSION['ytidc_lastip'] = $this->getSystem()->getClientIp();
                                $user->setLastIp($this->getSystem()->getClientIp());
                                $this->goIndex();
                            }
                        }else{
                            $this->goLogin('账户密码错误');
                        }
                    }
                }else{
                    $this->goLogin('请填写账户密码');
                }
            }else{
                $this->getTemplate()->setTemplateFile('UserLogin');
                $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
                //echo $event->getTemplate()->setTemplateContent('Index', array('config' => $this->getSystem()->getConfigAll()));
                if(!$this->getTemplateEvent()->isCancelled()){
                    $params = $this->getSystem()->getGetParams();
                    if(empty($params['msg'])){
                        $params['msg'] = '欢迎登录';
                    }
                    $template_code = array(
                        'config' => $this->getSystem()->getConfigAll(),
                        'template' => $this->getSystem()->getTemplateCustom(),
                        'message' => $params['msg'],
                    );
                    $this->getTemplate()->setTemplateCode($template_code);
                    echo $this->getTemplate()->outputTemplate();
                }
            }
        }
    }
    
    public function Register(){ //用户注册
        if($this->checkLogin()){
            $this->goIndex();
        }else{
            if(!empty($this->getSystem()->getPostParams())){
                $params = $this->getSystem()->getPostParams();
                if(!empty($params['ytidc_username']) && !empty($params['ytidc_password']) && !empty($params['ytidc_password2'])){
                    if($params['ytidc_password'] != $params['ytidc_password2']){
                        $this->goRegister('请填写账户密码');
                    }else{
                        if(is_numeric($params['ytidc_username'])){
                            $this->goRegister('用户名不能为纯数字');
                        }
                        $event = new UserRegisterEvent($params['ytidc_username'], $params['ytidc_password']);
                        $this->getPluginManager()->loadEvent('onUserRegister', $event);
                        if($event->isCancelled() === false){
                            if($this->getSystem()->registerUser($params['ytidc_username'], $params['ytidc_password'])){
                                $this->goLogin('注册成功，请登录');
                            }else{
                                $this->goRegister('系统插入数据库失败');
                            }
                        }else{
                            $this->goRegister('插件取消注册事件');
                        }
                    }
                }else{
                    $this->goRegister('请填写账户密码');
                }
            }else{
                $this->getTemplate()->setTemplateFile('UserRegister');
                $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
                if(!$this->getTemplateEvent()->isCancelled()){
                    $params = $this->getSystem()->getGetParams();
                    if(empty($params['msg'])){
                        $params['msg'] = '欢迎注册';
                    }
                    $template_code = array(
                        'config' => $this->getSystem()->getConfigAll(),
                        'template' => $this->getSystem()->getTemplateCustom(),
                        'message' => $params['msg'],
                    );
                    $this->getTemplate()->setTemplateCode($template_code);
                    echo $this->getTemplate()->outputTemplate();
                }
            }
        }
    }
    
    public function goIndex(){
        @header("Location: ./index.php?p=Clientarea&a=Index");
        exit;
    }
    
    public function goLogin($msg = null){
        @header("Location: ./index.php?p=Clientarea&a=Login&msg={$msg}");
        exit;
    }
    
    public function goRegister($msg = null){
        @header("Location: ./index.php?p=Clientarea&a=Register&msg={$msg}");
        exit;
    }
    
    public function goMsg($msg){
        @header("Location: ./index.php?p=Clientarea&a=Msg&msg={$msg}");
        exit;
    }
    
    public function Workorders(){ //工单列表
        if(!$this->checkLogin()){
            $this->goLogin();
        }else{
            $this->getTemplate()->setTemplateFile('UserWorkorders');
            $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
            if(!$this->getTemplateEvent()->isCancelled()){
                $workorder = $this->getUser()->getWorkorders();
                if($workorder === false){
                    $template = $this->getTemplate()->replaceListContent('WorkorderList', array());
                }else{
                    // exit(print_r($notice));
                    $template = $this->getTemplate()->replaceListContent('WorkorderList', $workorder);
                }
                $template_code = array(
                    'config' => $this->getSystem()->getConfigAll(),
                    'template' => $this->getSystem()->getTemplateCustom(),
                    'user' => $this->getUser()->getAll(),
                );
                $this->getTemplate()->setTemplateCode($template_code);
                echo $this->getTemplate()->outputTemplate();
            }
        }
    }
    
    public function Notices(){ //公告列表
        if(!$this->checkLogin()){
            $this->goLogin();
        }else{
            $this->getTemplate()->setTemplateFile('UserNotices');
            $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
            if(!$this->getTemplateEvent()->isCancelled()){
                $notice = $this->getSystem()->getNotices();
                if($notice === false){
                    $template = $this->getTemplate()->replaceListContent('NoticeList', array());
                }else{
                    // exit(print_r($notice));
                    $template = $this->getTemplate()->replaceListContent('NoticeList', $notice);
                }
                $template_code = array(
                    'config' => $this->getSystem()->getConfigAll(),
                    'template' => $this->getSystem()->getTemplateCustom(),
                    'user' => $this->getUser()->getAll(),
                );
                $this->getTemplate()->setTemplateCode($template_code);
                echo $this->getTemplate()->outputTemplate();
            }
        }
    }
    
    public function Services(){ //服务列表
        if(!$this->checkLogin()){
            $this->goLogin();
        }else{
            $this->getTemplate()->setTemplateFile('UserServices');
            $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
            if(!$this->getTemplateEvent()->isCancelled()){
                $service = $this->getUser()->getServices();
                if($service === false){
                    $template = $this->getTemplate()->replaceListContent('ServiceList', array());
                }else{
                    $template = $this->getTemplate()->replaceListContent('ServiceList', $service);
                }
                $template_code = array(
                    'config' => $this->getSystem()->getConfigAll(),
                    'template' => $this->getSystem()->getTemplateCustom(),
                    'user' => $this->getUser()->getAll(),
                );
                $this->getTemplate()->setTemplateCode($template_code);
                echo $this->getTemplate()->outputTemplate();
            }
        }
    }
    
    public function Orders(){ //订单列表
        if(!$this->checkLogin()){
            $this->goLogin();
        }else{
            $this->getTemplate()->setTemplateFile('UserOrders');
            $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
            if(!$this->getTemplateEvent()->isCancelled()){
                $order = $this->getUser()->getOrders();
                if($order === false){
                    $template = $this->getTemplate()->replaceListContent('OrderList', array());
                }else{
                    $template = $this->getTemplate()->replaceListContent('OrderList', $order);
                }
                $template_code = array(
                    'config' => $this->getSystem()->getConfigAll(),
                    'template' => $this->getSystem()->getTemplateCustom(),
                    'user' => $this->getUser()->getAll(),
                );
                $this->getTemplate()->setTemplateCode($template_code);
                echo $this->getTemplate()->outputTemplate();
            }
        }
    }
    
    public function Pricesets(){ //价格组列表
        if(!$this->checkLogin()){
            $this->goLogin();
        }else{
            $params = $this->getSystem()->getGetParams();
            if(!empty($params['up'])){
                $priceset = new Priceset($params['up'], $this);
                if($priceset->isExisted() === false){
                    $this->goMsg('价格组不存在');
                }else{
                    $event = new UserUpgradePricesetEvent($this->getUser(), $priceset);
                    $userpriceset = $this->getUser()->getPriceset();
                    if($priceset->isDefault() == 1){
                        $event->setCancelled(true);
                        $this->getPluginManager()->loadEvent('onUserUpgradePriceset', $event);
                        $this->goMsg('不能开通默认的价格组');
                    }else{
                        if($priceset->getId() == $userpriceset->getId()){
                            $event->setCancelled(true);
                            $this->getPluginManager()->loadEvent('onUserUpgradePriceset', $event);
                            $this->goMsg('不能开通同一个价格组');
                        }else{
                            if($userpriceset->getWeight() >= $priceset->getWeight()){
                                $event->setCancelled(true);
                                $this->getPluginManager()->loadEvent('onUserUpgradePriceset', $event);
                                $this->goMsg('不能开通等级相同或者更低的价格组哦！');
                            }else{
                                if($this->getUser()->getMoney() >= $priceset->getMoney()){
                                    $this->getPluginManager()->loadEvent('onUserUpgradePriceset', $event);
                                    if($event->isCancelled() === false){
                                        $this->getUser()->setPriceset($priceset->getId());
                                        $this->goMsg('升级价格组成功');
                                    }
                                }else{
                                    $event->setCancelled(true);
                                    $this->getPluginManager()->loadEvent('onUserUpgradePriceset', $event);
                                    $this->goMsg('预存不足升级价格组');
                                }
                            }
                        }
                    }
                }
            }else{
                $this->getTemplate()->setTemplateFile('UserPricesets');
                $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
                if(!$this->getTemplateEvent()->isCancelled()){
                $priceset = $this->getSystem()->getPricesets();
                if($priceset === false){
                    $template = $this->getTemplate()->replaceListContent('PricesetList', array());
                }else{
                    $template = $this->getTemplate()->replaceListContent('PricesetList', $priceset);
                }
                $template_code = array(
                    'config' => $this->getSystem()->getConfigAll(),
                    'template' => $this->getSystem()->getTemplateCustom(),
                    'user' => $this->getUser()->getAll(),
                );
                $this->getTemplate()->setTemplateCode($template_code);
                echo $this->getTemplate()->outputTemplate();
                }
            }
        }
    }
    
    public function Msg(){
        if(!$this->checkLogin()){
            $this->goLogin();
        }else{
            $this->getTemplate()->setTemplateFile('UserMsg');
            $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
            if(!$this->getTemplateEvent()->isCancelled()){
                $params = $this->getSystem()->getGetParams();
                $template_code = array(
                    'config' => $this->getSystem()->getConfigAll(),
                    'template' => $this->getSystem()->getTemplateCustom(),
                    'user' => $this->getUser()->getAll(),
                    'msg' => $params['msg'],
                );
                $this->getTemplate()->setTemplateCode($template_code);
                echo $this->getTemplate()->outputTemplate();
            }
        }
    }
    
    public function AddWorkorder(){
        if(!$this->checkLogin()){
            $this->goLogin();
        }else{
            $params = $this->getSystem()->getPostParams();
            if(!empty($params['title']) && !empty($params['content'])){
                $event = new UserAddWorkorderEvent($this->getUser(), $params['title'], $params['content']);
                $this->getPluginManager()->loadEvent('onUserAddWorkorder', $event);
                if($event->isCancelled() == false){
                    if($this->getSystem()->addWorkorder($params['title'], $params['content'], $params['service'], $this->getUser()->getId())){
                        $this->goMsg('提交工单成功');
                    }else{
                        $this->goMsg('录入数据库失败');
                    }
                }
            }else{
                $this->getTemplate()->setTemplateFile('UserAddWorkorder');
                $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
                if(!$this->getTemplateEvent()->isCancelled()){
                $service = $this->getUser()->getServices();
                if($service === false){
                    $template = $this->getTemplate()->replaceListContent('ServiceList', array());
                }else{
                    $template = $this->getTemplate()->replaceListContent('ServiceList', $service);
                }
                $template_code = array(
                    'config' => $this->getSystem()->getConfigAll(),
                    'template' => $this->getSystem()->getTemplateCustom(),
                    'user' => $this->getUser()->getAll(),
                );
                $this->getTemplate()->setTemplateCode($template_code);
                echo $this->getTemplate()->outputTemplate();
                }
            }
        }
    }
    
    public function Workorder(){
        if(!$this->checkLogin()){
            $this->goLogin();
        }else{
            $params = $this->getSystem()->getPostParams();
            $workorder = $this->getSystem()->getGetParams()['wid'];
            $workorder = new Workorder($workorder, $this);
            if($workorder->isExisted() === false){
                $this->goMsg('工单不存在！');
            }else{
                if(!empty($params['reply'])){
                    $event = new UserReplyWorkorderEvent($this->getUser(), $workorder, $params['reply']);
                    $this->getPluginManager()->loadEvent('onUserReplyWorkorder', $event);
                    if($event->isCancelled() === false){
                        if($workorder->addReply($this->getUser()->getUsername(), $params['reply'])){
                            $this->goMsg('回复成功');
                        }else{
                            $this->goMsg('录入数据库失败');
                        }
                    }
                    }else{
                    $this->getTemplate()->setTemplateFile('UserWorkorder');
                    $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
                    if(!$this->getTemplateEvent()->isCancelled()){
                        $reply = $workorder->getReplys();
                        if($reply === false){
                            $template = $this->getTemplate()->replaceListContent('ReplyList', array());
                        }else{
                            $template = $this->getTemplate()->replaceListContent('ReplyList', $reply);
                        }
                        $template_code = array(
                            'config' => $this->getSystem()->getConfigAll(),
                            'template' => $this->getSystem()->getTemplateCustom(),
                            'user' => $this->getUser()->getAll(),
                            'workorder' => $workorder->getAll(),
                        );
                        $this->getTemplate()->setTemplateCode($template_code);
                        echo $this->getTemplate()->outputTemplate();
                    }
                }
            }
        }
    }
    
    public function ChangePassword(){
        if(!$this->checkLogin()){
            $this->goLogin();
        }else{
            $params = $this->getSystem()->getPostParams();
            if(!empty($params['password'])){
                $event = new UserChangePasswordEvent($this->getUser(), $params['password']);
                $this->getPluginManager()->loadEvent('onUserChangePassword', $event);
                if($event->isCancelled() === false){
                    if($this->getUser()->setPassword($params['password'])){
                        $this->goMsg('修改成功');
                    }else{
                        $this->goMsg('录入数据库失败(可能是密码一样)');
                    }
                }
            }else{
                $this->getTemplate()->setTemplateFile('UserChangePassword');
                $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
                if(!$this->getTemplateEvent()->isCancelled()){
                    $template_code = array(
                        'config' => $this->getSystem()->getConfigAll(),
                        'template' => $this->getSystem()->getTemplateCustom(),
                        'user' => $this->getUser()->getAll(),
                    );
                    echo $this->getTemplate()->setTemplateCode($template_code);
                    echo $this->getTemplate()->outputTemplate();
                }
            }
        }
    }
    
    public function Notice(){
        if(!$this->checkLogin()){
            $this->goLogin();
        }else{
            $params = $this->getSystem()->getGetParams();
            if(!empty($params['nid'])){
                $notice = new Notice($params['nid'], $this);
                if($notice->isExisted() === false){
                    $this->goMsg('公告不存在');
                }
                $this->getTemplate()->setTemplateFile('UserNotice');
                $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
                if(!$this->getTemplateEvent()->isCancelled()){
                    $template_code = array(
                        'config' => $this->getSystem()->getConfigAll(),
                        'template' => $this->getSystem()->getTemplateCustom(),
                        'user' => $this->getUser()->getAll(),
                        'notice' => $notice->getAll(),
                    );
                    echo $this->getTemplate()->setTemplateCode($template_code);
                    echo $this->getTemplate()->outputTemplate();
                }
            }else{
                $this->goMsg('公告不存在');
            }
        }
    }
    
    public function Pay(){
        if(!$this->checkLogin()){
            $this->goLogin();
        }else{
            $this->getTemplate()->setTemplateFile('UserPay');
            $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
            if(!$this->getTemplateEvent()->isCancelled()){
                $Gateway = $this->getSystem()->getGateways();
                // exit(print_r($Gateway));
                if($Gateway === false){
                    $template = $this->getTemplate()->replaceListContent('GatewayList', array());
                }else{
                    $template = $this->getTemplate()->replaceListContent('GatewayList', $Gateway);
                }
                $template_code = array(
                    'config' => $this->getSystem()->getConfigAll(),
                    'template' => $this->getSystem()->getTemplateCustom(),
                    'user' => $this->getUser()->getAll(),
                );
                echo $this->getTemplate()->setTemplateCode($template_code);
                echo $this->getTemplate()->outputTemplate();
            }
        }
    }
    
    public function Service(){
        if(!$this->checkLogin()){
            $this->goLogin();
        }else{
            $params = $this->getSystem()->getGetParams();
            if(!empty($params['sid'])){
                $service = new Service($params['sid'], $this);
                if($service->isExisted() === false){
                    $this->goMsg('服务不存在');
                }
                if($service->getUser()->getId() != $this->getUser()->getId()){
                    $this->goMsg('该服务不属于您');
                }else{
                    $this->getTemplate()->setTemplateFile('UserService');
                    $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
                    if(!$this->getTemplateEvent()->isCancelled()){
                        $Product = $service->getProduct();
                        $Period = $Product->getPeriod();
                        $Priceset = $Price = $this->getUser()->getPriceset();
                        if($Priceset !== false){
                            $Price = $this->getUser()->getPriceset()->getPrice()[$Product->getId()];
                            if(empty($Price)){
                                $Price = $this->getUser()->getPriceset()->getPrice()['*'];
                                if(empty($Price)){
                                    $Price = 100;
                                }
                            }
                        }else{
                            $Price = 100;
                        }
                        foreach($Period as $k => $v){
                            $Period[$k]['price'] = $v['price'] * $Price / 100;
                        }
                        if($Period === false){
                            $this->getTemplate()->replaceListContent('PeriodList', array());
                        }else{
                            $this->getTemplate()->replaceListContent('PeriodList', $Period);
                        }
                        $service = $service->getAll();
                        $service['password'] = base64_decode($service['password']);
                        $template_code = array(
                            'config' => $this->getSystem()->getConfigAll(),
                            'template' => $this->getSystem()->getTemplateCustom(),
                            'user' => $this->getUser()->getAll(),
                            'service' => $service,
                            'product' => $Product->getAll(),
                        );
                        echo $this->getTemplate()->setTemplateCode($template_code);
                        echo $this->getTemplate()->outputTemplate();
                    }
                }
            }else{
                $this->goMsg('请选择服务进行管理');
            }
        }
    }
    
}

?>