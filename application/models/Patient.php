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
        return $this->fetchRow($select)->toArray(); //fetchOne
    }
            
    function editPatient($patientData, $patientId){
        return $this->update($patientData, "patient.id = $patientId");
    }
    
    function getPatientById($patientId){
        $select = $this->select()->where("patient.id = $patientId");
        return $this->fetchRow($select)->toArray();
    }
    function getPatientFullDataById($patientId){
        $select = $this->select()
        ->setIntegrityCheck(false)
        ->from(array('pat' => 'patient'))
        ->join(array('per' => 'person'),'per.id = pat.id')
        ->join(array('addr' => 'address'),'addr.id = per.id')
        ->where("pat.id = $patientId");
        return $this->fetchAll($select)->toArray();
    }
    
    function listPatients(){
        $select = $this->select()
        ->setIntegrityCheck(false)
        ->from(array('pat' => 'patient'))
        ->join(array('per' => 'person'),'per.id = pat.id')
        ->join(array('addr' => 'address'),'addr.id = per.id');
        return $this->fetchAll($select)->toArray();
    }
    
    function deletePatient($patientId){
        return $this->delete("id=$patientId");
    }

}

