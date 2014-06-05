<?php

class Application_Model_User extends Zend_Db_Table_Abstract
{
    protected $_name='user';
    
    function addUser($userData){
        return $this->insert($userData);
    }
    
    function editUser($userData, $userId){
        return $this->update($userData, "user.id = $userId");
    }
    
    function getUserById($userId){
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('u' => 'user'))
                ->join(array('p' => 'person'), 'p.id = u.id')
                ->join(array('r' => 'Roles'),'u.role_id = r.id',array("role" => "r.name"))
                ->where("u.id = $userId");
        //return $this->fetchRow($select)->toArray();
        $row =  $this->fetchRow($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    function searchUsersByEmailRole($userEmail, $userRole){
        if($userRole == "all"){
            $condition = "user.email like '$userEmail%'";
        }else{
            $condition = "user.email like '$userEmail%' and role_id=$userRole";
        }
        $select = $this->select()->where($condition);
        //return $this->fetchRow($select)->toArray();  
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }

    function adminSearchUsersByEmailRole(){
        
    }
            
    function listUsers(){
        $select = $this->select()
       ->setIntegrityCheck(false)
       ->from(array('u' => 'user'))
       ->join(array('p' => 'person'),'p.id = u.id')
       ->join(array('r' => 'Roles'),'u.role_id = r.id',array("role" => "r.name"));       
       //return $this->fetchAll($select)->toArray();   
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function deleteUser($userId){
        return $this->delete("id=$userId"); 
    }
}

