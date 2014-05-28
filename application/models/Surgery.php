<?php

class Application_Model_Surgery extends Zend_Db_Table_Abstract
{
    protected $_name = "surgery";

    function getAllSurgery()
    {
        return $this->fetchAll()->toArray();
    }
    
    function addSurgery($operation)
    {
        $row = $this->createRow();
        $row->operation = $operation;
        
        $row->save();
    }
    
    function editSurgery($operation,$surgeryID)
    {
        $surgeryData = array("operation" => $operation);
        $where = "id = $surgeryID";
        
        $this->update($surgeryData, $where);
    }
    
    function deleteSurgery($surgeryID)
    {
        $where = "id = $surgeryID";
        
        $this->delete($where);
    }
    
    function getSurgeryByID($surgeryID)
    {
        $cond = "id = $surgeryID";
        $select = $this->select()->where($cond);
        
        return $this->fetchRow($select)->toArray();
    }
    
    function getSurgeryByName($surgeryName)
    {
        $cond = "name = '$surgeryName'";
        $select = $this->select()->where($cond);
        
        return $this->fetchRow($select)->toArray();
    }
    
    function searchSurgery($operation)
    {
        $cond = 'name LIKE "%'.$operation.'%"';
        $select = $this->select()->where($cond);
        
        return $this->fetchAll($select)->toArray();
    }
    
    function getSurgeryInHashArray()
    {
        $surgeries = $this->getAllSurgery();
                
        if(count($surgeries) > 0)
        {
            for($i = 0 ; $i<count($surgeries) ; $i++)
            {
                $assArray [$surgeries[$i]['id']] = $surgeries[$i]['operation'];
            }
            return $assArray;
        }
        else
            return FALSE;
    }
    
    function getJsonSurgery($key) {
        $cond = 'name LIKE "%'.$surgeryName.'%"';
        $select = $this->select()->where($cond);
        
        $surgerys =  $this->fetchAll($select)->toArray();
        
        foreach ($surgerys as $surgery) {
                $return_arr[] =  $surgery['name'];
        }
            
        return json_encode($return_arr);
    }
}

