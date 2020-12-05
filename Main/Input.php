<?php

namespace CloudTowerIDC\Input;

use htmlspecialchars;

class Input{
    
    public $getfilter = "\\<.+javascript:window\\[.{1}\\\\x|<.*=(&#\\d+?;?)+?>|<.*(data|src)=data:text\\/html.*>|\\b(alert\\(|confirm\\(|expression\\(|prompt\\(|benchmark\s*?\(.*\)|sleep\s*?\(.*\)|\\b(group_)?concat[\\s\\/\\*]*?\\([^\\)]+?\\)|\bcase[\s\/\*]*?when[\s\/\*]*?\([^\)]+?\)|load_file\s*?\\()|<[a-z]+?\\b[^>]*?\\bon([a-z]{4,})\s*?=|^\\+\\/v(8|9)|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.*\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)|UPDATE\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)@{0,2}(\\(.+\\)|\\s+?.+?\\s+?|(`|'|\").*?(`|'|\"))FROM(\\(.+\\)|\\s+?.+?|(`|'|\").*?(`|'|\"))|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)|<.*(iframe|frame|style|embed|object|frameset|meta|xml)";
    //post拦截规则
    public $postfilter = "<.*=(&#\\d+?;?)+?>|<.*data=data:text\\/html.*>|\\b(alert\\(|confirm\\(|expression\\(|prompt\\(|benchmark\s*?\(.*\)|sleep\s*?\(.*\)|\\b(group_)?concat[\\s\\/\\*]*?\\([^\\)]+?\\)|\bcase[\s\/\*]*?when[\s\/\*]*?\([^\)]+?\)|load_file\s*?\\()|<[^>]*?\\b(onerror|onmousemove|onload|onclick|onmouseover)\\b|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.*\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)|UPDATE\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)(\\(.+\\)|\\s+?.+?\\s+?|(`|'|\").*?(`|'|\"))FROM(\\(.+\\)|\\s+?.+?|(`|'|\").*?(`|'|\"))|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)|<.*(iframe|frame|style|embed|object|frameset|meta|xml)";
    //cookie拦截规则
    public $cookiefilter = "benchmark\s*?\(.*\)|sleep\s*?\(.*\)|load_file\s*?\\(|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.*\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)|UPDATE\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)@{0,2}(\\(.+\\)|\\s+?.+?\\s+?|(`|'|\").*?(`|'|\"))FROM(\\(.+\\)|\\s+?.+?|(`|'|\").*?(`|'|\"))|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    //referer获取
    
    public $webscan_switch = 1;
    public $webscan_white_directory = '';
    public $webscan_white_url = array();
    
    public $webscan_referer;
        
    public function __construct(){
        $this->webscan_referer = empty($_SERVER['HTTP_REFERER']) ? array() : array('HTTP_REFERER'=>$_SERVER['HTTP_REFERER']);
        if ($this->webscan_switch&&$this->webscan_white($this->webscan_white_directory,$this->webscan_white_url)) {
            foreach($_GET as $key=>$value) {
              $this->webscan_StopAttack($key,$value,$this->getfilter,"GET");
            }
            foreach($_POST as $key=>$value) {
              $this->webscan_StopAttack($key,$value,$this->postfilter,"POST");
            }
            foreach($_COOKIE as $key=>$value) {
              $this->webscan_StopAttack($key,$value,$this->cookiefilter,"COOKIE");
            }
            foreach($this->webscan_referer as $key=>$value) {
              $this->webscan_StopAttack($key,$value,$this->postfilter,"REFERRER");
            }
        }
    }
    
    public function getInputs($type = 'GET'){
        switch ($type) {
            case 'GET':
                return $this->daddslashes($_GET);
                break;
            case 'POST':
                return $this->daddslashes($_POST);
                break;
            default:
                return $this->daddslashes($_GET);
                break;
        }
    }
    
    public function daddslashes($string, $strip = FALSE){
    	if(is_array($string)) {
    		foreach($string as $key => $val) {
    			$string[$key] = $this->daddslashes($val, $strip);
    		}
    	} else {
    		$string = addslashes($strip ? stripslashes($string) : $string);
    	}
    	return $string;
    }
    
    public function webscan_error() {
      if (ini_get('display_errors')) {
        ini_set('display_errors', '0');
      }
    }
    
