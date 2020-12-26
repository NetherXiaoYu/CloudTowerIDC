<?php
error_reporting(0);
session_start();
@header('Content-Type: text/html; charset=UTF-8');
$do=isset($_GET['do'])?$_GET['do']:'0';

function checkfunc($f,$m = false) {
	if (function_exists($f)) {
		return '<font color="green">可用</font>';
	} else {
		if ($m == false) {
			return '<font color="black">不支持</font>';
		} else {
			return '<font color="red">不支持</font>';
		}
	}
}
?>
<!doctype html>
<html  lang="en">

    <head>
        <!-- meta data -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <!--font-family-->
		<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&amp;subset=devanagari,latin-ext" rel="stylesheet">
        
        <!-- title of site -->
        <title>安装系统 - 云塔IDC财务管理系统v3.0.3</title>

        <!-- For favicon png -->
		<link rel="shortcut icon" type="image/icon" href="install/logo/favicon.png"/>
       
        <!--font-awesome.min.css-->
        <link rel="stylesheet" href="install/css/font-awesome.min.css">
		
		<!--animate.css-->
        <link rel="stylesheet" href="install/css/animate.css">
		
        <!--bootstrap.min.css-->
        <link rel="stylesheet" href="install/css/bootstrap.min.css">
		
		<!-- bootsnav -->
		<link rel="stylesheet" href="install/css/bootsnav.css" >	
        
        <!--style.css-->
        <link rel="stylesheet" href="install/css/style.css">
        
        <!--responsive.css-->
        <link rel="stylesheet" href="install/css/responsive.css">
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		
        <!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
	
	<body style="background-color: #f4f6fa;">
		<!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->
		
		<!-- signin end -->
		<section class="signin signup">
			<div class="container">

				<div class="sign-content">
					<h2>云塔IDC系统v3.0.3</h2>

