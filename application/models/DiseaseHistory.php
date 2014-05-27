<?php

class Application_Model_DiseaseHistory extends Zend_Db_Table_Abstract
{
    protected $_name = "disease_history";
    
    function getDiseaseHistoryByPatientID($patientID)
    {
        $cond = "patient_id = $patientID";
        $select = $this->select()->where($cond);
        
        return $this->fetchAll($select)->toArray();
    }
    
    function getDiseaseHistoryByPatientName($name)
    {
        $cond = 'person.name LIKE "%'.$name.'%"';
        $select = $this->select()->from("disease_history")->setIntegrityCheck(FALSE)->
                joinInner("person", "person.id = disease_history.patient_id")->
                joinInner("disease", "disease.id = disease_history.disease")->
                where($cond);
        return $this->fetchAll($select)->toArray();
    }
    
    function getDiseaseHistoryByID($id)
    {
        $cond = "id = $id";
        $select = $this->select()->where($cond);
        
        return $this->fetchRow($select)->toArray();
    }
    
    function deleteDiseaseHistory($diseaseHistoryID)
    {
        $where = "id = $diseaseHistoryID";
        
        $this->delete($where);
    }
    
    function addDiseaseHistory($data)
    {
        $row = $this->createRow();
        $row->disease_id = $data["disease"];
        $row->patient_id = $data["patient"];
        $row->date = $data["date"];
        
        $row->save();
    }
    
    function editDiseaseHistory($data)
    {
        $diseaseData = array(
            "disease_id" => $data["disease"],
            "patient_id" => $data["patient"],
            "date" => $data["date"]
                );
        $where = "id = ".$data["id"];
        
        $this->update($diseaseData, $where);
    }
}

