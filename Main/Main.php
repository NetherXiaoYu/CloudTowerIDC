<?php

//Main.php处理一切

namespace YunTaIDC\Main;

use YunTaIDC\Database\Database;
use YunTaIDC\Logger\Logger;
use YunTaIDC\Page;
use YunTaIDC\User\User;
use YunTaIDC\Input\Input;
use YunTaIDC\Plugin\PluginManager;

class Main{

    public $Database;
    private $config;
    public $Logger;
    public $Gets;
    public $Posts;
    public $PluginManager;

    public function __construct(){
        $this->Database = new Database();
        $this->config = $this->Database->get_rows("SELECT * FROM `ytidc_config`");
        $this->setSystemConfig($this->config);
        $this->Logger = new Logger();
        $input = new Input();
        $this->Gets = $input->getInputs('GET');
        $this->Posts = $input->getInputs('POST');
        $this->PluginManager = new PluginManager($this);
        $this->PluginManager->loadPlugins($this->Database, $this->Gets, $this->Posts);
    }

    public function setSystemConfig($config){
        $new_config = array();
        foreach($config as $k => $v){
            $new_config[$v['key']] = $v['value'];
        }
        $this->config = $new_config;
    }

    public function Load(){
        if(empty($this->Gets['p'])){
            $page = 'Index';
        }else{
            $page = $this->Gets['p'];
        }
        if(empty($this->Gets['a'])){
            $action = 'Index';
        }else{
            $action = $this->Gets['a'];
        }
        $this->LoadPage($page, $action);
    }

    private function LoadPage($page, $action){
        if(!file_exists(BASE_ROOT.'Main/Pages/'.$page.'.php')){
            exit();
        }else{
            require_once(BASE_ROOT.'Main/Pages/'.$page.'.php');
            $class = 'YunTaIDC\Page\\'.$page;
            $pager = new $class($this);
            if(method_exists($pager, $action)){
                $pager->$action();
            }else{
                $pager->Index();
            }
        }
    }
    
    public function getSystem(){
        return $this;
    }
    
    public function getPluginManager(){
        return $this->PluginManager;
    }
    
    public function getConfigValue($key){
        return $this->config[$key];
    }
    
    public function getConfigAll(){
        return $this->config;
    }
    
    public function getTemplateCustom(){
        $custom = $this->Database->get_rows("SELECT * FROM `ytidc_template`");
        $customs = array();
        foreach($custom as $k => $v){
            $customs[$v['key']] = $v['value'];
        }
        return $customs;
    }
    
    public function getDatabase(){
        return $this->Database;
    }
    
    public function getLogger(){
        return $this->Logger;
    }
    
    public function getPostParams(){
        return $this->Posts;
    }
    
    public function getGetParams(){
        return $this->Gets;
    }
    
    public function getClientIp(){
    	$ip = false;
    	if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
    		$ip = $_SERVER["HTTP_CLIENT_IP"];
    	}
    	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    		$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
    		if ($ip) {
    			array_unshift($ips, $ip);
    			$ip = FALSE;}
    		for ($i = 0; $i < count($ips); $i++) {
    			if (!preg_match("^(10│172.16│192.168).", $ips[$i])) {
    				$ip = $ips[$i];
    				break;
    			}
    		}
    	}
    	return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }
    
    public function registerUser($username, $password){
        $password = md5(md5($password));
        if($this->getDatabase()->num_rows("SELECT * FROM `ytidc_user` WHERE `username`='{$username}'") != 0){
            return false;
        }else{
            return $this->getDatabase()->exec("INSERT INTO `ytidc_user`(`username`, `password`, `money`, `priceset`, `lastip`, `status`) VALUES ('{$username}', '{$password}', '0.00', '0', '0', '1')");
        }
    }
    
    public function checkUserLogin(){
        $Lastip = $_SESSION['ytidc_lastip'];
        $User = new User($_SESSION['ytidc_user'], $this);
        if($User->isExisted() === false){
            return false;
        }else{
            if($this->getClientIp() == $Lastip && $User->getLastIp() == $this->getClientIp()){
                return true;
            }else{
                return false;
            }
        }
    }
    
    public function getNotices(){
        return $this->getDatabase()->get_rows("SELECT * FROM `ytidc_notice` WHERE `status`='1'");
    }
    
    public function getProductGroups(){
        return $this->getDatabase()->get_rows("SELECT * FROM `ytidc_group` WHERE `status`='1'");
    }
    
    public function getGateways(){
        return $this->getDatabase()->get_rows("SELECT * FROM `ytidc_gateway` WHERE `status`='1'");
    }
    
    public function getPricesets(){
        return $this->getDatabase()->get_rows("SELECT * FROM `ytidc_priceset` WHERE `status`='1'");
    }
    
    public function getDefaultPriceset(){
        return $this->getDatabase()->get_row("SELECT * FROM `ytidc_priceset` WHERE `default`='1'");
    }
    
    public function setDefaultPriceset($priceset){
        if($priceset->getDefault() == 1){
            return true;
        }else{
            $priceset = $priceset->getId();
            $this->getDatabase()->exec("UPDATE `ytidc_priceset` SET `default`='0' WHERE `default`='1'");
            return $this->getDatabase()->exec("UPDATE `ytidc_priceset` SET `default`='1' WHERE `id`='{$priceset}'");
        }
    }
    
    public function addWorkorder($title, $content, $service, $user){
        return $this->getDatabase()->exec("INSERT INTO `ytidc_workorder`(`title`, `content`, `service`, `user`, `status`) VALUES ('{$title}', '{$content}', '{$service}', '{$user}', '待处理')");
    }
    
    public function addOrder($orderid, $description, $money, $action, $user, $status){
        return $this->getDatabase()->exec("INSERT INTO `ytidc_order`(`orderid`, `description`, `money`, `action`, `user`, `status`) VALUES ('{$orderid}', '{$description}', '{$money}', '{$action}', '{$user}', '{$status}')");
    }
    
    public function addService($User, $Username, $Password, $Buydate, $Enddate, $Period, $Product, $Customoption){
        $Password = base64_encode($Password);
        $Period = json_encode($Period, JSON_UNESCAPED_UNICODE);
        $Customoption = json_encode($Customoption, JSON_UNESCAPED_UNICODE);
        return $this->getDatabase()->exec("INSERT INTO `ytidc_service`(`user`, `username`, `password`, `buydate`, `enddate`, `period`, `product`, `customoption`, `configoption`, `status`) VALUES ('{$User}', '{$Username}', '{$Password}', '{$Buydate}', '{$Enddate}', '{$Period}', '{$Product}', '{$Customoption}', '', '待开通')");
    }
}

?>