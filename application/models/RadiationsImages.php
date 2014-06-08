<?php

class Application_Model_RadiationsImages extends Zend_Db_Table_Abstract
{
    protected $_name = "radiation_images";
    
    public function addRadiationImage($radiationId, $timeStamp, $extension) {
        $row = $this->createRow();
        
        $row->radiation_id = $radiationId;
        $row->title = $timeStamp.".".$extension;
        
        return $row->save();
    }
    
    public function getRadiationImages($resultId) {
        $select = $this->select()->where("radiation_id=$resultId");
        
        return $this->fetchAll($select)->toArray();
    }
    
    public function removeImage($imageId) {
        $this->delete("id=$imageId");
    }

}

