<?php

namespace CloudTowerIDC\Database;

use PDO;
use CloudTowerIDC\Logger\Logger;

class Database{

    public $database;

    public function __construct(){
        try {
            $dsn=DB_TYPE.":host=".DB_HOST.";dbname=".DB_NAME;
            $this->database = new PDO($dsn, DB_USER, DB_PASS);
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->newCrashDump('连接数据库失败', $e->getMessage());
            exit('YunTaIDC:连接数据库失败！');
        }
    }

    public function get_row($sql){
        $result = $this->database->prepare($sql);
        $result->execute();
        $result = $result->fetch(PDO::FETCH_ASSOC);
        if(empty($result) || $result === false){
            return false;
        }else{
            return $result;
        }
    }
    
    public function num_rows($sql){
        if($result = $this->database->query($sql)){
            $number = $result->fetchColumn();
            if(empty($number)){
                return 0;
            }else{
                return $number;
            }
        }
    }
    
    public function exec($sql){
        if($this->database->exec($sql)){
            return true;
        }else{
            return false;
        }
    }
    
    public function lastInsertId(){
        return $this->database->lastInsertId();
    }
    
    public function error(){
        return $this->database->errorInfo();
    }

    public function get_rows($sql){
        $result = $this->database->prepare($sql);
        $result->execute();
        $result = $result->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}

?>