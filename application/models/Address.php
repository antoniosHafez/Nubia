<?php

class Application_Model_Address extends Zend_Db_Table_Abstract
{
    protected $_name = 'address';
    
    function addAddress($addressData){
        return $this->insert($addressData);
    }
    function getAddressByPId($personId){
        $select = $this->select()->where("address.id = $personId");
        return $this->fetchRow($select)->toArray();
    }
    
    function editAddress($addressData, $personId){
        return $this->update($addressData, "address.id = $personId");
    }
    function deleteAddress($personId){
        return $this->delete("id=$personId");
    }

}

