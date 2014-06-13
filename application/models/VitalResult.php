<?php

class Application_Model_VitalResult extends Zend_Db_Table_Abstract
{
    protected $_name = "vital_result";
    
    function addVitalResult($vitalData,$userID = NULL,$type = NULL) {
        $row = $this->createRow();
        $row->vital_id = $vitalData['vitalId'];
        $row->visit_request_id = $vitalData['requestId'];
        $row->type = $type;
        $row->user_modified_id = $userID;
        
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
    
   
     function getVitalResultByVisitID($VisitID)
    {
        
        $cond = "vital_result.visit_request_id = $VisitID";
        $select = $this->select()->from("vital_result",array("data" => "vital_data"))->
                setIntegrityCheck(FALSE)->
                joinInner("vital", "vital.id = vital_result.vital_id",
                        array("vital_name" => "vital.name"))->
                where($cond);
        //return $this->fetchAll($select)->toArray();
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function getVitalResultByVisitIDAndType($visitID, $type = 'pre')
    {
        $cond = "vital_result.visit_request_id = $visitID";
        $select = $this->select()->from("$this->_name")
                ->from("vital",array("vitalName"=>"name"))
                ->where("vital_data IS NULL")
                ->where("type = '$type'")
                ->where($cond)
                ->setIntegrityCheck(false)
                ->where("vital.id=vital_result.vital_id");
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }
}
