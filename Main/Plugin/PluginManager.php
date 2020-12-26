<?php

namespace CloudTowerIDC\Plugin;

use CloudTowerIDC\Main\Main;

class PluginManager{
    
    public $path = BASE_ROOT.'/Plugins/';
    public $dataFolder = BASE_ROOT .'/PluginData/';
    public $pageFolder = BASE_ROOT .'/PluginTemplates/';
    public $PluginClass = array();
    public $Plugins = array();
    public $PluginType = array();
    public $PluginPage = array();
    public $PluginPath = array();
    
    private $Database;
    
    public function __construct(private Main $System){
        $this->Database = $this->System->getDatabase();
    }
    
    public function loadPluginFiles(){
        if($handle = opendir($this->path)){
            $pluginsconfig = array();
            while(false !== ($entry = readdir($handle))){
                if($entry != '.' && $entry != '..' && is_dir($this->path .'/'. $entry)){
                    if(file_exists($this->path.'/'. $entry.'/plugin.json')){
                        $config = json_decode(file_get_contents($this->path.'/'. $entry.'/plugin.json'),true);
                        $pluginsconfig[$config['priority']][] = array(
                            'config' => $config,
                            'entry' => $entry,
                        );
                    }
                }
            }
            for($i = 100; $i >= 1; $i--){
                $priority = $i;
                if(!empty($pluginsconfig[$priority])){
                    if(is_array($pluginsconfig[$priority])){
                        foreach($pluginsconfig[$priority] as $k => $v){
                            $mainClass = $v['config']['main'];
                            $pluginPath = str_replace('\\', '/', $mainClass);
                            $mainPath = $this->path . '/' . $v['entry'] . '/src/' . $pluginPath .'.php';
                            if(!file_exists($mainPath) || !is_file($mainPath)){
                                $this->System->getLogger()->newCrashDump('无法加载插件', 'Cannot load plugins '.$v['config']['name'].' due to the incorrect or non existed path of the plugin main file, main file should be '.$mainPath);
                                exit('CloudTowerIDC:加载插件出错'.$mainPath);
                            }else{
                                require_once($mainPath);
                                $this->PluginClass[$v['config']['name']] = $v['config']['main'];
                                if(!empty($v['config']['page'])){
                                    $PagePath = str_replace('\\', '/', $v['config']['page']);
                                    $PagePath = $this->path . '/' . $v['entry'] . '/src/' . $PagePath . '.php';
                                    if(!file_exists($PagePath) || !is_file($PagePath)){
                                        $this->System->getLogger()->newCrashDump('无法加载插件', 'Cannot load plugins '.$v['config']['name'].'\'s page due to the incorrect or non existed path of the plugin page file, main file should be '.$PagePath);
                                        exit('CloudTowerIDC:加载插件出错'.$PagePath);
                                    }else{
                                        require_once($PagePath);
                                        $this->PluginPage[$v['config']['name']] = $v['config']['page'];
                                    }
                                }
                                if(empty($v['config']['type'])){
                                    $this->System->getLogger()->newCrashDump('无法加载插件', 'Cannot load plugins '.$v['config']['name'].' due to the empty type set in the plugin config file');
                                    exit('CloudTowerIDC:加载插件出错');
                                }
                                if(!in_array($v['config']['type'], array('FUNCTION','SERVER','PAYMENT'))){
                                    $this->System->getLogger()->newCrashDump('无法加载插件', 'Cannot load plugins '.$v['config']['name'].' due to the unknown type set in the plugin config file');
                                    exit('CloudTowerIDC:加载插件出错');
                                }
                                if($v['config']['api'] < $this->getSupportedApiVersion()){
                                    $this->System->getLogger()->newCrashDump('无法加载插件', 'Cannot load plugins '.$v['config']['name'].' due to the unsupoorted api version.');
                                    exit('CloudTowerIDC:加载插件出错');
                                }
                                $this->PluginType[$v['config']['type']][] = $v['config']['name'];
                                $this->PluginPath[$v['config']['name']] = $this->path . '/'. $v['entry'];
                            }
                        }
                    }
                }
            }
        }else{
            $this->System->getLogger()->newCrashDump('无法加载插件', 'Cannot load plugins due to the error open can\'t opening the directory of the plugins,please check if there is enough permission');
            exit('CloudTowerIDC:加载插件出错');
        }
    }

