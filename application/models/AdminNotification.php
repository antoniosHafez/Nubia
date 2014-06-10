<?php

class Application_Model_AdminNotification extends Zend_Db_Table_Abstract
{
    protected $_name = "notification_admin";
    
    function getNotificationNum() {
        $select = $this->select()->where("status IS NULL");
        $rows = $this->fetchAll($select)->toArray();
        
        if($rows) {
            return count($rows);
        }
        else {
            return "noNew";
        }
    }
    
    function getNotification() {
        $select = $this->select()
                  ->from("$this->_name",array('adminStatus'=>'status','*'))
                  ->joinInner("person", "person.id = user_created_id")
                  ->order("date DESC")
                  ->setIntegrityCheck(false)
                  ->where("person.id=user_created_id");        
        
        $rows = $this->fetchAll($select)->toArray();
        
        if($rows) {
            return $rows;
        }
        else {
            "noNew";
        }
    }
    
    function setNotificationAdminSeen() {
         $this->getAdapter()->query("UPDATE $this->_name SET status='seen'");
    }

}

