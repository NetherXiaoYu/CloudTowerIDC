<?php

//Logger.php处理一切报告档案

namespace CloudTowerIDC\Logger;

use Exception;
use PDOException;

use file_put_contents;
use yaml_emit;
use file_get_contents;
use date;

class Logger{

    public function newCrashDump($info, $error){
        $date = date('YmdHis');
        $filename = 'CrashDump_'.$date.'.dump';
        $path = BASE_ROOT.'/logs/'.$filename;
        $content = array(
            'createDate' => $date,
            'shortMessage' => $info,
            'errorMessage' => $error,
        );
        if(file_put_contents($path, json_encode($content, JSON_UNESCAPED_UNICODE))){
            return true;
        }else{
            return false;
        }
    }

    public function getSystemLog(){
        $path = BASE_ROOT.'/logs/system.log';
        if(!file_exists($path)){
            return false;
        }else{
            return file_get_contents($path);
        }
    }

    public function addSystemLog($log){
        $date = date('Y-m-d H:i:s');
        $content = "[{$date}]{$log}\r\n";
        $path = BASE_ROOT.'/logs/system.log';
        if(file_put_contents($path, $content, FILE_APPEND|LOCK_EX)){
            return true;
        }else{
            return false;
        }
    }

}

?>