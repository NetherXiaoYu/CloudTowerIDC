<?php

namespace YunTaIDC\Page;

use YunTaIDC\Admin\Admin as A;
use YunTaIDC\Workorder\Workorder;
use YunTaIDC\Server\Server;

use YunTaIDC\Events\AdminReplyWorkorderEvent;
use YunTaIDC\Events\ServiceDeleteEvent;
use YunTaIDC\Events\DeleteServiceEvent;
use YunTaIDC\Events\CreateServiceEvent;
use YunTaIDC\Events\ProductConfigEvent;

use YunTaIDC\Service\Service;

class Admin{
    
    private $System;
    private $Admin;
    
    public function __construct($System){
        $this->System = $System;
    }
    
    public function getSystem(){
        return $this->System;
    }
    
    public function checkLogin(){
        $Lastip = $_SESSION['ctadmin_ip'];
        $this->Admin = new A($_SESSION['ctadmin_user'], $this);
        if($this->Admin->isExisted() === false){
            return false;
        }else{
            if($this->getSystem()->getClientIp() == $Lastip && $this->Admin->getLastIp() == $this->getSystem()->getClientIp()){
                return true;
            }else{
                return false;
            }
        }
    }
    
    private function CheckPermission($permission){
        if(in_array('*', $this->Admin->getPermission()) || in_array($permission, $this->Admin->getPermission())){
            return true;
        }else{
            return false;
        }
    }
    
    private function Header(){
        echo '<!doctype html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
      <title>管理中心 - 云塔IDC系统v3.0.1</title>
      <style>#loader{transition:all .3s ease-in-out;opacity:1;visibility:visible;position:fixed;height:100vh;width:100%;background:#fff;z-index:90000}#loader.fadeOut{opacity:0;visibility:hidden}.spinner{width:40px;height:40px;position:absolute;top:calc(50% - 20px);left:calc(50% - 20px);background-color:#333;border-radius:100%;-webkit-animation:sk-scaleout 1s infinite ease-in-out;animation:sk-scaleout 1s infinite ease-in-out}@-webkit-keyframes sk-scaleout{0%{-webkit-transform:scale(0)}100%{-webkit-transform:scale(1);opacity:0}}@keyframes sk-scaleout{0%{-webkit-transform:scale(0);transform:scale(0)}100%{-webkit-transform:scale(1);transform:scale(1);opacity:0}}</style>
      <link href="/Main/Pages/Admin/style.css" rel="stylesheet">
   </head>
   <body class="app">
      <div id="loader">
         <div class="spinner"></div>
      </div>
      <script>window.addEventListener(\'load\', function load() {
         const loader = document.getElementById(\'loader\');
         setTimeout(function() {
           loader.classList.add(\'fadeOut\');
         }, 300);
         });
      </script>
      <div>
         <div class="sidebar">
            <div class="sidebar-inner">
               <div class="sidebar-logo">
                  <div class="peers ai-c fxw-nw">
                     <div class="peer peer-greed">
                        <a class="sidebar-link td-n" href="./index.php?p=Admin&a=Index">
                           <div class="peers ai-c fxw-nw">
                              <div class="peer">
                                 <div class="logo"><img src="/Main/Pages/Admin/assets/static/images/logo.png" alt=""></div>
                              </div>
                              <div class="peer peer-greed">
                                 <h5 class="lh-1 mB-0 logo-text">云塔IDC系统v3.0</h5>
                              </div>
                           </div>
                        </a>
                     </div>
                     <div class="peer">
                        <div class="mobile-toggle sidebar-toggle"><a href="" class="td-n"><i class="ti-arrow-circle-left"></i></a></div>
                     </div>
                  </div>
               </div>
               <ul class="sidebar-menu scrollable pos-r">
                  <li class="nav-item mT-30 actived"><a class="sidebar-link" href="./index.php?p=Admin&a=Index"><span class="icon-holder"><i class="c-blue-500 ti-home"></i> </span><span class="title">管理中心</span></a></li>
                  <li class="nav-item dropdown">
                     <a class="dropdown-toggle" href="javascript:void(0);"><span class="icon-holder"><i class="c-orange-500 ti-view-list-alt"></i> </span><span class="title">产品服务</span> <span class="arrow"><i class="ti-angle-right"></i></span></a>
                     <ul class="dropdown-menu">
                        <li><a class="sidebar-link" href="./index.php?p=Admin&a=ProductGroups">产品组管理</a></li>
                        <li><a class="sidebar-link" href="./index.php?p=Admin&a=Products">产品管理</a></li>
                        <li><a class="sidebar-link" href="./index.php?p=Admin&a=Servers">服务器管理</a></li>
                        <li><a class="sidebar-link" href="./index.php?p=Admin&a=Services">在线服务管理</a></li>
                     </ul>
                  </li>
                  <li class="nav-item"><a class="sidebar-link" href="./index.php?p=Admin&a=Users"><span class="icon-holder"><i class="c-brown-500 ti-user"></i> </span><span class="title">用户管理</span></a></li>
                  <li class="nav-item dropdown">
                     <a class="dropdown-toggle" href="javascript:void(0);"><span class="icon-holder"><i class="c-purple-500 ti-money"></i> </span><span class="title">财务管理</span> <span class="arrow"><i class="ti-angle-right"></i></span></a>
                     <ul class="dropdown-menu">
                        <li><a href="./index.php?p=Admin&a=Gateways">支付接口管理</a></li>
                        <li><a href="./index.php?p=Admin&a=Pricesets">价格组管理</a></li>
                        <li><a href="./index.php?p=Admin&a=Orders">交易记录</a></li>
                     </ul>
                  </li>
                  <li class="nav-item"><a class="sidebar-link" href="./index.php?p=Admin&a=Workorders"><span class="icon-holder"><i class="c-gray-500 ti-pencil-alt"></i> </span><span class="title">工单管理</span></a></li>
                  <li class="nav-item"><a class="sidebar-link" href="./index.php?p=Admin&a=Admins"><span class="icon-holder"><i class="c-green-500 ti-headphone-alt"></i> </span><span class="title">管理员管理</span></a></li>
                  <li class="nav-item dropdown">
                     <a class="dropdown-toggle" href="javascript:void(0);"><span class="icon-holder"><i class="c-red-500 ti-settings"></i> </span><span class="title">网站设置</span> <span class="arrow"><i class="ti-angle-right"></i></span></a>
                     <ul class="dropdown-menu">
                        <li><a class="sidebar-link" href="./index.php?p=Admin&a=Setting&set=Seo">SEO管理</a></li>
                        <li><a class="sidebar-link" href="./index.php?p=Admin&a=Notices">公告管理</a></li>
                        <li><a class="sidebar-link" href="./index.php?p=Admin&a=Setting&set=Template">模板管理</a></li>
                        <li><a class="sidebar-link" href="./index.php?p=Admin&a=Template">模板自定义</a></li>
                        <li><a class="sidebar-link" href="./index.php?p=Admin&a=Setting&set=Cron">Cron管理</a></li>
                     </ul>
                  </li>
               </ul>
            </div>
         </div>
         <div class="page-container">
            <div class="header navbar">
               <div class="header-container">
                  <ul class="nav-left">
                     <li><a id="sidebar-toggle" class="sidebar-toggle" href="javascript:void(0);"><i class="ti-menu"></i></a></li>
                  </ul>
                  <ul class="nav-right">
                     <li class="dropdown">
                        <a href="" class="dropdown-toggle no-after peers fxw-nw ai-c lh-1" data-toggle="dropdown">
                           <div class="peer mR-10"><img class="w-2r bdrs-50p" src="https://randomuser.me/api/portraits/men/10.jpg" alt=""></div>
                           <div class="peer"><span class="fsz-sm c-grey-900">管理员</span></div>
                        </a>
                        <ul class="dropdown-menu fsz-sm">
                           <li><a href="./index.php?p=Admin&a=Setting&set=Seo" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700"><i class="ti-settings mR-10"></i> <span>网站设置</span></a></li>
                           <li><a href="./index.php?p=Admin&a=Users" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700"><i class="ti-user mR-10"></i> <span>用户管理</span></a></li>
                           <li><a href="./index.php?p=Admin&a=Workorders" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700"><i class="ti-email mR-10"></i> <span>工单管理</span></a></li>
                           <li role="separator" class="divider"></li>
                           <li><a href="./index.php?p=Admin&a=Logout" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700"><i class="ti-power-off mR-10"></i> <span>注销登陆</span></a></li>
                        </ul>
                     </li>
                  </ul>
               </div>
            </div>';
    }
    
    public function Logout(){
        unset($_SESSION['ctadmin_ip']);
        unset($_SESSION['ctadmin_user']);
        @header("Location: ./index.php?p=Admin&a=Login");
        exit;
    }
    
    private function Footer(){
        echo '
            <footer class="bdT ta-c p-30 lh-0 fsz-sm c-grey-600"><span>Copyright © 2020 云塔 设计: Colorlib.</span></footer>
         </div>
      </div>
      <script type="text/javascript" src="/Main/Pages/Admin/vendor.js"></script><script type="text/javascript" src="/Main/Pages/Admin/bundle.js?511"></script>
   </body>
</html>';
    }
    
    private function get_dir($dir){
    	if ($handle = opendir($dir)) {
    		$array = array();
        	while (false !== ($entry = readdir($handle))) {
        	   if ($entry != "." && $entry != "..") {
            	    $array[$entry] = $entry;
            	}
        	}
        	closedir($handle);
    	}
    	return $array;
    }
    
    public function Login(){
        if($this->checkLogin() === true){
            @header("Location: ./index.php?p=Admin&a=Index");
            exit;
        }else{
            if(!empty($this->getSystem()->getPostParams()['username'])){
                $Params = $this->getSystem()->getPostParams();
                $Admin = new A($Params['username'], $this);
                if($Admin->isExisted() === false){
                    $this->goLogin('账号不存在');
                }else{
                    if(md5(md5($Params['password'])) != $Admin->getPassword()){
                        $this->goLogin('密码错误');
                    }else{
                        $_SESSION['ctadmin_ip'] = $this->getSystem()->getClientIp();
                        $_SESSION['ctadmin_user'] = $Params['username'];
                        $Admin->setLastIp($this->getSystem()->getClientIp());
                        @header("Location: ./index.php?p=Admin&a=Index");
                        exit;
                    }
                }
            }else{
                $gets = $this->getSystem()->getGetParams();
                if(!empty($gets['msg'])){
                    $msg = $gets['msg'];
                }else{
                    $msg = '欢迎登陆';
                }
                echo '<!doctype html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
      <title>登陆 - 云塔IDC系统v3.0</title>
      <style>#loader{transition:all .3s ease-in-out;opacity:1;visibility:visible;position:fixed;height:100vh;width:100%;background:#fff;z-index:90000}#loader.fadeOut{opacity:0;visibility:hidden}.spinner{width:40px;height:40px;position:absolute;top:calc(50% - 20px);left:calc(50% - 20px);background-color:#333;border-radius:100%;-webkit-animation:sk-scaleout 1s infinite ease-in-out;animation:sk-scaleout 1s infinite ease-in-out}@-webkit-keyframes sk-scaleout{0%{-webkit-transform:scale(0)}100%{-webkit-transform:scale(1);opacity:0}}@keyframes sk-scaleout{0%{-webkit-transform:scale(0);transform:scale(0)}100%{-webkit-transform:scale(1);transform:scale(1);opacity:0}}</style>
      <link href="/Main/Pages/Admin/style.css" rel="stylesheet">
   </head>
   <body class="app">
      <div id="loader">
         <div class="spinner"></div>
      </div>
      <script>window.addEventListener(\'load\', function load() {
         const loader = document.getElementById(\'loader\');
         setTimeout(function() {
           loader.classList.add(\'fadeOut\');
         }, 300);
         });
      </script>
      <div class="peers ai-s fxw-nw h-100vh">
         <div class="d-n@sm- peer peer-greed h-100 pos-r bgr-n bgpX-c bgpY-c bgsz-cv" style="background-image:url(/Main/Pages/Admin/assets/static/images/bg.jpg)">
            <div class="pos-a centerXY">
               <div class="bgc-white bdrs-50p pos-r" style="width:120px;height:120px"><img class="pos-a centerXY" src="/Main/Pages/Admin/assets/static/images/logo.png" alt=""></div>
            </div>
         </div>
         <div class="col-12 col-md-4 peer pX-40 pY-80 h-100 bgc-white scrollable pos-r" style="min-width:320px">
            <h4 class="fw-300 c-grey-900 mB-40">登陆 - 云塔IDC系统</h4>
            <div class="alert alert-danger" role="alert">'.$msg.'</div>
            <form action="#" method="POST">
               <div class="form-group"><label class="text-normal text-dark">账号：</label> <input type="text" name="username" class="form-control" placeholder="后台账号"></div>
               <div class="form-group"><label class="text-normal text-dark">密码：</label> <input type="password" name="password" class="form-control" placeholder="后台密码"></div>
               <div class="form-group">
                  <div class="peers ai-c jc-sb fxw-nw">
                     <div class="peer">
                        <div class="checkbox checkbox-circle checkbox-info peers ai-c"><input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer"> <label for="inputCall1" class="peers peer-greed js-sb ai-c"><span class="peer peer-greed">记住我</span></label></div>
                     </div>
                     <div class="peer"><button class="btn btn-primary">登陆</button></div>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <script type="text/javascript" src="/Main/Pages/Admin/vendor.js"></script><script type="text/javascript" src="/Main/Pages/Admin/bundle.js"></script>
   </body>
</html>';
            }
        }
    }
    
    private function goLogin($msg = '欢迎登陆'){
        @header("Location: ./index.php?p=Admin&a=Login&msg={$msg}");
        exit;
    }
    
    private function goIndex(){
        @header("Location: ./index.php?p=Admin&a=Index");
        exit;
    }
    
    public function Index(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            $usercount = $this->getSystem()->getDatabase()->num_rows("SELECT COUNT(*) FROM `ytidc_user` WHERE `status`='1'");
            $workordercount = $this->getSystem()->getDatabase()->num_rows("SELECT COUNT(*) FROM `ytidc_workorder` WHERE `status`='待处理'");
            $servicecount = $this->getSystem()->getDatabase()->num_rows("SELECT COUNT(*) FROM `ytidc_service` WHERE `status`='激活'");
            $config = $this->getSystem()->getConfigAll();
            $services = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_service` ORDER BY `id` DESC LIMIT 5");
            $this->Header();
            echo '
            <main class="main-content bgc-grey-100">
               <div id="mainContent">
                  <div class="row gap-20 masonry pos-r">
                     <div class="masonry-sizer col-md-6"></div>
                     <div class="masonry-item w-100">
                        <div class="row gap-20">
                           <div class="col-md-3">
                              <div class="layers bd bgc-white p-20">
                                 <div class="layer w-100 mB-10">
                                    <h6 class="lh-1">注册用户</h6>
                                 </div>
                                 <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                       <div class="peer peer-greed"><span id="sparklinedash"></span></div>
                                       <div class="peer"><span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">'.$usercount.'</span></div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="layers bd bgc-white p-20">
                                 <div class="layer w-100 mB-10">
                                    <h6 class="lh-1">在线服务</h6>
                                 </div>
                                 <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                       <div class="peer peer-greed"><span id="sparklinedash2"></span></div>
                                       <div class="peer"><span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-red-50 c-red-500">'.$servicecount.'</span></div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="layers bd bgc-white p-20">
                                 <div class="layer w-100 mB-10">
                                    <h6 class="lh-1">发起工单</h6>
                                 </div>
                                 <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                       <div class="peer peer-greed"><span id="sparklinedash3"></span></div>
                                       <div class="peer"><span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-purple-50 c-purple-500">'.$workordercount.'</span></div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="layers bd bgc-white p-20">
                                 <div class="layer w-100 mB-10">
                                    <h6 class="lh-1">Cron工作</h6>
                                 </div>
                                 <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                       <div class="peer peer-greed"><span id="sparklinedash4"></span></div>
                                       <div class="peer"><span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">'.$config['cron_date'].'</span></div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="masonry-item col-md-12">
                        <div class="bd bgc-white">
                           <div class="layers">
                              <div class="layer w-100 p-20">
                                 <h6 class="lh-1">最新服务</h6>
                              </div>
                              <div class="layer w-100 table-responsive" style="padding: 1em">
                                 <table class="table table-striped">
                                    <thead>
                                       <tr>
                                          <th scope="col">#</th>
                                          <th scope="col">用户名</th>
                                          <th scope="col">所属产品</th>
                                          <th scope="col">到期时间</th>
                                       </tr>
                                    </thead>
                                    <tbody>';
                                    foreach($services as $k => $v){
                                        $product = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_product` WHERE `id`='{$v['product']}'");
                                        echo '
                                       <tr>
                                          <th scope="row">'.$v['id'].'</th>
                                          <td>'.$v['username'].'</td>
                                          <td>'.$product['name'].'</td>
                                          <td>'.$v['enddate'].'</td>
                                       </tr>';
                                    }
                                    echo '
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </main>
            ';
            $this->Footer();
        }
    }
    
