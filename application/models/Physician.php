
<?php

class Application_Model_Physician extends Zend_Db_Table_Abstract
{


    protected $_name = "physican";
   // protected $_name = "person";
    function addPhysician($physicianName,$groupName,$physicianTitle,$physicianGender,$physicianTelephone,$physicianMobile,$physicianSex){
        
        $row = $this->createRow();
        $row->title = $physicianTitle;
        $row->name =$groupName; 
        $row->name = $physicianName ;
        $row->telephone = $physicianTelephone;
        $row->mobile = $physicianMobile;
        $row->sex = $physicianSex;
 
        
        $row->save();
        
    }
    
    function getAllPhysicians(){
        
        return $this->fetchAll()->toArray();
        
    }
    
    function editPhysician($physicianName,$groupName,$physicianTitle,$physicianGender,$physicianTelephone,$physicianMobile,$physicianSex){
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
        ->join(array('per' => 'person'),'per.id = pat.id');
       // ->join(array('addr' => 'address'),'addr.id = per.id');
        return $this->fetchAll($select)->toArray();
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
    
    /*
     function getPhysiciansStatistics() {
        $count = $this->getPhysicianCount();
        
        return array('count'=>$count);
    }
    */
    
    
}