    /**
     *  数据统计回传
     */
    public function webscan_slog($logs) {
      //日志记录
      return true;
    }
    /**
     *  参数拆分
     */
    public function webscan_arr_foreach($arr) {
      static $str;
      static $keystr;
      if (!is_array($arr)) {
        return $arr;
      }
      foreach ($arr as $key => $val ) {
        $keystr=$keystr.$key;
        if (is_array($val)) {
    
          $this->webscan_arr_foreach($val);
        } else {
    
          $str[] = $val.$keystr;
        }
      }
      return implode($str);
    }
    
    /**
     *  防护提示页
     */
    public function webscan_pape(){
      $pape='
    <html>
    <head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <title>安全拦截 - 云塔IDC系统</title>
    <style>
    body, h1, h2, p,dl,dd,dt{margin: 0;padding: 0;font: 12px/1.5 微软雅黑,tahoma,arial;}
    body{background:#efefef;}
    h1, h2, h3, h4, h5, h6 {font-size: 100%;cursor:default;}
    h1{font-size: 24px;}
    ul, ol {list-style: none outside none;}
    a {text-decoration: none;color:#447BC4}
    a:hover {text-decoration: underline;}
    .ip-attack{width:600px; margin:200px auto 0;}
    .ip-attack dl{ background:#fff; padding:30px; border-radius:10px;border: 1px solid #CDCDCD;-webkit-box-shadow: 0 0 8px #CDCDCD;-moz-box-shadow: 0 0 8px #cdcdcd;box-shadow: 0 0 8px #CDCDCD;}
    .ip-attack dt{text-align:center;}
    .ip-attack dd{font-size:16px; color:#333; text-align:center;}
    .tips{text-align:center; font-size:14px; line-height:50px; color:#999;}
    </style>
    </head>
    <body>
    <div class="ip-attack">
    <dl>
    <dt><h1>云塔IDC系统提醒您：请勿输入危险字符哦！</h1></dt>
    <dt><a href="javascript:history.go(-1)">返回上一页</a></dt>
    </dl>
    </div>
    </body>
    </html>';
      exit($pape);
    }
    
    /**
     *  攻击检查拦截
     */
    public function webscan_StopAttack($StrFiltKey,$StrFiltValue,$ArrFiltReq,$method) {
      $StrFiltValue=$this->webscan_arr_foreach($StrFiltValue);
      if (preg_match("/".$ArrFiltReq."/is",$StrFiltValue)==1){
        $this->webscan_slog(array('ip' => $_SERVER["REMOTE_ADDR"],'time'=>strftime("%Y-%m-%d %H:%M:%S"),'page'=>$_SERVER["PHP_SELF"],'method'=>$method,'rkey'=>$StrFiltKey,'rdata'=>$StrFiltValue,'user_agent'=>$_SERVER['HTTP_USER_AGENT'],'request_url'=>$_SERVER["REQUEST_URI"]));
        exit($this->webscan_pape());
      }
      if (preg_match("/".$ArrFiltReq."/is",$StrFiltKey)==1){
        $this->webscan_slog(array('ip' => $_SERVER["REMOTE_ADDR"],'time'=>strftime("%Y-%m-%d %H:%M:%S"),'page'=>$_SERVER["PHP_SELF"],'method'=>$method,'rkey'=>$StrFiltKey,'rdata'=>$StrFiltKey,'user_agent'=>$_SERVER['HTTP_USER_AGENT'],'request_url'=>$_SERVER["REQUEST_URI"]));
        exit($this->webscan_pape());
      }
    
    }
    /**
     *  拦截目录白名单
     */
    public function webscan_white($webscan_white_name,$webscan_white_url=array()) {
      $url_path=$_SERVER['SCRIPT_NAME'];
      $url_var=$_SERVER['QUERY_STRING'];
      if (preg_match("/".$webscan_white_name."/is",$url_path)==1&&!empty($webscan_white_name)) {
        return false;
      }
      foreach ($webscan_white_url as $key => $value) {
        if(!empty($url_var)&&!empty($value)){
          if (stristr($url_path,$key)&&stristr($url_var,$value)) {
            return false;
          }
        }
        elseif (empty($url_var)&&empty($value)) {
          if (stristr($url_path,$key)) {
            return false;
          }
        }
    
      }
    
      return true;
    }
    
}

?>