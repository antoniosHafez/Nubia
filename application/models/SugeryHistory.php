<?php

class Application_Model_SugeryHistory extends Zend_Db_Table_Abstract
{
    protected $_name="sugery_history";
    
    function getSugeryHistoryByPatientID($patientID)
    {
        $cond = "patient_id = $patientID";
        $select = $this->select()->where($cond);
        
        return $this->fetchAll($select)->toArray();
    }
    
    function getSurgeryHistoryByPatientName($name)
    {
        $cond = 'person.name LIKE "%'.$name.'%"';
        $select = $this->select()->from("sugery_history")->setIntegrityCheck(FALSE)->
                joinInner("person", "person.id = sugery_history.patient_id")->
                joinInner("person", "person.id = sugery_history.physician_id")->
                joinInner("surgery", "surgery.id = sugery_history.surgery_id")
                ->where($cond);
        return $this->fetchAll($select)->toArray();
    }
    
    function getsurgeryHistoryByID($id)
    {
        $cond = "id = $id";
        $select = $this->select()->where($cond);
        
        return $this->fetchRow($select)->toArray();
    }
    
    function getSurgeryHistoryByPhysicianID($physicianID)
    {
        $cond = "physician = $physicianID";
        $select = $this->select()->where($cond);
        
        return $this->fetchAll($select)->toArray();
    }
    
    function deleteSurgeryHistory($sugeryHistoryID)
    {
        $where = "id = $sugeryHistoryID";
        
        $this->delete($where);
    }
    
    function addSurgeryHistory($data)
    {
        $row = $this->createRow();
        $row->physician_id = $data["physician"];
        $row->patient_id = $data["patient"];
        $row->surgery_id = $data["surgery"];
        $row->date = $data["date"];
        
        $row->save();
    }
    
    function editSurgeryHistory($data)
    {
        $medicationData = array(
            "surgery_id" => $data["medication"],
            "patient_id" => $data["patient"],
            "physician_id" => $data["physician"],
            "date" => $data["date"]
                );
        $where = "id = ".$data["id"];
        
        $this->update($medicationData, $where);
    }
    
}

