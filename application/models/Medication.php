<?php

class Application_Model_Medication extends Zend_Db_Table_Abstract
{
    
    protected $_name = "medication";

    function getAllMedication()
    {
        return $this->fetchAll()->toArray();
    }
    
    function addMedication($medicationName)
    {
        $row = $this->createRow();
        $row->name = $medicationName;
        
        $row->save();
    }
    
    function editMedication($medicationName,$medicationID)
    {
        $medicationData = array("name" => $medicationName);
        $where = "id = $medicationID";
        
        $this->update($medicationData, $where);
    }
    
    function deleteMedication($medicationID)
    {
        $where = "id = $medicationID";
        
        $this->delete($where);
    }
    
    function searchMedication($medicationName)
    {
        $cond = 'name LIKE "%'.$medicationName.'%"';
        $select = $this->select()->where($cond);
        
        return $this->fetchAll($select)->toArray();
    }
    
    function getMedicationByID($medicationID)
    {
        $cond = "id = $medicationID";
        $select = $this->select()->where($cond);
        
        return $this->fetchRow($select)->toArray();
    }
    
    function getMedicationInHashArray()
    {
        $medications = $this->getAllMedication();
                
        if(count($medications) > 0)
        {
            for($i = 0 ; $i<count($medications) ; $i++)
            {
                $assArray [$medications[$i]['id']] = $medications[$i]['name'];
            }
            return $assArray;
        }
        else
            return FALSE;
    }
    
}

