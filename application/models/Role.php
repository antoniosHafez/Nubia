<?php

class Application_Model_Role extends Zend_Db_Table_Abstract
{
    protected $_name = "Roles";
    
    public static function getAll() {
        $obj = new Application_Model_Role();
        $object = $obj->fetchAll();
        if($object) {
            return $object->toArray();
        }
        else {
            return NULL;
        }        
        //return $obj->fetchAll()->toArray();
    }
    
    public function getFullRoles() {
        $fullRoles = array();
        $roles = self::getAll();
        $resources = Application_Model_Resources::getAll();
        
        foreach ( $roles as $role ) {
            $fullRole = array();
            $fullRole['id'] = $role['id'];
            $fullRole['name'] = $role['name'];
            
            $fullResources = array();
            foreach ( $resources as $resource ) {
                $controller = $resource['controller'];
                $action = $resource['action'];
                $resourceId = $resource['id'];
                
                $isAllowed = Application_Model_RoleResources::isAllowed($role['id'], $resourceId);
                
                if(isset($fullResources[$controller]))  {
                    array_push($fullResources[$controller], array('id'=>$resourceId, 'name'=>$action, 'status'=>$isAllowed));
                }
                else {
                    $fullResources[$controller] = array(array('id'=>$resourceId, 'name'=>$action, 'status'=>$isAllowed));
                }
            }
            $fullRole['resources'] = $fullResources;
            array_push($fullRoles, $fullRole);
             
        }
        return $fullRoles;
    }

}

