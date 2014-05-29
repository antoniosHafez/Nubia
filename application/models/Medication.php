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
    
    function getMedicationByName($medicationName)
    {
        $cond = "name = '$medicationName'";
        $select = $this->select()->where($cond);
        
        $row = $this->fetchRow($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
        
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
    
    function getJsonMedication($key) {
        $cond = 'name LIKE "%'.$key.'%"';
        $select = $this->select()->where($cond);
        
        $medications =  $this->fetchAll($select)->toArray();
        
        foreach ($medications as $medication) {
                $return_arr[] =  $medication['name'];
        }
            
        return json_encode($return_arr);
    }
}

