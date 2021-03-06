<?php

class Application_Model_MedicationHistory extends Zend_Db_Table_Abstract
{
    protected $_name = "medication_history";
    
    function getMedicationByPatientID($patientID)
    {
        //$where = "medication_history.patient_id = $patientID";
        //$select = $this->select()->where($where);       
        //return $this->fetchRow($select)->toArray();
        $cond = "medication_history.patient_id = $patientID";
        $select = $this->select()->from("medication_history",array("medHisID" => "id"))->
                setIntegrityCheck(FALSE)->
                joinLeft(array("phy" => "person") , "phy.id = medication_history.physician_id",
                        array("physician" => "phy.name"))->
                joinLeft("visit_request", "visit_request.id = medication_history.visit_request_id", 
                        "created_date")->
                joinLeft("medication", "medication.id = medication_history.medication_id",
                        array("medication" => "medication.name"))->
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
    
    function getMedicationByVisitID($visitID)
    {
        //$where = "medication_history.patient_id = $patientID";
        //$select = $this->select()->where($where);       
        //return $this->fetchRow($select)->toArray();
        $cond = "medication_history.visit_request_id = $visitID";
        $select = $this->select()->from("medication_history",array("medHisID" => "id"))->
                setIntegrityCheck(FALSE)->
                joinInner(array("phy" => "person") , "phy.id = medication_history.physician_id",
                        array("physician" => "phy.name"))->
                joinInner("visit_request", "visit_request.id = medication_history.visit_request_id", 
                        "date")->
                joinInner("medication", "medication.id = medication_history.medication_id",
                        array("medication" => "medication.name"))->
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
    
    function getMedicationHistoryByPatientName($name)
    {
        $cond = 'per.name LIKE "%'.$name.'%"';
        $select = $this->select()->from("medication_history",array("medHisID" => "id"))->
                setIntegrityCheck(FALSE)->
                joinInner(array("per" => "person"), "per.id = medication_history.patient_id", 
                        array("patient" => "per.name"))->
                joinInner(array("phy" => "person") , "phy.id = medication_history.physician_id",
                        array("physician" => "phy.name"))->
                joinInner("visit_request", "visit_request.id = medication_history.visit_request_id", 
                        "date")->
                joinInner("medication", "medication.id = medication_history.medication_id",
                        array("medication" => "medication.name"))->
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
    
    function getMedicationHistoryByID($id)
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
            
    function addMedicationHistory($data)
    {
        $row = $this->createRow();
        $row->medication_id = $data["medication"];
        $row->patient_id = $data["patient"];
        if(!isset($data["physician"])){
            $row->physician_id = NULL;
        }else{
            $row->physician_id = $data["physician"];  
        }
        if(!isset($data["visit"])){
            $row->visit_request_id = NULL;
        }else{
            $row->visit_request_id = $data["visit"];
        }
        $row->user_modified_id = $data['user_modified_id'];
        if($row->save()) {
            return 1;
        }
    }
    
    function deleteMedicationHistory($medicationHistoryID)
    {
        $where = "id = $medicationHistoryID";
        
        $this->delete($where);
    }
    
    function editMedicationHistory($data)
    {
        $medicationData = array(
            "medication_id" => $data["medication"],
            "patient_id" => $data["patient"],
            "physician_id" => $data["physician"],
            "visit_request_id" => $data["visit"]
                );
        $where = "id = ".$data["id"];
        
        $this->update($medicationData, $where);
    }
    
    function addMedHistoryForVisit($data)
    {
        $this->insert($data);
    }
    
    function getMedicationHistoryCount() {
        $rows = $this->select()->from($this->_name,'count(*) as count')->query()->fetchAll();
        
        return($rows[0]['count']);
    }
    
}

