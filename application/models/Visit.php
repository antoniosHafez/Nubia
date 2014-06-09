<?php

class Application_Model_Visit extends Zend_DB_Table_Abstract
{
protected $_name='visit_request';

function addVisit($date,$description,$physican_id,$group_id,$patient_id,$type,$notes,$gp_id,$depandency)
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
          $row->group_id = $group_id;
          $id= $row->save();
          return $id;
    }
    
    function getAllVisit()
    {
        //return $this->fetchAll()->toArray();
        $select = $this->select()->from("person",array("patname" => "name"))->
                setIntegrityCheck(FALSE)->
                joinInner(array("visit" => "visit_request") , "visit.patient_id = person.id");
        
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }

     function editVisit($visitbody,$id)
    {
        $this->update($visitbody, "id=$id");
    }
    
    function cancelVisit($visitbody,$id,$phyid)
    {
        $this->update($visitbody, "id=$id and physican_id=$phyid");
    }
    
    function selectVisitById($id)
    {
        //$row=$this->select()->where("id=$id");
        //return $this->fetchRow($row)->toArray();
        //
        $select=$this->select("*")
         ->joinLeft("person as pat", "pat.id=visit_request.patient_id",array("name as patname"))
         ->joinLeft("person as phys", "phys.id=visit_request.physican_id",array("name as phyname"))
         ->joinLeft("person as gp", "gp.id=visit_request.gp_id",array("name as gpname"))
        
         ->setIntegrityCheck(false)->where("visit_request.id=$id");
            //$result = $this->fetchRow($row)->toArray();       
        //return $result;
        $row =  $this->fetchRow($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          

    }
    
    function selectVisitsByDate($date)
    {
         $select=$this->select("*")
         ->joinLeft("person as pat", "pat.id=visit_request.patient_id",array("name as patname"))
         ->joinLeft("person as phys", "phys.id=visit_request.physican_id",array("name as phyname"))
         ->joinLeft("person as gp", "gp.id=visit_request.gp_id",array("name as gpname"))
        
         ->setIntegrityCheck(false)->where("visit_request.date= '$date'");
            //$result = $this->fetchRow($row)->toArray();       
        //return $result;
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }        
    }
            
    function selectVisitByPatientID($id)
    {
        //$row=$this->select()->where("id=$id");
        //return $this->fetchRow($row)->toArray();
        //
        $select=$this->select("*")
         ->join("person as pat", "pat.id=visit_request.patient_id",array("name as patname"))
         ->joinLeft("person as phys", "phys.id=visit_request.physican_id",array("name as phyname"))
         ->join("person as gp", "gp.id=visit_request.gp_id",array("name as gpname"))
        
         ->setIntegrityCheck(false)->where("visit_request.patient_id=$id");
            //$result = $this->fetchAll($row)->toArray();
          
        //return $result;
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          

    }
    
    function listVisit()
    {
        //return $this->fetchAll()->toArray();
        $row =  $this->fetchAll();
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
       
    }
    
    function deleteVisit($id)
    {
        
        $this->delete("id=$id");

    }
    
    function getVisitsInHashArray()
    {
        $visits = $this->getAllVisit();
                
        if(count($visits) > 0)
        {
            for($i = 0 ; $i<count($visits) ; $i++)
            {
                $assArray [$visits[$i]['id']] = $visits[$i]['date'];
            }
            return $assArray;
        }
        else
            return FALSE;
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
        $select = $this->select()->setIntegrityCheck(false)
                ->from("visit_request")
                ->joinInner(array("per" => "person") , "per.id = visit_request.physican_id",
                        array("physician" => "per.name"))
                ->joinInner("group", "group.id = visit_request.group_id",
                        array("group_name" => "group.name"))
                ->where("visit_request.patient_id = $patientId")
                ->where("visit_request.date < NOW()")
                ->where("visit_request.physican_id IS NOT NULL");
        //return $this->fetchAll($select)->toArray();
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    function getPendingVisits($patientId){
        $select = $this->select()->setIntegrityCheck(false)
                ->from("visit_request")
                ->joinInner("group", "group.id = visit_request.group_id",
                        array("group_name" => "group.name"))
                ->where("visit_request.patient_id = $patientId")
                ->where("visit_request.physican_id IS NULL");
        //return $this->fetchAll($select)->toArray(); 
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    function getAcceptedVisits($patientId){
         $select = $this->select()->setIntegrityCheck(false)
                ->from("visit_request")
                ->joinInner(array("per" => "person") , "per.id = visit_request.physican_id",
                        array("physician" => "per.name"))
                ->joinInner("group", "group.id = visit_request.group_id",
                        array("group_name" => "group.name"))
                ->where("visit_request.patient_id = $patientId")
                ->where("visit_request.physican_id IS NOT NULL");
        //return $this->fetchAll($select)->toArray(); 
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }           
    }
    function getPreviousVisitsPhysician($pysId){
        $select = $this->select()->setIntegrityCheck(false)
                ->from("visit_request")
                ->joinInner(array("per" => "person") , "per.id = visit_request.patient_id",
                        array("patient" => "per.name"))
                ->joinInner("group", "group.id = visit_request.group_id",
                        array("group_name" => "group.name"))
                ->where("visit_request.physican_id = $pysId")
                ->where("visit_request.date < NOW()");
        //return $this->fetchAll($select)->toArray();
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    function getPendingVisitsPhysician($grp_id){
        $select = $this->select()->setIntegrityCheck(false)
                ->from("visit_request")
                ->joinInner("group", "group.id = visit_request.group_id",
                        array("group_name" => "group.name"))
                ->where("visit_request.gp_id = $grp_id")
                ->where("visit_request.physican_id IS NULL");
        //return $this->fetchAll($select)->toArray();  
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }          
    }
    function getAcceptedVisitsPhysician($pysId){
         $select = $this->select()->setIntegrityCheck(false)
                ->from("visit_request")
                ->joinInner(array("per" => "person") , "per.id = visit_request.patient_id",
                        array("patient" => "per.name"))
                ->joinInner("group", "group.id = visit_request.group_id",
                        array("group_name" => "group.name"))
                ->where("visit_request.physican_id =$pysId");
        //return $this->fetchAll($select)->toArray();
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }           
    }
    
    

}

