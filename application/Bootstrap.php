<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initPlugins() {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Access_');
        
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Access_Controller_Plugin_ACL(), 1);
        
            
    }

}

