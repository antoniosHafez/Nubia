<?php

class Application_Model_VitalResult extends Zend_Db_Table_Abstract
{
    protected $_name = "vital_result";
    
    function addVitalResult($vitalData) {
        $row = $this->createRow();
        $row->vital_id = $vitalData['vitalId'];
        $row->visit_request_id = $vitalData['requestId'];
        
        $row->save();
    }
    
    function viewVitalResult($vitalId, $requestId) {
        $select = $this->select()->from("$this->_name")->from("vital",array("vital_result.*","vitalName"=>"name"))->setIntegrityCheck(false)->where("vital_id=".$vitalId)->where("visit_request_id=".$requestId)->where("vital.id=vital_result.vital_id");
        //return $this->fetchAll($select)->toArray();
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function getAllvitalResults() {
        $select = $this->select()->from("$this->_name")->from("vital",array("vital_result.*","vitalName"=>"name"))->setIntegrityCheck(false)->where("vital.id=vital_result.vital_id");
        //return $this->fetchAll($select)->toArray();
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function editVitalResult($vitalId, $requestId, $vitalData) {
        $this->update($vitalData, "vital_id= $vitalId && visit_request_id= $requestId");
    }
    
    function deleteVitalResult($vitalId, $requestId) {
        $this->delete("visit_request_id=".$requestId." AND vital_id=".$vitalId);
    }
    
    function checkDuplication($id, $requestId, $vitalId) {
        $vitalDuplicatesValidator = new Zend_Validate_Db_RecordExists(array('table' => $this->_name, 'field' => 'vital_id','exclude' => array('field' => 'id','value' => $id)));
        $vitalDuplicate = $vitalDuplicatesValidator->isValid($vitalId);
        
        $requestDuplicatesValidator = new Zend_Validate_Db_RecordExists(array('table' => $this->_name, 'field' => 'visit_request_id','exclude' => array('field' => 'id','value' => $id)));
        $requestDuplicate = $requestDuplicatesValidator->isValid($requestId);
        
        return ($vitalDuplicate && $requestDuplicate ? true : false);
    }
    
    function searchVitalResults($requestId) {
        $select = $this->select()->from("$this->_name")->from("vital",array("vital_result.*","vitalName"=>"name"))->setIntegrityCheck(false)->where("vital.id=vital_result.vital_id")->where("visit_request_id=".$requestId);
        //return $this->fetchAll($select)->toArray();
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function getVitalResultsCount() {
        $rows = $this->select()->from($this->_name,'count(*) as count')->query()->fetchAll();
        
        return($rows[0]['count']);
    }
    
    function getVitalResultsStatistics() {
        $count = $this->getVitalResultsCount();
        
        return array('count'=>$count);
    }
    
    function viewAllVitalResult($requestId) {
        $select = $this->select()->from("$this->_name")->from("vital",array("vital_result.*","vitalName"=>"name"))->setIntegrityCheck(false)->where("visit_request_id=".$requestId)->where("vital.id=vital_result.vital_id");
        //return $this->fetchAll($select)->toArray();
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    
     function addvitResultForVisit($data)
    {
        $this->insert($data);
    }
    
}
