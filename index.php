<?php
if(file_exists("./install.php")){
    @header("Location: ./install.php");
    exit;
}
if(phpversion() < 8){
    exit('CloudTowerIDC:请使用PHP8.0.0版本以上运行云塔IDC系统');
}
session_start();
define('BASE_ROOT',str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/");

require_once(BASE_ROOT.'config.php');//加载数据库配置
if(DEBUG_MODE === false){
    error_reporting(0);
}
require_once(BASE_ROOT.'Main/Logger.php');//加载文档配置
require_once(BASE_ROOT.'Main/Database.php');//加载数据库执行文件
require_once(BASE_ROOT.'Main/Input.php');//加载输入执行文件
require_once(BASE_ROOT.'Main/Template.php');//加载模板执行文件
require_once(BASE_ROOT.'Main/Main.php');//加载系统执行文件
require_once(BASE_ROOT.'Main/Gateway.php');
require_once(BASE_ROOT.'Main/User.php');
require_once(BASE_ROOT.'Main/Notice.php');
require_once(BASE_ROOT.'Main/Workorder.php');
require_once(BASE_ROOT.'Main/Order.php');
require_once(BASE_ROOT.'Main/Server.php');
require_once(BASE_ROOT.'Main/Type.php');
require_once(BASE_ROOT.'Main/Product.php');
require_once(BASE_ROOT.'Main/Service.php');
require_once(BASE_ROOT.'Main/Priceset.php');
require_once(BASE_ROOT.'Main/Admin.php');
require_once(BASE_ROOT.'Main/Events/Events.php');
require_once(BASE_ROOT.'Main/Events/TemplateLoadEvent.php');
require_once(BASE_ROOT.'Main/Events/CronEvent.php');
require_once(BASE_ROOT.'Main/Events/UserLoginEvent.php');
require_once(BASE_ROOT.'Main/Events/UserRegisterEvent.php');
require_once(BASE_ROOT.'Main/Events/UserUpgradePricesetEvent.php');
require_once(BASE_ROOT.'Main/Events/UserAddWorkorderEvent.php');
require_once(BASE_ROOT.'Main/Events/UserReplyWorkorderEvent.php');
require_once(BASE_ROOT.'Main/Events/UserChangePasswordEvent.php');
require_once(BASE_ROOT.'Main/Events/OrderCreateEvent.php');
require_once(BASE_ROOT.'Main/Events/OrderChangeEvent.php');
require_once(BASE_ROOT.'Main/Events/PaySendEvent.php');
require_once(BASE_ROOT.'Main/Events/PayReturnEvent.php');
require_once(BASE_ROOT.'Main/Events/PayNotifyEvent.php');
require_once(BASE_ROOT.'Main/Events/ProductConfigEvent.php');
require_once(BASE_ROOT.'Main/Events/ServiceCreateEvent.php');
require_once(BASE_ROOT.'Main/Events/AdminReplyWorkorderEvent.php');
require_once(BASE_ROOT.'Main/Events/CreateServiceEvent.php');
require_once(BASE_ROOT.'Main/Events/ServiceRenewEvent.php');
require_once(BASE_ROOT.'Main/Events/RenewServiceEvent.php');
require_once(BASE_ROOT.'Main/Events/ServiceDeleteEvent.php');
require_once(BASE_ROOT.'Main/Events/DeleteServiceEvent.php');
require_once(BASE_ROOT.'Main/Events/LoginServiceEvent.php');
require_once(BASE_ROOT.'Main/Plugin/PluginManager.php');
require_once(BASE_ROOT.'Main/Plugin/PluginConfig.php');
require_once(BASE_ROOT.'Main/Plugin/PluginBase.php');
require_once(BASE_ROOT.'Main/Plugin/PluginPage.php');
require_once(BASE_ROOT.'Main/Pages/Page.php');

$system = new CloudTowerIDC\Main\Main();
$system->Load();
?>