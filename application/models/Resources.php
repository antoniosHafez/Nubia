<?php

class Application_Model_Resources extends Zend_Db_Table_Abstract
{
    protected $_name = "resources";
    
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
        $object = $obj->fetchAll();
        if($object) {
            return $object->toArray();
        }
        else {
            return NULL;
        }
    }
    
    public static function getAllGroupedByController() {
        $obj = new Application_Model_Resources();
        $select = $obj->select()->order("controller");
        
        if($select) {
            $controllers =  $obj->fetchAll($select)->toArray();
            $groupedController = array();
            
            foreach ( $controllers as $controller ) {
                $controllerName = $controller['controller'];
                $actionName = $controller['action'];
                $resourceId = $controller['id'];
                
                if(isset($groupedController[$controllerName])) {
                    array_push($groupedController[$controllerName], array("id" => $resourceId, "action" => $actionName));
                }
                else {
                    $groupedController[$controllerName] = array(array("id" => $resourceId, "action"=> $actionName));
                }
            }  
            return $groupedController;
        }
        else {
            return NULL;
        }
    }
    
    public function truncate()
    {
        $this->getAdapter()->query('SET FOREIGN_KEY_CHECKS=0');
        $roleResourceModel = new Application_Model_RoleResources();
        $roleResourceModel->truncate();
        
        $this->getAdapter()->query('TRUNCATE TABLE `' . $this->_name . '`');
        $this->getAdapter()->query('SET FOREIGN_KEY_CHECKS=1');
    }

}

