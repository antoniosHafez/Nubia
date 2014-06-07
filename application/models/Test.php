<?php

class Application_Model_Test extends Zend_Db_Table_Abstract
{
    protected $_name = "test";
    
    function addTest($testData) {
        $row = $this->createRow();
        $row->name = $testData['typeName'];
        
        $row->save();
    }
    
    function getAllTests() {
        //return $this->fetchAll()->toArray();
        $row =  $this->fetchAll();
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function editTest($testId,$testData) {
        $this->update($testData, "id= $testId");
    }
    
    function deleteTest($testId) {
        $this->delete("id=$testId");
    }
    
    function viewTest($testId) {
        $select = $this->select()->where('id = ?', $testId);
        //$result = $this->fetchAll($select)->toArray();
        //return $result;
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function checkDuplication($testId, $testName) {
        $hasDuplicatesValidator = new Zend_Validate_Db_RecordExists(array('table' => $this->_name,'field' => 'name', 'exclude' => array(
                                        'field' => 'id',
                                        'value' => $testId
                                       )));
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
        //$result = $this->fetchAll($select)->toArray();
        //return $result;
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function getTestsFormated() {
        $tests = $this->getAllTests();
        $formatedTests = array();
        
        foreach ( $tests as $test ) {
            $formatedTests[$test['id']] = $test['name'];
        }
        
        return $formatedTests;
    }
    
      function getJsonTest($key) {
        $cond = 'name LIKE "%'.$key.'%"';
        $select = $this->select()->where($cond);
        
        $tests =  $this->fetchAll($select)->toArray();
        
        foreach ($tests as $test) {
                $return_arr[$test['id']] = $test['name'];
        }
            
        return json_encode($return_arr);
    }
    
}
