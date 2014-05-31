<?php

class Application_Model_DiseaseHistory extends Zend_Db_Table_Abstract
{
    protected $_name = "disease_history";
    
    function getDiseaseHistoryByPatientID($patientID)
    {
        /*$cond = "disease_history.patient_id = $patientID";
        $select = $this->select()->where($cond);       
        return $this->fetchRow($select)->toArray();*/
        $cond = "disease_history.patient_id = $patientID";
        $select = $this->select()->from("disease_history",array("disHisID" => "id","date"))->
                setIntegrityCheck(FALSE)->
                joinInner("disease", "disease.id = disease_history.disease_id",
                        array("disease" => "disease.name"))->
                where($cond);
        return $this->fetchAll($select)->toArray();
    }
    
    function getDiseaseHistoryByPatientName($name)
    {
        $cond = 'per.name LIKE "%'.$name.'%"';
        $select = $this->select()->from("disease_history",array("disHisID" => "id","date"))->
                setIntegrityCheck(FALSE)->
                joinInner(array("per" => "person"), "per.id = disease_history.patient_id", 
                        array("patient" => "per.name"))->
                joinInner("disease", "disease.id = disease_history.disease_id",
                        array("disease" => "disease.name"))->
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
        
        if($row->save()) {
            return 1;
        }
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
    
    function addDiseaseHistoryForVisit($data)
    {
        $this->insert($data);
    }
}

