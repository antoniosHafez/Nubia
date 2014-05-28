
<?php

class Application_Model_Physician extends Zend_Db_Table_Abstract
{


    protected $_name = "physican";
   // protected $_name = "person";
    function addPhysician($data){
        
        return $this->insert($data);
        
    }
    
    function getAllPhysicians(){
        
        return $this->fetchAll()->toArray();
        
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
        $result = $this->fetchAll($select)->toArray();
        
        return $result;
        
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
    
    
    function searchByName($physicianKey){
        
        $select = $this->select()->where('title LIKE ?', $vitalKey);
        $result = $this->fetchAll($select)->toArray();

        return $result;
        
    }
    
    function searchById($physicianKey){
        
        $select = $this->select()->where("id=$physicianKey");
        $result = $this->fetchRow($select)->toArray();

        return $result;

    }
    
    function listPhysician(){
        $select = $this->select()
        ->setIntegrityCheck(false)
        ->from(array('pat' => 'physican'))
        ->join(array('per' => 'person'),'per.id = pat.id')
        ->join(array('addr' => 'address'),'addr.id = per.id');
        return $this->fetchAll($select)->toArray();
    }
    
    function getPhysicianInHashArray()
    {
        $physicians = $this->listPhysician();
                
        if(count($physicians) > 0)
        {
            for($i = 0 ; $i<count($physicians) ; $i++)
            {
                $assArray [$physicians[$i]['id']] = $physicians[$i]['name'];
            }
            return $assArray;
        }
        else
            return FALSE;
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
        $row=$this->select("*")
         ->join("person as per", "per.id=physican.id",array("*"))
         ->join("user as use", "use.id=physican.id",array("email"))
         
         ->setIntegrityCheck(false);
            $result = $this->fetchAll($row)->toArray();
          
        return $result;

    }
    
}

