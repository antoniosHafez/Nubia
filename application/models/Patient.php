<?php

class Application_Model_Patient extends Zend_Db_Table_Abstract
{
    protected $_name='patient';
    
    function addPatient($patientData){
        //$this->insert('person', $personData);
        //$id = $this->lastInsertId();
        return $this->insert($patientData);
    }
    
    function searchPatientByIDN($patientIDN){
        $select = $this->select()->where("patient.IDNumber = $patientIDN");
        $row = $this->fetchRow($select);
        if($row){
            return $row->toArray();
        }else {
            return NULL;
        } //fetchOne
    }
            
    function editPatient($patientData, $patientId){
        return $this->update($patientData, "patient.id = $patientId");
    }
    
    function getPatientById($patientId){
        $select = $this->select()->where("patient.id = $patientId");
        $row = $this->fetchRow($select);
        if($row){
            return $row->toArray();
        }else {
            return NULL;
        }
    }
    function getPatientFullDataById($patientId){
        $select = $this->select()
        ->setIntegrityCheck(false)
        ->from(array('pat' => 'patient'))
        ->join(array('per' => 'person'),'per.id = pat.id')
        ->join(array('addr' => 'address'),'addr.id = per.id')
        ->where("pat.id = $patientId");
        $row =  $this->fetchRow($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }
    
    function listPatients(){
        $select = $this->select()
        ->setIntegrityCheck(false)
        ->from(array('pat' => 'patient'))
        ->join(array('per' => 'person'),'per.id = pat.id')
        ->join(array('addr' => 'address'),'addr.id = per.id');
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }
    
    function deletePatient($patientId){
        return $this->delete("id=$patientId");
    }
    
    function getPatientInHashArray()
    {
        $patients = $this->listPatients();
                
        if(count($patients) > 0)
        {
            for($i = 0 ; $i<count($patients) ; $i++)
            {
                $assArray [$patients[$i]['id']] = $patients[$i]['name'];
            }
            return $assArray;
        }
        else
            return FALSE;
    }
    
    function searchPatientByName($name)
    {
        $cond1 = 'person.name LIKE "%'.$name.'%"';
        $select = $this->select()->from("person")->
                setIntegrityCheck(FALSE)->
                joinInner("patient", "person.id = patient.id")->
                where($cond1)->orWhere("patient.IDNumber = ?",$name);
        return $this->fetchAll($select)->toArray();
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }

}

