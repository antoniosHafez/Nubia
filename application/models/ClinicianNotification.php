<?php

class Application_Model_ClinicianNotification extends Zend_Db_Table_Abstract
{
    protected $_name = "notification_clinic";
    
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
                  ->from("$this->_name")
                  ->setIntegrityCheck(false)
                  ->joinInner("visit_request", "visit_request.id = visit_request_id")
                  ->order("status DESC");
        
        $rows = $this->fetchAll($select)->toArray();
        
        if($rows) {
            return $rows;
        }
        else {
            "noNew";
        }
    }
    
    function setNotificationClinicSeen() {
         $this->getAdapter()->query("UPDATE $this->_name SET status='seen'");
    }

}

