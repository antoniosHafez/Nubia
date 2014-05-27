<?php

class Application_Model_Disease extends Zend_Db_Table_Abstract
{
    protected $_name = "disease";

    function getAllDisease()
    {
        return $this->fetchAll()->toArray();
    }
    
    function addDisease($diseaseName)
    {
        $row = $this->createRow();
        $row->name = $diseaseName;
        
        $row->save();
    }
    
    function editDisease($diseaseName,$diseaseID)
    {
        $diseaseData = array("name" => $diseaseName);
        $where = "id = $diseaseID";
        
        $this->update($diseaseData, $where);
    }
    
    function deleteDisease($diseaseID)
    {
        $where = "id = $diseaseID";
        
        $this->delete($where);
    }
    
    function getDiseaseByID($diseaseID)
    {
        $cond = "id = $diseaseID";
        $select = $this->select()->where($cond);
        
        return $this->fetchRow($select)->toArray();
    }
    
    function searchDisease($diseaseName)
    {
        $cond = 'name LIKE "%'.$diseaseName.'%"';
        $select = $this->select()->where($cond);
        
        return $this->fetchAll($select)->toArray();
    }
    
    function getDiseaseInHashArray()
    {
        $diseases = $this->getAllDisease();
                
        if(count($diseases) > 0)
        {
            for($i = 0 ; $i<count($diseases) ; $i++)
            {
                $assArray [$diseases[$i]['id']] = $diseases[$i]['name'];
            }
            return $assArray;
        }
        else
            return FALSE;
    }
}
