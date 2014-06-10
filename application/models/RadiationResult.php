<?php

class Application_Model_RadiationResult extends Zend_Db_Table_Abstract
{
    protected $_name = "radiation_result";
    
    function addRadiationResult($radiationData) {
        $row = $this->createRow();
        $row->radiation_id = $radiationData['radiationId'];
        $row->visit_request_id = $radiationData['requestId'];
        
        return $row->save();
    }
    
    function viewRadiationResult($radiationId, $requestId) {
        $select = $this->select()->from("$this->_name")->from("radiation",array("radiation_result.*","radiationName"=>"name"))->setIntegrityCheck(false)->where("radiation_id=".$radiationId)->where("visit_request_id=".$requestId)->where("radiation.id=radiation_result.radiation_id");
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }
    
    function getAllradiationResults() {
        $select = $this->select()->from("$this->_name")->from("radiation",array("radiation_result.*","radiationName"=>"name"))->setIntegrityCheck(false)->where("radiation.id=radiation_result.radiation_id");
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }
    
    function editRadiationResult($radiationId, $requestId, $radiationData) {
        $this->update($radiationData, "radiation_id= $radiationId && visit_request_id= $requestId");
    }
    
    function deleteRadiationResult($radiationId, $requestId) {
        $this->delete("visit_request_id=".$requestId." AND radiation_id=".$radiationId);
    }
    
    function checkDuplication($id, $requestId, $radiationId) {
        $radiationDuplicatesValidator = new Zend_Validate_Db_RecordExists(array('table' => $this->_name, 'field' => 'radiation_id','exclude' => array('field' => 'id','value' => $id)));
        $radiationDuplicate = $radiationDuplicatesValidator->isValid($radiationId." AND visit_request_id == $requestId");        
        echo $radiationDuplicatesValidator;
        exit;
        return ($radiationDuplicate ? true : false);
    }
    
    function searchRadiationResults($requestId) {
        $select = $this->select()->from("$this->_name")->from("radiation",array("radiation_result.*","radiationName"=>"name"))->setIntegrityCheck(false)->where("radiation.id=radiation_result.radiation_id")->where("visit_request_id=".$requestId);
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }
    
    function getRadiationResultsCount() {
        $rows = $this->select()->from($this->_name,'count(*) as count')->query()->fetchAll();
        
        return($rows[0]['count']);
    }
    
    function getRadiationResultsStatistics() {
        $count = $this->getRadiationResultsCount();
        
        return array('count'=>$count);
    }
    
    function viewAllRadiationResult($requestId) {
        $select = $this->select()->from("$this->_name")->from("radiation",array("radiation_result.*","radiationName"=>"name"))->setIntegrityCheck(false)->where("visit_request_id=".$requestId)->where("radiation.id=radiation_result.radiation_id");
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }
    
     function addRadResultForVisit($data)
    {
        $this->insert($data);
    }
    
    function getRadiationResultByVisitIDAndType($visitID, $type = 'pre')
    {
        $cond = "radiation_result.visit_request_id = $visitID";
        $select = $this->select()->from("$this->_name")
                ->from("radiation",array("radiationName"=>"name"))
                ->where("radiation_data IS NULL")
                ->where("type = '$type'")
                ->where($cond)
                ->setIntegrityCheck(false)
                ->where("radiation.id=radiation_result.radiation_id");
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }
}
