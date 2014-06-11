<?php

class Application_Model_PhysicianNotification extends Zend_Db_Table_Abstract
{
    protected $_name = "notification_physician";
    private $avail_visit_id;
    
    function getNotificationNum($userId) {
        $select = $this->select()->where("status IS NULL");
        $rows = $this->fetchAll($select)->toArray();
        
        if($rows) {
            return count($rows);
        }
        else {
            return "no New";
        }       
    }
    
    function getAllNotifications(){
        $select = $this->select();
        $rows = $this->fetchAll($select)->toArray();
        
        if($rows) {
            return $rows;
        }
        else {
            "no New";
        }
    }
    
    
    function getVisitID()
    {
        $this->avail_visit_id[0]=5;
        $this->avail_visit_id[1]=0;
        return $this->avail_visit_id;
    }
    

}

