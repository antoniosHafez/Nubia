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
        $select = $this->select()->where("user.id = $userId");
        return $this->fetchRow($select)->toArray();
    }
    function searchUserByEmail($userEmail){
        $select = $this->select()->where("user.email = '$userEmail'");
        return $this->fetchRow($select)->toArray();        
    }

    function listUsers(){
        $select = $this->select()
       ->setIntegrityCheck(false)
       ->from(array('u' => 'user'))
       ->join(array('p' => 'person'),'p.id = u.id');       
       return $this->fetchAll($select)->toArray();       
    }
    
    function deleteUser($userId){
        return $this->delete("id=$userId"); 
    }
}

