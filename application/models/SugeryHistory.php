<?php

class Application_Model_SugeryHistory extends Zend_Db_Table_Abstract
{
    protected $_name="surgery_history";
    
    function getSugeryHistoryByPatientID($patientID)
    {
        /*$cond = "surgery_history.patient_id = $patientID";
        $select = $this->select()->where($cond);      
        return $this->fetchRow($select)->toArray();*/
        $cond = "surgery_history.patient_id = $patientID";
        $select = $this->select()->from("surgery_history",array("sugHisID" => "id","date"))->
                setIntegrityCheck(FALSE)->
                joinInner("surgery", "surgery.id = surgery_history.surgery_id",
                        array("surgery" => "surgery.operation"))->
                where($cond);
        //return $this->fetchAll($select)->toArray();
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function getSugeryHistoryByVisitID($visitID)
    {
        /*$cond = "surgery_history.patient_id = $patientID";
        $select = $this->select()->where($cond);      
        return $this->fetchRow($select)->toArray();*/
        $cond = "surgery_history.visit_request_id = $visitID";
        $select = $this->select()->from("surgery_history",array("sugHisID" => "id","date"))->
                setIntegrityCheck(FALSE)->
                joinInner("surgery", "surgery.id = surgery_history.surgery_id",
                        array("surgery" => "surgery.operation"))->
                where($cond);
        //return $this->fetchAll($select)->toArray();
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function getSurgeryHistoryByPatientName($name)
    {
        
        $cond = 'per.name LIKE "%'.$name.'%"';
        /*$select = $this->select()->from("surgery_history",array("sugHisID" => "id","date"))->
                setIntegrityCheck(FALSE)->
                joinInner(array("per" => "person"), "per.id = surgery_history.patient_id", 
                        array("patient" => "per.name"))->
                joinInner(array("phy" => "person") , "phy.id = surgery_history.physician_id",
                        array("physician" => "phy.name"))->
                joinInner("surgery", "surgery.id = surgery_history.surgery_id",
                        array("surgery" => "surgery.operation"))->
                where($cond);*/
        //return $this->fetchAll($select)->toArray();
        
        $select = $this->select()->from("surgery_history",array("sugHisID" => "id","date"))->
                setIntegrityCheck(FALSE)->
                joinInner(array("per" => "person"), "per.id = surgery_history.patient_id", 
                        array("patient" => "per.name"))->
                joinInner(array("phy" => "visit_request") , "phy.id = surgery_history.visit_request_id",
                        array("physician" => "phy.date"))->
                joinInner("surgery", "surgery.id = surgery_history.surgery_id",
                        array("surgery" => "surgery.operation"))->
                where($cond);
        
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
           
    }
    
    function getsurgeryHistoryByID($id)
    {
        $cond = "id = $id";
        $select = $this->select()->where($cond);
        
        //return $this->fetchRow($select)->toArray();
        $row =  $this->fetchRow($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function getSurgeryHistoryByPhysicianID($physicianID)
    {
        $cond = "physician = $physicianID";
        $select = $this->select()->where($cond);
        
        //return $this->fetchAll($select)->toArray();
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    
    function deleteSurgeryHistory($surgeryHistoryID)
    {
        $where = "id = $surgeryHistoryID";
        
        $this->delete($where);
    }
    
    function addSurgeryHistory($data)
    {
        $row = $this->createRow();
        $row->visit_request_id = $data["physician"];
        $row->patient_id = $data["patient"];
        $row->surgery_id = $data["surgery"];
        $row->date = $data["date"];
        $row->user_modified_id = $data['user_modified_id'];
        
        $row->save();
    }
    
    function editSurgeryHistory($data)
    {
        $medicationData = array(
            "patient_id" => $data["patient"],
            "visit_request_id" => $data["physician"],
            "date" => $data["date"],
            "surgery_id" => $data["surgery"]
                );
        $where = "id = ".$data["id"];
        
        $this->update($medicationData, $where);
    }
    
     function addSurHistoryForVisit($data)
    {
        $this->insert($data);
    }
    
    function getSurgeryHistoryCount() {
        $rows = $this->select()->from($this->_name,'count(*) as count')->query()->fetchAll();
        
        return($rows[0]['count']);
    }
    
}

