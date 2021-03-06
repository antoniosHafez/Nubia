<?php

class Application_Model_Surgery extends Zend_Db_Table_Abstract
{
    protected $_name = "surgery";

    function getAllSurgery()
    {
        $row =  $this->fetchAll();
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
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
        
        $row =  $this->fetchRow($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }
    
    function getSurgeryByName($surgeryName)
    {
        $cond = "operation = '$surgeryName'";
        $select = $this->select()->where($cond);
        
        $row = $this->fetchRow($select);
        if($row) {
            return $row->toArray();
        }
        else {
            return 0;
        }
        
    }
    
    function searchSurgery($operation)
    {
        $cond = 'operation LIKE "%'.$operation.'%"';
        $select = $this->select()->where($cond);
        
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
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
        $cond = 'operation LIKE "%'.$key.'%"';
        $select = $this->select()->where($cond);
        
        $surgerys =  $this->fetchAll($select)->toArray();
        
        foreach ($surgerys as $surgery) {
                $return_arr[] =  $surgery['operation'];
        }
            
        return json_encode($return_arr);
    }
    
    function getSurgeryCount() {
        $rows = $this->select()->from($this->_name,'count(*) as count')->query()->fetchAll();
        
        return($rows[0]['count']);
    }
    
    function getSurgeryStatistics() {
        $count = $this->getSurgeryCount();
        
        return array('count'=>$count);
    }
}

