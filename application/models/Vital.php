<?php

class Application_Model_Vital extends Zend_Db_Table_Abstract
{
    protected $_name = "vital";
    
    function addVital($vitalData) {
        $row = $this->createRow();
        $row->name = $vitalData['name'];
        
        $row->save();
    }
    
    function getAllVitals() {
        return $this->fetchAll()->toArray();
    }
    
    function editVital($vitalId,$vitalData) {
        $this->update($vitalData, "id= $vitalId");
    }
    
    function deleteVital($vitalId) {
        $this->delete("id=$vitalId");
    }
    
    function viewVital($vitalId) {
        $select = $this->select()->where('id = ?', $vitalId);
        $result = $this->fetchAll($select)->toArray();

        return $result;
    }
    
    function checkDuplication($vitalName) {
        $hasDuplicatesValidator = new Zend_Validate_Db_RecordExists(array('table' => $this->_name,'field' => 'name'));
        $hasDuplicates = $hasDuplicatesValidator->isValid($vitalName);
        return ($hasDuplicates ? true : false);
    }
    
    function getVitalCount() {
        $rows = $this->select()->from($this->_name,'count(*) as count')->query()->fetchAll();
        
        return($rows[0]['count']);
    }
    
    function getVitalsStatistics() {
        $count = $this->getVitalCount();
        
        return array('count'=>$count);
    }
    
    function searchByName($vitalKey) {
        $select = $this->select()->where('name LIKE ?', $vitalKey);
        $result = $this->fetchAll($select)->toArray();

        return $result;
    }
    
}