<?php if($do=='0'){
$_SESSION['checksession']=1;
?>
<textarea style="width: 100%;height: 480px;text-align:center;background:transparent;border: 0;" disabled=""><?php echo file_get_contents('./LICENSE');?></textarea>

<div class="signin-footer">
	<a href="?do=1"><button type="button" class="btn signin_btn signin_btn_two" data-toggle="modal" data-target=".signin_modal">
	进行安装(已经安装过的请手动删除此文件)
	</button></a>
</div><!--/.signin-footer -->

<?php }elseif($do=='1'){?>

	<table class="table table-striped">
						<thead>
							<tr>
								<th>环境名称</th>
								<th>当前环境</th>
								<th>要求环境</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>PHP版本</td>
								<td><?php echo PHP_VERSION; ?></td>
								<td>8.0</td>
							</tr>
							<tr>
								<td>file_get_contents()功能</td>
								<td><?php echo checkfunc('file_get_contents',true); ?></td>
								<td>True</td>
							</tr>
						</tbody>
					</table>

					<div class="signin-footer">
						<a href="?do=2"><button type="button" class="btn signin_btn signin_btn_two">
						进行安装
						</button></a>
					</div><!--/.signin-footer -->

<?php }elseif($do=='2'){?>
	<div class="signin-form">
						<div class=" ">
							<div class=" ">
								<form action="?do=3" method="POST">
									<div class="form-group">
									    <label for="signin_form">数据库地址</label>
									    <input type="text" class="form-control" name="db_host" id="signin_form" placeholder="数据库地址" value="localhost">
									</div><!--/.form-group -->
									<div class="form-group">
										<label for="signin_form">数据库账号</label>
									    <input type="text" class="form-control" name="db_user" id="signin_form" placeholder="数据库账号">
									</div><!--/.form-group -->
									<div class="form-group">
										<label for="signin_form">数据库密码</label>
									    <input type="password" class="form-control" name="db_pwd" id="signin_form" placeholder="数据库密码">
									</div><!--/.form-group -->
									<div class="form-group">
										<label for="signin_form">数据库名称</label>
									    <input type="text" class="form-control" name="db_name" id="signin_form" placeholder="数据库名称">
									</div><!--/.form-group -->
							</div><!--/.col -->
						</div><!--/.row -->

					</div><!--/.signin-form -->

					<div class="signin-footer">
						<button type="submit" class="btn signin_btn signin_btn_two">
						安装程序
						</button>
</form>
					</div><!--/.signin-footer -->

<?php }elseif($do=='3'){
?>
<?php
	$db_host=isset($_POST['db_host'])?$_POST['db_host']:NULL;
	$db_user=isset($_POST['db_user'])?$_POST['db_user']:NULL;
	$db_pwd=isset($_POST['db_pwd'])?$_POST['db_pwd']:NULL;
	$db_name=isset($_POST['db_name'])?$_POST['db_name']:NULL;

	if($db_host==null || $db_user==null || $db_pwd==null || $db_name==null){
		echo '<p>保存错误,请确保每项都不为空<hr/>
		<a href="?do=2"><button type="button" class="btn signin_btn signin_btn_two">
		上一步
		</button></a></p>';
	} else {
		$config="<?php
		// 数据库配置
		define(\"DB_HOST\", '{$db_host}');
		define(\"DB_USER\", '{$db_user}'); //数据库用户
		define(\"DB_PASS\", '{$db_pwd}'); //数据库密码
		define(\"DB_NAME\", '{$db_name}'); //数据库名称
		define(\"DB_TYPE\", 'mysql');
		
		// 侦错模式
		define(\"DEBUG_MODE\", false);
?>";
		if(!$con= new mysqli($db_host, $db_user, $db_pwd, $db_name, $db_port)){
			echo '<p>连接数据库失败，请认真检查相关信息！</p>';
		}elseif(file_put_contents('./config.php',$config)){
			if(function_exists("opcache_reset"))@opcache_reset();
				echo '<p>数据库配置文件保存成功！</p>';
			if($con->query("select * from ytidc_config where 1")->num_rows == 0){
				echo '<p>
				<a href="?do=4"><button type="button" class="btn signin_btn signin_btn_two">
				创建数据表
				</button></a></p>';
			}else{
				echo '<p>你已经安装过了，如需继续安装，请清除数据库数据！</p>';
				echo '<a href="?do=5" ><button class="btn signin_btn signin_btn_two">直接前往登陆</button></a>';
			}
		}else
			echo '<p>保存失败，请确保网站根目录有写入权限<hr/>
			<a href="?do=2"><button type="button" class="btn signin_btn signin_btn_two">
			上一步
			</button></a></p>';
	}
?>
<?php }elseif($do=='4'){?>
<?php
require_once('./config.php');
if(!DB_USER||!DB_PASS||!DB_NAME) {
	echo '<p>请先填写好数据库并保存后再安装！<hr/>
	<a href="?do=3"><button type="button" class="btn signin_btn signin_btn_two">
	上一步
	</button></a></p>';
} else {
	$sql=file_get_contents("./install.sql");
	$sql=explode(';',$sql);
	$DB = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME,'3306');
	$DB->query("set sql_mode = ''");
	$DB->query("set names utf8");
	$t=0; $e=0; $error='';
	for($i=0;$i<count($sql);$i++) {
		if ($sql[$i]=='')continue;
		if($DB->query($sql[$i])) {
			++$t;
		} else {
			++$e;
			$error.=$DB->error.'<br/>';
		}
	}
	date_default_timezone_set("PRC");
	$date = date("Y-m-d");
}
if($e==0) {
	echo '<p">安装成功！<br/>SQL成功'.$t.'句/失败'.$e.'句</p><p>
	<a href="?do=5"><button type="button" class="btn signin_btn signin_btn_two">
	下一步
	</button></a></p>';
} else {
	echo '<p>安装失败<br/>SQL成功'.$t.'句/失败'.$e.'句<br/>错误信息：'.$error.'</p><p>
	<a href="?do=4"><button type="button" class="btn signin_btn signin_btn_two">
	上一步
	</button></a><a href="?do=5"><button type="button" class="btn signin_btn signin_btn_two">
	下一步
	</button></a></p>';
}
?>

<?php }elseif($do=='5'){?>
<?php
	unlink('install.php');
	unlink('install.sql');
	echo '
	<p>安装成功！默认账号密码：admin/123456</p>

	<div class="signin-footer">
		<a href="/index.php?p=Admin&a=Login"><button type="button" class="btn signin_btn signin_btn_two">
		点击进入管理后台
		</button></a>
	</div><!--/.signin-footer -->';
?>

<?php }elseif($do=='6'){?>
<?php
	unlink('index.php');
	unlink('install.sql');
	echo '
	<p>安装成功！默认账号密码：admin/123456</p>

	<div class="signin-footer">
	<a href="/index.php?p=Admin&a=Login"><button type="button" class="btn signin_btn signin_btn_two">
	点击进入管理后台
	</button></a>
	</div><!--/.signin-footer -->';
?>
<?php }?>

</div><!--/.sign-content -->
			</div><!--/.container -->

		</section><!--/.signin -->
		
		<!-- signin end -->

		<!--footer copyright start -->
		<footer class="footer-copyright">
			<div id="scroll-Top">
				<i class="fa fa-angle-double-up return-to-top" id="scroll-top" data-toggle="tooltip" data-placement="top" title="" data-original-title="Back to Top" aria-hidden="true"></i>
			</div><!--/.scroll-Top-->

		</footer><!--/.hm-footer-copyright-->
		<!--footer copyright  end -->


		 <!-- Include all js compiled plugins (below), or include individual files as needed -->

		<script src="install/js/jquery.js"></script>
        
        <!--modernizr.min.js-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
		
		<!--bootstrap.min.js-->
        <script src="install/js/bootstrap.min.js"></script>
		
		<!-- bootsnav js -->
		<script src="install/js/bootsnav.js"></script>
		
		<!-- jquery.sticky.js -->
		<script src="install/js/jquery.sticky.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
		
        
        <!--Custom JS-->
        <script src="install/js/custom.js"></script>

    </body>
	
</html>