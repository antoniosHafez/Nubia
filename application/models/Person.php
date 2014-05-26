<?php

class Application_Model_Person extends Zend_Db_Table_Abstract
{
    protected $_name='person';
    
    function addPerson($personData){
        return $this->insert($personData);
    }
    
    function getPersonById($personId){
        $select = $this->select()->where("person.id = $personId");
        return $this->fetchRow($select)->toArray();
    }
    
    function editPerson($personData, $personId){
        return $this->update($personData, "person.id = $personId");
    }

    function deletePerson($personId){
        return $this->delete("id=$personId");
    }
    
    



}

