<?php

namespace YunTaIDC\Template;

use YunTaIDC\Logger\Logger;
use YunTaIDC\Plugin\PluginManager;
use file_get_contents;
use str_replace;
use file_exists;
use preg_match_all;

class Template{

    public $path;
    public $name;
    public $translation;
    
    private $File;
    private $Template;
    private $TemplateCode;

    public function __construct($name){
        $this->path = BASE_ROOT.'/Templates/'.$name.'/';
        $this->translation = $this->path . '/languages/default.json';
        $this->name = $name;
    }
    
    public function getTemplatePath(){
        return $this->path;
    }
    
    public function getTemplateName(){
        return $this->name;
    }
    
    public function getTemplateFile(){
        return $this->File;
    }
    
    public function setTemplateName($name){
        $this->name = $name;
        $this->path = BASE_ROOT.'/Templates/'.$name.'/';
    }
    
    public function setTemplateFile($name){
        $this->File = $name;
        if(file_exists($this->getTemplatePath().$name.'.template')){
            $this->Template = file_get_contents($this->getTemplatePath().$name.'.template');
            return true;
        }else{
            return false;
        }
    }
    
    public function getTemplateContent(){
        return $this->Template;
    }
    
    public function setTemplateCode($TemplateCode){
        $this->TemplateCode = $TemplateCode;
    }
    
    public function getTemplateCode(){
        return $this->TemplateCode;
    }

    public function outputTemplate(){
        $this->replaceIncludeFile();
        $this->replaceTemplateCode();
        $this->replaceTranslate();
        echo $this->Template;
    }

    public function replaceTranslate(){
        if(empty($this->Template)){
            return false;
        }else{
            if(!file_exists($this->translation)){
                return false;
            }else{
                $Template = $this->Template;
                $Translation = json_decode($this->translation, true);
                foreach($Translation as $k => $v){
                    $Template = str_replace('[lang['.$k.']]', $v, $Template);
                }
                $this->Template = $Template;
                return true;
            }
        }
    }

    public function replaceTemplateCode(){
        if(empty($this->Template)){
            return false;
        }else{
            $Template = $this->Template;
            $TemplateCode = $this->TemplateCode;
            $TemplateCode['templatePath'] = '/Templates/'.$this->getTemplateName();
            foreach ($TemplateCode as $k => $v){
                if(is_array($v)){
                    foreach($v as $k1 => $v1){
                        $Template = str_replace('['.$k.'['.$k1.']]', $v1, $Template);
                    }
                }else{
                    $Template = str_replace('['.$k.']', $v, $Template);
                }
            }
            $this->Template = $Template;
            return true;
        }
    }

    public function replaceIncludeFile(){
    	if(empty($this->Template)){
    		return false;
    	}else{
    	    $Template = $this->Template;
    		preg_match_all("/\[include\[(.*)\]\]/U", $Template, $include_file);
    		foreach($include_file[1] as $k => $v){
    			if(file_exists($this->getTemplatePath()."/".$v)){
    				$replace = file_get_contents($this->getTemplatePath()."/".$v);
    				$Template = str_replace("[include[{$v}]]", $replace, $Template);
    			}
    		}
    		$this->Template = $Template;
    		return true;
    	}
    }
    
    public function replaceListContent($key, $params){
        if(empty($this->Template)){
            return false;
        }else{
            $Template = $this->Template;
            preg_match_all("/\[{$key}\](.*?)\[\/{$key}\]/is", $Template, $content);
            foreach ($content[1] as $k => $v){
                if($params === false || empty($params)){
                    $Template = str_replace($content[0][$k], '', $Template);
                }else{
                    $template_new = '';
                    foreach($params as $k1 => $v1){
                        $template_new_replace = $v;
                        foreach($v1 as $k2 => $v2){
                            $template_new_replace =  str_replace('['.$k2.']', $v2, $template_new_replace);
                        }
                        $template_new = $template_new . $template_new_replace;
                    }
                    $Template = str_replace($content[0][$k], $template_new, $Template);
                }
            }
            $this->Template = $Template;
            return true;
        }
    }

}

?>