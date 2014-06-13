<?php

class Application_Model_Disease extends Zend_Db_Table_Abstract
{
    protected $_name = "disease";

    function getAllDisease()
    {
        $row =  $this->fetchAll();
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
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
        
        $row =  $this->fetchRow($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }
    
    function getDiseaseByName($diseaseName)
    {
        $cond = "name = '$diseaseName'";
        $select = $this->select()->where($cond);
        
        $row =  $this->fetchRow($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
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
    
    function getJsonDisease($key) {
        $cond = 'name LIKE "%'.$key.'%"';
        $select = $this->select()->where($cond);
        
        $diseases =  $this->fetchAll($select)->toArray();
        
        foreach ($diseases as $disease) {
                $return_arr[] =  $disease['name'];
        }
            
        return json_encode($return_arr);
    }
    
    function getDiseaseCount() {
        $rows = $this->select()->from($this->_name,'count(*) as count')->query()->fetchAll();
        
        return($rows[0]['count']);
    }
    
    function getDiseaseStatistics() {
        $count = $this->getDiseaseCount();
        
        return array('count'=>$count);
    }
    
}

