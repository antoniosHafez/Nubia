<?php

class Application_Model_MedicationHistory extends Zend_Db_Table_Abstract
{
    protected $_name = "medication_history";
    
    function getMedicationByPatientID($patientID)
    {
        $where = "patient_id = $patientID";
        $select = $this->select()->where($where);
        
        return $this->fetchAll($select)->toArray();
    }
    
    function getMedicationHistoryByPatientName($name)
    {
        $cond = 'person.name LIKE "%'.$name.'%"';
        $select = $this->select()->from("medication_history")->setIntegrityCheck(FALSE)->
                joinInner("person", "person.id = medication_history.patient_id")->
                joinInner("person", "person.id = medication_history.physician_id")->
                joinInner("visit_request", "visit_request.id = medication_history.visit_request_id")->
                joinInner("medication", "medication.id = medication_history.medication_id")
                ->where($cond);
        return $this->fetchAll($select)->toArray();
    }
    
    function getMedicationHistoryByID($id)
    {
        $cond = "id = $id";
        $select = $this->select()->where($cond);
        
        return $this->fetchRow($select)->toArray();
    }
            
    function addMedicationHistory($data)
    {
        $row = $this->createRow();
        $row->medication_id = $data["medication"];
        $row->patient_id = $data["patient"];
        $row->physician_id = $data["physician"];
        $row->visit_request_id = $data["visit"];
        
        $row->save();
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
    
}

