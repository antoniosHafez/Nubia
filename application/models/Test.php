<?php

class Application_Model_Test extends Zend_Db_Table_Abstract
{
    protected $_name = "test";
    
    function addTest($testData) {
        $row = $this->createRow();
        $row->name = $testData['name'];
        
        $row->save();
    }
    
    function getAllTests() {
        return $this->fetchAll()->toArray();
    }
    
    function editTest($testId,$testData) {
        $this->update($testData, "id= $testId");
    }
    
    function deleteTest($testId) {
        $this->delete("id=$testId");
    }
    
    function viewTest($testId) {
        $select = $this->select()->where('id = ?', $testId);
        $result = $this->fetchAll($select)->toArray();

        return $result;
    }
    
    function checkDuplication($testName) {
        $hasDuplicatesValidator = new Zend_Validate_Db_RecordExists(array('table' => $this->_name,'field' => 'name'));
        $hasDuplicates = $hasDuplicatesValidator->isValid($testName);
        return ($hasDuplicates ? true : false);
    }
    
    function getTestCount() {
        $rows = $this->select()->from($this->_name,'count(*) as count')->query()->fetchAll();
        
        return($rows[0]['count']);
    }
    
    function getTestsStatistics() {
        $count = $this->getTestCount();
        
        return array('count'=>$count);
    }
    
    function searchByName($testKey) {
        $select = $this->select()->where('name LIKE ?', $testKey);
        $result = $this->fetchAll($select)->toArray();

        return $result;
    }
    
    function getTestsFormated() {
        $tests = $this->getAllTests();
        $formatedTests = array();
        
        foreach ( $tests as $test ) {
            $formatedTests[$test['id']] = $test['name'];
        }
        
        return $formatedTests;
    }
    
}