    public function loadPlugins(){
        $this->loadPluginFiles();
        foreach ($this->PluginClass as $k => $v){
            $pluginDataFolder = $this->dataFolder . $k;
            $pluginPageFolder = $this->pageFolder . $k;
            if(file_exists($pluginDataFolder) && !is_dir($pluginDataFolder)){
                $this->System->getLogger()->newCrashDump('无法加载插件', 'Projected plugin '.$k.' data folder cause to an error.');
                exit('CloudTowerIDC:加载插件出错');
            }
            if(!file_exists($pluginDataFolder)){
                mkdir($pluginDataFolder, 0755, true);
            }
            if(!file_exists($pluginPageFolder)){
                mkdir($pluginPageFolder, 0755, true);
            }
            try {
                $plugin = new $v($this->System, $pluginDataFolder, $pluginPageFolder, $this->PluginPath[$k], $k);
                $plugin->onLoad();
                $this->Plugins[$k] = $plugin;
            } catch (Error $e){
                $this->System->getLogger()->newCrashDump('无法加载插件', 'Plugin Error caught:'.$e->getMessage());
                exit('CloudTowerIDC:加载插件出错');
            }
        }
        return true;
    }
    
    public function loadEvent($name, $event){
        foreach ($this->Plugins as $k => $v) {
            try {
                if(method_exists($v, $name)){
                    $v->$name($event);
                }
            } catch (Exception $e) {
                $this->System->getLogger()->newCrashDump('插件运行出错', 'ErrorFile'.$e->getFile().'\n\r ErrorLine'.$e->getFile().'\n\r ErrorMessage:'.$e->getMessage());
                exit('CloudTowerIDC:插件运行出错');
            }
        }
    }
    
    public function loadEventByPlugin($name, $event, $pluginName){
        try {
            if(method_exists($this->Plugins[$pluginName], $name)){
                $this->System->getLogger()->addSystemLog('系统请求使用插件'.$pluginName.'执行了'.$name.'事件');
                return $this->Plugins[$pluginName]->$name($event);
            } else {
                return false;
            }
        } catch (Exception $e) {
            $this->System->getLogger()->newCrashDump('插件运行出错', 'ErrorFile'.$e->getFile().'\n\r ErrorLine'.$e->getFile().'\n\r ErrorMessage:'.$e->getMessage());
            exit('CloudTowerIDC:插件运行出错');
        }
    }
    
    public function getPlugins($type = "all"){
        switch ($type) {
            case 'SERVER':
                if(!empty($this->PluginType['SERVER'])){
                    return $this->PluginType['SERVER'];
                }else{
                    return false;
                }
            break;
            case 'PAYMENT':
                if(!empty($this->PluginType['PAYMENT'])){
                    return $this->PluginType['PAYMENT'];
                }else{
                    return false;
                }
            break;
            case 'FUNCTION':
                if(!empty($this->PluginType['FUNCTION'])){
                    return $this->PluginType['FUNCTION'];
                }else{
                    return false;
                }
            break;
            default:
                foreach($this->Plugins as $k => $v){
                    $return[] = $k;
                }
                return $return;
            break;
        }
    }
    
    public function PluginLoaded($name){
        if(empty($this->Plugins[$name])){
            return false;
        }else{
            return true;
        }
    }
    
    public function getPlugin($name){
        if(empty($this->Plugins[$name])){
            return false;
        }else{
            return $this->Plugins[$name];
        }
    }
    
    public function PageRegistered($Plugin){
        if(!empty($this->PluginPage[$Plugin])){
            return true;
        }else{
            return false;
        }
    }
    
    public function loadPluginPage($Plugin){
        if($this->PageRegistered($Plugin)){
            $PageClass = $this->PluginPage[$Plugin];
            return new $PageClass($this->Plugins[$Plugin]);
        }else{
            return false;
        }
    }
    
    public function getPluginPath($plugin){
        return $this->PluginPath[$plugin];
    }

    public function getSupportedApiVersion(){
        return '3.0.2';
    }

    public function getApiVersion(){
        return '3.0.3';
    }
    
    public function getPluginConfig($plugin){
        if(file_exists(BASE_ROOT.'PluginData/'.$plugin.'/config.json')){
            $config = json_decode(file_get_contents(BASE_ROOT.'/PluginData/'.$plugin.'/config.json'), true);
            return $config;
        }else{
            return false;
        }
    }
    
    public function setPluginConfig($plugin, $config){
        if(file_exists(BASE_ROOT.'/PluginData/'.$plugin.'/config.json')){
            if(is_array($config)){
                $config = json_encode($config, JSON_UNESCAPED_UNICODE);
                file_put_contents(BASE_ROOT.'/PluginData/'.$plugin.'/config.json', $config);
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

}

?>