    public function ProductGroups(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('group_check')){
                $this->goIndex();
            }
            $groups = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_group`");
            $this->Header();
            echo '
            <main class="main-content bgc-grey-100">
               <div id="mainContent">
                  <div class="container-fluid">
                     <h4 class="c-grey-900 mT-10 mB-30">产品组管理</h4>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="bgc-white bd bdrs-3 p-20 mB-20">
                              <h4 class="c-grey-900 mB-20">产品组列表<a href="./index.php?p=Admin&a=Add&add=ProductGroup" class="btn btn-sm btn-info">新增</a></h4>
                              <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th>ID</th>
                                       <th>名称</th>
                                       <th>权重</th>
                                       <th>操作</th>
                                    </tr>
                                 </thead>
                                 <tfoot>
                                    <tr>
                                       <th>ID</th>
                                       <th>名称</th>
                                       <th>权重</th>
                                       <th>操作</th>
                                    </tr>
                                 </tfoot>
                                 <tbody>
                                 ';
                                 foreach($groups as $k => $v){
                                     echo '
                                    <tr>
                                       <th>'.$v['id'].'</th>
                                       <th>'.$v['name'].'</th>
                                       <th>'.$v['weight'].'</th>
                                       <th><a href="./index.php?p=Admin&a=ProductGroup&gid='.$v['id'].'" class="btn btn-sm btn-primary">编辑</a><a href="./index.php?p=Admin&a=ProductGroup&gid='.$v['id'].'&act=del" class="btn btn-sm btn-danger">删除</a></th>
                                    </tr>';
                                 }
                                 echo '
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </main>
            ';
            $this->Footer();
        }
    }
    
    public function Products(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('product_check')){
                $this->goIndex();
            }
            $products = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_product`");
            $this->Header();
            echo '
            <main class="main-content bgc-grey-100">
               <div id="mainContent">
                  <div class="container-fluid">
                     <h4 class="c-grey-900 mT-10 mB-30">产品管理</h4>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="bgc-white bd bdrs-3 p-20 mB-20">
                              <h4 class="c-grey-900 mB-20">产品列表<a href="./index.php?p=Admin&a=Add&add=Product" class="btn btn-sm btn-info">新增</a></h4>
                              <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th>ID</th>
                                       <th>名称</th>
                                       <th>产品组</th>
                                       <th>操作</th>
                                    </tr>
                                 </thead>
                                 <tfoot>
                                    <tr>
                                       <th>ID</th>
                                       <th>名称</th>
                                       <th>产品组</th>
                                       <th>操作</th>
                                    </tr>
                                 </tfoot>
                                 <tbody>
                                 ';
                                 foreach($products as $k => $v){
                                     $group = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_group` WHERE `id`='{$v['group']}'");
                                     echo '
                                    <tr>
                                       <th>'.$v['id'].'</th>
                                       <th>'.$v['name'].'</th>
                                       <th>'.$group['name'].'</th>
                                       <th><a href="./index.php?p=Admin&a=Product&pid='.$v['id'].'" class="btn btn-sm btn-primary">编辑</a><a href="./index.php?p=Admin&a=Product&pid='.$v['id'].'&act=del" class="btn btn-sm btn-danger">删除</a></th>
                                    </tr>';
                                 }
                                 echo '
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </main>
            ';
            $this->Footer();
        }
    }
    
    public function Servers(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('server_check')){
                $this->goIndex();
            }
            $servers = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_server`");
            $this->Header();
            echo '
            <main class="main-content bgc-grey-100">
               <div id="mainContent">
                  <div class="container-fluid">
                     <h4 class="c-grey-900 mT-10 mB-30">服务器管理</h4>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="bgc-white bd bdrs-3 p-20 mB-20">
                              <h4 class="c-grey-900 mB-20">服务器列表<a href="./index.php?p=Admin&a=Add&add=Server" class="btn btn-sm btn-info">新增</a></h4>
                              <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th>ID</th>
                                       <th>名称</th>
                                       <th>对接插件</th>
                                       <th>操作</th>
                                    </tr>
                                 </thead>
                                 <tfoot>
                                    <tr>
                                       <th>ID</th>
                                       <th>名称</th>
                                       <th>对接插件</th>
                                       <th>操作</th>
                                    </tr>
                                 </tfoot>
                                 <tbody>
                                 ';
                                 foreach($servers as $k => $v){
                                     echo '
                                    <tr>
                                       <th>'.$v['id'].'</th>
                                       <th>'.$v['name'].'</th>
                                       <th>'.$v['plugin'].'</th>
                                       <th><a href="./index.php?p=Admin&a=Server&sid='.$v['id'].'" class="btn btn-sm btn-primary">编辑</a><a href="./index.php?p=Admin&a=Server&sid='.$v['id'].'&act=del" class="btn btn-sm btn-danger">删除</a></th>
                                    </tr>';
                                 }
                                 echo '
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </main>
            ';
            $this->Footer();
        }
    }
    
    public function Services(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('service_check')){
                $this->goIndex();
            }
            $services = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_service`");
            $this->Header();
            echo '
            <main class="main-content bgc-grey-100">
               <div id="mainContent">
                  <div class="container-fluid">
                     <h4 class="c-grey-900 mT-10 mB-30">在线服务管理</h4>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="bgc-white bd bdrs-3 p-20 mB-20">
                              <h4 class="c-grey-900 mB-20">在线服务列表</h4>
                              <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th>ID</th>
                                       <th>服务账号</th>
                                       <th>所属产品</th>
                                       <th>购买用户</th>
                                       <th>到期时间</th>
                                       <th>操作</th>
                                    </tr>
                                 </thead>
                                 <tfoot>
                                    <tr>
                                       <th>ID</th>
                                       <th>服务账号</th>
                                       <th>所属产品</th>
                                       <th>购买用户</th>
                                       <th>到期时间</th>
                                       <th>操作</th>
                                    </tr>
                                 </tfoot>
                                 <tbody>
                                 ';
                                 foreach($services as $k => $v){
                                     $product = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_product` WHERE `id`='{$v['product']}'");
                                     echo '
                                    <tr>
                                       <th>'.$v['id'].'</th>
                                       <th>'.$v['username'].'</th>
                                       <th>'.$product['name'].'</th>
                                       <th>'.$v['user'].'</th>
                                       <th>'.$v['enddate'].'</th>
                                       <th><a href="./index.php?p=Admin&a=Service&sid='.$v['id'].'" class="btn btn-sm btn-primary">编辑</a>';
                                       if($v['status'] == '待开通'){
                                           echo '<a href="./index.php?p=Admin&a=ReopenService&sid='.$v['id'].'" class="btn btn-sm btn-success">开通</a>';
                                       }
                                       echo '<a href="./index.php?p=Admin&a=Service&sid='.$v['id'].'&act=del" class="btn btn-sm btn-danger">删除</a></th>
                                    </tr>';
                                 }
                                 echo '
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </main>
            ';
            $this->Footer();
        }
    }
    
    public function Users(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('user_check')){
                $this->goIndex();
            }
            $users = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_user`");
            $this->Header();
            echo '
            <main class="main-content bgc-grey-100">
               <div id="mainContent">
                  <div class="container-fluid">
                     <h4 class="c-grey-900 mT-10 mB-30">用户管理</h4>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="bgc-white bd bdrs-3 p-20 mB-20">
                              <h4 class="c-grey-900 mB-20">用户列表</h4>
                              <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th>ID</th>
                                       <th>账号</th>
                                       <th>账号余额</th>
                                       <th>操作</th>
                                    </tr>
                                 </thead>
                                 <tfoot>
                                    <tr>
                                       <th>ID</th>
                                       <th>账号</th>
                                       <th>账号余额</th>
                                       <th>操作</th>
                                    </tr>
                                 </tfoot>
                                 <tbody>
                                 ';
                                 foreach($users as $k => $v){
                                     echo '
                                    <tr>
                                       <th>'.$v['id'].'</th>
                                       <th>'.$v['username'].'</th>
                                       <th>'.$v['money'].'</th>
                                       <th><a href="./index.php?p=Admin&a=User&uid='.$v['id'].'" class="btn btn-sm btn-primary">编辑</a><a href="./index.php?p=Admin&a=User&uid='.$v['id'].'&act=del" class="btn btn-sm btn-danger">删除</a></th>
                                    </tr>';
                                 }
                                 echo '
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </main>
            ';
            $this->Footer();
        }
    }
    
    public function Gateways(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('gateway_check')){
                $this->goIndex();
            }
            $gateways = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_gateway`");
            $this->Header();
            echo '
            <main class="main-content bgc-grey-100">
               <div id="mainContent">
                  <div class="container-fluid">
                     <h4 class="c-grey-900 mT-10 mB-30">支付接口管理</h4>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="bgc-white bd bdrs-3 p-20 mB-20">
                              <h4 class="c-grey-900 mB-20">支付接口列表<a href="./index.php?p=Admin&a=Add&add=Gateway" class="btn btn-sm btn-info">新增</a></h4>
                              <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th>ID</th>
                                       <th>名称</th>
                                       <th>到账率</th>
                                       <th>插件</th>
                                       <th>操作</th>
                                    </tr>
                                 </thead>
                                 <tfoot>
                                    <tr>
                                       <th>ID</th>
                                       <th>名称</th>
                                       <th>到账率</th>
                                       <th>插件</th>
                                       <th>操作</th>
                                    </tr>
                                 </tfoot>
                                 <tbody>
                                 ';
                                 foreach($gateways as $k => $v){
                                     echo '
                                    <tr>
                                       <th>'.$v['id'].'</th>
                                       <th>'.$v['name'].'</th>
                                       <th>'.$v['rate'].'%</th>
                                       <th>'.$v['plugin'].'</th>
                                       <th><a href="./index.php?p=Admin&a=Gateway&gid='.$v['id'].'" class="btn btn-sm btn-primary">编辑</a><a href="./index.php?p=Admin&a=Gateway&gid='.$v['id'].'&act=del" class="btn btn-sm btn-danger">删除</a></th>
                                    </tr>';
                                 }
                                 echo '
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </main>
            ';
            $this->Footer();
        }
    }
    
    public function Pricesets(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('priceset_check')){
                $this->goIndex();
            }
            $pricesets = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_priceset`");
            $this->Header();
            echo '
            <main class="main-content bgc-grey-100">
               <div id="mainContent">
                  <div class="container-fluid">
                     <h4 class="c-grey-900 mT-10 mB-30">价格组管理</h4>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="bgc-white bd bdrs-3 p-20 mB-20">
                              <h4 class="c-grey-900 mB-20">价格组列表<a href="./index.php?p=Admin&a=Add&add=Priceset" class="btn btn-sm btn-info">新增</a></h4>
                              <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th>ID</th>
                                       <th>名称</th>
                                       <th>预存条件</th>
                                       <th>权重</th>
                                       <th>操作</th>
                                    </tr>
                                 </thead>
                                 <tfoot>
                                    <tr>
                                       <th>ID</th>
                                       <th>名称</th>
                                       <th>预存条件</th>
                                       <th>权重</th>
                                       <th>操作</th>
                                    </tr>
                                 </tfoot>
                                 <tbody>
                                 ';
                                 foreach($pricesets as $k => $v){
                                     echo '
                                    <tr>
                                       <th>'.$v['id'].'</th>
                                       <th>'.$v['name'].'</th>
                                       <th>'.$v['money'].'</th>
                                       <th>'.$v['weight'].'</th>
                                       <th><a href="./index.php?p=Admin&a=Priceset&pid='.$v['id'].'" class="btn btn-sm btn-primary">编辑</a><a href="./index.php?p=Admin&a=Price&pid='.$v['id'].'" class="btn btn-sm btn-success">价格</a><a href="./index.php?p=Admin&a=Priceset&pid='.$v['id'].'&act=del" class="btn btn-sm btn-danger">删除</a></th>
                                    </tr>';
                                 }
                                 echo '
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </main>
            ';
            $this->Footer();
        }
    }
    
    public function Orders(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('order_check')){
                $this->goIndex();
            }
            $orders = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_order` ORDER BY `orderid` DESC");
            $this->Header();
            echo '
            <main class="main-content bgc-grey-100">
               <div id="mainContent">
                  <div class="container-fluid">
                     <h4 class="c-grey-900 mT-10 mB-30">交易记录</h4>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="bgc-white bd bdrs-3 p-20 mB-20">
                              <h4 class="c-grey-900 mB-20">记录列表</h4>
                              <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th>ID</th>
                                       <th>用户ID</th>
                                       <th>详细</th>
                                       <th>金额</th>
                                       <th>操作</th>
                                       <th>状态</th>
                                    </tr>
                                 </thead>
                                 <tfoot>
                                    <tr>
                                       <th>ID</th>
                                       <th>用户ID</th>
                                       <th>详细</th>
                                       <th>金额</th>
                                       <th>操作</th>
                                       <th>状态</th>
                                    </tr>
                                 </tfoot>
                                 <tbody>
                                 ';
                                 foreach($orders as $k => $v){
                                     echo '
                                    <tr>
                                       <th>'.$v['orderid'].'</th>
                                       <th>'.$v['user'].'</th>
                                       <th>'.$v['description'].'</th>
                                       <th>'.$v['money'].'</th>
                                       <th>'.$v['action'].'</th>
                                       <th>'.$v['status'].'</th>
                                    </tr>';
                                 }
                                 echo '
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </main>
            ';
            $this->Footer();
        }
    }
    
    public function Workorders(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('workorder_check')){
                $this->goIndex();
            }
            $workorders = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_workorder`");
            $this->Header();
            echo '
            <main class="main-content bgc-grey-100">
               <div id="mainContent">
                  <div class="container-fluid">
                     <h4 class="c-grey-900 mT-10 mB-30">工单管理</h4>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="bgc-white bd bdrs-3 p-20 mB-20">
                              <h4 class="c-grey-900 mB-20">工单记录</h4>
                              <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th>ID</th>
                                       <th>标题</th>
                                       <th>服务编号</th>
                                       <th>用户编号</th>
                                       <th>状态</th>
                                       <th>操作</th>
                                    </tr>
                                 </thead>
                                 <tfoot>
                                    <tr>
                                       <th>ID</th>
                                       <th>标题</th>
                                       <th>服务编号</th>
                                       <th>用户编号</th>
                                       <th>状态</th>
                                       <th>操作</th>
                                    </tr>
                                 </tfoot>
                                 <tbody>
                                 ';
                                 foreach($workorders as $k => $v){
                                     echo '
                                    <tr>
                                       <th>'.$v['id'].'</th>
                                       <th>'.$v['title'].'</th>
                                       <th>'.$v['service'].'</th>
                                       <th>'.$v['user'].'</th>
                                       <th>'.$v['status'].'</th>
                                       <th><a href="./index.php?p=Admin&a=Workorder&wid='.$v['id'].'" class="btn btn-sm btn-primary">编辑</a><a href="./index.php?p=Admin&a=Workorder&wid='.$v['id'].'&act=del" class="btn btn-sm btn-danger">删除</a></th>
                                    </tr>';
                                 }
                                 echo '
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </main>
            ';
            $this->Footer();
        }
    }
    
    public function Admins(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('admin_check')){
                $this->goIndex();
            }
            $admins = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_admin`");
            $this->Header();
            echo '
            <main class="main-content bgc-grey-100">
               <div id="mainContent">
                  <div class="container-fluid">
                     <h4 class="c-grey-900 mT-10 mB-30">管理员管理</h4>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="bgc-white bd bdrs-3 p-20 mB-20">
                              <h4 class="c-grey-900 mB-20">管理员记录<a href="./index.php?p=Admin&a=Add&add=Admin" class="btn btn-sm btn-info">新增</a></h4>
                              <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th>ID</th>
                                       <th>账户</th>
                                       <th>操作</th>
                                    </tr>
                                 </thead>
                                 <tfoot>
                                    <tr>
                                       <th>ID</th>
                                       <th>账户</th>
                                       <th>操作</th>
                                    </tr>
                                 </tfoot>
                                 <tbody>
                                 ';
                                 foreach($admins as $k => $v){
                                     echo '
                                    <tr>
                                       <th>'.$v['id'].'</th>
                                       <th>'.$v['username'].'</th>
                                       <th><a href="./index.php?p=Admin&a=Admin&aid='.$v['id'].'" class="btn btn-sm btn-primary">编辑</a><a href="./index.php?p=Admin&a=Admin&aid='.$v['id'].'&act=del" class="btn btn-sm btn-danger">删除</a></th>
                                    </tr>';
                                 }
                                 echo '
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </main>
            ';
            $this->Footer();
        }
    }
    
    public function Notices(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('notice_check')){
                $this->goIndex();
            }
            $notices = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_notice`");
            $this->Header();
            echo '
            <main class="main-content bgc-grey-100">
               <div id="mainContent">
                  <div class="container-fluid">
                     <h4 class="c-grey-900 mT-10 mB-30">公告管理</h4>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="bgc-white bd bdrs-3 p-20 mB-20">
                              <h4 class="c-grey-900 mB-20">公告记录<a href="./index.php?p=Admin&a=Add&add=Notice" class="btn btn-sm btn-info">新增</a></h4>
                              <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th>ID</th>
                                       <th>标题</th>
                                       <th>操作</th>
                                    </tr>
                                 </thead>
                                 <tfoot>
                                    <tr>
                                       <th>ID</th>
                                       <th>标题</th>
                                       <th>操作</th>
                                    </tr>
                                 </tfoot>
                                 <tbody>
                                 ';
                                 foreach($notices as $k => $v){
                                     echo '
                                    <tr>
                                       <th>'.$v['id'].'</th>
                                       <th>'.$v['title'].'</th>
                                       <th><a href="./index.php?p=Admin&a=Notice&nid='.$v['id'].'" class="btn btn-sm btn-primary">编辑</a><a href="./index.php?p=Admin&a=Notice&nid='.$v['id'].'&act=del" class="btn btn-sm btn-danger">删除</a></th>
                                    </tr>';
                                 }
                                 echo '
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </main>
            ';
            $this->Footer();
        }
    }
    
    public function Setting(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('web_setting')){
                $this->goIndex();
            }
            if(!empty($this->getSystem()->getPostParams())){
                foreach($this->getSystem()->getPostParams() as $k => $v){
                    $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_config` SET `value`='{$v}' WHERE `key`='{$k}'");
                }
                @header("Location: ./index.php?p=Admin&a=Index");
                exit;
            }else{
                $config = $this->getSystem()->getConfigAll();
                $this->Header();
                switch ($this->getSystem()->getGetParams()['set']) {
                    case 'Seo':
                        echo '
                        <main class="main-content bgc-grey-100">
                   <div id="mainContent">
                      <div class="row gap-20 masonry pos-r">
                         <div class="masonry-sizer col-md-6"></div>
                         <div class="masonry-item col-md-12">
                            <div class="bgc-white p-20 bd">
                               <h6 class="c-grey-900">SEO设置</h6>
                               <div class="mT-30">
                                  <form action="./index.php?p=Admin&a=Setting" method="POST">
                                     <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">SEO标题</label>
                                        <div class="col-sm-10"><input type="text" class="form-control" id="" placeholder="SEO标题" name="seo_title" value="'.$config['seo_title'].'"></div>
                                     </div>
                                     <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">SEO副标题</label>
                                        <div class="col-sm-10"><input type="text" class="form-control" id="" placeholder="SEO副标题" name="seo_subtitle" value="'.$config['seo_subtitle'].'"></div>
                                     </div>
                                     <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">SEO介绍</label>
                                        <div class="col-sm-10"><textarea name="seo_description" rows="4" class="form-control" placeholder="SEO介绍">'.$config['seo_description'].'</textarea></div>
                                     </div>
                                     <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">SEO关键词</label>
                                        <div class="col-sm-10"><input type="text" class="form-control" id="" placeholder="SEO关键词" name="seo_keywords" value="'.$config['seo_keywords'].'"></div>
                                     </div>
                                     <div class="form-group row">
                                        <div class="col-sm-10"><button type="submit" class="btn btn-primary">修改</button></div>
                                     </div>
                                  </form>
                               </div>
                            </div>
                         </div>
                      </div>
                   </div>
                </main>';
                        break;
                    case 'Template':
                        $Templates = $this->get_dir(BASE_ROOT.'/Templates/');
                        echo '
                        <main class="main-content bgc-grey-100">
                   <div id="mainContent">
                      <div class="row gap-20 masonry pos-r">
                         <div class="masonry-sizer col-md-6"></div>
                         <div class="masonry-item col-md-12">
                            <div class="bgc-white p-20 bd">
                               <h6 class="c-grey-900">模板设置</h6>
                               <div class="mT-30">
                                  <form action="./index.php?p=Admin&a=Setting" method="POST">
                                     <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">默认模板</label>
                                        <div class="col-sm-10"><select class="form-control" name="template">';
                                            foreach($Templates as $k => $v){
                                      			if($config['template'] == $v){
                                      				$selected = "selected";
                                      			}else{
                                      				$selected = "";
                                      			}
                                      			echo '<option value="'.$v.'" '.$selected.'>'.$k.'</option>';
                                      		}
                                            echo '
                                        </select></div>
                                     </div>
                                     <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">手机模板</label>
                                        <div class="col-sm-10"><select class="form-control" name="template_mobile">';
                                            foreach($Templates as $k => $v){
                                      			if($config['template_mobile'] == $v){
                                      				$selected = "selected";
                                      			}else{
                                      				$selected = "";
                                      			}
                                      			echo '<option value="'.$v.'" '.$selected.'>'.$k.'</option>';
                                      		}
                                            echo '
                                        </select></div>
                                     </div>
                                     <div class="form-group row">
                                        <div class="col-sm-10"><button type="submit" class="btn btn-primary">修改</button></div>
                                     </div>
                                  </form>
                               </div>
                            </div>
                         </div>
                      </div>
                   </div>
                </main>';
                        break;
                    case 'Cron':
                        echo '
                        <main class="main-content bgc-grey-100">
                   <div id="mainContent">
                      <div class="row gap-20 masonry pos-r">
                         <div class="masonry-sizer col-md-6"></div>
                         <div class="masonry-item col-md-12">
                            <div class="bgc-white p-20 bd">
                               <h6 class="c-grey-900">CRON设置[<a href="./index.php?p=Cron&a=Service">服务Cron</a>][<a href="./index.php?p=Cron&a=Orders">订单Cron</a>]</h6>
                               <div class="mT-30">
                                  <form action="./index.php?p=Admin&a=Setting" method="POST">
                                     <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">距离到期（多少）天暂停服务</label>
                                        <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="距离到期（多少）天暂停服务" name="cron_stopday" value="'.$config['cron_stopday'].'"></div>
                                     </div>
                                     <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">到期后（多少）天删除服务</label>
                                        <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="到期后（多少）天删除服务" name="cron_deleteday" value="'.$config['cron_deleteday'].'"></div>
                                     </div>
                                     <div class="form-group row">
                                        <div class="col-sm-10"><button type="submit" class="btn btn-primary">修改</button></div>
                                     </div>
                                  </form>
                               </div>
                            </div>
                         </div>
                      </div>
                   </div>
                </main>';
                        break;
                    default:
                        break;
                }
                $this->Footer();
            }
        }
    }
    
    public function Template(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('web_setting')){
                $this->goIndex();
            }
            if(!empty($this->getSystem()->getPostParams())){
                $custom = $this->getSystem()->getPostParams()['custom'];
                foreach ($custom as $k => $v){
                    if(empty($v['value'])){
                        $this->getSystem()->getDatabase()->exec("DELETE FROM `ytidc_template` WHERE `key`='{$v['key']}'");
                    }else{
                        if($this->getSystem()->getDatabase()->num_rows("SELECT COUNT(*) FROM `ytidc_template` WHERE `key`='{$v['key']}'") != 0){
                            $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_template` SET `value`='{$v['value']}' WHERE `key`='{$v['key']}'");
                        }else{
                            $this->getSystem()->getDatabase()->exec("INSERT INTO `ytidc_template` (`key`, `value`) VALUES ('{$v['key']}', '{$v['value']}')");
                        }
                    }
                }
                @header("Location: ./index.php?p=Admin&a=Template");
                exit;
            }else{
                $customcount = count($this->getSystem()->getTemplateCustom());
                $custom = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_template`");
                $this->Header();
                echo '
                <script>
                                var customcount = '.$customcount.';
                        
                                function AddCustomInput() {
                                    customcount++;
                                    var custom = document.getElementById("customtable");
                                    var editform = document.getElementById("editform");
                                    editform.style.height = editform.offsetHeight + 50 +\'px\';
                        
                                    var tr = document.createElement(\'tr\');
                            	    	var td = document.createElement(\'td\');
                            	    	td.innerHTML=\'<input type="text" class="form-control" name="custom[\' + customcount + \'][key]" value="" style="min-width: 100px;"/>\';
                            			tr.appendChild(td);
                            	    	var td = document.createElement(\'td\');
                            	    	td.innerHTML=\'<input type="text" class="form-control" name="custom[\' + customcount + \'][value]" value="" style="min-width: 100px;"/>\';
                            			tr.appendChild(td);
                            		custom.appendChild(tr);
                        
                                }
                            </script>
                    <main class="main-content bgc-grey-100">
                   <div id="mainContent">
                      <div class="row gap-20 masonry pos-r"  id="editform">
                         <div class="masonry-sizer col-md-6"></div>
                         <div class="masonry-item col-md-12">
                            <div class="bgc-white p-20 bd">
                               <h6 class="c-grey-900">模板自定义设置</h6>
                               <div class="mT-30">
                                  <form action="./index.php?p=Admin&a=Template" method="POST">
                                     <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">模板自定义设置 <button class="btn btn-sm btn-primary" onclick="AddCustomInput()" type="button">新增设置</button></label>
                                            <div class="table-responsive col-sm-10">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                           <th scope="col">自定义KEY</th>
                                                           <th scope="col">自定义内容(留空删除)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="customtable">';
                                                    foreach($custom as $k => $v){
                                                        echo '
                                                            <tr>
                                                               <td><input class="form-control" name="custom['.$k.'][key]" value="'.$v['key'].'"></td>
                                                               <td><input class="form-control" name="custom['.$k.'][value]" value="'.$v['value'].'"></td>
                                                            </tr>
                                                        ';
                                                    }
                                                    echo '
                                                    </tbody>
                                                </table>
                                            </div>
                                         </div>
                                     <div class="form-group row">
                                        <div class="col-sm-10"><button type="submit" class="btn btn-primary">保存</button></div>
                                     </div>
                                  </form>
                               </div>
                            </div>
                         </div>
                      </div>
                   </div>
                </main>';
                $this->Footer();
            }
        }
    }
    
    public function Add(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(empty($this->getSystem()->getGetParams()['add'])){
                @header("Location: ./index.php?p=Admin&a=Index");
                exit;
            }else{
                $rand = random_int(100, 999);
                switch ($this->getSystem()->getGetParams()['add']) {
                    case 'ProductGroup':
                        if(!$this->CheckPermission('group_add')){
                            $this->goIndex();
                        }
                        $result = $this->getSystem()->getDatabase()->exec("INSERT INTO `ytidc_group`(`name`, `description`, `weight`, `status`) VALUES ('新建产品组{$rand}', '', '0', '1')");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库添加'.$this->getSystem()->getGetParams()['add'].'错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=ProductGroups");
                        exit;
                        break;
                    case 'Product':
                        if(!$this->CheckPermission('product_add')){
                            $this->goIndex();
                        }
                        $result = $this->getSystem()->getDatabase()->exec("INSERT INTO `ytidc_product`(`name`, `description`, `weight`, `period`, `group`, `configoption`, `customoption`, `server`, `hidden`, `status`) VALUES ('新建产品{$rand}', '', '0', '[]', '0', '[]', '[]', '0', '0', '1')");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库添加'.$this->getSystem()->getGetParams()['add'].'错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=Products");
                        exit;
                        break;
                    case 'Server':
                        if(!$this->CheckPermission('server_add')){
                            $this->goIndex();
                        }
                        $result = $this->getSystem()->getDatabase()->exec("INSERT INTO `ytidc_server`(`name`, `serverip`, `serverdomain`, `serverdns1`, `serverdns2`, `serverusername`, `serverpassword`, `serveraccesshash`, `servercpanel`, `serverport`, `plugin`, `status`) VALUES ('新建服务器{$rand}', '', '', '', '', '', '', '', '', '0', '', '1')");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库添加'.$this->getSystem()->getGetParams()['add'].'错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=Servers");
                        exit;
                        break;
                    case 'Gateway':
                        if(!$this->CheckPermission('gateway_add')){
                            $this->goIndex();
                        }
                        $result = $this->getSystem()->getDatabase()->exec("INSERT INTO `ytidc_gateway`(`name`, `rate`, `plugin`, `configoption`, `status`) VALUES ('新建支付渠道{$rand}', '100.00', '', '[]', '1')");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库添加'.$this->getSystem()->getGetParams()['add'].'错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=Gateways");
                        exit;
                        break;
                    case 'Admin':
                        if(!$this->CheckPermission('admin_add')){
                            $this->goIndex();
                        }
                        $password = md5(md5('admin'.$rand));
                        $result = $this->getSystem()->getDatabase()->exec("INSERT INTO `ytidc_admin`(`username`, `password`, `permission`, `lastip`, `status`) VALUES ('admin{$rand}', '{$password}', '[]', '', '1')");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库添加'.$this->getSystem()->getGetParams()['add'].'错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=Admins");
                        exit;
                        break;
                    case 'Notice':
                        if(!$this->CheckPermission('notice_add')){
                            $this->goIndex();
                        }
                        $date = date('Y-m-d H:i:s');
                        $result = $this->getSystem()->getDatabase()->exec("INSERT INTO `ytidc_notice`(`title`, `content`, `date`, `status`) VALUES ('新增公告{$rand}', '', '{$date}', '1')");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库添加'.$this->getSystem()->getGetParams()['add'].'错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=Notice");
                        exit;
                        break;
                    case 'Priceset':
                        if(!$this->CheckPermission('priceset_add')){
                            $this->goIndex();
                        }
                        $result = $this->getSystem()->getDatabase()->exec("INSERT INTO `ytidc_priceset`(`name`, `description`, `weight`, `money`, `price`, `default`, `status`) VALUES ('新建价格组{$rand}', '', '0', '999', '[]', '0', '1')");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库添加'.$this->getSystem()->getGetParams()['add'].'错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=Pricesets");
                        exit;
                        break;
                    default:
                        @header("Location: ./index.php?p=Admin&a=Index");
                        break;
                }
            }
        }
    }
    
    public function ProductGroup(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('group_edit')){
                $this->goIndex();
            }
            if(empty($this->getSystem()->getGetParams()['gid'])){
                @header("Location: ./index.php?p=Admin&a=ProductGroups");
                exit;
            }else{
                if($this->getSystem()->getDatabase()->num_rows("SELECT COUNT(*) FROM `ytidc_group` WHERE `id`='{$this->getSystem()->getGetParams()['gid']}'") != 1){
                    @header("Location: ./index.php?p=Admin&a=ProductGroups");
                    exit;
                }else{
                    $group = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_group` WHERE `id`='{$this->getSystem()->getGetParams()['gid']}'");
                    if($this->getSystem()->getGetParams()['act'] == 'del'){
                        if(!$this->CheckPermission('group_delete')){
                            $this->goIndex();
                        }
                        $this->getSystem()->getDatabase()->exec("DELETE FROM `ytidc_group` WHERE `id`='{$group['id']}'");
                        @header("Location: ./index.php?p=Admin&a=ProductGroups");
                        exit;
                    }
                    if(!empty($this->getSystem()->getPostParams())){
                        $params = $this->getSystem()->getPostParams();
                        $result = $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_group` SET `name`='{$params['name']}', `description`='{$params['description']}', `weight`='{$params['weight']}', `status`='{$params['status']}' WHERE `id`='{$group['id']}'");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库修改产品组错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=ProductGroup&gid=".$group['id']);
                        exit;
                    }else{
                        $this->Header();
                        echo '
                            <main class="main-content bgc-grey-100">
                       <div id="mainContent">
                          <div class="row gap-20 masonry pos-r">
                             <div class="masonry-sizer col-md-6"></div>
                             <div class="masonry-item col-md-12">
                                <div class="bgc-white p-20 bd">
                                   <h6 class="c-grey-900">编辑产品组</h6>
                                   <div class="mT-30">
                                      <form action="./index.php?p=Admin&a=ProductGroup&gid='.$group['id'].'" method="POST">
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">产品组名称</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="产品组名称" name="name" value="'.$group['name'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">产品组介绍</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="产品组介绍务" name="description" value="'.$group['description'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">产品组权重</label>
                                            <div class="col-sm-10"><input type="number" class="form-control" id="inputEmail3" placeholder="产品组权重" name="weight" value="'.$group['weight'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">产品组状态</label>
                                            <div class="col-sm-10"><select name="status" class="form-control">';
                                                if($group['status'] == 1){
                                                    echo '<option value="1" selected>开启</option><option value="0">关闭</option>';
                                                }else{
                                                    echo '<option value="1">开启</option><option value="0" selected>关闭</option>';
                                                }
                                            echo'</select></div>
                                         </div>
                                         <div class="form-group row">
                                            <div class="col-sm-10"><button type="submit" class="btn btn-primary">修改</button></div>
                                         </div>
                                      </form>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                    </main>';
                    $this->Footer();
                    }
                }
            }
        }
    }
    
    public function Notice(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('notice_edit')){
                $this->goIndex();
            }
            if(empty($this->getSystem()->getGetParams()['nid'])){
                @header("Location: ./index.php?p=Admin&a=Notices");
                exit;
            }else{
                if($this->getSystem()->getDatabase()->num_rows("SELECT COUNT(*) FROM `ytidc_notice` WHERE `id`='{$this->getSystem()->getGetParams()['nid']}'") != 1){
                    @header("Location: ./index.php?p=Admin&a=Notices");
                    exit;
                }else{
                    $notice = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_notice` WHERE `id`='{$this->getSystem()->getGetParams()['nid']}'");
                    if($this->getSystem()->getGetParams()['act'] == 'del'){
                        if(!$this->CheckPermission('notice_delete')){
                            $this->goIndex();
                        }
                        $this->getSystem()->getDatabase()->exec("DELETE FROM `ytidc_notice` WHERE `id`='{$notice['id']}'");
                        @header("Location: ./index.php?p=Admin&a=Notices");
                        exit;
                    }
                    if(!empty($this->getSystem()->getPostParams())){
                        $params = $this->getSystem()->getPostParams();
                        $date = date('Y-m-d H:i:s');
                        $result = $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_notice` SET `title`='{$params['title']}', `content`='{$params['content']}', `date`='{$date}', `status`='{$params['status']}' WHERE `id`='{$notice['id']}'");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库修改公告错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=Notice&nid=".$notice['id']);
                        exit;
                    }else{
                        $this->Header();
                        echo '
                            <main class="main-content bgc-grey-100">
                       <div id="mainContent">
                          <div class="row gap-20 masonry pos-r">
                             <div class="masonry-sizer col-md-6"></div>
                             <div class="masonry-item col-md-12">
                                <div class="bgc-white p-20 bd">
                                   <h6 class="c-grey-900">编辑公告</h6>
                                   <div class="mT-30">
                                      <form action="./index.php?p=Admin&a=Notice&nid='.$notice['id'].'" method="POST">
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">公告标题</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="公告标题" name="title" value="'.$notice['title'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">公告内容</label>
                                            <div class="col-sm-10"><textarea class="form-control" id="inputEmail3" placeholder="公告内容" name="content" rows="6">'.$notice['content'].'</textarea></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">公告状态</label>
                                            <div class="col-sm-10"><select name="status" class="form-control">';
                                                if($notice['status'] == 1){
                                                    echo '<option value="1" selected>显示</option><option value="0">关闭</option>';
                                                }else{
                                                    echo '<option value="1">显示</option><option value="0" selected>关闭</option>';
                                                }
                                            echo'</select></div>
                                         </div>
                                         <div class="form-group row">
                                            <div class="col-sm-10"><button type="submit" class="btn btn-primary">修改</button></div>
                                         </div>
                                      </form>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                    </main>';
                    $this->Footer();
                    }
                }
            }
        }
    }
    
    public function Service(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('service_edit')){
                $this->goIndex();
            }
            if(empty($this->getSystem()->getGetParams()['sid'])){
                @header("Location: ./index.php?p=Admin&a=Services");
                exit;
            }else{
                if($this->getSystem()->getDatabase()->num_rows("SELECT COUNT(*) FROM `ytidc_service` WHERE `id`='{$this->getSystem()->getGetParams()['sid']}'") != 1){
                    @header("Location: ./index.php?p=Admin&a=Services");
                    exit;
                }else{
                    $service = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_service` WHERE `id`='{$this->getSystem()->getGetParams()['sid']}'");
                    if($this->getSystem()->getGetParams()['act'] == 'del'){
                        if(!$this->CheckPermission('service_delete')){
                            $this->goIndex();
                        }
                        $PluginManager = $this->getSystem()->getPluginManager();
                        $Service = new Service($service['id'], $this);
                        $Product = $Service->getProduct();
                        $Server = $Product->getServer();
                        if($Product->isExisted() !== false){
                            if($Server->isExisted() !== false){
                                $Event = new ServiceDeleteEvent($Service, $Server);
                                $PluginManager->loadEvent('onServiceDelete', $Event);
                                $Event = new DeleteServiceEvent($Service, $Server);
                                $PluginManager->loadEventByPlugin('DeleteService', $Event, $Server->getServerPluginName());
                            }
                        }
                        $this->getSystem()->getDatabase()->exec("DELETE FROM `ytidc_service` WHERE `id`='{$service['id']}'");
                        @header("Location: ./index.php?p=Admin&a=Services");
                        exit;
                    }
                    if(!empty($this->getSystem()->getPostParams())){
                        $params = $this->getSystem()->getPostParams();
                        $params['customoption'] = json_encode($params['customoption'], JSON_UNESCAPED_UNICODE);
                        $params['password'] = base64_encode($params['password']);
                        $result = $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_service` SET `username`='{$params['username']}', `password`='{$params['password']}', `enddate`='{$params['enddate']}', `customoption`='{$params['customoption']}', `status`='{$params['status']}' WHERE `id`='{$service['id']}'");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库修改在线服务错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=Service&sid=".$service['id']);
                        exit;
                    }else{
                        $this->Header();
                        echo '
                            <main class="main-content bgc-grey-100">
                       <div id="mainContent">
                          <div class="row gap-20 masonry pos-r">
                             <div class="masonry-sizer col-md-6"></div>
                             <div class="masonry-item col-md-12">
                                <div class="bgc-white p-20 bd">
                                   <h6 class="c-grey-900">编辑在线服务</h6>
                                   <div class="mT-30">
                                      <form action="./index.php?p=Admin&a=Service&sid='.$service['id'].'" method="POST">
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">服务账号</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="服务账号" name="username" value="'.$service['username'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">服务密码</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="服务密码" name="password" value="'.base64_decode($service['password']).'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">到期时间</label>
                                            <div class="col-sm-10"><input type="date" class="form-control" id="inputEmail3" placeholder="到期时间" name="enddate" value="'.$service['enddate'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">服务状态</label>
                                            <div class="col-sm-10"><select name="status" class="form-control">';
                                                if($service['status'] == '激活'){
                                                    echo '<option value="激活" selected>激活</option><option value="暂停">暂停</option>';
                                                }elseif($service['status'] == '暂停'){
                                                    echo '<option value="激活">激活</option><option value="暂停" selected>暂停</option>';
                                                }else{
                                                    echo '<option value="待开通" selected>待开通</option><option value="激活">激活</option><option value="暂停">暂停</option>';
                                                }
                                            echo'</select></div>
                                         </div>';
                                         $customoption = json_decode($service['customoption'], true);
                                         if(!empty($customoption)&&is_array($customoption)){
                                             foreach ($customoption as $k => $v){
                                                 echo '
                                                 <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-2 col-form-label">自定义输入['.$k.']</label>
                                                    <div class="col-sm-10"><input type="date" class="form-control" id="inputEmail3" placeholder="自定义输入['.$k.']" name="customoption['.$k.']" value="'.$v.'"></div>
                                                 </div>';
                                             }
                                         }
                                         echo'
                                         <div class="form-group row">
                                            <div class="col-sm-10"><button type="submit" class="btn btn-primary">修改</button></div>
                                         </div>
                                      </form>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                    </main>';
                    $this->Footer();
                    }
                }
            }
        }
    }
    
    public function User(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('user_edit')){
                $this->goIndex();
            }
            if(empty($this->getSystem()->getGetParams()['uid'])){
                @header("Location: ./index.php?p=Admin&a=Users");
                exit;
            }else{
                if($this->getSystem()->getDatabase()->num_rows("SELECT COUNT(*) FROM `ytidc_user` WHERE `id`='{$this->getSystem()->getGetParams()['uid']}'") != 1){
                    @header("Location: ./index.php?p=Admin&a=Users");
                    exit;
                }else{
                    $user = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_user` WHERE `id`='{$this->getSystem()->getGetParams()['uid']}'");
                    if($this->getSystem()->getGetParams()['act'] == 'del'){
                        if(!$this->CheckPermission('user_delete')){
                            $this->goIndex();
                        }
                        $this->getSystem()->getDatabase()->exec("DELETE FROM `ytidc_user` WHERE `id`='{$user['id']}'");
                        @header("Location: ./index.php?p=Admin&a=Users");
                        exit;
                    }
                    if(!empty($this->getSystem()->getPostParams())){
                        $params = $this->getSystem()->getPostParams();
                        if(!empty($params['password'])){
                            $password = md5(md5($params['password']));
                            $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_user` sET `password`='{$password}' WHERE `id`='{$user['id']}'");
                        }
                        $result = $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_user` SET `username`='{$params['username']}', `money`='{$params['money']}', `priceset`='{$params['priceset']}', `status`='{$params['status']}' WHERE `id`='{$user['id']}'");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库修改用户错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=User&uid=".$user['id']);
                        exit;
                    }else{
                        $this->Header();
                        echo '
                            <main class="main-content bgc-grey-100">
                       <div id="mainContent">
                          <div class="row gap-20 masonry pos-r">
                             <div class="masonry-sizer col-md-6"></div>
                             <div class="masonry-item col-md-12">
                                <div class="bgc-white p-20 bd">
                                   <h6 class="c-grey-900">编辑用户</h6>
                                   <div class="mT-30">
                                      <form action="./index.php?p=Admin&a=User&uid='.$user['id'].'" method="POST">
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">用户账号</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="用户账号" name="username" value="'.$user['username'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">用户密码（留空为不修改）</label>
                                            <div class="col-sm-10"><input type="password" class="form-control" id="inputEmail3" placeholder="用户密码" name="password"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">用户余额</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="用户余额" name="money" value="'.$user['money'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">用户价格组</label>
                                            <div class="col-sm-10"><select name="priceset" class="form-control">';
                                                $pricesets = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_priceset` WHERE `status`='1'");
                                                foreach ($pricesets as $k => $v){
                                                    if($v['id'] == $user['priceset']){
                                                        echo '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                                                    }else{
                                                        echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                                                    }
                                                }
                                            echo'</select></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">用户状态</label>
                                            <div class="col-sm-10"><select name="status" class="form-control">';
                                                if($user['status'] == '1'){
                                                    echo '<option value="1" selected>正常</option><option value="0">封禁</option>';
                                                }else{
                                                    echo '<option value="1">正常</option><option value="0" selected>封禁</option>';
                                                }
                                            echo'</select></div>
                                         </div>
                                         <div class="form-group row">
                                            <div class="col-sm-10"><button type="submit" class="btn btn-primary">修改</button></div>
                                         </div>
                                      </form>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                    </main>';
                    $this->Footer();
                    }
                }
            }
        }
    }
    
    public function Priceset(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('priceset_edit')){
                $this->goIndex();
            }
            if(empty($this->getSystem()->getGetParams()['pid'])){
                @header("Location: ./index.php?p=Admin&a=Pricesets");
                exit;
            }else{
                if($this->getSystem()->getDatabase()->num_rows("SELECT COUNT(*) FROM `ytidc_priceset` WHERE `id`='{$this->getSystem()->getGetParams()['pid']}'") != 1){
                    @header("Location: ./index.php?p=Admin&a=Pricesets");
                    exit;
                }else{
                    $priceset = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_priceset` WHERE `id`='{$this->getSystem()->getGetParams()['pid']}'");
                    if($this->getSystem()->getGetParams()['act'] == 'del'){
                        if(!$this->CheckPermission('priceset_delete')){
                            $this->goIndex();
                        }
                        $this->getSystem()->getDatabase()->exec("DELETE FROM `ytidc_priceset` WHERE `id`='{$priceset['id']}'");
                        @header("Location: ./index.php?p=Admin&a=Pricesets");
                        exit;
                    }
                    if(!empty($this->getSystem()->getPostParams())){
                        $params = $this->getSystem()->getPostParams();
                        if($params['default'] == 1){
                            $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_priceset` SET `default`='0' WHERE `default`='1'");
                            $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_priceset` SET `default`='1' WHERE `id`='{$priceset['id']}'");
                        }
                        $result = $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_priceset` SET `name`='{$params['name']}', `description`='{$params['description']}', `money`='{$params['money']}', `status`='{$params['status']}' WHERE `id`='{$priceset['id']}'");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库修改价格组错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=Priceset&pid=".$priceset['id']);
                        exit;
                    }else{
                        $this->Header();
                        echo '
                            <main class="main-content bgc-grey-100">
                       <div id="mainContent">
                          <div class="row gap-20 masonry pos-r">
                             <div class="masonry-sizer col-md-6"></div>
                             <div class="masonry-item col-md-12">
                                <div class="bgc-white p-20 bd">
                                   <h6 class="c-grey-900">编辑价格组</h6>
                                   <div class="mT-30">
                                      <form action="./index.php?p=Admin&a=Priceset&pid='.$priceset['id'].'" method="POST">
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">价格组名称</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="价格组名称" name="name" value="'.$priceset['name'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">价格组介绍</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="价格组介绍" name="description" value="'.$priceset['description'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">开通所需余额</label>
                                            <div class="col-sm-10"><input type="number" class="form-control" id="inputEmail3" placeholder="开通所需余额" name="money" value="'.$priceset['money'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">价格组权重</label>
                                            <div class="col-sm-10"><input type="number" class="form-control" id="inputEmail3" placeholder="价格组权重" name="weight" value="'.$priceset['weight'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">默认价格组</label>
                                            <div class="col-sm-10"><select name="default" class="form-control">';
                                                if($priceset['default'] == '1'){
                                                    echo '<option value="1" selected>开启</option><option value="0">关闭</option>';
                                                }else{
                                                    echo '<option value="1">开启</option><option value="0" selected>关闭</option>';
                                                }
                                            echo'</select></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">价格组状态</label>
                                            <div class="col-sm-10"><select name="status" class="form-control">';
                                                if($priceset['status'] == '1'){
                                                    echo '<option value="1" selected>开启</option><option value="0">关闭</option>';
                                                }else{
                                                    echo '<option value="1">开启</option><option value="0" selected>关闭</option>';
                                                }
                                            echo'</select></div>
                                         </div>
                                         <div class="form-group row">
                                            <div class="col-sm-10"><button type="submit" class="btn btn-primary">修改</button></div>
                                         </div>
                                      </form>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                    </main>';
                    $this->Footer();
                    }
                }
            }
        }
    }
    
    public function Price(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('priceset_edit')){
                $this->goIndex();
            }
            if(empty($this->getSystem()->getGetParams()['pid'])){
                @header("Location: ./index.php?p=Admin&a=Pricesets");
                exit;
            }else{
                if($this->getSystem()->getDatabase()->num_rows("SELECT COUNT(*) FROM `ytidc_priceset` WHERE `id`='{$this->getSystem()->getGetParams()['pid']}'") != 1){
                    @header("Location: ./index.php?p=Admin&a=Pricesets");
                    exit;
                }else{
                    $priceset = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_priceset` WHERE `id`='{$this->getSystem()->getGetParams()['pid']}'");
                    if(!empty($this->getSystem()->getPostParams())){
                        $params = $this->getSystem()->getPostParams();
                        $Prices = json_encode($params['price']);
                        $result = $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_priceset` SET `price`='{$Prices}' WHERE `id`='{$priceset['id']}'");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库修改价格错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=Price&pid=".$priceset['id']);
                        exit;
                    }else{
                        $Prices = json_decode($priceset['price'], true);
                        $Products = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_product`");
                        $this->Header();
                        echo '
                            <main class="main-content bgc-grey-100">
                       <div id="mainContent">
                          <div class="row gap-20 masonry pos-r">
                             <div class="masonry-sizer col-md-6"></div>
                             <div class="masonry-item col-md-12">
                                <div class="bgc-white p-20 bd">
                                   <h6 class="c-grey-900">编辑价格（百分比）</h6>
                                   <div class="mT-30">
                                      <form action="./index.php?p=Admin&a=Price&pid='.$priceset['id'].'" method="POST">
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">统一价格</label>
                                            <div class="col-sm-10"><input type="number" class="form-control" id="inputEmail3" placeholder="请输入百分比" name="price[*]" value="'.$Prices['*'].'"></div>
                                         </div>';
                                      foreach($Products as $k => $v){
                                          echo '
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">'.$v['name'].'</label>
                                            <div class="col-sm-10"><input type="number" class="form-control" id="inputEmail3" placeholder="请输入百分比" name="price['.$v['id'].']" value="'.$Prices[$v['id']].'"></div>
                                         </div>';
                                      }
                                         echo '
                                         <div class="form-group row">
                                            <div class="col-sm-10"><button type="submit" class="btn btn-primary">修改</button></div>
                                         </div>
                                      </form>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                    </main>';
                    $this->Footer();
                    }
                }
            }
        }
    }
    
    public function Workorder(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('workorder_edit')){
                $this->goIndex();
            }
            if(empty($this->getSystem()->getGetParams()['wid'])){
                @header("Location: ./index.php?p=Admin&a=Workorders");
                exit;
            }else{
                if($this->getSystem()->getDatabase()->num_rows("SELECT COUNT(*) FROM `ytidc_workorder` WHERE `id`='{$this->getSystem()->getGetParams()['wid']}'") != 1){
                    @header("Location: ./index.php?p=Admin&a=Workorders");
                    exit;
                }else{
                    $workorder = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_workorder` WHERE `id`='{$this->getSystem()->getGetParams()['wid']}'");
                    if($this->getSystem()->getGetParams()['act'] == 'del'){
                        if(!$this->CheckPermission('workorder_delete')){
                            $this->goIndex();
                        }
                        $this->getSystem()->getDatabase()->exec("DELETE FROM `ytidc_workorder` WHERE `id`='{$workorder['id']}'");
                        @header("Location: ./index.php?p=Admin&a=Workorders");
                        exit;
                    }
                    if(!empty($this->getSystem()->getPostParams())){
                        $params = $this->getSystem()->getPostParams();
                        $result = $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_workorder` SET `status`='已处理' WHERE `id`='{$workorder['id']}'");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库修改工单状态错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        $date = date('Y-m-d H:i:s');
                        $result = $this->getSystem()->getDatabase()->exec("INSERT INTO `ytidc_workorder_reply`(`person`, `content`, `workorder`, `time`) VALUES ('{$_SESSION['ctadmin_user']}', '{$params['reply']}', '{$workorder['id']}', '{$date}')");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库新增工单回复错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        $PluginManager = $this->getSystem()->getPluginManager();
                        $workorder = new Workorder($workorder['id'], $this);
                        $Event = new AdminReplyWorkorderEvent($params['reply'], $workorder, $this->Admin);
                        $PluginManager->loadEvent('onAdminReplyWorkorder', $Event);
                        @header("Location: ./index.php?p=Admin&a=Workorder&wid=".$workorder->getId());
                        exit;
                    }else{
                        $replys = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_workorder_reply` WHERE `workorder`='{$workorder['id']}'");
                        $this->Header();
                        echo '
                            <main class="main-content bgc-grey-100">
                       <div id="mainContent">
                          <div class="row gap-20 masonry pos-r">
                             <div class="masonry-sizer col-md-6"></div>
                             <div class="masonry-item col-md-12">
                                <div class="bgc-white p-20 bd">
                                   <h6 class="c-grey-900">'.$workorder['title'].'（配置服务ID：'.$workorder['service'].'）</h6>
                                   <div class="mT-30">
                                      '.$workorder['content'].'
                                   </div>
                                </div>
                             </div>';
                                foreach($replys as $k => $v){
                                    echo '
                             <div class="masonry-item col-md-12">
                                <div class="bgc-white p-20 bd">
                                   <h6 class="c-grey-900">'.$v['person'].'（'.$v['time'].'）</h6>
                                   <div class="mT-30">
                                      '.$v['content'].'
                                   </div>
                                </div>
                             </div>';
                                }
                             echo'
                             <div class="masonry-item col-md-12">
                                <div class="bgc-white p-20 bd">
                                   <h6 class="c-grey-900">回复工单</h6>
                                   <div class="mT-30">
                                      <form action="./index.php?p=Admin&a=Workorder&wid='.$workorder['id'].'" method="POST">
                                         <div class="form-group row">
                                            <div class="col-sm-12"><textarea class="form-control" placeholder="回复工单" name="reply" rows="5"></textarea></div>
                                         </div>
                                         <div class="form-group row">
                                            <div class="col-sm-10"><button type="submit" class="btn btn-primary">回复</button></div>
                                         </div>
                                      </form>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                    </main>';
                    $this->Footer();
                    }
                }
            }
        }
    }
    
    public function Admin(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('admin_edit')){
                $this->goIndex();
            }
            if(empty($this->getSystem()->getGetParams()['aid'])){
                @header("Location: ./index.php?p=Admin&a=Admins");
                exit;
            }else{
                if($this->getSystem()->getDatabase()->num_rows("SELECT COUNT(*) FROM `ytidc_admin` WHERE `id`='{$this->getSystem()->getGetParams()['aid']}'") != 1){
                    @header("Location: ./index.php?p=Admin&a=Admins");
                    exit;
                }else{
                    $admin = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_admin` WHERE `id`='{$this->getSystem()->getGetParams()['aid']}'");
                    if($this->getSystem()->getGetParams()['act'] == 'del'){
                        if(!$this->CheckPermission('admin_delete')){
                            $this->goIndex();
                        }
                        $this->getSystem()->getDatabase()->exec("DELETE FROM `ytidc_admin` WHERE `id`='{$admin['id']}'");
                        @header("Location: ./index.php?p=Admin&a=Admins");
                        exit;
                    }
                    if(!empty($this->getSystem()->getPostParams())){
                        $params = $this->getSystem()->getPostParams();
                        // exit(print_r($params['permission']));
                        $params['permission'] = json_encode($params['permission']);
                        if(!empty($params['password'])){
                            $params['password'] = md5(md5($params['password']));
                        }else{
                            $params['password'] = $admin['password'];
                        }
                        $result = $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_admin` SET `username`='{$params['username']}', `password`='{$params['password']}', `permission`='{$params['permission']}', `status`='{$params['status']}' WHERE `id`='{$admin['id']}'");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库修改管理员错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=Admin&aid=".$admin['id']);
                        exit;
                    }else{
                        $allpermissions = array('*'=>'全部权限', 'Index'=>'首页权限', 'group_check'=>'产品组检视', 'group_add'=>'产品组添加', 'group_edit'=>'产品组编辑', 'group_delete'=>'产品组删除', 'product_check'=>'产品检视', 'product_add'=>'产品添加', 'product_edit'=>'产品编辑', 'product_delete'=>'产品删除', 'server_check'=>'服务器检视', 'server_add'=>'服务器添加', 'server_edit'=>'服务器编辑', 'server_delete'=>'服务器删除', 'service_check'=>'在线服务检视', 'service_edit'=>'在线服务编辑', 'service_delete'=>'在线服务删除', 'user_check'=>'用户检视', 'user_edit'=>'用户编辑', 'user_delete'=>'用户删除', 'gateway_check'=>'支付渠道检视', 'gateway_add'=>'支付渠道添加', 'gateway_edit'=>'支付渠道编辑', 'gateway_delete'=>'支付渠道删除', 'priceset_check'=>'价格组检视', 'priceset_add'=>'价格组添加', 'priceset_edit'=>'价格组编辑', 'priceset_delete'=>'价格组删除', 'order_check'=>'订单检视', 'workorder_check'=>'工单检视','workorder_edit'=>'工单回复', 'workorder_delete'=>'工单删除', 'admin_check'=>'管理员检视', 'admin_add'=>'管理员添加', 'admin_edit'=>'管理员编辑', 'admin_delete'=>'管理员删除','web_setting'=>'网站设置', 'notice_add'=>'公告添加', 'workorder_check'=>'公告检视', 'notice_edit'=>'公告编辑', 'notice_delete'=>'公告删除');
                        $permissions = json_decode($admin['permission'], true);
                        $this->Header();
                        echo '
                            <main class="main-content bgc-grey-100">
                       <div id="mainContent">
                          <div class="row gap-20 masonry pos-r">
                             <div class="masonry-sizer col-md-6"></div>
                             <div class="masonry-item col-md-12">
                                <div class="bgc-white p-20 bd">
                                   <h6 class="c-grey-900">编辑管理员</h6>
                                   <div class="mT-30">
                                      <form action="./index.php?p=Admin&a=Admin&aid='.$admin['id'].'" method="POST">
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">管理员账户</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="管理员账户" name="username" value="'.$admin['username'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">管理员密码（留空为不修改）</label>
                                            <div class="col-sm-10"><input type="password" class="form-control" id="inputEmail3" placeholder="管理员密码" name="password""></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">管理员密码（留空为不修改）</label>
                                            <div class="col-sm-10">';
                                         foreach($allpermissions as $k => $v){
                                             if(is_array($permissions)){
                                                 if(in_array($k, $permissions)){
                                                     echo '
                                                     <div class="form-check"><label class="form-check-label"><input name="permission[]" value="'.$k.'" class="form-check-input" type="checkbox" checked>'.$v.'</label></div>';
                                                 }else{
                                                     echo '
                                                   <div class="form-check"><label class="form-check-label"><input name="permission[]" value="'.$k.'" class="form-check-input" type="checkbox">'.$v.'</label></div>';
                                                 }
                                             }else{
                                                 echo '
                                               <div class="form-check"><label class="form-check-label"><input name="permission[]" value="'.$k.'" class="form-check-input" type="checkbox">'.$v.'</label></div>';
                                             }
                                         }
                                         echo '</div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">管理员状态</label>
                                            <div class="col-sm-10"><select name="status" class="form-control">';
                                                if($admin['status'] == '1'){
                                                    echo '<option value="1" selected>正常</option><option value="0">封禁</option>';
                                                }else{
                                                    echo '<option value="1">正常</option><option value="0" selected>封禁</option>';
                                                }
                                            echo'</select></div>
                                         </div>
                                         <div class="form-group row">
                                            <div class="col-sm-10"><button type="submit" class="btn btn-primary">修改</button></div>
                                         </div>
                                      </form>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                    </main>';
                    $this->Footer();
                    }
                }
            }
        }
    }
    
    public function Server(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('server_edit')){
                $this->goIndex();
            }
            if(empty($this->getSystem()->getGetParams()['sid'])){
                @header("Location: ./index.php?p=Admin&a=Servers");
                exit;
            }else{
                if($this->getSystem()->getDatabase()->num_rows("SELECT COUNT(*) FROM `ytidc_server` WHERE `id`='{$this->getSystem()->getGetParams()['sid']}'") != 1){
                    @header("Location: ./index.php?p=Admin&a=Servers");
                    exit;
                }else{
                    $server = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_server` WHERE `id`='{$this->getSystem()->getGetParams()['sid']}'");
                    if($this->getSystem()->getGetParams()['act'] == 'del'){
                        if(!$this->CheckPermission('server_delete')){
                            $this->goIndex();
                        }
                        $this->getSystem()->getDatabase()->exec("DELETE FROM `ytidc_server` WHERE `id`='{$server['id']}'");
                        @header("Location: ./index.php?p=Admin&a=Servers");
                        exit;
                    }
                    if(!empty($this->getSystem()->getPostParams())){
                        $params = $this->getSystem()->getPostParams();
                        $params['serverpassword'] = base64_encode($params['serverpassword']);
                        $params['serveraccesshash'] = base64_encode($params['serveraccesshash']);
                        $result = $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_server` SET `name`='{$params['name']}',`serverip`='{$params['serverip']}',`serverdomain`='{$params['serverdomain']}',`serverdns1`='{$params['serverdns1']}',`serverdns2`='{$params['serverdns2']}',`serverusername`='{$params['serverusername']}',`serverpassword`='{$params['serverpassword']}',`serveraccesshash`='{$params['serveraccesshash']}',`servercpanel`='{$params['servercpanel']}',`serverport`='{$params['serverport']}',`plugin`='{$params['plugin']}',`status`='{$params['status']}' WHERE `id`='{$server['id']}'");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库修改服务器错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=Server&sid=".$server['id']);
                        exit;
                    }else{
                        $Plugins = $this->getSystem()->getPluginManager()->getPlugins('SERVER');
                        $server['serverpassword'] = base64_decode($server['serverpassword']);
                        $server['serveraccesshash'] = base64_decode($server['serveraccesshash']);
                        $this->Header();
                        echo '
                            <main class="main-content bgc-grey-100">
                       <div id="mainContent">
                          <div class="row gap-20 masonry pos-r">
                             <div class="masonry-sizer col-md-6"></div>
                             <div class="masonry-item col-md-12">
                                <div class="bgc-white p-20 bd">
                                   <h6 class="c-grey-900">编辑服务器</h6>
                                   <div class="mT-30">
                                      <form action="./index.php?p=Admin&a=Server&sid='.$server['id'].'" method="POST">
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">服务器名称</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="服务器名称" name="name" value="'.$server['name'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">服务器IP</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="服务器IP" name="serverip" value="'.$server['serverip'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">服务器域名</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="服务器域名" name="serverdomain" value="'.$server['serverdomain'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">服务器DNS1</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="服务器DNS1" name="serverdns1" value="'.$server['serverdns1'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">服务器DNS2</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="服务器DNS2" name="serverdns2" value="'.$server['serverdns2'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">服务器账户</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="服务器账户" name="serverusername" value="'.$server['serverusername'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">服务器密码</label>
                                            <div class="col-sm-10"><input type="password" class="form-control" id="inputEmail3" placeholder="服务器密码" name="serverpassword" value="'.$server['serverpassword'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">服务器哈希</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="服务器哈希" name="serveraccesshash" value="'.$server['serveraccesshash'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">服务器控制面板</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="服务器控制面板" name="servercpanel" value="'.$server['servercpanel'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">服务器端口</label>
                                            <div class="col-sm-10"><input type="number" class="form-control" id="inputEmail3" placeholder="服务器端口" name="serverport" value="'.$server['serverport'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">服务器插件</label>
                                            <div class="col-sm-10"><select name="plugin" class="form-control">';
                                            foreach($Plugins as $Plugin){
                                                if($Plugin == $server['plugin']){
                                                    echo '<option value="'.$Plugin.'" selected>'.$Plugin.'</option>';
                                                }else{
                                                    echo '<option value="'.$Plugin.'">'.$Plugin.'</option>';
                                                }
                                            }
                                            echo '</select></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">服务器状态</label>
                                            <div class="col-sm-10"><select name="status" class="form-control">';
                                                if($server['status'] == '1'){
                                                    echo '<option value="1" selected>正常</option><option value="0">封禁</option>';
                                                }else{
                                                    echo '<option value="1">正常</option><option value="0" selected>封禁</option>';
                                                }
                                            echo'</select></div>
                                         </div>
                                         <div class="form-group row">
                                            <div class="col-sm-10"><button type="submit" class="btn btn-primary">修改</button></div>
                                         </div>
                                      </form>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                    </main>';
                    $this->Footer();
                    }
                }
            }
        }
    }
    
    public function ReopenService(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!empty($this->getSystem()->getGetParams()['sid'])){
                $Service = new Service($this->getSystem()->getGetParams()['sid'], $this);
                if($Service->isExisted() == false){
                    @header("Location: ./index.php?p=Admin&a=Services");
                    exit;
                }else{
                    if($Service->getStatus() != '待开通'){
                        @header("Location: ./index.php?p=Admin&a=Services");
                        exit;
                    }else{
                        $Product = $Service->getProduct();
                        if($Product->isExisted() === false){
                            @header("Location: ./index.php?p=Admin&a=Services");
                            exit;
                        }else{
                            $Server = $Product->getServer();
                            if($Server->isExisted() === false){
                                @header("Location: ./index.php?p=Admin&a=Services");
                                exit;
                            }else{
                                $PluginManager = $this->getSystem()->getPluginManager();
                                $Event = new CreateServiceEvent($Service, $Product, $Service->getPeriod(), $Service->getUser());
                                $result = $PluginManager->loadEventByPlugin('CreateService', $Event, $Server->getServerPluginName());
                                if($result === true){
                                    $Service->setStatus('激活');
                                    @header("Location: ./index.php?p=Admin&a=Services");
                                    exit;
                                }else{
                                    @header("Location: ./index.php?p=Admin&a=Services");
                                    exit;
                                }
                            }
                        }
                    }
                }
            }else{
                @header("Location: ./index.php?p=Admin&a=Services");
                exit;
            }
        }
    }
    
    public function Product(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('product_edit')){
                $this->goIndex();
            }
            if(empty($this->getSystem()->getGetParams()['pid'])){
                @header("Location: ./index.php?p=Admin&a=Products");
                exit;
            }else{
                if($this->getSystem()->getDatabase()->num_rows("SELECT COUNT(*) FROM `ytidc_product` WHERE `id`='{$this->getSystem()->getGetParams()['pid']}'") != 1){
                    @header("Location: ./index.php?p=Admin&a=Products");
                    exit;
                }else{
                    $product = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_product` WHERE `id`='{$this->getSystem()->getGetParams()['pid']}'");
                    if($this->getSystem()->getGetParams()['act'] == 'del'){
                        if(!$this->CheckPermission('product_delete')){
                            $this->goIndex();
                        }
                        $this->getSystem()->getDatabase()->exec("DELETE FROM `ytidc_product` WHERE `id`='{$product['id']}'");
                        @header("Location: ./index.php?p=Admin&a=Products");
                        exit;
                    }
                    if(!empty($this->getSystem()->getPostParams())){
                        $params = $this->getSystem()->getPostParams();
                        foreach($params['period'] as $k => $v){
                            if(!empty($v['name'])){
                                $period[$k] = $v;
                            }
                        }
                        $params['period'] = json_encode($period, JSON_UNESCAPED_UNICODE);
                        foreach($params['customoption'] as $k => $v){
                            if(!empty($v['name'])){
                                $customoption[$k] = $v;
                            }
                        }
                        $params['customoption'] = json_encode($customoption, JSON_UNESCAPED_UNICODE);
                        $params['configoption'] = json_encode($params['configoption'], JSON_UNESCAPED_UNICODE);
                        $result = $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_product` SET `name`='{$params['name']}',`description`='{$params['description']}',`weight`='{$params['weight']}',`period`='{$params['period']}',`group`='{$params['group']}',`configoption`='{$params['configoption']}',`customoption`='{$params['customoption']}',`server`='{$params['server']}',`hidden`='{$params['hidden']}',`status`='{$params['status']}' WHERE `id`='{$product['id']}'");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库修改产品错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=Product&pid=".$product['id']);
                        exit;
                    }else{
                        $Servers = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_server` WHERE `status`='1'");
                        $Groups = $this->getSystem()->getDatabase()->get_rows("SELECT * FROM `ytidc_group` WHERE `status`='1'");
                        $periods = json_decode($product['period'],true);
                        if(is_array($periods)){
                            $periodcount = count($periods);
                        }else{
                            $periodcount = 0;
                        }
                        $product['configoption'] = json_decode($product['configoption'], true);
                        $customoption = json_decode($product['customoption'], true);
                        $server = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_server` WHERE `id`='{$product['server']}'");
                        $Plugin = $this->getSystem()->getPluginManager()->getPlugin($server['plugin']);
                        $PluginServer = new Server($server['id'], $this);
                        $Event = new ProductConfigEvent($PluginServer);
                        if($Plugin  !== false){
                            $ProductConfigs = $Plugin->ProductConfig($Event);
                        }else{
                            $ProductConfigs = array();
                        }
                        if(is_array($customcount)){
                            $customcount = count($customoption);
                        }else{
                            $customcount = 0;
                        }
                        $this->Header();
                        echo '
                            <script>
                                var periodcount = '.$periodcount.';
                                var customcount = '.$customcount.';
                        
                                function AddTimeInput() {
                                    periodcount++;
                                    var time = document.getElementById("timetable");
                                    var editform = document.getElementById("editform");
                                    editform.style.height = editform.offsetHeight + 50 +\'px\';
                        
                                    var tr = document.createElement(\'tr\');
                            	    	var td = document.createElement(\'td\');
                            	    	td.innerHTML=\'<input type="text" class="form-control" name="period[\' + periodcount + \'][name]" value="" style="min-width: 100px;"/>\';
                            			tr.appendChild(td);
                            	    	var td = document.createElement(\'td\');
                            	    	td.innerHTML=\'<input type="text" class="form-control" name="period[\' + periodcount + \'][day]" value="" style="min-width: 100px;"/>\';
                            			tr.appendChild(td);
                            	    	var td = document.createElement(\'td\');
                            	    	td.innerHTML=\'<input type="text" class="form-control" name="period[\' + periodcount + \'][price]" value="" style="min-width: 100px;"/>\';
                            			tr.appendChild(td);
                            	    	var td = document.createElement(\'td\');
                            	    	td.innerHTML=\'<select type="select" class="form-control" name="period[\' + periodcount + \'][renew]" style="min-width: 100px;"><option value="1">允许</option><option value="0">不允许</option></select>\';
                            			tr.appendChild(td);
                            	    	var td = document.createElement(\'td\');
                            	    	td.innerHTML=\'<input type="text" class="form-control" name="period[\' + periodcount + \'][remark]" value="" style="min-width: 100px;"/>\';
                            			tr.appendChild(td);
                            		time.appendChild(tr);
                        
                                }
                                function AddCustomInput() {
                                    customcount++;
                                    var custom = document.getElementById("customtable");
                                    var editform = document.getElementById("editform");
                                    editform.style.height = editform.offsetHeight + 50 +\'px\';
                        
                                    var tr = document.createElement(\'tr\');
                            	    	var td = document.createElement(\'td\');
                            	    	td.innerHTML=\'<input type="text" class="form-control" name="customoption[\' + customcount + \'][name]" value="" style="min-width: 100px;"/>\';
                            			tr.appendChild(td);
                            	    	var td = document.createElement(\'td\');
                            	    	td.innerHTML=\'<input type="text" class="form-control" name="customoption[\' + customcount + \'][type]" value="" style="min-width: 100px;"/>\';
                            			tr.appendChild(td);
                            	    	var td = document.createElement(\'td\');
                            	    	td.innerHTML=\'<input type="text" class="form-control" name="customoption[\' + customcount + \'][label]" value="" style="min-width: 100px;"/>\';
                            			tr.appendChild(td);
                            		custom.appendChild(tr);
                        
                                }
                            </script>
                        
                            <main class="main-content bgc-grey-100">
                       <div id="mainContent">
                          <div class="row gap-20 masonry pos-r" id="editform">
                             <div class="masonry-sizer col-md-6"></div>
                             <div class="masonry-item col-md-12">
                                <div class="bgc-white p-20 bd">
                                   <h6 class="c-grey-900">编辑产品</h6>
                                   <div class="mT-30">
                                      <form action="./index.php?p=Admin&a=Product&pid='.$product['id'].'" method="POST">
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">产品名称</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="服务器名称" name="name" value="'.$product['name'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">产品介绍</label>
                                            <div class="col-sm-10"><textarea class="form-control" name="description" rows="4">'.$product['description'].'</textarea></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">产品权重</label>
                                            <div class="col-sm-10"><input type="number" class="form-control" id="inputEmail3" placeholder="服务器名称" name="weight" value="'.$product['weight'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">产品周期 <button class="btn btn-sm btn-primary" onclick="AddTimeInput()" type="button">新增周期</button></label>
                                            <div class="table-responsive col-sm-10">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                           <th scope="col">周期名称</th>
                                                           <th scope="col">开通天数</th>
                                                           <th scope="col">周期价格</th>
                                                           <th scope="col" style="min-width:60px;">周期续费</th>
                                                           <th scope="col">周期备注</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="timetable">';
                                                    foreach($periods as $k => $v){
                                                        echo '
                                                            <tr>
                                                               <td scope="row"><input class="form-control" name="period['.$k.'][name]" value="'.$v['name'].'"></td>
                                                               <td><input class="form-control" name="period['.$k.'][day]" value="'.$v['day'].'"></td>
                                                               <td><input class="form-control" name="period['.$k.'][price]" value="'.$v['price'].'"></td>
                                                               <td><select type="select" class="form-control" name="period['.$k.'][renew]">';
                                                               if($v['renew'] == 1){
                                                                   echo '<option value="1" selected>允许</option><option value="0">禁止</option>';
                                                               }else{
                                                                   echo '<option value="1">允许</option><option value="0" selected>禁止</option>';
                                                               }
                                                               echo '
                                                               </select></td>
                                                               <td><input class="form-control" name="period['.$k.'][remark]" value="'.$v['remark'].'"></td>
                                                            </tr>
                                                        ';
                                                    }
                                                    echo '
                                                    </tbody>
                                                </table>
                                            </div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">自定义输入 <button class="btn btn-sm btn-primary" onclick="AddCustomInput()" type="button">新增自定义输入</button></label>
                                            <div class="table-responsive col-sm-10">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                           <th scope="col">输入名称</th>
                                                           <th scope="col">输入类型</th>
                                                           <th scope="col">输入提示</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="customtable">';
                                                    if(is_array($customoption)){
                                                        foreach($customoption as $k => $v){
                                                            echo '
                                                                <tr>
                                                                   <td scope="row"><input class="form-control" name="customoption['.$k.'][name]" value="'.$v['name'].'"></td>
                                                                   <td><input class="form-control" name="customoption['.$k.'][type]" value="'.$v['type'].'"></td>
                                                                   <td><input class="form-control" name="customoption['.$k.'][label]" value="'.$v['label'].'"></td>
                                                                </tr>
                                                            ';
                                                        }
                                                    }
                                                    echo '
                                                    </tbody>
                                                </table>
                                            </div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">所属产品组</label>
                                            <div class="col-sm-10"><select name="group" class="form-control">';
                                            if(is_array($Groups)){
                                                foreach($Groups as $k => $v){
                                                    if($v['id'] == $product['group']){
                                                        echo '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                                                    }else{
                                                        echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                                                    }
                                                }
                                            }
                                            echo '</select></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">产品服务器</label>
                                            <div class="col-sm-10"><select name="server" class="form-control">';
                                            if(is_array($Servers)){
                                                foreach($Servers as $k => $v){
                                                    if($v['id'] == $product['server']){
                                                        echo '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                                                    }else{
                                                        echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                                                    }
                                                }
                                            }
                                            echo '</select></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">隐藏产品</label>
                                            <div class="col-sm-10"><select name="hidden" class="form-control">';
                                                if($product['status'] == '1'){
                                                    echo '<option value="1" selected>显示</option><option value="0">隐藏</option>';
                                                }else{
                                                    echo '<option value="1">显示</option><option value="0" selected>隐藏</option>';
                                                }
                                            echo'</select></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">产品状态</label>
                                            <div class="col-sm-10"><select name="status" class="form-control">';
                                                if($product['status'] == '1'){
                                                    echo '<option value="1" selected>正常</option><option value="0">下架</option>';
                                                }else{
                                                    echo '<option value="1">正常</option><option value="0" selected>下架</option>';
                                                }
                                            echo'</select></div>
                                         </div>';
                                         if(is_array($ProductConfigs)){
                                             foreach($ProductConfigs as $k => $v){
                                                 if($v['type'] == 'text' || $v['type'] == "number" || $v['type'] == "password"){
                                                     echo '
                                                 <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-2 col-form-label">插件配置：'.$v['label'].'</label>
                                                    <div class="col-sm-10"><input type="'.$v['type'].'" class="form-control" id="inputEmail3" placeholder="'.$v['placeholder'].'" name="configoption['.$k.']" value="'.$product['configoption'][$k].'"></div>
                                                 </div>';
                                                 }
                                                 if($v['type'] == 'textarea'){
                                                     echo '
                                                 <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-2 col-form-label">插件配置：'.$v['label'].'</label>
                                                    <div class="col-sm-10"><textarea class="form-control" placeholder="'.$v['placeholder'].'" name="configoption['.$k.']">'.$product['configoption'][$k].'</textarea></div>
                                                 </div>';
                                                 }
                                                 if($v['type'] == "select"){
                                                     echo '
                                                 <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-2 col-form-label">插件配置：'.$v['label'].'</label>
                                                    <div class="col-sm-10"><select name="configoption['.$k.']" class="form-control">';
                                                        foreach($v['option'] as $k1 => $v1){
                                                            if($product['configoption'][$k] == $v1){
                                            					echo '<option value="'.$v1.'" selected>'.$k1.'</option>';
                                            				}else{
                                            					echo '<option value="'.$v1.'">'.$k1.'</option>';
                                            				}
                                                        }
                                                    echo '</select></div>
                                                 </div>';
                                                 }
                                             }
                                         }
                                         echo '
                                         <div class="form-group row">
                                            <div class="col-sm-10"><button type="submit" class="btn btn-primary">修改</button></div>
                                         </div>
                                      </form>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                    </main>';
                    $this->Footer();
                    }
                }
            }
        }
    }
    
    public function Gateway(){
        if($this->checkLogin() === false){
            $this->goLogin();
        }else{
            if(!$this->CheckPermission('gateway_edit')){
                $this->goIndex();
            }
            if(empty($this->getSystem()->getGetParams()['gid'])){
                @header("Location: ./index.php?p=Admin&a=Gateway");
                exit;
            }else{
                if($this->getSystem()->getDatabase()->num_rows("SELECT COUNT(*) FROM `ytidc_gateway` WHERE `id`='{$this->getSystem()->getGetParams()['gid']}'") != 1){
                    @header("Location: ./index.php?p=Admin&a=Gateway");
                    exit;
                }else{
                    $gateway = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_gateway` WHERE `id`='{$this->getSystem()->getGetParams()['gid']}'");
                    if($this->getSystem()->getGetParams()['act'] == 'del'){
                        if(!$this->CheckPermission('gateway_delete')){
                            $this->goIndex();
                        }
                        $this->getSystem()->getDatabase()->exec("DELETE FROM `ytidc_gateway` WHERE `id`='{$gateway['id']}'");
                        @header("Location: ./index.php?p=Admin&a=Gateways");
                        exit;
                    }
                    if(!empty($this->getSystem()->getPostParams())){
                        $params = $this->getSystem()->getPostParams();
                        $params['configoption'] = json_encode($params['configoption'], JSON_UNESCAPED_UNICODE);
                        $result = $this->getSystem()->getDatabase()->exec("UPDATE `ytidc_gateway` SET `name`='{$params['name']}',`rate`='{$params['rate']}',`plugin`='{$params['plugin']}',`configoption`='{$params['configoption']}',`status`='{$params['status']}' WHERE `id`='{$gateway['id']}'");
                        if($result == 0){
                            $this->getSystem()->getLogger()->addSystemLog('数据库修改服务器错误：'.print_r($this->getSystem()->getDatabase()->error()));
                        }
                        @header("Location: ./index.php?p=Admin&a=Gateway&gid=".$gateway['id']);
                        exit;
                    }else{
                        $Plugins = $this->getSystem()->getPluginManager()->getPlugins('PAYMENT');
                        $gateway['configoption'] = json_decode($gateway['configoption'], true);
                        $Plugin = $this->getSystem()->getPluginManager()->getPlugin($gateway['plugin']);
                        if($Plugin  !== false){
                            $GatewayConfigs = $Plugin->GatewayConfig();
                        }else{
                            $GatewayConfigs = array();
                        }
                        $this->Header();
                        echo '
                            <main class="main-content bgc-grey-100">
                       <div id="mainContent">
                          <div class="row gap-20 masonry pos-r">
                             <div class="masonry-sizer col-md-6"></div>
                             <div class="masonry-item col-md-12">
                                <div class="bgc-white p-20 bd">
                                   <h6 class="c-grey-900">编辑支付渠道</h6>
                                   <div class="mT-30">
                                      <form action="./index.php?p=Admin&a=Gateway&gid='.$gateway['id'].'" method="POST">
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">支付通道名称</label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="inputEmail3" placeholder="支付通道名称" name="name" value="'.$gateway['name'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">到账费率</label>
                                            <div class="col-sm-10"><input type="number" max="100" min="0" class="form-control" id="inputEmail3" placeholder="到账费率" name="rate" value="'.$gateway['rate'].'"></div>
                                         </div>
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">渠道插件</label>
                                            <div class="col-sm-10"><select name="plugin" class="form-control">';
                                            foreach($Plugins as $Plugin){
                                                if($Plugin == $gateway['plugin']){
                                                    echo '<option value="'.$Plugin.'" selected>'.$Plugin.'</option>';
                                                }else{
                                                    echo '<option value="'.$Plugin.'">'.$Plugin.'</option>';
                                                }
                                            }
                                            echo '</select></div>
                                         </div>';
                                         foreach($GatewayConfigs as $k => $v){
                                             if($v['type'] == 'text' || $v['type'] == "number" || $v['type'] == "password"){
                                                 echo '
                                             <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-2 col-form-label">插件配置：'.$v['label'].'</label>
                                                <div class="col-sm-10"><input type="'.$v['type'].'" class="form-control" id="inputEmail3" placeholder="'.$v['placeholder'].'" name="configoption['.$k.']" value="'.$gateway['configoption'][$k].'"></div>
                                             </div>';
                                             }
                                             if($v['type'] == 'textarea'){
                                                 echo '
                                             <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-2 col-form-label">插件配置：'.$v['label'].'</label>
                                                <div class="col-sm-10"><textarea class="form-control" placeholder="'.$v['placeholder'].'" name="configoption['.$k.']">'.$gateway['configoption'][$k].'</textarea></div>
                                             </div>';
                                             }
                                             if($v['type'] == "select"){
                                                 echo '
                                             <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-2 col-form-label">插件配置：'.$v['label'].'</label>
                                                <div class="col-sm-10"><select name="configoption['.$k.']" class="form-control">';
                                                    foreach($v['option'] as $k1 => $v1){
                                                        if($gateway['configoption'][$k] == $v1){
                                        					echo '<option value="'.$v1.'" selected>'.$k1.'</option>';
                                        				}else{
                                        					echo '<option value="'.$v1.'">'.$k1.'</option>';
                                        				}
                                                    }
                                                echo '</select></div>
                                             </div>';
                                             }
                                         }
                                         echo '
                                         <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">渠道</label>
                                            <div class="col-sm-10"><select name="status" class="form-control">';
                                                if($gateway['status'] == '1'){
                                                    echo '<option value="1" selected>正常</option><option value="0">封禁</option>';
                                                }else{
                                                    echo '<option value="1">正常</option><option value="0" selected>封禁</option>';
                                                }
                                            echo'</select></div>
                                         </div>
                                         <div class="form-group row">
                                            <div class="col-sm-10"><button type="submit" class="btn btn-primary">修改</button></div>
                                         </div>
                                      </form>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                    </main>';
                    $this->Footer();
                    }
                }
            }
        }
    }
    
}

?>