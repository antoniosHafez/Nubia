<?php

class Application_Model_RoleResources extends Zend_Db_Table_Abstract
{
    protected $_name = "RoleResources";
    
    public static function getAll() {
        $obj = new Application_Model_RoleResources();
        $select = $obj->select()
                  ->from("$obj->_name")
                  ->from("Roles",array("Roles.name"))
                  ->from("Resources",array("Resources.module", "Resources.controller", "Resources.action"))
                  ->setIntegrityCheck(false)
                  ->where("roleId=Roles.id")
                  ->where("resourceId=Resources.id");

        //return $obj->fetchAll($select)->toArray();
        $object = $obj->fetchAll($select);
        if($object) {
            return $object->toArray();
        }
        else {
            return NULL;
        }        
    }
    
    public static function isAllowed($roleId, $resourceId) {
        $obj = new Application_Model_RoleResources();
        
        $select = $obj->select()->where("roleId=$roleId && resourceId=$resourceId");
        
        $isAllowed = $obj->fetchRow($select);
        
        return $isAllowed? TRUE : FALSE;
        
    }
    
    public function addRoleResources($params) {
        $this->truncate();
        foreach ($params as $key => $param) {
            if($key != "controller" && $key != "module" && $key != "action") {
                $roleResource = explode("::", $key);

                $roleId = $roleResource[0];
                $resourceId = $roleResource[1];
                
                $row = $this->createRow();
        
                $date = new Zend_Date(); 			
                $createdDate= $date->get('YYYY-MM-dd HH:mm:ss');

                $row->roleId = $roleId;
                $row->resourceId = $resourceId;
                $row->created = $createdDate;
                $row->modified = $createdDate;

                $row->save();
            }
        }
    }
    
    public function truncate()
    {
        $this->getAdapter()->query('TRUNCATE TABLE `' . $this->_name . '`');

        return $this;
    }

}
