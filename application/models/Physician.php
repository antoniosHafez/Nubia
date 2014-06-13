
<?php

class Application_Model_Physician extends Zend_Db_Table_Abstract
{


    protected $_name = "physican";
   // protected $_name = "person";
    function addPhysician($data){
        
        return $this->insert($data);
        
    }
    
    function getAllPhysicians(){
        
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from('physican')
                ->join("group","group.id = physican.group_id",array("group_name"=>"group.name"))
                ->join("person", "person.id = physican.id",array("name"=>"person.name"));
        $row =  $this->fetchAll($select); 
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
        
    }
    
    function editPhysician($data,$id){
        $this->update($data, "id=$id");
/*        
       $data = array('title'=>$physicianName,
            'name'=>$groupName,
            'title'=>$physicianTitle,
            'sex'=> $physicianGender,
            'telephone'=>$physicianTelephone,
            'mobile'=>$physicianMobile,
            );
  */      
         $this->update($data, 'id = '. (int)$id);
          
    }
    
     function deletePhysician($physicianId) {
        $this->delete("id=$physicianId");
    }
    
    
    
    function viewPhysician($physicianId){
        
        $select = $this->select()->where('id = ?', $physicianId);
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
        
    }
    
    function checkDuplication($physicianName) {
        $hasDuplicatesValidator = new Zend_Validate_Db_RecordExists(array('table' => $this->_title,'field' => 'title'));
        $hasDuplicates = $hasDuplicatesValidator->isValid($physicianName);
        return ($hasDuplicates ? true : false);
    }
    
      
      
     
    function getPhysicianCount() {
        $rows = $this->select()->from($this->_title,'count(*) as count')->query()->fetchAll();
        
        return($rows[0]['count']);
    }
    
    
    function searchByName($name){

        $cond = 'person.name LIKE "%'.$name.'%" AND person.type="Physician"';
        $select = $this->select()->from("person")->
                setIntegrityCheck(FALSE)->
                joinLeft("physican", "person.id = physican.id")->
                joinLeft("group","group.id = physican.group_id",array("group_name"=>"group.name"))->
                where($cond);
        
        $row =  $this->fetchAll($select);       
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
        
    }
    
    function searchById($physicianKey){
        
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from('physican')
                ->join("group","group.id = physican.group_id",array("group_name"=>"group.name"))
                ->where("physican.id=$physicianKey");
        $row =  $this->fetchRow($select); 
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }

    }
    
    function listPhysician(){
        $select = $this->select()
        ->setIntegrityCheck(false)
        ->from(array('pat' => 'physican'))
        ->join(array('per' => 'person'),'per.id = pat.id');
       // ->join(array('addr' => 'address'),'addr.id = per.id');
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }
    
    function getPhysicianInHashArray()
    {
        $physicians = $this->listPhysician();
        $test = array();     
        if(count($physicians) > 0)
        {
            for($i = 0 ; $i<count($physicians) ; $i++)
            {
                $assArray [$physicians[$i]['id']] = $physicians[$i]['name'];
            }
            return $assArray;
        }
        else
            return $test;
    }
    
    function selectFullPhyById($id)
    {
        //$row=$this->select()->where("id=$id");
        //return $this->fetchRow($row)->toArray();
        //
        $row=$this->select("*")
         ->join("person ", "person.id=physican.id",array("*"))
         ->join("user ", "user.id=physican.id",array("*"))
         
         ->setIntegrityCheck(false)->where("physican.id=$id");
            $result = $this->fetchRow($row)->toArray();
          
        return $result;

    }
    function selectFullPhysician()
    {
        //$row=$this->select()->where("id=$id");
        //return $this->fetchRow($row)->toArray();
        //
        $select=$this->select("*")
         ->join("person as per", "per.id=physican.id",array("*"))
         ->joinLeft("user as use", "use.id=physican.id",array("email"))
         
         ->setIntegrityCheck(false);
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }

    }
    function getPhyisicanGroup($physicianId){
        
        $select = $this->select()->where('id = ?', $physicianId);
        $row =  $this->fetchRow($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
        
    }
    
    function getJsonFullPhysicianByGroupId($id) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name)
                ->joinInner("group","group.id=group_id")
                ->joinInner("person","$this->_name.id=person.id")
                ->where("group_id=$id");
        
        
        $physicians = $this->fetchAll($select)->toArray();

        foreach ($physicians as $physician) {
                $return_arr[$physician['id']] = $physician['name'];
        }
            
        return json_encode($return_arr);  
    }
    
}
