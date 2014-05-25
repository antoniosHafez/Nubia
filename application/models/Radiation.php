<?php

class Application_Model_Radiation extends Zend_Db_Table_Abstract
{
    protected $_name = "radiation";
    
    function addRadiation($radiationData) {
        $row = $this->createRow();
        $row->name = $radiationData['name'];
        
        $row->save();
    }
    
    function getAllRadiations() {
        return $this->fetchAll()->toArray();
    }
    
    function editRadiation($radiationId,$radiationData) {
        $this->update($radiationData, "id= $radiationId");
    }
    
    function deleteRadiation($radiationId) {
        $this->delete("id=$radiationId");
    }
    
    function viewRadiation($radiationId) {
        $select = $this->select()->where('id = ?', $radiationId);
        $result = $this->fetchAll($select)->toArray();

        return $result;
    }
    
    function checkDuplication($radiationId, $radiationName) {
        $hasDuplicatesValidator = new Zend_Validate_Db_RecordExists(
                array(
                    'table' => $this->_name,
                    'field' => 'name',
                    'exclude' => array(
                                        'field' => 'id',
                                        'value' => $radiationId
                                       )
                    )
        );
        $hasDuplicates = $hasDuplicatesValidator->isValid($radiationName);
        return ($hasDuplicates ? true : false);
    }
    
    function getRadiationCount() {
        $rows = $this->select()->from($this->_name,'count(*) as count')->query()->fetchAll();
        
        return($rows[0]['count']);
    }
    
    function getRadiationsStatistics() {
        $count = $this->getRadiationCount();
        
        return array('count'=>$count);
    }
    
    function searchByName($radiationKey) {
        $select = $this->select()->where('name LIKE ?', $radiationKey);
        $result = $this->fetchAll($select)->toArray();

        return $result;
    }
    
    function getRadiationsFormated() {
        $radiations = $this->getAllRadiations();
        $formatedRadiations = array();
        
        foreach ( $radiations as $radiation ) {
            $formatedRadiations[$radiation['id']] = $radiation['name'];
        }
        
        return $formatedRadiations;
    }
    
}
