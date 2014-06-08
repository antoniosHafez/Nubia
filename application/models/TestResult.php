<?php

class Application_Model_TestResult extends Zend_Db_Table_Abstract
{
    protected $_name = "test_result";
    
    function addTestResult($testData) {
        $row = $this->createRow();
        $row->test_id = $testData['testId'];
        $row->visit_request_id = $testData['requestId'];
        
        return $row->save();
    }
    
    function viewTestResult($testId, $requestId) {
        $select = $this->select()->from("$this->_name")->from("test",array("test_result.*","testName"=>"name"))->setIntegrityCheck(false)->where("test_id=".$testId)->where("visit_request_id=".$requestId)->where("test.id=test_result.test_id");
        //return $this->fetchAll($select)->toArray();
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function getAlltestResults() {
        $select = $this->select()->from("$this->_name")->from("test",array("test_result.*","testName"=>"name"))->setIntegrityCheck(false)->where("test.id=test_result.test_id");
        //return $this->fetchAll($select)->toArray();
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function editTestResult($testId, $requestId, $testData) {
        $this->update($testData, "test_id= $testId && visit_request_id= $requestId");
    }
    
    function deleteTestResult($testId, $requestId) {
               
        $this->delete("visit_request_id=".$requestId." AND test_id=".$testId);
    }
    
    function checkDuplication($id, $requestId, $testId) {
        $select = $this->select()->where("test_id=$testId AND visit_request_id=$requestId AND id!=$id");
        
        $data = $this->fetchAll($select)->toArray();

        return ($data ? $data : false);
    }
    
    function searchTestResults($requestId) {
        $select = $this->select()->from("$this->_name")->from("test",array("test_result.*","testName"=>"name"))->setIntegrityCheck(false)->where("test.id=test_result.test_id")->where("visit_request_id=".$requestId);
        //return $this->fetchAll($select)->toArray();
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function getTestResultsCount() {
        $rows = $this->select()->from($this->_name,'count(*) as count')->query()->fetchAll();
        
        return($rows[0]['count']);
    }
    
    function getTestResultsStatistics() {
        $count = $this->getTestResultsCount();
        
        return array('count'=>$count);
    }
    
    function viewAllTestResult($requestId) {
        $select = $this->select()->from("$this->_name")->from("test",array("test_result.*","testName"=>"name"))->setIntegrityCheck(false)->where("visit_request_id=".$requestId)->where("test.id=test_result.test_id");
        //return $this->fetchAll($select)->toArray();
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
     function addTestResultForVisit($data)
    {
        $this->insert($data);
    }
    
    
     function getTestResultByVisitID($VisitID)
    {
        
        $cond = "test_result.visit_request_id = $VisitID";
        $select = $this->select()->from("test_result",array("data" => "test_data"))->
                setIntegrityCheck(FALSE)->
                joinInner("test", "test.id = test_result.test_id",
                        array("test_name" => "test.name"))->
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
    
}
