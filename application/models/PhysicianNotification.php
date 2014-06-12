<?php

class Application_Model_PhysicianNotification extends Zend_Db_Table_Abstract
{
    protected $_name = "notification_physician";
    private $avail_visit_id;
    
    function getNotificationNum($groupId) {
        $select = $this->select()->where("status IS NULL AND group_id=$groupId");
        $rows = $this->fetchAll($select)->toArray();
        
        if($rows) {
            return count($rows);
        }
        else {
            return "noNew";
        }       
    }
    
    function getNotification(){
        $select = $this->select();
        $rows = $this->fetchAll($select)->toArray();
        
        if($rows) {
            return $rows;
        }
        else {
            "noNew";
        }
    }
    
    
    function getVisitID()
    {
   $fc = Zend_Controller_Front::getInstance();
        $url =  $fc->getBaseUrl();    
        $data = file_get_contents($url+"/vid.json");
       
        

        return $this->$data;
    } 
    
    function setNotificationPhysicianSeen() { 
        $this->getAdapter()->query("UPDATE $this->_name SET status='seen'");
        //$this->update("status='seen'");
    }
    
    function getNotificationsByGroupId($groupId){
        $select = $this->select()->where("group_id=$groupId");
        $rows = $this->fetchAll($select)->toArray();
        
        if($rows) {
            return $rows;
        }
        else {
            "noNew";
        }
    }
    
    function addVisitID($visitID)
    {
        
    }
    

}

