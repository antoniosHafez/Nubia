<?php

class Application_Model_Role extends Zend_Db_Table_Abstract
{
    protected $_name = "roles";
    
    public static function getAll() {
        $obj = new Application_Model_Role();
        $object = $obj->fetchAll();
        if($object) {
            return $object->toArray();
        }
        else {
            return NULL;
        }        
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
    
    function getRolesNames(){
        $select = $this->select()
                ->from("roles",array("id","name"));
        $row =  $this->fetchAll($select);        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }

    public function getUserType($roleId) {
        $select = $this->select()->where("id=$roleId");
        
        $row = $this->fetchRow($select);
        if($row)
        {
            $row->toArray();
            return $row['name'];
        }
        else
            return NULL;
        
    }
    
    function addRole($roleData) {
        $row = $this->createRow();
        $row->name = $roleData['typeName'];
        
        $row->save();
    }
    
    function getAllRoles() {
        $row =  $this->fetchAll();
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }
    
    function editRole($roleId,$roleData) {
        $this->update($roleData, "id= $roleId");
    }
    
    function deleteRole($roleId) {
        $this->delete("id=$roleId");
    }
    
    function viewRole($roleId) {
        $select = $this->select()->where('id = ?', $roleId);
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }
    
    function checkDuplication($roleId, $roleName) {
        $hasDuplicatesValidator = new Zend_Validate_Db_RecordExists(
                array(
                    'table' => $this->_name,
                    'field' => 'name',
                    'exclude' => array(
                                        'field' => 'id',
                                        'value' => $roleId
                                       )
                    )
        );
        $hasDuplicates = $hasDuplicatesValidator->isValid($roleName);
        return ($hasDuplicates ? true : false);
    }
    
    function getRoleCount() {
        $rows = $this->select()->from($this->_name,'count(*) as count')->query()->fetchAll();
        
        return($rows[0]['count']);
    }
    
    function getRolesStatistics() {
        $count = $this->getRoleCount();
        
        return array('count'=>$count);
    }
}

