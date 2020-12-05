<?php

namespace CloudTowerIDC\Page;

use CloudTowerIDC\Template\Template;
use CloudTowerIDC\Logger\Logger;
use CloudTowerIDC\Product\Product;
use CloudTowerIDC\ProductGroup\ProductGroup;
use CloudTowerIDC\User\User;

use CloudTowerIDC\Events\TemplateLoadEvent;

class Cart extends Page{
    
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

    public function Index(){
        $this->getTemplate()->setTemplateFile('Cart');
        $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
        if(!$this->getTemplateEvent()->isCancelled()){
            $ProductGroup = $this->getSystem()->getProductGroups();
            if($ProductGroup === false){
                $template = $this->getTemplate()->replaceListContent('ProductGroupList', array());
            }else{
                $template = $this->getTemplate()->replaceListContent('ProductGroupList', $ProductGroup);
            }
            if(empty($this->getSystem()->getGetParams()['gid'])){
                $Group = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_group` WHERE `status`='1' ORDER BY `weight` DESC");
                $Group = new ProductGroup($Group['id'], $this);
            }else{
                $Group = new ProductGroup($this->getSystem()->getGetParams()['gid'], $this);
                if($Group->isExisted() === false){
                    $Group = $this->getSystem()->getDatabase()->get_row("SELECT * FROM `ytidc_group` WHERE `status`='1' ORDER BY `weight` DESC");
                    $Group = new ProductGroup($Group['id'], $this);
                }
            }
            $Product = $Group->getProducts();
            $Products = array();
            if(is_array($Product)){
                foreach($Product as $k => $v){
                    $Products[$k] = $v;
                    $vperiod = array_shift(json_decode($v['period'], true));
                    $Products[$k]['periodname'] = $vperiod['name'];
                    $Products[$k]['periodprice'] = $vperiod['price'];
                }
            }
            if($Product === false){
                $this->getTemplate()->replaceListContent('ProductList', array());
            }else{
                $this->getTemplate()->replaceListContent('ProductList', $Products);
            }
            $template_code = array(
                'config' => $this->getSystem()->getConfigAll(),
                'template' => $this->getSystem()->getTemplateCustom(),
                'group' => $Group->getAll(),
            );
            $this->getTemplate()->setTemplateCode($template_code);
            echo $this->getTemplate()->outputTemplate();
        }
    }
    
    public function goMsg($msg){
        @header("Location: ./index.php?p=Clientarea&a=Msg&msg={$msg}");
        exit;
    }
    
    public function Buy(){
        if(empty($this->getSystem()->getGetParams()['pid'])){
            $this->goMsg('请先选择产品');
        }else{
            $Product = new Product($this->getSystem()->getGetParams()['pid'] ,$this);
            if($Product->isExisted() === false || $Product->getStatus() != 1){
                $this->goMsg('产品不存在或不允许开通');
            }else{
                $this->getTemplate()->setTemplateFile('Buy');
                $this->getPluginManager()->loadEvent('onTemplateLoad', $this->getTemplateEvent());
                if(!$this->getTemplateEvent()->isCancelled()){
                    $CustomOption = $Product->getCustomOption();
                    if($CustomOption === false){
                        $this->getTemplate()->replaceListContent('CustomOptionList', array());
                    }else{
                        $this->getTemplate()->replaceListContent('CustomOptionList', $CustomOption);
                    }
                    $Period = $Product->getPeriod();
                    if($this->checkLogin() === true){
                        $Priceset = $this->getUser()->getPriceset();
                        if($Priceset !== false){
                            $Price = $Priceset->getPrice()[$Product->getId()];
                            if(empty($Price)){
                                $Price = $this->getUser()->getPriceset()->getPrice()['*'];
                                if(empty($Price)){
                                    $Price = 100;
                                }
                            }
                        }else{
                            $Price = 100;
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
                    $template_code = array(
                        'config' => $this->getSystem()->getConfigAll(),
                    'template' => $this->getSystem()->getTemplateCustom(),
                        'product' => $Product->getAll(),
                    );
                    $this->getTemplate()->setTemplateCode($template_code);
                    echo $this->getTemplate()->outputTemplate();
                }
            }
        }
    }
    
}

?>