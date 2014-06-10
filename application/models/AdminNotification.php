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
        $select = $this->select();
        $rows = $this->fetchAll($select)->toArray();
        
        if($rows) {
            return $rows;
        }
        else {
            "noNew";
        }
    }

}

