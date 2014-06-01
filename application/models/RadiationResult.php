<?php

class Application_Model_RadiationResult extends Zend_Db_Table_Abstract
{
    protected $_name = "radiation_result";
    
    function addRadiationResult($radiationData) {
        $row = $this->createRow();
        $row->radiation_id = $radiationData['radiationId'];
        $row->visit_request_id = $radiationData['requestId'];
        
        $row->save();
    }
    
    function viewRadiationResult($radiationId, $requestId) {
        $select = $this->select()->from("$this->_name")->from("radiation",array("radiation_result.*","radiationName"=>"name"))->setIntegrityCheck(false)->where("radiation_id=".$radiationId)->where("visit_request_id=".$requestId)->where("radiation.id=radiation_result.radiation_id");
        return $this->fetchAll($select)->toArray();
    }
    
    function getAllradiationResults() {
        $select = $this->select()->from("$this->_name")->from("radiation",array("radiation_result.*","radiationName"=>"name"))->setIntegrityCheck(false)->where("radiation.id=radiation_result.radiation_id");
        return $this->fetchAll($select)->toArray();
    }
    
    function editRadiationResult($radiationId, $requestId, $radiationData) {
        $this->update($radiationData, "radiation_id= $radiationId && visit_request_id= $requestId");
    }
    
    function deleteRadiationResult($radiationId, $requestId) {
        $this->delete("visit_request_id=".$requestId." AND radiation_id=".$radiationId);
    }
    
    function checkDuplication($id, $requestId, $radiationId) {
        $radiationDuplicatesValidator = new Zend_Validate_Db_RecordExists(array('table' => $this->_name, 'field' => 'radiation_id','exclude' => array('field' => 'id','value' => $id)));
        $radiationDuplicate = $radiationDuplicatesValidator->isValid($radiationId);
        
        $requestDuplicatesValidator = new Zend_Validate_Db_RecordExists(array('table' => $this->_name, 'field' => 'visit_request_id', 'exclude' => array('field' => 'id','value' => $id)));
        $requestDuplicate = $requestDuplicatesValidator->isValid($requestId);
        
        return ($radiationDuplicate && $requestDuplicate ? true : false);
    }
    
    function searchRadiationResults($requestId) {
        $select = $this->select()->from("$this->_name")->from("radiation",array("radiation_result.*","radiationName"=>"name"))->setIntegrityCheck(false)->where("radiation.id=radiation_result.radiation_id")->where("visit_request_id=".$requestId);
        return $this->fetchAll($select)->toArray();
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
        return $this->fetchAll($select)->toArray();
    }
    
     function addRadResultForVisit($data)
    {
        $this->insert($data);
    }
}
