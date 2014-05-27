<?php

class Application_Model_TestResult extends Zend_Db_Table_Abstract
{
    protected $_name = "test_result";
    
    function addTestResult($testData) {
        $row = $this->createRow();
        $row->test_id = $testData['testId'];
        $row->visit_request_id = $testData['requestId'];
        
        $row->save();
    }
    
    function viewTestResult($testId, $requestId) {
        $select = $this->select()->from("$this->_name")->from("test",array("test_result.*","testName"=>"name"))->setIntegrityCheck(false)->where("test_id=".$testId)->where("visit_request_id=".$requestId)->where("test.id=test_result.test_id");
        return $this->fetchAll($select)->toArray();
    }
    
    function getAlltestResults() {
        $select = $this->select()->from("$this->_name")->from("test",array("test_result.*","testName"=>"name"))->setIntegrityCheck(false)->where("test.id=test_result.test_id");
        return $this->fetchAll($select)->toArray();
    }
    
    function editTestResult($testId, $requestId, $testData) {
        $this->update($testData, "test_id= $testId && visit_request_id= $requestId");
    }
    
    function deleteTestResult($testId, $requestId) {
               
        $this->delete("visit_request_id=".$requestId." AND test_id=".$testId);
    }
    
    function checkDuplication($id, $requestId, $testId) {
        $testDuplicatesValidator = new Zend_Validate_Db_RecordExists(array('table' => $this->_name, 'field' => 'test_id', 'exclude' => array('field' => 'id','value' => $id)));
        $testDuplicate = $testDuplicatesValidator->isValid($testId);
        
        $requestDuplicatesValidator = new Zend_Validate_Db_RecordExists(array('table' => $this->_name, 'field' => 'visit_request_id','exclude' => array('field' => 'id','value' => $id)));
        $requestDuplicate = $requestDuplicatesValidator->isValid($requestId);
        
        return ($testDuplicate && $requestDuplicate ? true : false);
    }
    
    function searchTestResults($requestId) {
        $select = $this->select()->from("$this->_name")->from("test",array("test_result.*","testName"=>"name"))->setIntegrityCheck(false)->where("test.id=test_result.test_id")->where("visit_request_id=".$requestId);
        return $this->fetchAll($select)->toArray();
    }
    
    function getTestResultsCount() {
        $rows = $this->select()->from($this->_name,'count(*) as count')->query()->fetchAll();
        
        return($rows[0]['count']);
    }
    
    function getTestResultsStatistics() {
        $count = $this->getTestResultsCount();
        
        return array('count'=>$count);
    }
}
