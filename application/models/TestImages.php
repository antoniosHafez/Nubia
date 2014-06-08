<?php

class Application_Model_TestImages extends Zend_Db_Table_Abstract
{
    protected $_name = "test_images";
    
    public function addTestImage($testId, $timeStamp, $extension) {
        $row = $this->createRow();
        
        $row->test_id = $testId;
        $row->title = $timeStamp.".".$extension;
        
        return $row->save();
    }
    
    public function getTestImages($resultId) {
        $select = $this->select()->where("test_id=$resultId");
        
        return $this->fetchAll($select)->toArray();
    }
    
    public function removeImage($imageId) {
        $this->delete("id=$imageId");
    }

}

