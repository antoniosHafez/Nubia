<?php

class Application_Model_Visit extends Zend_DB_Table_Abstract
{
protected $_name='visit_request';

function addVisit($date,$description,$physican_id,$patient_id,$type,$notes,$gp_id,$depandency)
    {
          $row=$this->createRow();
          $row->date=$date;
          $row->description=$description;
          $row->physican_id=$physican_id;
          $row->patient_id = $patient_id;
          $row->type = $type;
          $row->notes = $notes;
          $row->gp_id = $gp_id;
          $row->depandency = $depandency;
          $id= $row->save();
          return $id;
    }
    
    function getAllVisit()
    {
        return $this->fetchAll()->toArray();
    }

     function editVisit($visitbody,$id)
    {
        $this->update($visitbody, "id=$id");
    }
    
    function selectVisitById($id)
    {
        //$row=$this->select()->where("id=$id");
        //return $this->fetchRow($row)->toArray();
        //
        $row=$this->select("*")
         ->join("person as patient", "patient.id=visit_request.patient_id",array("name as patient"))
         ->join("person", "person.id=visit_request.physican_id",array("name"))
         ->join("person", "person.id=visit_request.gp_id",array("name as gp"))
        
         ->setIntegrityCheck(false)->where("visit_request.id=$id");
            $result = $this->fetchRow($row)->toArray();
          
        return $result;

    }
    
    function listVisit()
    {
        return $this->fetchAll()->toArray();
       
    }
    
    function deleteVisit($id)
    {
        
        $this->delete("id=$id");

    }
    
    function getRequestsFormated() {
        $visits = $this->listVisit();
        $formatedVisits = array();
        
        foreach ( $visits as $visit ) {
            $formatedVisits[$visit['id']] = $visit['date'];
        }
        
        return $formatedVisits;
    }
    
    function getPreviousVisits($patientId){
        $select = $this->select()->where("visit_request.patient_id = $patientId")
                ->where("visit_request.date < NOW()");
        return $this->fetchAll($select)->toArray();
    }
    function getPendingVisits($patientId){
        $select = $this->select()->where("visit_request.patient_id = $patientId")
                ->where("visit_request.physican_id IS NULL");
        return $this->fetchAll($select)->toArray();        
    }
    function getAcceptedVisits($patientId){
         $select = $this->select()->where("visit_request.patient_id = $patientId")
                ->where("visit_request.physican_id IS NOT NULL");
        return $this->fetchAll($select)->toArray();       
    }


}

