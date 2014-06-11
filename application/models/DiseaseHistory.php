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
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
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
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }
    
    function getDiseaseHistoryByID($id)
    {
        $cond = "id = $id";
        $select = $this->select()->where($cond);
        
        $row = $this->fetchRow($select);
        if($row){
            return $row->toArray();
        }else {
            return NULL;
        }
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
        $row->user_modified_id = $data['user_modified_id'];
        
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
    
    function getDiseaseHistoryCount() {
        $rows = $this->select()->from($this->_name,'count(*) as count')->query()->fetchAll();
        
        return($rows[0]['count']);
    }
}

