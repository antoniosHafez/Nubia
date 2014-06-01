<?php

class Application_Model_Physiciangroup extends Zend_Db_Table_Abstract
{

    
        protected $_name= "group";
    function addPhysiciangroup($physiciangroupName){
        
        $row = $this->createRow();
        $row->name = $physiciangroupName["name"];
        $row->save();
        # $this->insert( $physiciangroupName, $physiciangroupId);
    }
    
    function getAllPhysiciansgroup(){
        
        $row =  $this->fetchAll();
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
        
    }
    
    function editPhysiciangroup($physiciangroupId,$physiciangroupData){
        
        $this->update( $physiciangroupData, "id=$physiciangroupId");
            
    }
    
     function deletePhysiciangroup($physiciangroupId) {
        $this->delete("id=$physiciangroupId");
    }
    
    
    
    function viewPhysiciangroup($physiciangroupId){
        
        $select = $this->select()->where("id = $physiciangroupId");
        $row =  $this->fetchRow($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
        
    }
    
    function checkDuplication($physiciangroupName) {
        $hasDuplicatesValidator = new Zend_Validate_Db_RecordExists(array('table' => $this->_name,'field' => 'name'));
        $hasDuplicates = $hasDuplicatesValidator->isValid($physiciangroupName);
        return ($hasDuplicates ? true : false);
    }
    
    function getPhysiciangroupCount() {
        $rows = $this->select()->from($this->_title,'count(*) as count')->query()->fetchAll();
        
        return($rows[0]['count']);
    }
    
    
    function searchByName($physicianKey){
        
        $select = $this->select()->where('title LIKE ?', $vitalKey);
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
        
    }
    function searchById($physicianKey){
        
        $select = $this->select()->where("id=$physicianKey");
        $row =  $this->fetchRow($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
        
    }
    
    
     function getPhysiciansgroupStatistics() {
        $count = $this->getPhysiciangroupCount();
        
        return array('count'=>$count);
    }


}

