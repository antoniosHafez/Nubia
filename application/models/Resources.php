<?php

class Application_Model_Resources extends Zend_Db_Table_Abstract
{
    protected $_name = "Resources";
    
    public function addResource($module, $controller, $action, $name) {
        $row = $this->createRow();
        
        $date = new Zend_Date(); 			
        $createdDate= $date->get('YYYY-MM-dd HH:mm:ss');
        
        $row->module = $module;
        $row->controller = $controller;
        $row->action = $action;
        $row->name = $name;
        $row->created = $createdDate;
        $row->modified = $createdDate;
        
        $row->save();
    }
    
    public static function getAll() {
        $obj = new Application_Model_Resources();
        return $obj->fetchAll()->toArray();
    }
    
    public function truncate()
    {
        $this->getAdapter()->query('TRUNCATE TABLE `' . $this->_name . '`');

        return $this;
    }

}

