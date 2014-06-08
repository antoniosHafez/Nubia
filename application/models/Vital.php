<?php

class Application_Model_Vital extends Zend_Db_Table_Abstract
{
    protected $_name = "vital";
    
    function addVital($vitalData) {
        $row = $this->createRow();
        $row->name = $vitalData['typeName'];
        
        $row->save();
    }
    
    function getAllVitals() {
        //return $this->fetchAll()->toArray();
        $row =  $this->fetchAll();
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function editVital($vitalId,$vitalData) {
        $this->update($vitalData, "id= $vitalId");
    }
    
    function deleteVital($vitalId) {
        $this->delete("id=$vitalId");
    }
    
    function viewVital($vitalId) {
        $select = $this->select()->where('id = ?', $vitalId);
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
    
    function checkDuplication($vitalId, $vitalName) {
        $hasDuplicatesValidator = new Zend_Validate_Db_RecordExists(array('table' => $this->_name,'field' => 'name', 'exclude' => array(
                                        'field' => 'id',
                                        'value' => $vitalId
                                       )));
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
    
    function getVitalsFormated() {
        $vitals = $this->getAllVitals();
        $formatedVitals = array();
        
        foreach ( $vitals as $vital ) {
            $formatedVitals[$vital['id']] = $vital['name'];
        }
        
        return $formatedVitals;
    }
    
      function getJsonVital($key) {
        $cond = 'name LIKE "%'.$key.'%"';
        $select = $this->select()->where($cond);
        
        $vitals =  $this->fetchAll($select)->toArray();
        
        foreach ($vitals as $vital) {
                $return_arr[$vital['id']] = $vital['name'];
        }
            
        return json_encode($return_arr);
    }
    
}

